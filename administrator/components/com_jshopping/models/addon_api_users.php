<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_users extends JshoppingModelAddon_api_baseadmin {

        protected $entity_name = 'AddonApiUser';

        /**
         * @return AddonApiUser
         * @throws Exception
         */
        public function save(array $post) {
            $addon = AddonApi::getInst();
            foreach ([
                'email' => _JSHOP_EMAIL
            ] as $key => $name) {
                if (empty($post[$key])) {
                    throw new Exception(JText::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $name));
                }
            }
            if (empty($post['id'])) {
                $email = trim($post['email']);
                if ($addon->getModel('user_api')->getIdByEmail($email)) {
                    throw new Exception(
                        JText::sprintf(_JSHOP_ADDON_API_EMAIL_IN_USE, $email)
                    );
                }
                $post['creation_datetime'] = getJsDate();
            }
            if (!empty($post['password'])) {
                $post['password']    = trim($post['password']);
                $password_length_min = $addon->getParam('user[password_length_min]');
                $password_length_max = $addon->getParam('user[password_length_max]');
                if (
                    strlen($post['password']) < $password_length_min ||
                    strlen($post['password']) > $password_length_max
                ) {
                    throw new Exception(
                        JText::sprintf(
                            _JSHOP_ADDON_API_ERROR_PASSRORD_LENGTH,
                            $password_length_min,
                            $password_length_max
                        )
                    );
                }
            }
            return parent::save($post);
        }

        public function deleteList(array $cid, $msg = true) {
            $res   = [];
            $addon = AddonApi::getInst();
            $model = $addon->getModel('user_api');
            foreach ($cid as $id) {
                try {
                    $user = AddonApiUser::getInst($id ? $id : -1);
                } catch (Exception $e) {
                    $res[$id] = false;
                    if ($msg) {
                        $addon->msg($e->getMessage(), 'e');
                    }
                    continue;
                }
                if ($model->getConnections($user)) {
                    $res[$id] = false;
                    if ($msg) {
                        $addon->msg(
                            JText::sprintf(
                                _JSHOP_ADDON_API_DELETE_CONNECTIONS_FIRSTLY,
                                '<a href="' . $user->getLink() . '" target="_blank">' . $user->getEmail() . '</a>'
                            ),
                            'w'
                        );
                    }
                    continue;
                }
                $res[$id] = $user->delete();
                if ($msg) {
                    if ($res[$id]) {
                        $addon->msg(_JSHOP_ADDON_API_SUCCESSFULLY_DELETED);
                    }
                    else {
                        $addon->msg(_JSHOP_ADDON_API_ERROR_DELETE, 'e');
                    }
                }
            }
            $this->getTable()->reorder();
            return $res;
        }

    }
