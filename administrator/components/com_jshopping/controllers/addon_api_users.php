<?php
    /*
    * @version      1.0.0 20.07.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_users extends JshoppingControllerAddon_api_baseadmin {

        public function display($cachable = false, $urlparams = false) {
            $app              = JFactory::getApplication();
            $model            = $this->getAdminModel();
            $context          = 'jshoping.list.admin.' . $this->nameController;
            $filter_publish   = $app->getUserStateFromRequest($context . 'filter_publish',   'filter_publish',   '',                         'cmd');
            $filter_order     = $app->getUserStateFromRequest($context . 'filter_order',     'filter_order',     'ordering',                 'cmd');
            $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', 'asc',                      'cmd');
            $limitstart       = $app->getUserStateFromRequest($context . 'limitstart',       'limitstart',       0,                          'int');
            $limit            = $app->getUserStateFromRequest($context . 'limit',            'limit',            $app->getCfg('list_limit'), 'int');
            $conditions       = [];
            if ($filter_publish !== '') {
                $conditions['state'] = $filter_publish;
            }
            $view                   = $this->getView('addon_api', 'html');
            $view->setLayout($this->alias);
            $view->link             = $this->getUrlListItems();
            $view->items            = $model->getEntities($conditions, $filter_order, $filter_order_Dir, $limitstart, $limit);
            $view->filter_publish   = $filter_publish;
            $view->filter_order     = $filter_order;
            $view->filter_order_Dir = $filter_order_Dir;
            $view->pagination       = new JPagination(count($model->getItems(['id'], $conditions)), $limitstart, $limit);
            $view->html_sidebar     = $model->getSidebarHtml();
            $view->html_menu        = $model->getMenuHtml();
            $view->tool_bar         = [
                'title'   => [
                    _JSHOP_ADDON_API . ' - ' . _JSHOP_USERS,
                    'users'
                ],
                'buttons' => [
                    ['addNew']
                ]
            ];
            if ($view->items) {
                $view->tool_bar['buttons'] = array_merge(
                    $view->tool_bar['buttons'],
                    [
                        ['publish'],
                        ['unpublish'],
                        ['deleteList']
                    ]
                );
            }
            $view->display();
            return true;
        }

        public function edit() {
            $id   = $this->input->getInt('id');
            $item = AddonApiUser::getInst($id);
            if ($id && !$item) {
               JFactory::getApplication()->redirect($this->getUrlListItems());
            }
            $view = $this->getView('addon_api', 'html');
            $view->setLayout($this->alias_item);
            $view->link           = $this->getUrlEditItem();
            $view->item           = $item;
            $view_log             = clone $view;
            $view_log->setLayout('log');
            $view_log->log        = array_reverse(
                array_map(
                    function($el) {
                        return [
                            'message'    => $el['message'],
                            'postfields' => $el['postfields'],
                            'ip'         => $el['ip']
                        ];
                    },
                    $item->getLog()
                )
            );
            $view_log->link_clear = $this->getUrlEditItem($item->getId()) . '&task=clearLog&file=' . base64_encode($item->getLogPath());
            $view->html_log       = $view_log->loadTemplate();
            $view->tool_bar       = [
                'title'   => [
                    _JSHOP_USERS . ' - ' . ($item->getId() ? (_JSHOP_EDIT . ' ' . JText::sprintf(_JSHOP_ADDON_API_QUOTES, $item->getEmail())) : _JSHOP_NEW),
                    $item->getId() ? 'pencil' : 'plus-2'
                ],
                'buttons' => [
                    ['apply'],
                    ['save'],
                    ['save2new'],
                    ['cancel']
                ]
            ];
            $view->display();
            return true;
        }

    }
