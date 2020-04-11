<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class AddonApi extends AddonApiAddonCorext {

        /**
         * @return AddonApi
         */
        public static function getInst($id = 0, $cached = true) {
            return parent::getInst($id, $cached);
        }

        protected function setParams() {
            $res        = parent::setParams();
            $dir_addons = JPath::clean(JPATH_JOOMSHOPPING . '/addons/');
            foreach ((array) JFolder::folders($dir_addons) as $dir) {
                $ini_path = JPath::clean($dir_addons . $dir . '/params.ini');
                if (!JFile::exists($ini_path)) {
                    continue;
                }
                $ini = (array) @parse_ini_file($ini_path, true);
                if (empty($ini['addon_api_reports'])) {
                    continue;
                }
                $key = $ini['alias'] . '_error';
                $this->params['reply']['statuses'][$key] = (
                    implode(' ', array_map('ucfirst', explode('_', $ini['alias']))
                    ) . ' error'
                );
                $this->params['reports'][$ini['alias'] . '_error'] = $this->typify(
                    $ini['addon_api_reports']
                );
            }
            ksort($this->params['reply']['statuses']);
            ksort($this->params['reports']);
            return $res;
        }

        public function isApiRequest() {
            $jinp = JFactory::getApplication()->input->get;
            return (
                $jinp->getCmd('option')     == 'com_jshopping' &&
                $jinp->getCmd('controller') == 'addon_api'
            );
        }

        public function log($msg, $file = '', $line = 0, $code = 0, array $extras = [], $filepath = null) {
            parent::log(
                $msg,
                $file,
                $line,
                $code,
                array_merge(
                    $extras,
                    [
                        'postfields' => print_r($_POST, true)
                    ]
                ),
                $filepath
            );
        }

    }
