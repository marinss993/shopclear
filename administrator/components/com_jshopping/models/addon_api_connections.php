<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_connections extends JshoppingModelAddon_api_baseadmin {

        protected
            $entity_name        = 'AddonApiConnection',
            $tableFieldOrdering = 'last_activity_datetime';

        public function getItems($props = [], $conditions = [], $order = 'last_activity_datetime', $order_dir = 'DESC', $limitstart = 0, $limit = 0) {
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
            if (key_exists('state', $conditions)) {
                $query .= '
                    AND ' . $db->qn('last_activity_datetime') .
                    (
                        $conditions['state'] ? ' > ' : ' < '
                    ) .
                    $db->q(
                        getJsDate(time() - AddonApi::getInst()->getParam('token[lifetime]'))
                    );
                unset($conditions['state']);
            }
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

        public function getEntities($conditions = [], $order = 'last_activity_datetime', $order_dir = 'DESC', $limitstart = 0, $limit = 0) {
            $res         = [];
            $db          = JFactory::getDbo();
            $entity_name = $this->entity_name;
            $query       = '
                SELECT ' . $db->qn($entity_name::getIdName()) . '
                FROM   ' . $db->qn($entity_name::getTableName()) . '
                WHERE 1
            ';
            if (key_exists('state', $conditions)) {
                $query .= '
                    AND ' . $db->qn('last_activity_datetime') .
                    (
                        $conditions['state'] ? ' > ' : ' < '
                    ) .
                    $db->q(
                        getJsDate(time() - AddonApi::getInst()->getParam('token[lifetime]'))
                    );
                unset($conditions['state']);
            }
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

    }
