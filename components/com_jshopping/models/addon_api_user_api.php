<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_user_api extends jshopBase {

        /**
         * @throws AddonApiException
         */
        public function login(string $email, string $password): AddonApiUser {
            try {
                $id  = $this->getIdByEmail($email);
                $res = AddonApiUser::getInst($id ? $id : -1);
            } catch (Exception $e) {
                throw new AddonApiException_connection(5, ' \'' . $email . '\'');
            }
            if ($password !== $res->getPassword()) {
                throw new AddonApiException_connection(6);
            }
            return $res;
        }

        public function getIdByEmail(string $email): int {
            $db    = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . AddonApiUser::getIdName() . '
                FROM '   . AddonApiUser::getTableName() . '
                WHERE '  . $db->qn('email') . '
                LIKE '   . $db->q($email)
            );
            return (int) $db->loadResult();
        }

        public function getConnections(AddonApiUser $user): array {
            $res = [];
            $db  = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . AddonApiConnection::getIdName() . '
                FROM '   . AddonApiConnection::getTableName() . '
                WHERE '  . $db->qn('api_user_id') . ' = '   . $db->q($user->getId())
            );
            foreach ((array) $db->loadColumn() as $id) {
                $id       = (int) $id;
                $res[$id] = AddonApiConnection::getInst($id);
            }
            return $res;
        }

        public function log(
            AddonApiUser $user,
            string       $msg,
            string       $file = '',
            int          $line = 0,
            int          $code = 0
        ): bool {
            if (!$user->getId()) {
                return false;
            }
            $res    = [];
            $caller = debug_backtrace()[0];
            $path   = $user->getLogPath();
            $res[]  = AddonApi::getInst()->log(
                $msg,
                $file ? $file : $caller['file'],
                $line ? $line : $caller['line'],
                $code,
                [],
                $path
            );
            if (end($res)) {
                $res[] = $user->setLog(@parse_ini_file($path, true));
            }
            return !in_array(false, $res);
        }

    }
