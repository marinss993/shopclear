<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_baseadmin extends JshoppingControllerBaseadmin {

        protected
            $alias      = '',
            $alias_item = '';

        public function __construct($config = []) {
            parent::__construct($config);
            addSubmenu('other');
            $model = $this->getAdminModel();
            if ($model) {
                $entity_name = $model->getEntityName();
                if ($entity_name) {
                    $this->urlEditParamId = $entity_name::getIdName();
                    $this->alias          = $this->alias      ? $this->alias      : $entity_name::getAlias();
                    $this->alias_item     = $this->alias_item ? $this->alias_item : $entity_name::getAliasItem();
                }
            }
        }

        public function addNew() {
            $this->setRedirect($this->getUrlEditItem());
            return true;
        }

        public function save() {
            if ($this->checkToken['save']) {
                JSession::checkToken() or die('Invalid token');
            }
            $id = $this->input->getInt('id', 0);
            try {
                $entity = $this->getAdminModel()->save($_POST);
            } catch (Exception $e) {
                $this->setRedirect($this->getUrlEditItem($id), $e->getMessage(), 'e');
                return false;
            }
            $this->setRedirect(
                $this->getTask() == 'apply' ? $this->getUrlEditItem($entity->getId()) : $this->getUrlListItems(),
                $id ? _JSHOP_ADDON_API_SUCCESSFULLY_SAVED : _JSHOP_ADDON_API_SUCCESSFULLY_CREATED
            );
            return true;
        }

        public function save2new() {
            $id  = $this->input->getInt('id', 0);
            $res = $this->save();
            $this->setRedirect(
                $this->getUrlEditItem((!$res && $id) ? $id : 0)
            );
            return $res;
        }

        public function save2copy() {
            if (!$this->save()) {
                return false;
            }
            try {
                $entity = $this->getAdminModel()->save2copy($_POST);
            } catch (Exception $e) {
                $this->setRedirect($this->getUrlListItems(), $e->getMessage(), 'e');
                return false;
            }
            $this->setRedirect($this->getUrlEditItem($entity->getId()), _JSHOP_ADDON_API_SUCCESSFULLY_CREATED);
            return true;
        }

        public function cancel() {
            $this->setRedirect($this->getUrlListItems());
            return true;
        }

        public function clearLog() {
            JSFactory::loadExtLanguageFile('addon_api');
            if (
                $this->getModel('addon_api_baseadmin')->clearLog(
                    base64_decode($this->input->getBase64('file'))
                )
            ) {
                $this->setRedirect($_SERVER['HTTP_REFERER'], _JSHOP_ADDON_API_SUCCESSFULLY_CLEARED);
                return true;
            }
            else {
                $this->setRedirect($_SERVER['HTTP_REFERER'], _JSHOP_ADDON_API_ERROR_CLEAR, 'e');
                return false;
            }
        }

        public function setRedirect($url, $msg = '', $type = 'm') {
            if ($msg) {
                JFactory::getApplication()->enqueueMessage(
                    $msg,
                    [
                        'e' => 'error',
                        'm' => 'message',
                        'n' => 'notice',
                        'w' => 'warning'
                    ][$type]
                );
            }
            return parent::setRedirect($url);
        }

        public function getUrlEditItem($id = 0) {
            return 'index.php?option=com_jshopping&controller=' .
                    $this->getNameController() .
                    '&task=edit' .
                    ($id ? ('&' . $this->urlEditParamId . '=' . $id) : '');
        }

        public function getView($name = '', $type = '', $prefix = '', $config = []) {
            $res = parent::getView($name, $type, $prefix, $config);
            if ($res) {
                $res->addon = AddonApi::getInst();
            }
            return $res;
        }

    }
