<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_reply extends jshopBase {

        /**
         * @throws Exception
         */
        public function buildReply(
            string $status,
            int    $code,
                   $result = null,
            string $note   = ''
        ): array {
            $addon    = AddonApi::getInst();
            $statuses = $addon->getParam('reply[statuses]');
            $reports  = $addon->getParam('reports');
            if (!key_exists($status, $reports)) {
                throw new Exception('Unknown/No reply status \'' . $status . '\'');
            }
            if (!key_exists($code, $reports[$status])) {
                throw new Exception(
                    'Unknown code ' . $code . ' of a reply of the status \'' . $status . '\''
                );
            }
			$report = preg_replace(
                ['/\s+/', '/"/'],
                [' ',     '\"' ],
                trim(
                    $statuses[$status] . '. ' .
                    $reports[$status][$code] .
                    ($note ? ($status === 'ok' && $code > 1 ? (': ' . lcfirst($note)) : $note) : '')
                )
            );
            return [
                'status' => $status,
                'code'   => $code,
                'report' => $report,
                'result' => $result
            ];
        }

        /**
         * @throws Exception
         */
        public function formatReply(array $reply, string $format = ''): string {
            $addon  = AddonApi::getInst();
            $format = $format ? $format : $addon->getModel('adapter')->getBasicData()['format'];
            $res    = $addon->typify(
                array_map(
                    $closure = function($el) use(&$closure) {
                        if (is_object($el)) {
                            $el = get_object_vars($el);
                        }
                        if (is_array($el)) {
                            $el = array_map($closure, $el);
                        }
                        return $el;
                    },
                    $reply
                )
            );
            switch ($format) {
                case 'json':
                    return json_encode($res);
                case 'var_dump':
					ob_start();
					var_dump($res);
                    return ob_get_clean();
            }
            throw new Exception('Unknown/No reply format \'' . $format . '\'');
        }

        public function reply(string $reply) {
            ob_end_clean();
            exit($reply);
        }

        public function replyFailure(
            string $error,
            string $file,
            int    $line,
            string $type
        ) {
            $addon = AddonApi::getInst();
            $addon->log(
                $error,
                $file,
                $line,
                0,
                [
                    'errortype' => $type
                ]
            );
            try {
                throw new AddonApiException_server(1);
            } catch (AddonApiException $e) {
                $this->reply(
                    $this->formatReply(
                        $this->buildReply($e->getStatus(), $e->getCode())
                    )
                );
            }
        }

    }
