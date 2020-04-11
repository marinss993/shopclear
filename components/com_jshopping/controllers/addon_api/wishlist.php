<?php
    /*
    * @version      1.0.0 22.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    include_once 'cart.php';

    class JshoppingControllerAddon_api_subcontroller_wishlist extends JshoppingControllerAddon_api_subcontroller_cart {

        protected
            $type = 'wishlist';

        public function __construct() {
            parent::__construct();
            JPluginHelper::importPlugin('jshoppingcheckout');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerWishlist', [&$this]);
        }

        public function add(
            int   $product_id,
            int   $quantity          = 1,
            array $attributes        = [],
            array $freeattributes    = [],
            array $additional_fields = []
        ): bool {
            return parent::add(
                $product_id,
                $quantity,
                $attributes,
                $freeattributes,
                $additional_fields
            );
        }

        public function clear(): bool {
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load($this->type);
            $cart->clear();
            return true;
        }

        public function delete(int $index): bool {
            return parent::delete($index);
        }

        public function info(): array {
            return parent::info();
        }

        public function toCart(int $index): bool {
            $wishlist = JSFactory::getModel('cart', 'jshop');
            $wishlist->load($this->type);
            if (!key_exists($index, $wishlist->products)) {
                $this->setCode(2);
                $this->setNote('No item #' . $index . ' in the ' . $this->type);
            }
            JSFactory::getModel('checkout', 'jshop')->removeWishlistItemToCart($index);
            return true;
        }

        public function update(array $quantities): bool {
            return $this->getModel('cart')->update($quantities, $this->type);
        }

    }
