<?php
    /*
    * @version      1.0.0 20.07.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_connections extends JshoppingControllerAddon_api_baseadmin {

        public function display($cachable = false, $urlparams = false) {
            AddonApi::getInst()->getModel('connection')->deleteExpired();
            $app              = JFactory::getApplication();
            $model            = $this->getAdminModel();
            $context          = 'jshoping.list.admin.' . $this->nameController;
            $filter_api_user  = $app->getUserStateFromRequest($context . 'filter_api_user',  'filter_api_user',  '',                         'cmd');
            $filter_publish   = $app->getUserStateFromRequest($context . 'filter_publish',   'filter_publish',   '',                         'cmd');
            $filter_order     = $app->getUserStateFromRequest($context . 'filter_order',     'filter_order',     'last_activity_datetime',   'cmd');
            $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'desc',                     'cmd');
            $limitstart       = $app->getUserStateFromRequest($context . 'limitstart',       'limitstart',       0,                          'int');
            $limit            = $app->getUserStateFromRequest($context . 'limit',            'limit',            $app->getCfg('list_limit'), 'int');
            $conditions       = [];
            if ($filter_api_user !== '') {
                $conditions['api_user_id'] = $filter_api_user;
            }
            if ($filter_publish !== '') {
                $conditions['state'] = $filter_publish;
            }
            $view                   = $this->getView('addon_api', 'html');
            $view->setLayout($this->alias);
            $view->link             = $this->getUrlListItems();
            $view->items            = $model->getEntities($conditions, $filter_order, $filter_order_Dir, $limitstart, $limit);
            $view->api_users        = array_map(
                function($el) {
                    return $el->email;
                },
                JSFactory::getModel('addon_api_users')->getItems([AddonApiUser::getIdName(), 'email'])
            );
            $view->filter_api_user  = $filter_api_user;
            $view->filter_publish   = $filter_publish;
            $view->filter_order     = $filter_order;
            $view->filter_order_Dir = $filter_order_Dir;
            $view->pagination       = new JPagination(count($model->getItems(['id'], $conditions)), $limitstart, $limit);
            $view->html_sidebar     = $model->getSidebarHtml();
            $view->html_menu        = $model->getMenuHtml();
            $view->tool_bar         = [
                'title'   => [
                    _JSHOP_ADDON_API . ' - ' . constant('_JSHOP_ADDON_API_' . strtoupper($this->alias)),
                    'power-cord'
                ]
            ];
            if ($view->items) {
                $view->tool_bar['buttons'] = array_merge(
                    [
                        ['deleteList']
                    ]
                );
            }
            $view->display();
            return true;
        }

        public function edit() {
            $id   = $this->input->getInt('id');
            $item = AddonApiConnection::getInst($id);
            if ($id && !$item) {
               JFactory::getApplication()->redirect($this->getUrlListItems());
            }
            $view = $this->getView('addon_api', 'html');
            $view->setLayout($this->alias_item);
            $view->link     = $this->getUrlEditItem();
            $view->item     = $item;
            $view->tool_bar = [
                'title'   => [
                    constant('_JSHOP_ADDON_API_' . strtoupper($this->alias)) . ': ' . $item->getToken(),
                    'pencil'
                ],
                'buttons' => [
                    ['cancel']
                ]
            ];
            $view->display();
            return true;
        }

    }
