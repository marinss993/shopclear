<?php
    /*
    * @version      0.2.6 03.12.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_cart extends JshoppingControllerAddon_api_subcontroller {

        protected
            $type = 'cart';

        public function __construct() {
            parent::__construct();
            JPluginHelper::importPlugin('jshoppingcheckout');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerCart', [&$this]);
        }

        public function add(
            int   $product_id,
            int   $quantity          = 1,
            array $attributes        = [],
            array $freeattributes    = [],
            array $additional_fields = []
        ): bool {
            return $this->getModel()->add(
                $product_id,
                $quantity,
                $attributes,
                $freeattributes,
                $additional_fields,
                $this->type
            );
        }

        public function clear(): bool {
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load($this->type);
            $cart->clear();
            return true;
        }

        public function delete(int $index): bool {
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load($this->type);
            if (!key_exists($index, $cart->products)) {
                $this->setCode(2);
                $this->setNote('No item #' . $index . ' in the ' . $this->type);
            }
            return $this->getModel()->delete($index, $this->type);
        }

        public function discount(string $code): bool {
            return $this->getModel()->discount($code);
        }

        public function info(): array {
            return $this->getModel()->info();
        }

        public function update(array $quantities): bool {
            return $this->getModel()->update($quantities);
        }

    }
