<?php
    /*
    * @version      1.0.3 01.11.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api extends JshoppingControllerBase {

        private
            $section = '',
            $args    = [],
            $format  = '';

        public function __construct(array $config = []) {
            parent::__construct($config);
            try {
                $this->setProps();
                $this->connect();
            } catch (Exception $e) {
                JFactory::getSession()->destroy();
                $this->reply($e->getStatus(), $e->getCode(), null, $e->getMessage());
            }
        }

        private function setProps(): bool {
            $addon      = AddonApi::getInst();
            $basic_data = $addon->getModel('adapter')->getBasicData();
            foreach ($basic_data as $key =>  $val) {
                $this->$key = $val;
            }
            if (
                !in_array($this->format, $addon->getParam('reply[formats]'))
            ) {
                $this->format = $addon->getParam('reply[default_format]');
                throw new AddonApiException_request(1, ' \'' . $basic_data['format'] . '\'');
            }
            if (!$this->section) {
                throw new AddonApiException_request(2);
            }
            if (!$this->task) {
                throw new AddonApiException_request(3);
            }
            return true;
        }

        private function connect(): bool {
            $auth_data = AddonApi::getInst()->getModel('connection')->getAuthData();
            if (!$auth_data) {
                throw new AddonApiException_connection(1);
            }
            if ($this->section === 'connection' && $this->task === 'open') {
                return true;
            }
            AddonApi::getInst()->getModel('connection')->connect($auth_data);
            return true;
        }

        public function execute($task) {
            $model = AddonApi::getInst()->getModel('adapter');
            try {
                $subcontroller  = $model->getSubcontroller($this->section);
                $task_available = in_array($task, $model->getAvailableTasks($subcontroller));
				if (!$task_available) {
					throw new AddonApiException_request(
                        5,
                        '. No task \'' . $task . '\' in section \'' . $this->section . '\''
                    );
				}
                $result = call_user_func_array(
                    [
                        $subcontroller,
                        $task
                    ],
                    $model->prepareArgs($subcontroller, $task, $this->args)
                );
            } catch (AddonApiException $e) {
                $this->reply(
                    $e->getStatus(),
                    $e->getCode(),
                    isset($subcontroller) && !empty($task_available) ? $model->getDefaultResult($subcontroller, $task) : null,
                    $e->getMessage()
                );
            }
            $this->reply('ok', $subcontroller->getCode(), $result, $subcontroller->getNote());
        }

        private function reply(
            string $status,
            int    $code,
                   $result = null,
            string $note   = ''
        ) {
            $addon = AddonApi::getInst();
            $model = $addon->getModel('reply');
            $reply = $model->buildReply($status, $code, $result, $note);
            if (
                $reply['status'] !== 'ok' &&
                $addon->getModel('connection')->getAuthData()
            ) {
                $addon->getModel('user_api')->log(
                    AddonApiConnection::getInst()->getApiUser(),
                    $reply['report']
                );
            }
            $model->reply(
                $model->formatReply($reply, $this->format)
            );
        }

    }
