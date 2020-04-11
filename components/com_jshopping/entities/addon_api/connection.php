<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class AddonApiConnection extends AddonApiEntity {

        protected static
            $alias         = 'connections',
            $alias_item    = 'connection',
            $ordering_name = 'last_activity_datetime',
            $tables_names  = [
                'connections' => '#__jshopping_addon_api_connections'
            ];

        protected
            $id                     = 0,
            $api_user_id            = 0,
            $session_id             = '',
            $opening_datetime       = '',
            $last_activity_datetime = '',
            $token                  = '';

        /**
         * @throws Exception
         * @return AddonApiConnection
         */
        public static function getInst($id = 0, $cached = true) {
            if (!$id && JFactory::getApplication()->isClient('site')) {
               $model = AddonApi::getInst()->getModel('connection');
               $id    = $model->getIdByToken($model->getAuthData()['value']);
            }
            return parent::getInst($id, $cached);
        }

        public function delete() {
            $session = JFactory::getSession();
            if ($session->getId() == $this->getSessionId()) {
                $session->destroy();
            }
            return parent::delete();
        }

        public function isExpired(): bool {
            return (
                ($this->getLastActivityDatetime(true) + AddonApi::getInst()->getParam('token[lifetime]'))
                <
                getJsTimestamp()
            );
        }

        public function setApiUserId($api_user_id) {
            return $this->setProp('api_user_id', $api_user_id);
        }

        public function setSessionId($session_id) {
            return $this->setProp('session_id', $session_id);
        }

        public function setOpeningDatetime($opening_datetime) {
            return $this->setProp('opening_datetime', $opening_datetime);
        }

        public function setLastActivityDatetime($last_activity_datetime) {
            return $this->setProp('last_activity_datetime', $last_activity_datetime);
        }

        public function setToken($token) {
            return $this->setProp('token', $token);
        }

        public function getApiUserId() {
            return $this->api_user_id;
        }

        public function getApiUser(): AddonApiUser {
            return AddonApiUser::getInst($this->api_user_id);
        }

        public function getSessionId() {
            return $this->session_id;
        }

        public function getOpeningDatetime($as_timestamp = false) {
            return $as_timestamp ? strtotime($this->opening_datetime) : $this->opening_datetime;
        }

        public function getLastActivityDatetime($as_timestamp = false) {
            return $as_timestamp ? strtotime($this->last_activity_datetime) : $this->last_activity_datetime;
        }

        public function getToken() {
            return $this->token;
        }

        public function getState() {
            return (
                (
                    $this->getLastActivityDatetime(true)
                    +
                    AddonApi::getInst()->getParam('token[lifetime]')
                )
                >
                getJsTimestamp()
            );
        }

        public function getLink($front_end = false) {
            return parent::getLink($front_end);
        }

    }
