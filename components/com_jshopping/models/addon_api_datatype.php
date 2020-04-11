<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_datatype extends jshopBase {

        public function getType($var) {
            $type = gettype($var);
            switch ($type) {
                case 'boolean':
                    return 'bool';
                case 'integer':
                    return 'int';
                case 'double':
                    return 'float';
                default:
                    return $type;
            }
        }

        public function checkArray(array $array, string $type): bool {
            foreach ($array as $el) {
                if ($this->getType($el) !== $type) {
                    return false;
                }
            }
            return true;
        }

    }
