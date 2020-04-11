<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api extends jshopBase {

        protected function triggerView(string $trigger, array &$assigns, array $args) {
            $view = reset($args);
            foreach ($assigns as $key => $val) {
                $view->$key = $val;
            }
            $res = JDispatcher::getInstance()->trigger($trigger, $args);
            foreach ($assigns as $key => $val) {
                if (property_exists($view, $key)) {
                    $assigns[$key] = $view->$key;
                }
                else {
                    unset($assigns[$key]);
                }
            }
            return $res;
        }

    }
