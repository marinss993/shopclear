<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class plgJshoppingMenuAddon_api extends JPlugin {

        public function onBeforeAdminOptionPanelIcoDisplay(&$menu) {
            $this->addMenuItem($menu);
        }

        public function onBeforeAdminOptionPanelMenuDisplay(&$menu) {
            $this->addMenuItem($menu);
        }

        private function addMenuItem(&$menu) {
            $addon        = AddonApi::getInst();
            $alias        = $addon->getAlias();
            $menu[$alias] = [
                constant('_JSHOP_' . strtoupper($alias)),
                (
                    'index.php?option=com_jshopping&controller=' . $alias . '_' .
                    $addon->getParam('default_menu_item')
                ),
                $alias . '/logo.png',
                true
            ];
        }

    }
