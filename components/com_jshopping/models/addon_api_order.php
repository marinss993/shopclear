<?php
    /*
    * @version      1.0.0 10.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_order extends JshoppingModelAddon_api {

        public function ids(): array {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . $db->qn('order_id') . '
                FROM '   . $db->qn('#__jshopping_orders')
            );
            $res = array_map('intval', (array) $db->loadColumn());
            sort($res);
            return $res;
        }

        /**
         * @throws AddonApiException
         */
        public function item(string $id): jshopOrder {
            $addon = AddonApi::getInst();
            $order = JSFactory::getTable('order');
            $order->load($id);
            if (!$order->order_id) {
                throw new AddonApiException_order(1, ' \'' . $id . '\'');
            }
            $order->prepareBirthdayFormat();
            $order->client_type_name = $order->getClientTypeName();
            $order->coupon_code      = $order->getCouponCode();
            $order->order_tax_list   = $order->getTaxExt();
            $order->payment_name     = $order->getPaymentName();
            filterHTMLSafe($order);
            if ($addon->addonExists('addon_servicebox')) {
                $order->delivery_id = AddonServicebox::getInst()->getModel('order')->getDeliveryId($order);
            }
            return $order;
        }

        public function states(): array {
            $res = [];
            foreach ((array) JSFactory::getModel('orders')->getAllOrderStatus() as $state) {
                $res[$state->status_id] = [
                    'id'   => (int)    $state->status_id,
                    'code' => (string) $state->status_code,
                    'name' => (string) $state->name
                ];
            }
            ksort($res);
            return $res;
        }

    }
