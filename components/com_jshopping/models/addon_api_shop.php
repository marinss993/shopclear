<?php
    /*
    * @version      0.2.6 08.12.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_shop extends JshoppingModelAddon_api {

        public function config(): jshopConfig {
            $res        = JSFactory::getConfig();
            $black_list = (array) AddonApi::getInst()->getParam('shop_config[black_list]');
            foreach ($res as $key => $val) {
                if (
                    in_array($key, $black_list) ||
                    (
                        is_string($val) &&
                        preg_match('/^' . str_replace('\\', '\\\\', JPATH_ROOT) . '/i', $val)
                    )
                ) {
                    unset($res->$key);
                }
            }
            return $res;
        }

    }
