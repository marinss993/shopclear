<?php
    /*
    * @version      1.0.0 13.07.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_baseadmin extends JshoppingModelBaseadmin {

        protected $entity_name = '';

        public function __construct($config = []) {
            parent::__construct($config);
            $entity_name = $this->entity_name;
            if ($entity_name) {
                $this->nameTable          = $entity_name::getTableName();
                $this->tableFieldPublish  = $entity_name::getPublishName();
                $this->tableFieldOrdering = $entity_name::getOrderingName();
            }
        }

        public function getSidebarHtml() {
            return JHtmlSidebar::render();
        }

        public function getMenuHtml() {
            return JLayoutHelper::render(
                'menu',
                [
                    'items' => $this->getMenu()
                ],
                JPATH_COMPONENT_ADMINISTRATOR . '/views/addon_api/tmpl'
            );
        }

        public function getMenu() {
            $res    = [];
            $link   = 'index.php?option=com_jshopping&controller=addon_api_';
            $active = str_replace(
                'addon_api_',
                '',
                JFactory::getApplication()->input->getCmd('controller', AddonApi::getInst()->getParam('default_menu_item'))
            );
            foreach ([
                'users'       => _JSHOP_USERS,
                'connections' => _JSHOP_ADDON_API_CONNECTIONS,
                'config'      => _JSHOP_CONFIG,
                'about'       => _JSHOP_ADDON_API_ABOUT,
            ] as $key => $val) {
                $res[$key] = (object) [
                    'link'   => $link . $key,
                    'name'   => $val,
                    'active' => $key == $active
                ];
            }
            return $res;
        }

        public function getItems($props = [], $conditions = [], $order = 'ordering', $order_dir = 'ASC', $limitstart = 0, $limit = 0) {
            $db          = JFactory::getDbo();
            $entity_name = $this->entity_name;
            $query       = 'SELECT';
            if ($props) {
                foreach ($props as $prop) {
                    $query .= ' ' . $db->qn($prop) . ', ';
                }
                $query = rtrim($query, ', ');
            }
            else {
                $query .= ' * ';
            }
            $query .= ' FROM ' . $db->qn($entity_name::getTableName()) . ' WHERE 1 ';
            foreach ($conditions as $prop => $val) {
                $query .= ' AND ' . $db->qn($prop) . ' = ' . $db->q($val) . ' ';
            }
            if ($order) {
                $query .= ' ORDER BY ' . $db->qn($order) . ' ' . ($order_dir ? $order_dir : '') . ' ';
            }
            if ($limit) {
                $query .= ' LIMIT ' . intval($limitstart) . ', ' . intval($limit);
            }
            $db->setQuery($query);
            return (array) $db->loadObjectList($entity_name::getIdName());
        }

        public function getEntities($conditions = [], $order = 'ordering', $order_dir = 'ASC', $limitstart = 0, $limit = 0) {
            $res         = [];
            $db          = JFactory::getDbo();
            $entity_name = $this->entity_name;
            $query       = '
                SELECT ' . $db->qn($entity_name::getIdName()) . '
                FROM   ' . $db->qn($entity_name::getTableName()) . '
                WHERE 1
            ';
            foreach ($conditions as $prop => $val) {
                $query .= ' AND ' . $db->qn($prop) . ' = ' . $db->q($val) . ' ';
            }
            if ($order) {
                $query .= ' ORDER BY ' . $db->qn($order) . ' ' . ($order_dir ? $order_dir : '') . ' ';
            }
            if ($limit) {
                $query .= ' LIMIT ' . intval($limitstart) . ', ' . intval($limit);
            }
            $db->setQuery($query);
            foreach ((array) $db->loadColumn() as $id) {
                $res[] = call_user_func($this->entity_name . '::getInst', $id);
            }
            return $res;
        }

        /**
         * @return AddonApiEntity
         * @throws Exception
         */
        public function save(array $post) {
            $entity_name = $this->entity_name;
            $entity      = $entity_name::getInst(isset($post['id']) ? $post['id'] : 0);
            $table       = $this->getTable();
            if (
                empty($post['id']) &&
                property_exists($table, $table->getColumnAlias('ordering'))
            ) {
                $post['ordering'] = (int) $table->getNextOrder();
            }
            foreach ($post as $key => $val) {
                $method = 'set' . JStringNormalise::toCamelCase($key);
                if (method_exists($entity, $method)) {
                    $entity->$method($val);
                }
            }
            if (!$entity->store()) {
                throw new Exception(_JSHOP_ERROR_SAVE_DATABASE);
            };
            return $entity;
        }

        /**
         * @return AddonApiEntity
         * @throws Exception
         */
        public function save2copy(array $post) {
            if (isset($post['id'])) {
                unset($post['id']);
            }
            if (isset($post['name'])) {
                $name    = trim($post['name']);
                $start   = strrpos($name, ' ');
                $postfix = (string) substr($name, $start);
                $postfix = trim($postfix);
                if (strpos($postfix, '(') !== false && strpos($postfix, ')') !== false) {
                    $post['name'] = substr_replace(
                        $post['name'],
                        ((trim($postfix, '()')) + 1) . ')',
                        $start + 2
                    );
                }
                else {
                    $post['name'] .= ' (1)';
                }
            }
            if (isset($post['publish'])) {
                $post['publish'] = 0;
            }
            return $this->save($post);
        }

        public function clearLog($file) {
            $file = JPath::clean($file);
            if (!JFile::exists($file)) {
                return true;
            }
            return (bool) JFile::delete($file);
        }

        public function deleteList(array $cid, $msg = true) {
            $res         = [];
            $addon       = AddonApi::getInst();
            $entity_name = $this->entity_name;
            foreach ($cid as $id) {
                try {
                    $item = $entity_name::getInst($id ? $id : -1);
                } catch (Exception $e) {
                    $res[$id] = false;
                    if ($msg) {
                        $addon->msg($e->getMessage(), 'e');
                    }
                    continue;
                }
                $res[$id] = $item->delete();
                if ($msg) {
                    if ($res[$id]) {
                        $addon->msg(_JSHOP_ADDON_API_SUCCESSFULLY_DELETED);
                    }
                    else {
                        $addon->msg(_JSHOP_ADDON_API_ERROR_DELETE, 'e');
                    }
                }
            }
            $table = $this->getTable();
            if (property_exists($table, $table->getColumnAlias('ordering'))) {
                $table->reorder();
            }
            return $res;
        }

        public function getTable($table = '', $key = '', $db = null) {
            $entity_name = $this->entity_name;
            return new JTableAvto(
                $table ? $table : $entity_name::getTableName(),
                $key   ? $key   : $entity_name::getIdName(),
                $db    ? $db    : JFactory::getDbo()
            );
        }

        public function getDefaultTable(){
            return $this->getTable();
        }

        public function getEntityName() {
            return $this->entity_name;
        }

    }
