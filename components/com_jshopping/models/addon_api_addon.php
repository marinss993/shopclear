<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_addon extends JshoppingModelAddon_api {

        public function ids(): array {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . $db->qn('alias') . '
                FROM '   . $db->qn('#__jshopping_addons')
            );
            $res = (array) $db->loadColumn();
            sort($res);
            return $res;
        }

        /**
         * @throws AddonApiException
         */
        public function item(string $id): array {
            $res   = [];
            $table = JSFactory::getTable('addon');
            $table->load([
                'alias' => $id
            ]);
            if (!$table->id) {
                throw new AddonApiException_addon(1, ' \'' . $id . '\'');
            }
            $class_name = implode(
                array_map(
                    function($el) {
                        return ucfirst(strtolower(trim($el)));
                    },
                    explode('_', $id)
                )
            );
            $method = 'getInst';
            $keys   = [
                'name',
                'alias',
                'version',
                'date',
                'logo',
                'author',
                'author_email',
                'author_url'
            ];
            if (
                class_exists($class_name) &&
                method_exists($class_name, $method)
            ) {
                $addon = $class_name::$method();
                foreach ($keys as $key) {
                    $res[$key] = $addon->getParam($key, '');
                }
            }
            else {
                foreach ($keys as $key) {
                    $res[$key] = $table->$key ?? '';
                }
            }
            $logo = 'administrator/components/com_jshopping/images/' . $id . '/logo.png';
            if (JFile::exists(JPATH_ROOT . '/' . $logo)) {
                $res['logo'] = JUri::root() . $logo;
            }
            return $res;
        }

    }
