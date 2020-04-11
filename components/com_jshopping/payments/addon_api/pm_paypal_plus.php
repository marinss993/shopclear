<?php
    /*
    * @version      0.2.2 29.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class addon_api_pm_paypal_plus extends pm_paypal_plus {

        /**
         * @throws AddonApiException
         */
        public function showEndForm($params, $order) : string {
            if (!$this->checkLicKey()) {
                throw new AddonApiException_payment(1);
            }
            $class = get_parent_class();
            $uri   = JUri::getInstance(
                JUri::getInstance()->toString(['scheme', 'host', 'port']) .
                SEFLink(
                    (
                        'index.php?' .
                        'option=com_jshopping&' .
                        'controller=addon_api_checkout&' .
                        'task=step7&' .
                        'js_paymentclass=' . $class . '&' .
                        'act=cancel&' .
                        'no_lang=1&' .
                        'oid=' . $order->order_id . '&' .
                        'order_id=' . $order->order_id
                    ),
                    0,
                    1
                )
            );
            $uri->setVar('back_url', urlencode(func_get_arg(2)));
            $cancel_url = $uri->toString();
            $uri->setVar('act', 'notify');
            $notify_url = $uri->toString();
            $uri->setVar('act', 'return');
            $return_url = $uri->toString();
            $country    = JTable::getInstance('country', 'jshop');
            $country->load($order->country);
            $paypal     = new JshopPaypalPlus($params);
            $res        = $paypal->fullProcessCreatePayment([
                'order'            => $order,
                'cancel_url'       => $cancel_url,
                'notify_url'       => $notify_url,
                'return_url'       => $return_url,
                'shipping_address' => 1
            ]);
            if ($res === false) {
                throw new AddonApiException_payment(2, '. ' . $paypal->getError());
            }
            $order->transaction = $res['payment_id'];
            $order->store();
            $view               = AddonApi::getInst()->getView('payment_form_' . $class);
            $view->js_links     = [
                'https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js'
            ];
            $view->js_config    = [
                'approvalUrl'      => $res['approval_url'],
                'placeholder'      => 'ppplus',
                'country'          => $country->country_code_2,
                'language'         => $this->getLocaleCode(JFactory::getLanguage()->getTag()),
                'mode'             => $paypal->getJsMode(),
                'buttonLocation'   => 'inside',
                'showPuiOnSandbox' => 'true',
                'useraction'       => 'commit'
            ];
            return $view->loadTemplate();
        }

    }
