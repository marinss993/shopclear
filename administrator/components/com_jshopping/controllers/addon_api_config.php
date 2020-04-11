<?php
    /*
    * @version      1.0.0 20.07.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_config extends JshoppingControllerAddon_api_baseadmin {

        protected
            $alias = 'config';

        public function display($cachable = false, $urlparams = false) {
            $view  = $this->getView('addon_api', 'html');
            $model = $view->addon->getModel('baseadmin');
            $view->setLayout($this->alias);
            $view->link           = $this->getUrlListItems();
            $view->item           = (object) $view->addon->getParams();
            $view->html_sidebar   = $model->getSidebarHtml();
            $view->html_menu      = $model->getMenuHtml();
            $view->menu_items     = $model->getMenu();
            $view->tool_bar       = [
                'title'   => [
                    _JSHOP_ADDON_API . ' - ' . _JSHOP_CONFIG,
                    'cogs'
                ],
                'buttons' => [
                    ['apply']
                ]
            ];
            $view_log             = clone $view;
            $view_log->setLayout('log');
            $view_log->log        = array_reverse($view->addon->getLog());
            $view_log->link_clear = $this->getUrlListItems() . '&task=clearLog&file=' . base64_encode($view->addon->getLogPath());
            $view->html_log       = $view_log->loadTemplate();
            $view->display();
        }

        public function getAdminModel() {}

        public function save() {
            JSFactory::loadExtLanguageFile('addon_api');
            JSFactory::getModel($this->getNameModel())->save($_POST)
                ? $this->setRedirect($this->getUrlListItems(), _JSHOP_ADDON_API_SUCCESSFULLY_SAVED)
                : $this->setRedirect($this->getUrlListItems(), _JSHOP_ADDON_API_ERROR_SAVE, 'e');
        }

    }
