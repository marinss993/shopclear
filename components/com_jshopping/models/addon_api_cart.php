<?php
    /*
    * @version      0.2.6 08.12.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_cart extends JshoppingModelAddon_api {

        /**
         * @throws AddonApiException
         */
        public function add(
            int    $product_id,
            int    $quantity          = 1,
            array  $attributes        = [],
            array  $freeattributes    = [],
            array  $additional_fields = [],
            string $type              = 'cart'
        ): bool {
            $db        = JFactory::getDbo();
            $exception = $this->getExceptionClassName();
            $product   = JSFactory::getTable('product', 'jshop');
            $product->load($product_id);
            if (!$product->product_id) {
                throw new AddonApiException_product(1, ' ' . $product_id);
            }
            foreach ($attributes as $attribute_id => $attribute_value) {
                $db->setQuery('
                    SELECT ' . $db->qn('attr_id') . '
                    FROM '   . $db->qn('#__jshopping_attr') . '
                    WHERE '  . $db->qn('attr_id') . ' = ' . $db->q($attribute_id)
                );
                if (!$db->loadResult()) {
                    throw new $exception(7, ' ' . $attribute_id);
                }
                $db->setQuery('
                    SELECT ' . $db->qn('value_id') . '
                    FROM '   . $db->qn('#__jshopping_attr_values') . '
                    WHERE '  . $db->qn('attr_id')  . ' = ' . $db->q($attribute_id) . '
                    AND '    . $db->qn('value_id') . ' = ' . $db->q($attribute_value)
                );
                if (!$db->loadResult()) {
                    throw new $exception(
                        8,
                        ' \'' . $attribute_value . '\' for atribute with id ' . $attribute_id
                    );
                }
            }
            foreach ($freeattributes as $freeattribute_id => $freeattribute_value) {
                $db->setQuery('
                    SELECT ' . $db->qn('id') . '
                    FROM '   . $db->qn('#__jshopping_free_attr') . '
                    WHERE '  . $db->qn('id') . ' = ' . $db->q($freeattribute_id)
                );
                if (!$db->loadResult()) {
                    throw new $exception(9, ' ' . $freeattribute_id);
                }
            }
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load($type);
            $errors = [];
            if (
                !$cart->add(
                    $product_id,
                    $quantity,
                    $attributes,
                    $freeattributes,
                    $additional_fields,
                    1,
                    $errors,
                    0
                )
            ) {
                foreach ($errors as $code => $error) {
                    switch ($code) {
                        case 100:
                            throw new $exception(1, ' ' . $quantity);
                        case 101:
                            throw new $exception(5);
                        case 102:
                            throw new $exception(6);
                        case 103:
                        case 106:
                            throw new $exception(
                                3,
                                trim('
                                    . Quantity can not be more than ' .
                                    JSFactory::getConfig()->max_count_order_one_product . '. ' .
                                    $quantity . ' sent
                                ')
                            );
                        case 104:
                        case 107:
                            throw new $exception(
                                2,
                                trim('
                                    . Quantity can not be less than ' .
                                    JSFactory::getConfig()->min_count_order_one_product . '. ' .
                                    $quantity . ' sent
                                ')
                            );
                        case 105:
                        case 108:
                            throw new $exception(4);
                    }
                }
            }
            return true;
        }

        public function delete(int $index, string $type = 'cart'): bool {
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load($type);
            $cart->delete($index);
            return true;
        }

        /**
         * @throws AddonApiException
         */
        public function discount(string $code): bool {
            if (!JFactory::getUser()->id) {
                throw new AddonApiException_user(2);
            }
            $cart       = JSFactory::getModel('cart', 'jshop');
            $coupon     = JSFactory::getTable('coupon', 'jshop');
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onLoadDiscountSave', []);
            JSFactory::getModel('checkout', 'jshop')->setMaxStep(2);
            if ($coupon->getEnableCode($code)) {
                $cart->load();
                $dispatcher->trigger('onBeforeDiscountSave', [&$coupon, &$cart]);
                $cart->setRabatt($coupon->coupon_id, $coupon->coupon_type, $coupon->coupon_value);
                $dispatcher->trigger('onAfterDiscountSave', [&$coupon, &$cart]);
            } else {
                throw new AddonApiException_cart(11);
            }
            return true;
        }

        public function getExceptionClassName(): string {
            return (string) preg_replace(
                '/^JshoppingModelAddon_api_/',
                'AddonApiException_',
                __CLASS__
            );
        }

        public function info(): array {
            $jshopConfig = JSFactory::getConfig();
            if (!JshopHelpersCart::checkView()){
                return [];
            }
            $dispatcher  = JDispatcher::getInstance();
            $cart        = JSFactory::getModel('cart', 'jshop')->init('cart', 1);
            $cartpreview = JSFactory::getModel('cartPreview', 'jshop');
            $cartpreview->setCart($cart);
            $cartpreview->setCheckoutStep(0);
            $view        = $this->getView('cart');
            $res         = [
                'products'           => (array)  $cartpreview->getProducts(),
                'summ'               => (float)  $cartpreview->getSubTotal(),
                'image_product_path' => (string) $jshopConfig->image_product_live_path,
                'image_path'         => (string) $jshopConfig->live_path,
                'no_image'           => (string) $jshopConfig->noimage,
                'discount'           => (float)  $cartpreview->getDiscount(),
                'free_discount'      => (float)  $cartpreview->getFreeDiscount(1),
                'use_rabatt'         => (bool)   $jshopConfig->use_rabatt_code,
                'tax_list'           => (array)  $cartpreview->getTaxExt(),
                'fullsumm'           => (float)  $cartpreview->getFullSum(),
                'show_percent_tax'   => (bool)   $cartpreview->getShowPercentTax(),
                'hide_subtotal'      => (bool)   $cartpreview->getHideSubtotal(),
                'weight'             => (float)  $cartpreview->getWeight(),
                'cartdescr'          => (string) $cartpreview->getCartStaticText(),
                'deliverytimes'      => (array)  JSFactory::getAllDeliveryTime()
            ];
            $this->triggerView('onBeforeDisplayCartView', $res, [&$view]);
            return $res;
        }

        public function update(array $quantities, string $type = 'cart'): bool {
            $exception = $this->getExceptionClassName();
            $cart      = JSFactory::getModel('cart', 'jshop');
            $cart->load($type);
            foreach ($quantities as $index => $quantity) {
                if ($quantity <= 0) {
                    throw new $exception(
                        1,
                        ' ' . $quantity . '. Quantity must be more than than zero'
                    );
                }
                if (!isset($cart->products[$index])) {
                    throw new $exception(10, ' ' . $index);
                }
            }
            $errors_init = JError::getErrors();
            $res         = (bool) $cart->refresh($quantities);
            if ($errors_init !== JError::getErrors()) {
                switch (JError::getError()->getCode()) {
                    case 111:
                        throw new $exception(
                            3,
                            '. Quantity can not be mode than ' . JSFactory::getConfig()->max_count_order_one_product
                        );
                        break;
                    case 112:
                        throw new $exception(
                            2,
                            '. Quantity can not be less than ' . JSFactory::getConfig()->min_count_order_one_product
                        );
                        break;
                    case 113:
                        throw new $exception(4);
                        break;
                }
                return false;
            }
            JSFactory::getModel('checkout', 'jshop')->setMaxStep(2);
            return $res;
        }

    }
