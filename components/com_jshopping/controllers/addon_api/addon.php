<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_addon extends JshoppingControllerAddon_api_subcontroller {

        public function ids(): array {
            return $this->getModel()->ids();
        }

        public function item(string $id): array {
            return parent::_item($id);
        }

        public function items(array $ids): array {
            return parent::_items($ids);
        }

    }
