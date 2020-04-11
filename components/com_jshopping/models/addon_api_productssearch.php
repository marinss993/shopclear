<?php
    /*
    * @version      0.2.6 08.12.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    require_once 'productssearch.php';

    class JshoppingModelAddon_api_productssearch extends jshopProductsSearch {

        public function getFilterListProduct() {
            $request = JSFactory::getModel('searchrequest', 'jshop');
            $filters = [
                'search'         => (string) $request->getSearch(),
                'search_type'    => (string) $request->getSearchType(),
                'categorys'      => [],
                'include_subcat' => (int)    $request->getIncludeSubcat(),
                'manufacturers'  => [],
                'vendors'        => [],
                'labels'         => [],
                'price_from'     => (float)  $request->getPriceFrom(),
                'price_to'       => (float)  $request->getPriceTo(),
                'date_from'      => (string) $request->getDateFrom(),
                'date_to'        => (string) $request->getDateTo(),
                'extra_fields'   => array_map(
                    function($el) {
                        return is_array($el) ? array_map('intval', $el) : strval($el);
                    },
                    (array) $request->getExtraFields()
                )
            ];
            foreach ((array) $request->getData()['categorys'] as $id) {
                $filters['categorys'][] = $id;
                if ($filters['include_subcat']) {
                    $cat_search = [$id];
                    searchChildCategories(
                        $id,
                        JSFactory::getTable('category', 'jshop')->getAllCategories(),
                        $cat_search
                    );
                    $filters['categorys'] = array_merge(
                        $filters['categorys'],
                        $cat_search
                    );
                }
            }
            foreach ([
                'manufacturers',
                'vendors',
                'labels'
            ] as $key) {
                foreach ((array) $request->getData()[$key] as $id) {
                    $filters[$key][] = $id;
                }
            }
            foreach ([
                'categorys',
                'manufacturers',
                'vendors',
                'labels'
            ] as $key) {
                if (isset($filters[$key])) {
                    $filters[$key] = array_map('intval', array_unique($filters[$key]));
                    sort($filters[$key]);
                }
            }
            return $filters;
        }

    }
