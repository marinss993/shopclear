<?php
    /*
    * @version      1.0.0 12.07.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_about extends JshoppingControllerAddon_api_baseadmin {

        protected
            $alias = 'about';

        public function display($cachable = false, $urlparams = false) {
            $view  = $this->getView('addon_api', 'html');
            $model = $view->addon->getModel('baseadmin');
            $view->setLayout($this->alias);
            $view->html_sidebar = $model->getSidebarHtml();
            $view->html_menu    = $model->getMenuHtml();
            $view->tool_bar     = [
                'title' => [
                    _JSHOP_ADDON_API . ' - ' . _JSHOP_ADDON_API_ABOUT,
                    'info'
                ]
            ];
            $view->display();
        }

    }
