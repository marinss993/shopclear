<?php
    /*
    * @version      0.2.1 29.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_payment extends JshoppingModelAddon_api {

        public function getForm(
            jshopCheckoutOrder $checkout,
            jshopOrder         $order,
            string             $back_link
        ) : string {
            $pm_method = $order->getPayment();
            $path      = JPath::clean(
                AddonApi::getInst()->getParam('dirs_pathes[payments]') . $pm_method->payment_class . '.php'
            );
            if (!JFile::exists($path)) {
                return (string) $checkout->showEndFormPaymentSystem($order->order_id);
            }
            $class = AddonApi::getInst()->getAlias() . '_' . $pm_method->payment_class;
            $cart  = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            JDispatcher::getInstance()->trigger('onBeforeShowEndFormStep6', [&$order, &$cart, $pm_method]);
            $checkout->setSendEndForm(1);
            require_once $path;
            return (new $class)->showEndForm($pm_method->getConfigs(), $order, $back_link);
        }

    }
