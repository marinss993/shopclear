<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_category extends JshoppingModelAddon_api {

        public function ids(): array {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . $db->qn('category_id') . '
                FROM '   . $db->qn('#__jshopping_categories')
            );
            $res = array_map('intval', (array) $db->loadColumn());
            sort($res);
            return $res;
        }

        /**
         * @throws AddonApiException
         */
        public function item(int $id): array {
            $user        = JFactory::getUser();
            $jshopConfig = JSFactory::getConfig();
            $dispatcher  = JDispatcher::getInstance();
            $category    = JSFactory::getTable('category');
            $category->load($id);
            if (!$category->category_id) {
				throw new AddonApiException_category(1, ' ' . $id);
            }
            $category->getDescription();
            $dispatcher->trigger('onAfterLoadCategory', [&$category, &$user]);
            $sub_categories = $category->getChildCategories(
                $category->getFieldListOrdering(),
                $category->getSortingDirection(),
                1
            );
            $dispatcher->trigger('onBeforeDisplayCategory', [&$category, &$sub_categories]);
            $res  = [
                'category'                 =>          $category,
                'count_category_to_row'    => (int)    $category->getCountToRow(),
                'count_product_to_row'     => (int)    $category->getCountProductsToRow(),
                'filter_show'              =>          true,
                'filter_show_category'     =>          false,
                'filter_show_manufacturer' =>          true,
                'image_category_path'      => (string) $jshopConfig->image_category_live_path,
                'manufacuturers'           => (array)  $category->getManufacturers(),
                'noimage'                  => (string) $jshopConfig->noimage,
                'subcategories'            => (array)  $sub_categories,
            ];
            return $res;
        }

        public function tree(): array {
            $parents = [];
            foreach (JSFactory::getTable('category')->getAllCategories() as $category) {
                $parent_id             = (int) $category->category_parent_id;
                $parents[$parent_id][] = [
                    'id'            => (int) $category->category_id,
                    'parent_id'     => $parent_id,
                    'subcategories' => []
                ];
            }
            ksort($parents);
            $create_node = function($items) use(&$create_node, $parents) {
                $node = [];
                foreach ($items as $item) {
                    if (isset($parents[$item['id']])) {
                        $item['subcategories'] = $create_node($parents[$item['id']]);
                        ksort($item['subcategories']);
                    }
                    $node[$item['id']] = $item;
                }
                return $node;
            };
            return $create_node(reset($parents));
        }

    }
