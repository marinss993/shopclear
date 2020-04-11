<?php
    /*
    * @version      1.0.0 22.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_checkout extends JshoppingModelAddon_api {

        private $step_number = 2;

        public function __construct() {
            $this->step_number = (int) JFactory::getSession()->get(
                'jhop_max_step',
                $this->step_number
            );
        }

        private function setStepNumber(int $step_number): bool {
            $this->step_number = $step_number;
            return true;
        }

        private function saveStepNumber(int $step_number): bool {
            $this->step_number = $step_number;
            JSFactory::getModel('checkout', 'jshop')->setMaxStep($step_number);
            return true;
        }

        public function getStepNumber(): int {
            return $this->step_number;
        }

        public function getNextStepNumber(int $step): int {
            $jshopConfig = JSFactory::getConfig();
            switch ($step) {
                case $step < 2:
                case $step > 4:
                    return 2;
                case $step == 2 && $jshopConfig->hide_payment_step:
                case $step == 3 && $jshopConfig->hide_shipping_step:
                    $step++;
                    break;
            }
            return (int) JSFactory::getModel('checkoutstep', 'jshop')->getNextStep($step);
        }

        /**
         * @throws AddonApiException
         */
        public function checkStep(int $step): bool {
            $jshopConfig = JSFactory::getConfig();
            if (!$jshopConfig->shop_user_guest && !JFactory::getUser()->id) {
                throw new AddonApiException_user(2);
            }
            if (
                ($step == 3 && ($jshopConfig->without_payment  || $jshopConfig->hide_payment_step)) ||
                ($step == 4 && ($jshopConfig->without_shipping || $jshopConfig->hide_shipping_step))
            ) {
                throw new AddonApiException_checkout(5, ' (step #' . $step . ')');
            }
            if ($step > $this->getStepNumber()) {
                throw new AddonApiException_checkout(
                    4,
                    '.
                        Current step #' . $this->getStepNumber() . ',
                        requested step #' . $step
                );
            }
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            if ($cart->getCountProduct() <= 0) {
                throw new AddonApiException_checkout(1);
            }
            $sum     = $cart->getPriceProducts();
            $min_sum = $jshopConfig->min_price_order * $jshopConfig->currency_value;
            $max_sum = $jshopConfig->max_price_order * $jshopConfig->currency_value;
            if ($jshopConfig->min_price_order && ($sum < $min_sum)) {
                throw new AddonApiException_checkout(
                    2,
                    '. Current sum ' . $sum . ', minimal ' . $min_sum
                );
            }
            if ($jshopConfig->max_price_order && ($sum > $max_sum)) {
                throw new AddonApiException_checkout(
                    3,
                    '. Current sum ' . $sum . ', maximal ' . $min_sum
                );
            }
            return true;
        }

        public function steps(int $step = 0): array {
            $step        = $step ? $step : $this->getStepNumber();
            $jshopConfig = JSFactory::getConfig();
            if (in_array($step, [0, 1]) && !$jshopConfig->ext_menu_checkout_step) {
                return [];
            }
            $output = [];
            $steps  = [
                0 => 0,
                1 => 1,
                2 => 2,
                4 => 4,
                3 => 3,
                5 => 5
            ];
            if (!$jshopConfig->step_4_3) {
                ksort($steps);
            }
            if (!$jshopConfig->ext_menu_checkout_step) {
                unset($steps[0], $steps[1]);
            }
            if ($jshopConfig->shop_user_guest == 2) {
                unset($steps[1]);
            }
            if ($jshopConfig->without_payment || $jshopConfig->hide_payment_step) {
                unset($steps[3]);
            }
            if ($jshopConfig->without_shipping || $jshopConfig->hide_shipping_step) {
                unset($steps[4]);
            }
            JDispatcher::getInstance()->trigger('onBeforeDisplayCheckoutNavigator', [&$output, &$steps, &$step]);
            return $steps;
        }

        public function step2(): array {
            $jshopConfig = JSFactory::getConfig();
            return [
                'step'            => (int)   2,
                'next_step'       => (int)   $this->getNextStepNumber(2),
                'client_types'    => (array) JshopHelpersSelectOptions::getClientTypes(),
                'countries'       => (array) JshopHelpersSelectOptions::getCountrys(),
                'delivery_adress' => (bool)  (
                    JSFactory::getUser()->loadDataFromEdit()->delivery_adress &&
                    $jshopConfig->getEnableDeliveryFiledRegistration('address')
                ),
                'fields'          => (array) $jshopConfig->getListFieldsRegisterType('address'),
                'titles'          => (array) JshopHelpersSelectOptions::getTitles()
            ];
        }

        /**
         * @throws AddonApiException
         */
        public function step2save(array $input = []): bool {
            $user        = JFactory::getUser();
            $adv_user    = JSFactory::getUser();
            $jshopConfig = JSFactory::getConfig();
            $dispatcher  = JDispatcher::getInstance();
            $model       = JSFactory::getModel('useredit', 'jshop');
            $dispatcher->trigger('onLoadCheckoutStep2save', [&$input]);
            $model->setUser($adv_user);
            $model->setData($input);
            if (!$model->check('address')) {
                throw new AddonApiException_user(
                    AddonApi::getInst()->getModel('user')->getStatusCodeByMsg(
                        $model->getError()
                    )
                );
            }
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            $dispatcher->trigger('onBeforeSaveCheckoutStep2', [&$adv_user, &$user, &$cart, &$model]);
            if (!$model->save()) {
                throw new AddonApiException_server(2);
            }
            setNextUpdatePrices();
            $checkout = JSFactory::getModel('checkout', 'jshop');
            $checkout->setCart($cart);
            $checkout->setEmptyCheckoutPrices();
            $dispatcher->trigger('onAfterSaveCheckoutStep2', [&$adv_user, &$user, &$cart]);
            if ($jshopConfig->without_payment) {
                $current_step = 3;
            }
            elseif ($jshopConfig->hide_payment_step) {
                $checkout_payment = JSFactory::getModel('checkoutPayment', 'jshop');
                $checkout_payment->setCart($cart);
                $payment_method = (string) $checkout_payment->getCheckoutFirstPaymentClass(
                    $checkout_payment->getCheckoutListPayments()
                );
                if (!$payment_method) {
                    throw new AddonApiException_checkout(6);
                }
                $this->setStepNumber(3);
                return $this->step3save($payment_method);
            }
            else {
                $current_step = 2;
            }
            $this->saveStepNumber(
                JSFactory::getModel('checkoutStep', 'jshop')->getNextStep($current_step)
            );
            return true;
        }

        public function step3(): array {
            $jshopConfig = JSFactory::getConfig();
            $adv_user    = JSFactory::getUser();
            $checkout    = JSFactory::getModel('checkoutPayment', 'jshop');
            $cart        = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            $checkout->setCart($cart);
            $paym = $checkout->getCheckoutListPayments();
            return [
                'step'            => (int)   3,
                'next_step'       => (int)   $this->getNextStepNumber(3),
                'payment_methods' => (array) $paym,
                'active_payment'  => (int)   $checkout->getCheckoutActivePayment($paym, $adv_user)
            ];
        }

        /**
         * @throws AddonApiException
         */
        public function step3save(int $payment_id, array $extra_params = []): bool {
            $input          = [];
            $adv_user       = JSFactory::getUser();
            $jshopConfig    = JSFactory::getConfig();
            $dispatcher     = JDispatcher::getInstance();
            $checkout       = JSFactory::getModel('checkoutPayment', 'jshop');
            $payment_method = JSFactory::getTable('paymentmethod', 'jshop');
            $payment_method->load($payment_id);
            $payment_method = (string) $payment_method->payment_class;
            $dispatcher->trigger('onBeforeSaveCheckoutStep3save', [&$input]);
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            $checkout->setCart($cart);
            if (!$checkout->savePaymentData($payment_method, $extra_params, $adv_user)) {
                if ($checkout->getError() == _JSHOP_ERROR_PAYMENT) {
                    throw new AddonApiException_checkout(6, ' with id ' . $payment_id);
                }
                else {
                    throw new AddonApiException_checkout(7);
                }
            }
            $paym_method = $checkout->getActivePaymMethod();
            $dispatcher->trigger('onAfterSaveCheckoutStep3save', [&$adv_user, &$paym_method, &$cart]);
            if ($jshopConfig->without_shipping) {
                $current_step = 4;
            }
            elseif ($jshopConfig->hide_shipping_step) {
                $checkout_shipping = JSFactory::getModel('checkoutShipping', 'jshop');
                $checkout_shipping->setCart($cart);
                $shipping_method = (int) $checkout_shipping->getCheckoutFirstShipping(
                    $checkout_shipping->getCheckoutListShippings($adv_user)
                );
                if (!$shipping_method) {
                    throw new AddonApiException_checkout(8);
                }
                $this->setStepNumber(4);
                return $this->step4save($shipping_method);
            }
            else {
                $current_step = 3;
            }
            $this->saveStepNumber(
                JSFactory::getModel('checkoutStep', 'jshop')->getNextStep($current_step)
            );
            return true;
        }

        public function step4(): array {
            $adv_user = JSFactory::getUser();
            $checkout = JSFactory::getModel('checkoutShipping', 'jshop');
            $cart     = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            $checkout->setCart($cart);
            $shippings = $checkout->getCheckoutListShippings($adv_user);
            if (
                $checkout->getError() == _JSHOP_REGWARN_COUNTRY ||
                (!$shippings && JSFactory::getConfig()->checkout_step4_show_error_shipping_config)
            ) {
                throw new AddonApiException_checkout(8);
            }
            return [
                'step'             => (int)   4,
                'next_step'        => (int)   $this->getNextStepNumber(4),
                'shipping_methods' => (array) $shippings,
                'active_shipping'  => (int)   $checkout->getCheckoutActiveShipping($shippings, $adv_user)
            ];
        }

        /**
         * @throws AddonApiException
         */
        public function step4save(int $shipping_id, array $extra_params = []): bool {
            $adv_user   = JSFactory::getUser();
            $dispatcher = JDispatcher::getInstance();
            $checkout   = JSFactory::getModel('checkoutShipping', 'jshop');
            $dispatcher->trigger('onBeforeSaveCheckoutStep4save', []);
            $cart = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            $checkout->setCart($cart);
            if (!$checkout->saveShippingData($shipping_id, $extra_params, $adv_user)) {
                if ($checkout->getError() == _JSHOP_ERROR_SHIPPING) {
                    throw new AddonApiException_checkout(8, ' with id ' . $shipping_id);
                }
                else {
                    throw new AddonApiException_checkout(9);
                }
            }
            $shipping_method       = $checkout->getActiveShippingMethod();
            $shipping_method_price = $checkout->getActiveShippingMethodPrice();
            $dispatcher->trigger('onAfterSaveCheckoutStep4', [&$adv_user, &$shipping_method, &$shipping_method_price, &$cart]);
            $next_step = JSFactory::getModel('checkoutStep', 'jshop')->getNextStep(4);
            $this->saveStepNumber($next_step == 3 ? 4 : $next_step);
            return true;
        }

        public function step5(): array {
            $adv_user              = JSFactory::getUser();
            $checkout              = JSFactory::getModel('checkout', 'jshop');
            $cart                  = JSFactory::getModel('cart', 'jshop');
            $cart->load();
            $checkout->setCart($cart);
            $shipping_method       = $checkout->getShippingMethod();
            $shipping_method->name = $shipping_method->getName();
            $payment_method        = $checkout->getPaymentMethod();
            $invoice_info          = $checkout->getInvoiceInfo($adv_user);
            return [
                'step'             => (int)    5,
                'next_step'        => (int)    $this->getNextStepNumber(5),
                'no_return'        => (bool)   $checkout->getNoReturn(),
                'shipping_method'  =>          $shipping_method,
                'payment_name'     => (string) $payment_method->getName(),
                'delivery_info'    => (array)  $checkout->getDeliveryInfo($adv_user, $invoice_info),
                'invoice_info'     => (array)  $invoice_info,
                'delivery_time'    => (string) $checkout->getDeliveryTime(),
                'delivery_date'    => (string) $checkout->getDeliveryDateShow(),
                'delivery_address' => (bool)   JSFactory::getConfig()->getEnableDeliveryFiledRegistration('address')
            ];
        }

        /**
         * @throws AddonApiException
         */
        public function step5save(bool $confirmation, string $payment_back_link, array $extra_params = []): array {
            $checkagb   = $confirmation ? 'on' : '';
            $checkout   = JSFactory::getModel('checkoutOrder', 'jshop');
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onLoadStep5save', [&$checkagb]);
            $cart = JSFactory::getModel('cart', 'jshop')->init();
            $cart->setDisplayItem(1, 1);
            $checkout->setCart($cart);
            if (!$checkout->checkAgb($checkagb)) {
                throw new AddonApiException_checkout(10);
            }
            if (!$cart->checkListProductsQtyInStore()) {
                throw new AddonApiException_cart(4);
            }
            if (!$checkout->checkCoupon()) {
                throw new AddonApiException_cart(11);
            }
            $order     = $checkout->orderDataSave(JSFactory::getUser(), $extra_params);
            $pm_method = $order->getPayment();
            $dispatcher->trigger('onEndCheckoutStep5', [&$order, &$cart]);
            if (
                JSFactory::getConfig()->without_payment ||
                $order->order_total      == 0           ||
                $pm_method->payment_type == 1           ||
                $pm_method->getPaymentSystemData()->paymentSystemVerySimple
            ) {
                return $this->finish($order->order_id, '');
            }
            $checkout->setSendEndForm(0);
            ob_end_clean();
            ob_start();
            register_shutdown_function(function() use($order, $payment_back_link) {
                $addon   = AddonApi::getInst();
                $model   = $addon->getModel('reply');
                $uri     = JUri::getInstance(
                    JUri::getInstance()->toString(['scheme', 'host', 'port']) .
                    SEFLink(
                        (
                            'index.php?' .
                            'option=com_jshopping&' .
                            'controller=addon_api_checkout&' .
                            'task=step7&' .
                            'order_id=' . $order->order_id
                        ),
                        0,
                        1
                    )
                );
                $uri->setVar('back_url', urlencode($payment_back_link));
                $session = JFactory::getSession();
                $session->close();
                $session->start();
                $result = $addon->getModel('checkout')->finish(
                    $order->order_id,
                    preg_replace(
                        '/' . str_replace('/', '\/', JUri::root()) . '.*act=/i',
                        $uri->toString() . '&act=',
                        ob_get_contents()
                    )
                );
                $session->close();
                $session->start();
                $model->reply(
                    $model->formatReply(
                        $model->buildReply('ok', 1, $result)
                    )
                );
            });
            exit(
                AddonApi::getInst()->getModel('payment')->getForm(
                    $checkout,
                    $order,
                    $payment_back_link
                )
            );
        }

        public function step7save(int $order_id, string $act, string $payment_method, bool $no_lang): bool {
            $checkout = JSFactory::getModel('checkoutBuy', 'jshop');
            JDispatcher::getInstance()->trigger('onLoadStep7', []);
            $checkout->saveToLogPaymentData();
            $checkout->setSendEndForm(0);
            $checkout->setAct($act);
            $checkout->setPaymentMethodClass($payment_method);
            $checkout->setNoLang((int) $no_lang);
            if (!$checkout->loadUrlParams()) {
                return false;
            }
            if ($act == 'cancel') {
                if (!$order_id) {
                    $order_id = $checkout->getOrderId();
                }
                if (!$order_id) {
                    return false;
                }
                $checkout->cancelPayOrder($order_id);
                return true;
            }
            if ($act == 'return' && !$checkout->getCheckReturnParams()) {
                $checkout->noCheckReturnExecute();
                return true;
            }
            switch ($checkout->buy()) {
                case 0:
                    return false;
                case 2:
                    return true;
            }
            if ($checkout->checkTransactionNoBuyCode()) {
                return false;
            } else {
                return true;
            }
        }

        private function finish(int $order_id, string $payment_form): array {
            $checkout    = JSFactory::getModel('checkoutFinish', 'jshop');
            $finish_text = $checkout->getFinishStaticText();
            JDispatcher::getInstance()->trigger('onBeforeDisplayCheckoutFinish', [&$finish_text, &$order_id]);
            $checkout->paymentComplete($order_id, $finish_text);
            $checkout->clearAllDataCheckout();
            return [
                'step'         => 5,
                'next_step'    => 2,
                'payment_form' => $payment_form,
                'finish_text'  => $finish_text
            ];
        }

    }
