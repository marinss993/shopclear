<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class AddonApiUser extends AddonApiEntity {

        protected static
            $publish_name  = 'state',
            $alias         = 'users',
            $alias_item    = 'user',
            $tables_names  = [
                'users' => '#__jshopping_addon_api_users'
            ];

        protected
            $id                = 0,
            $email             = '',
            $password          = '',
            $creation_datetime = '',
            $ordering          = 0,
            $state             = 0,
            $log               = [];

        /**
         * @throws Exception
         * @return AddonApiUser
         */
        public static function getInst($id = 0, $cached = true) {
            return parent::getInst($id, $cached);
        }

        protected function __construct($id = 0) {
            parent::__construct($id);
            if ($this->getId()) {
                /* set log */
                $log_path = $this->getLogPath();
                $log      = @parse_ini_file($log_path, true);
                if ($log) {
                    $this->setLog($log);
                }
            }
        }

        public function setEmail($email) {
            return $this->setProp('email', $email);
        }

        public function setPassword($password) {
            return $this->setProp('password', $password);
        }

        public function setCreationDatetime($creation_datetime) {
            return $this->setProp('creation_datetime', $creation_datetime);
        }

        public function setOrdering($ordering) {
            return $this->setProp('ordering', $ordering);
        }

        public function setState($state) {
            return $this->setProp('state', $state);
        }

        public function setLog(array $log) {
            return $this->setProp('log', $log);
        }

        public function getEmail() {
            return $this->email;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getCreationDatetime($as_timestamp = false) {
            return $as_timestamp ? strtotime($this->creation_datetime) : $this->creation_datetime;
        }

        public function getOrdering() {
            return $this->ordering;
        }

        public function getState() {
            return $this->state;
        }

        public function getLink($front_end = false) {
            return parent::getLink($front_end);
        }

        public function getLog() {
            return $this->log;
        }

        public function getLogPath() {
            return JPath::clean($this->getPath() . '/log.ini');
        }

    }
