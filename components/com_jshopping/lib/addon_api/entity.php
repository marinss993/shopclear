<?php
    /*
    * @version      1.0.8 02.02.2018
    * @author       MAXXmarketing GmbH
    * @package      entity.php
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    abstract class AddonApiEntity extends AddonApiSingleton {

        protected static
            $id_name        = 'id',
            $linked_id_name = '',
            $publish_name   = 'publish',
            $ordering_name  = 'ordering',
            $alias          = '',
            $alias_item     = '',
            $tables_names   = [];

        /**
         * @throws Exception
         */
        public static function getInst($id, $cached = true) {
            return parent::getInst($id, $cached);
        }

        protected function __construct($id) {
            $addon = $this->getAddon();
            /* get default params */
            $default_params = (array) $addon->getParam(static::$alias_item, []);
            /* get entity params */
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT *
                FROM '  . $db->qn(static::getTableName()) . '
                WHERE ' . $db->qn(static::$id_name) . ' = ' . $db->q($id)
            );
            $entity_params = (array) $db->loadAssoc();
            /* get linked tables params */
            $linked_tables_params  = [];
            $linked_id_name        = static::getLinkedIdName();
            foreach ($this->getLinkedTables() as $alias => $name) {
                $key    = $this->getLinkedTableProp($alias);
                $fields = array_diff(
                    array_keys((array) $db->getTableColumns($name)),
                    [$linked_id_name]
                );
                $db->setQuery('
                    SELECT ' . implode(', ', array_map([$db, 'qn'], $fields)) . '
                    FROM   ' . $db->qn($name) . '
                    WHERE  ' . $db->qn($linked_id_name) . ' = ' . $db->q($id)
                );
                $first_field                = reset($fields);
                $linked_tables_params[$key] = (array) $db->loadAssocList($first_field);
                if ((count($fields) == 1)) {
                    foreach ($linked_tables_params[$key] as $k => $v) {
                        $linked_tables_params[$key][$k] = $v[$first_field];
                    }
                }
            }
            /* set params */
            $this->setProps(
                $addon->extendParams(
                    array_merge(
                        $default_params,
                        $entity_params,
                        $linked_tables_params
                    )
                )
            );
        }

        public function store() {
            $res         = [];
            $db          = JFactory::getDbo();
            $table_name  = static::getTableName();
            $fields      = (array) $db->getTableColumns($table_name);
            /* store entity entry */
            $object      = (object) array_intersect_key(get_object_vars($this), $fields);
            foreach (array_keys($fields) as $field) {
                $underscore = strripos($field, '_');
                $prop       = substr($field, 0, $underscore);
                if (substr($field, $underscore) === '_json' && isset($this->$prop)) {
                    $object->$field = json_encode($this->$prop);
                }
            }
            if ($this->getId()) {
                if (!$db->updateObject($table_name, $object, static::$id_name)) {
                    return false;
                }
            }
            else {
                unset($object->{static::$id_name});
                if (!$db->insertObject($table_name, $object, static::$id_name)) {
                    return false;
                }
                $this->setProp(static::$id_name, $db->insertid());
            }
            /* store linked tables entries */
            $linked_id_name = static::getLinkedIdName();
            foreach ($this->getLinkedTables() as $alias => $name) {
                /* delete old ones */
                $db->setQuery('
                    DELETE FROM ' . $db->qn($name) . '
                    WHERE '       . $db->qn($linked_id_name) . ' = ' . $db->q($this->getId())
                );
                $res[] = (bool) $db->execute();
                /* store current ones */
                if (empty($this->{$this->getLinkedTableProp($alias)})) {
                    continue;
                }
                $query = '
                    INSERT INTO ' . $db->qn($name) . '
                    (' .
                        implode(', ', array_map([$db, 'qn'], array_keys((array) $db->getTableColumns($name)))) . '
                    )
                    VALUES ';
                foreach ($this->{$this->getLinkedTableProp($alias)} as $table_value) {
                    $query .= '
                        (' .
                            $db->q($this->getId()) . ', ' .
                            implode(', ', array_map([$db, 'q'], (array) $table_value)) . '
                        ),
                    ';
                }
                $db->setQuery(
                    rtrim(
                        preg_replace('/\s+/', ' ', $query),
                        ', '
                    )
                );
                $res[] = (bool) $db->execute();
            }
            return !in_array(false, $res);
        }

        public function delete() {
            $res  = [];
            $db   = JFactory::getDbo();
            /* delete folder */
            $folder = $this->getPath();
            if (JFolder::exists($folder)) {
                $res[] = JFolder::delete($folder);
            }
            /* delete linked tables entries */
            $linked_id_name = static::getLinkedIdName();
            foreach ($this->getLinkedTables() as $name) {
                $db->setQuery('
                    DELETE
                    FROM '  . $db->qn($name) . '
                    WHERE ' . $db->qn($linked_id_name) . ' = ' . $db->q($this->getId())
                );
                $res[] = (bool) $db->execute();
            }
            /* delete entity entry */
            $db->setQuery('
                DELETE
                FROM '  . $db->qn(static::getTableName()) . '
                WHERE ' . $db->qn(static::$id_name)    . ' = ' . $this->getId()
            );
            $res[] = (bool) $db->execute();
            $res[] = $this->dropProps();
            return !in_array(false, $res);
        }

        protected function getLinkedTables() {
            $res = [];
            foreach (static::$tables_names as $alias => $name) {
                if (isset($this->{$alias}) || isset($this->{$alias . '_ids'})) {
                    $res[$alias] = $name;
                }
            }
            return $res;
        }

        protected function getLinkedTableProp($alias) {
            foreach ([
                $alias,
                $alias . '_ids'
            ] as $prop) {
                if (isset($this->$prop)) {
                    return $prop;
                }
            }
        }

        protected function getAddon() {
            return call_user_func(
                str_replace('Entity', '', __CLASS__) . '::getInst'
            );
        }

        public function getId() {
            return $this->{static::$id_name};
        }

        public static function getIdName() {
            return static::$id_name;
        }

        public static function getLinkedIdName() {
            return (
                static::$linked_id_name
                ? static::$linked_id_name
                : static::$alias_item . '_' . static::$id_name
            );
        }

        public static function getPublishName() {
            return static::$publish_name;
        }

        public static function getOrderingName() {
            return static::$ordering_name;
        }

        public static function getAlias() {
            return static::$alias;
        }

        public static function getAliasItem() {
            return static::$alias_item;
        }

        public static function getTableName($alias = '') {
            return (string) static::$tables_names[$alias ? $alias : static::getAlias()];
        }

        public static function getTablesNames() {
            return static::$tables_names;
        }

        protected function getLink($front_end = false) {
            return (
                JUri::root() .
                ($front_end ? '' : 'administrator/') .
                $this->getAddon()->getParam(static::$alias_item . '[link]') .
                $this->getId()
            );
        }

        protected function getPath() {
            return JPath::clean(
                $this->getAddon()->getParam('dirs_pathes[files]') .
                static::$alias . '/' .
                $this->getId()
            );
        }

    }
