<?php
    /*
    * @version      0.2.2 29.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class addon_api_pm_sofortueberweisung extends pm_sofortueberweisung {

        public function showEndForm($params, $order) {
            $class = get_parent_class();
            $uri   = JUri::getInstance(
                SEFLink(
                    (
                        'index.php?' .
                        'option=com_jshopping&' .
                        'controller=addon_api_checkout&' .
                        'task=step7&' .
                        'js_paymentclass=' . $class . '&' .
                        'act=return&' .
                        'no_lang=1&' .
                        'order_id=' . $order->order_id
                    ),
                    0,
                    1
                )
            );
            $uri->setVar('back_url', urlencode(func_get_arg(2)));
            $return_url = ltrim($uri->toString(), '/');
            $uri->setVar('act', 'cancel');
            $cancel_url = ltrim($uri->toString(), '/');
            $uri->setVar('act', 'notify');
            $notify_url = ltrim($uri->toString(), '/');
            $inputs     = [
                'user_id'               => $params['user_id'],
                'project_id'            => $params['project_id'],
                'sender_holder'         => '',
                'sender_account_number' => '',
                'sender_bank_code'      => '',
                'sender_country_id'     => '',
                'amount'                => $this->fixOrderTotal($order),
                'currency_id'           => $order->currency_code_iso,
                'reason_1'              => sprintf(_JSHOP_PAYMENT_NUMBER, $order->order_number),
                'reason_2'              => '',
                'user_variable_0'       => $order->order_id,
                'user_variable_1'       => $return_url,
                'user_variable_2'       => $cancel_url,
                'user_variable_3'       => $notify_url,
                'user_variable_4'       => '',
                'user_variable_5'       => '',
                'project_password'      => $params['project_password']
            ];
            $inputs     = array_merge(
                $inputs,
                [
                    'hash'              => sha1(implode('|', $inputs)),
                    'interface_version' => (
                        'joomshopping_' .
                        JApplicationHelper::parseXMLInstallFile(
                            JSFactory::getConfig()->admin_path . 'jshopping.xml'
                        )['version']
                    )
                ]
            );
            $view         = AddonApi::getInst()->getView('payment_form_' . $class);
            $view->inputs = $inputs;
            return $view->loadTemplate();
        }

    }
