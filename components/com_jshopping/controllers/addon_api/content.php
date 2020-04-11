<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_content extends JshoppingControllerAddon_api_subcontroller {

        public function __construct() {
            parent::__construct($config);
            JPluginHelper::importPlugin('content');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerContent', [&$this]);
        }

        public function cartReturnPolicy(): array {
            return parent::_item('return_policy', 0, true);
        }

        public function ids(): array {
            return $this->getModel()->ids();
        }

        public function item(string $id): array {
            return parent::_item($id);
        }

        public function items(array $ids): array {
            return parent::_items($ids);
        }

        public function orderReturnPolicy(int $order_id): array {
            return parent::_item('return_policy', $order_id, false);
        }

    }
