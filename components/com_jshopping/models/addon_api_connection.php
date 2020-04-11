<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_connection extends jshopBase {

        /**
         * @throws AddonApiException
         */
        public function connect(array $auth_data): AddonApiConnection {
            if ($auth_data['type'] !== 'bearer') {
                throw new AddonApiException_connection(
                    2,
                    '. \'bearer\' required, \'' . $auth_data['type'] . '\' given'
                );
            }
            $connection = AddonApiConnection::getInst();
            if (!$connection->getId()) {
                throw new AddonApiException_connection(7);
            }
            if ($connection->isExpired()) {
                throw new AddonApiException_connection(8);
            }
            if (!$connection->getApiUser()->getState()) {
                $connection->delete();
                throw new AddonApiException_connection(9);
            }
            $connection->setLastActivityDatetime(getJsDate());
            if ($connection->getSessionId()) {
                $this->restartSession($connection);
            }
            else {
                $connection->setSessionId(JFactory::getSession()->getId());
            }
            $connection->store();
            return $connection;
        }

        public function deleteExpired() {
            $addon = AddonApi::getInst();
            if (
                (
                    $addon->getParam('connections_checking_last_time')
                    +
                    $addon->getParam('connections_checking_interval')
                )
                >
                getJsTimestamp()
            ) {
                return true;
            }
            $res = [];
            $db  = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . AddonApiConnection::getIdName() . '
                FROM '   . AddonApiConnection::getTableName() . '
                WHERE '  . $db->qn('last_activity_datetime') . ' < ' .
                $db->q(
                    getJsDate(time() - $addon->getParam('token[lifetime]'))
                )
            );
            foreach ((array) $db->loadColumn() as $id) {
                $res[] = AddonApiConnection::getInst($id)->delete();
            }
            $res[] = $addon->updateDbParams([
                'connections_checking_last_time' => getJsTimestamp()
            ]);
            return !in_array(false, $res);
        }

        public function getAuthData(bool $cashed = true): array {
            static $res;
            if ($res && $cashed) {
                return $res;
            }
            $header = '';
			if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
				$header = $_SERVER['HTTP_AUTHORIZATION'];
			}
			elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
				$header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
			}
            $header = trim($header);
            if (!$header) {
                return $res = [];
            }
            $space_i    = strpos($header, ' ');
            $space_no   = $space_i === false;
            return $res = array_map(
                'trim',
                [
                    'raw'   => $header,
                    'type'  => strtolower(
                        $space_no ? substr($header, 0) : substr($header, 0, $space_i)
                    ),
                    'value' => $space_no ? '' : substr($header, $space_i)
                ]
            );
        }

        public function getIdByToken(string $token): int {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . AddonApiConnection::getIdName() . '
                FROM '   . AddonApiConnection::getTableName() . '
                WHERE '  . $db->qn('token') . '
                LIKE '   . $db->q($token)
            );
            return (int) $db->loadResult();
        }

        public function info(): array {
            $connection = AddonApiConnection::getInst();
            return [
                'id'                     => $connection->getId(),
                'opening_datetime'       => $connection->getOpeningDatetime(),
                'last_activity_datetime' => $connection->getLastActivityDatetime(),
                'session_id'             => $connection->getSessionId()
            ];
        }

        /**
         * @throws AddonApiException
         */
        public function open(array $auth_data): AddonApiConnection {
            if ($auth_data['type'] !== 'basic') {
                throw new AddonApiException_connection(
                    2,
                    '. \'basic\' required, \'' . $auth_data['type'] . '\' given'
                );
            }
            $arr = explode(':', base64_decode($auth_data['value']));
            if (empty($arr[0])) {
                throw new AddonApiException_connection(3);
            }
            if (empty($arr[1])) {
                throw new AddonApiException_connection(4);
            }
            $connection = AddonApiConnection::getInst();
            $connection->setApiUserId(
                AddonApi::getInst()->getModel('user_api')->login($arr[0], $arr[1])->getId()
            );
            $connection->setSessionId(JFactory::getSession()->getId());
            $connection->setOpeningDatetime(getJsDate());
            $connection->setToken($this->generateToken());
            if (!$connection->getApiUser()->getState()) {
                $connection->delete();
                throw new AddonApiException_connection(9);
            }
            $connection->setLastActivityDatetime(getJsDate());
            $connection->store();
            return $connection;
        }

        public function restartSession(
            AddonApiConnection $connection,
            bool               $delete_current = true
        ): bool {
            $session      = JFactory::getSession();
            $session_name = $session->getName();
            if ($delete_current) {
                $session->destroy();
            }
            else {
                $session->close();
            }
            JFactory::getApplication()->input->cookie->set(
                $session_name,
                $connection->getSessionId()
            );
            $session->start();
            return true;
        }

        public function user(): array {
            $user = AddonApiConnection::getInst()->getApiUser();
            return [
                'id'                => $user->getId(),
                'email'             => $user->getEmail(),
                'creation_datetime' => $user->getCreationDatetime()
            ];
        }

        private function generateToken(int $length = 0): string {
            $res    = '';
            $length = $length ? $length : AddonApi::getInst()->getParam('token[length]');
            do {
                $res = Joomla\CMS\User\UserHelper::genRandomPassword($length);
            } while (
                $this->getIdByToken($res)
            );
            return $res;
        }

    }
