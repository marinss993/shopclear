<?php
    /*
    * @version      1.0.0 20.07.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    require_once 'addons.php';

    class JshoppingModelAddon_api_config extends JshoppingModelAddons {

        public function save(array $post) {
            $addon = AddonApi::getInst();
            foreach ([
                'connections_checking_last_time'
            ] as $name) {
                $post['params'][$name] = $addon->getParam($name);
            }
            return parent::save($post);
        }

    }
