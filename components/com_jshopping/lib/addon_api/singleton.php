<?php
    /*
    * @version      1.0.3 19.01.2018
    * @author       MAXXmarketing GmbH
    * @package      singleton.php
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    abstract class AddonApiSingleton extends AddonApiProto {

        private static $instances  = [];

        private function __construct() {}

        /**
         * @throws Exception
         */
        public static function getInst($id, $cached = true) {
            $called_class = get_called_class();
            if ($cached && isset(self::$instances[$called_class][$id])) {
                return self::$instances[$called_class][$id];
            }
            $inst = new static($id);
            if (!$inst->getId()) {
                if ($id) {
                    throw new Exception('
                        No instance \'' . get_called_class() . '\'
                        with ' . static::getIdName() . ' ' . $id
                    );
                }
                return $inst;
            }
            return self::$instances[$called_class][$id] = $inst;
        }

    }
