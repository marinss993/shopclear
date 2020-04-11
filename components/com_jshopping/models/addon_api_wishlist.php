<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_wishlist extends JshoppingModelAddon_api_cart {

        public function info(): array {
            $jshopConfig = JSFactory::getConfig();
            $cartpreview = JSFactory::getModel('cartPreview', 'jshop');
            $cart        = JSFactory::getModel('cart', 'jshop')->init('wishlist', 1);
            $cartpreview->setCart($cart);
            $cartpreview->setCheckoutStep(0);
            $view        = $this->getView('cart');
            $res         = [
                'products'           => (array)  $cartpreview->getProducts(),
                'image_product_path' => (string) $jshopConfig->image_product_live_path,
                'image_path'         => (string) $jshopConfig->live_path,
                'no_image'           => (string) $jshopConfig->noimage
            ];
            $this->triggerView('onBeforeDisplayWishlistView', $res, [&$view]);
            return $res;
        }

    }
