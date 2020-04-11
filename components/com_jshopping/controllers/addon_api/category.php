<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_category extends JshoppingControllerAddon_api_subcontroller {

        public function __construct() {
            parent::__construct();
            JPluginHelper::importPlugin('jshoppingproducts');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerProduct', [&$this]);
        }

        public function ids(): array {
            return $this->getModel()->ids();
        }

        public function item(int $id): array {
            return parent::_item($id);
        }

        public function items(array $ids): array {
            return parent::_items($ids);
        }

        public function tree(): array {
            return $this->getModel()->tree();
        }

    }
