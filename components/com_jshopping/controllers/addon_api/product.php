<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_product extends JshoppingControllerAddon_api_subcontroller {

        public function __construct() {
            parent::__construct();
            JPluginHelper::importPlugin('jshoppingproducts');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerProduct', [&$this]);
        }

        public function group(string $group): array {
            return $this->getModel()->group($group);
        }

        public function ids(): array {
            return $this->getModel()->ids();
        }

        public function item(int $id, array $attributes = []): array {
            return parent::_item($id, $attributes);
        }

        public function items(array $ids): array {
            return parent::_items($ids);
        }

        public function search(
            string $search          = '',
            string $search_type     = '',
            array  $categories      = [],
            int    $include_subcat  = 1,
            array  $manufacturers   = [],
            array  $vendors         = [],
            array  $labels          = [],
            float  $price_from      = 0,
            float  $price_to        = 0,
            string $date_from       = '',
            string $date_to         = '',
            array  $extra_fields    = [],
            int    $order           = 0,
            int    $orderby         = 0,
            int    $limit           = 0,
            int    $limitstart      = 0
        ): array {
            return $this->getModel()->search(...func_get_args());
        }

        public function searchInfo() {
            return $this->getModel()->searchInfo();
        }

    }
