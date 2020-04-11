<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_user extends JshoppingModelAddon_api {

        /**
         * @throws AddonApiException
         */
        public function activate(string $token): jshopUserShop {
            JFactory::getLanguage()->load('com_users');
            $model = JSFactory::getModel('useractivate', 'jshop');
            if (!$model->check($token)) {
                switch ($model->getError()) {
                    case JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'):
                        throw new AddonApiException_user(70);
                        break;
                    default:
                        throw new AddonApiException_user(71);
                }
            }
            $user = $model->activate($token);
            if (!$user) {
                switch ($model->getError()) {
                    case JText::_('COM_USERS_ACTIVATION_TOKEN_NOT_FOUND'):
                        throw new AddonApiException_user(71);
                        break;
                    case JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'):
                        throw new AddonApiException_user(72, '. Failed to send the email');
                        break;
                    default:
                        throw new AddonApiException_user(72);
                }
            }
            return $this->item($user->id);
        }

        /**
         * @throws AddonApiException
         */
        public function cancelOrder(int $id, int $order_id): bool {
            if (!JFactory::getUser($id)->id) {
                throw new AddonApiException_user(1, ' ' . $id);
            }
            $order = JSFactory::getTable('order');
            $order->load($order_id);
            if (!$order->order_id) {
                throw new AddonApiException_order(1, ' ' . $order_id);
            }
            if ($order->user_id != $id) {
                throw new AddonApiException_user(74, '. Order id ' . $order_id);
            }
            if ($order->order_status == JSFactory::getConfig()->payment_status_for_cancel_client) {
                throw new AddonApiException_order(4, '. Order id ' . $order_id);
            }
            $model = JSFactory::getModel('userOrder', 'jshop');
            $model->setUserId($id);
            $model->setOrderId($order_id);
            if (!$model->userOrderCancel()) {
                throw new AddonApiException_order(3, '. ' . $model->getError());
            }
            return true;
        }

        /**
         * @throws AddonApiException
         */
        public function changePassword(
            int    $id,
            string $old_password,
            string $new_password
        ): bool {
            $user = JFactory::getUser($id);
            if (!$user->id) {
                throw new AddonApiException_user(1, ' ' . $id);
            }
            if (!JUserHelper::verifyPassword($old_password, $user->password)) {
                throw new AddonApiException_user(4);
            }
			$user->password = JUserHelper::hashPassword($new_password);
            if (!$user->save()) {
                throw new AddonApiException_server(3);
            }
			return true;
        }

        /**
         * @throws AddonApiException
         */
        public function create(array $input): jshopUserShop {
            JFactory::getLanguage()->load('com_users');
            $model          = JSFactory::getModel('userregister', 'jshop');
            $useractivation = $model->getUserParams()->get('useractivation');
            $model->setData($input);
            if (!$model->check()) {
                throw new AddonApiException_user(
                    AddonApi::getInst()->getModel('user')->getStatusCodeByMsg(
                        $model->getError()
                    )
                );
            }
            if (!$model->save()) {
                throw new AddonApiException_server(2);
            }
            $model->mailSend();
            $user     = $model->getUserJoomla();
            $usershop = $model->getUser();
            JDispatcher::getInstance()->trigger('onAfterRegister', [&$user, &$usershop, &$input, &$useractivation]);
            return $this->item($user->id);
        }

        public function createInfo(): array {
            return [
                'client_types' => (array) JshopHelpersSelectOptions::getClientTypes(),
                'countries'    => (array) JshopHelpersSelectOptions::getCountrys(),
                'fields'       => (array) JSFactory::getConfig()->getListFieldsRegisterType('register'),
                'titles'       => (array) JshopHelpersSelectOptions::getTitles()
            ];
        }

        /**
         * @throws AddonApiException
         */
        public function edit(int $id, array $input): bool {
            if (!JFactory::getUser($id)->id) {
                throw new AddonApiException_user(1, ' ' . $id);
            }
            JFactory::getLanguage()->load('com_users');
            $model      = JSFactory::getModel('useredit', 'jshop');
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onBeforeAccountSave', [&$input]);
            $model->setUserId($id);
            $model->setData($input);
            if (!$model->check('editaccount')) {
                throw new AddonApiException_user(
                    AddonApi::getInst()->getModel('user')->getStatusCodeByMsg(
                        $model->getError()
                    )
                );
            }
            if (!$model->save()) {
                throw new AddonApiException_server(2);
            }
            $model->updateJoomlaUserCurrentProfile();
            setNextUpdatePrices();
            $dispatcher->trigger('onAfterAccountSave', [&$model]);
            return true;
        }

        public function editInfo(): array {
            return [
                'client_types' => (array) JshopHelpersSelectOptions::getClientTypes(),
                'countries'    => (array) JshopHelpersSelectOptions::getCountrys(),
                'fields'       => (array) JSFactory::getConfig()->getListFieldsRegisterType('editaccount'),
                'titles'       => (array) JshopHelpersSelectOptions::getTitles()
            ];
        }

        public function getStatusCodeByMsg(string $msg): int {
            $params = JComponentHelper::getParams('com_users');
            $code   = (int) array_search(
                $msg,
                [
                    6  => _JSHOP_REGWARN_TITLE,
                    7  => _JSHOP_REGWARN_NAME,
                    8  => _JSHOP_REGWARN_LNAME,
                    9  => _JSHOP_REGWARN_MNAME,
                    10 => _JSHOP_REGWARN_FIRMA_NAME,
                    11 => _JSHOP_REGWARN_CLIENT_TYPE,
                    12 => _JSHOP_REGWARN_FIRMA_CODE,
                    13 => _JSHOP_REGWARN_TAX_NUMBER,
                    14 => _JSHOP_REGWARN_MAIL,
                    15 => _JSHOP_REGWARN_BIRTHDAY,
                    16 => _JSHOP_REGWARN_UNAME,
                    17 => sprintf(_JSHOP_VALID_AZ09, _JSHOP_USERNAME, 2),
                    18 => _JSHOP_REGWARN_INUSE,
                    19 => JText::_('COM_USERS_MSG_PASSWORD_TOO_LONG'),
                    20 => JText::_('COM_USERS_MSG_SPACES_IN_PASSWORD'),
                    21 => JText::plural('COM_USERS_MSG_NOT_ENOUGH_INTEGERS_N',          $params->get('minimum_integers')),
                    22 => JText::plural('COM_USERS_MSG_NOT_ENOUGH_SYMBOLS_N',           $params->get('minimum_symbols')),
                    23 => JText::plural('COM_USERS_MSG_NOT_ENOUGH_UPPERCASE_LETTERS_N', $params->get('minimum_uppercase')),
                    24 => JText::plural('COM_USERS_MSG_PASSWORD_TOO_SHORT_N',           $params->get('minimum_length')),
                    25 => _JSHOP_REGWARN_PASSWORD,
                    26 => _JSHOP_REGWARN_PASSWORD_NOT_MATCH,
                    27 => _JSHOP_REGWARN_EMAIL_INUSE,
                    28 => _JSHOP_REGWARN_HOME,
                    29 => _JSHOP_REGWARN_APARTMENT,
                    30 => _JSHOP_REGWARN_STREET,
                    31 => _JSHOP_REGWARN_ZIP,
                    32 => _JSHOP_REGWARN_CITY,
                    33 => _JSHOP_REGWARN_STATE,
                    34 => _JSHOP_REGWARN_COUNTRY,
                    35 => _JSHOP_REGWARN_PHONE,
                    36 => _JSHOP_REGWARN_MOBIL_PHONE,
                    37 => _JSHOP_REGWARN_FAX,
                    38 => _JSHOP_REGWARN_EXT_FIELD_1,
                    39 => _JSHOP_REGWARN_EXT_FIELD_2,
                    40 => _JSHOP_REGWARN_EXT_FIELD_3,
                    41 => _JSHOP_REGWARN_TITLE_DELIVERY,
                    42 => _JSHOP_REGWARN_NAME_DELIVERY,
                    43 => _JSHOP_REGWARN_LNAME_DELIVERY,
                    44 => _JSHOP_REGWARN_MNAME_DELIVERY,
                    45 => _JSHOP_REGWARN_FIRMA_NAME_DELIVERY,
                    46 => _JSHOP_REGWARN_FIRMA_CODE_DELIVERY,
                    47 => _JSHOP_REGWARN_TAX_NUMBER_DELIVERY,
                    48 => _JSHOP_REGWARN_MAIL_DELIVERY,
                    49 => _JSHOP_REGWARN_BIRTHDAY_DELIVERY,
                    50 => _JSHOP_REGWARN_HOME_DELIVERY,
                    51 => _JSHOP_REGWARN_APARTMENT_DELIVERY,
                    52 => _JSHOP_REGWARN_STREET_DELIVERY,
                    53 => _JSHOP_REGWARN_ZIP_DELIVERY,
                    54 => _JSHOP_REGWARN_CITY_DELIVERY,
                    55 => _JSHOP_REGWARN_STATE_DELIVERY,
                    56 => _JSHOP_REGWARN_COUNTRY_DELIVERY,
                    57 => _JSHOP_REGWARN_PHONE_DELIVERY,
                    58 => _JSHOP_REGWARN_MOBIL_PHONE_DELIVERY,
                    59 => _JSHOP_REGWARN_FAX_DELIVERY,
                    60 => _JSHOP_REGWARN_EXT_FIELD_1_DELIVERY,
                    61 => _JSHOP_REGWARN_EXT_FIELD_2_DELIVERY,
                    62 => _JSHOP_REGWARN_EXT_FIELD_3_DELIVERY
                ]
            );
            return $code ? $code :5;
        }

        public function groups(): array {
            $res = [];
            foreach ((array) JSFactory::getTable('userGroup')->getList() as $group) {
                $group->id       = $group->usergroup_id;
                $res[$group->id] = $group;
            }
            ksort($res);
            return $res;
        }

        public function ids(): array {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . $db->qn('user_id') . '
                FROM '   . $db->qn('#__jshopping_users')
            );
            $res = array_map('intval', (array) $db->loadColumn());
            sort($res);
            return $res;
        }

        /**
         * @throws AddonApiException
         */
        public function item(int $id): jshopUserShopBase {
            require_once JPATH_ROOT . '/components/com_jshopping/tables/usershop.php';
            $user = new jshopUserShop(JFactory::getDBO());
            $user->load($id);
            if (!$user->user_id) {
                throw new AddonApiException_user(1, ' ' . $id);
            }
            $user->percent_discount       = $user->getDiscount();
            JDispatcher::getInstance()->trigger('onAfterGetUserShopJSFactory', [&$user]);
            $juser = JFactory::getUser($id);
            $user->authorised_view_levels = $juser->getAuthorisedViewLevels();
            $user->orders_ids             = array_unique(
                array_map(
                    function($el) {
                        return (int) $el->order_id;
                    },
                    JSFactory::getTable('order')->getOrdersForUser($id)
                )
            );
            sort($user->orders_ids);
            foreach ([
                'block',
                'registerDate',
                'lastvisitDate',
                'activation'
            ] as $prop) {
                $user->$prop = $juser->$prop;
            }
            if (AddonApi::getInst()->addonExists('addon_servicebox')) {
                $model                     = AddonServicebox::getInst()->getModel('user');
                $user->company_id          = $model->getCompanyId($juser);
                $user->delivery_points_ids = array_keys($model->getDeliveryPoints($juser));
            }
            return $user;
        }

        /**
         * @throws AddonApiException
         */
        public function login(string $username, string $password): bool {
            $connection = AddonApiConnection::getInst();
            if (JFactory::getUser()->id) {
                $this->logout();
            }
            if (!JSFactory::getModel('userlogin', 'jshop')->login($username, $password)) {
                throw new AddonApiException_user(3);
            }
            if (!JFactory::getUser()->id) {
                throw new AddonApiException_user(73);
            }
            $connection->setSessionId(JFactory::getSession()->getId());
            $connection->store();
            setNextUpdatePrices();
            return true;
        }

        public function logout(): bool {
            JSFactory::getModel('userlogin', 'jshop')->logout();
            $connection = AddonApiConnection::getInst();
            $connection->setSessionId('');
            $connection->store();
            return true;
        }

        /**
         * @throws AddonApiException
         */
        public function order(int $id, int $order_id): array {
            $user        = JFactory::getUser($id);
            if (!$user->id) {
                throw new AddonApiException_user(1, ' ' . $id);
            }
            $order       = JSFactory::getTable('order');
            $dispatcher  = JDispatcher::getInstance();
            $jshopConfig = JSFactory::getConfig();
            $order->load($order_id);
            $dispatcher->trigger('onAfterLoadOrder', [&$order, &$user]);
            if (!$order->order_id) {
                throw new AddonApiException_order(1, ' ' . $order_id);
            }
            if ($order->user_id != $id) {
                throw new AddonApiException_user(74, '. Order id ' . $order_id);
            }
            $order->prepareOrderPrint('order_show');
            $allow_cancel     = $order->getClientAllowCancel();
            $hide_subtotal    = $order->getHideSubtotal();
            $show_percent_tax = $order->getShowPercentTax();
            $text_total       = $order->getTextTotal();
            $order->fixConfigShowWeightOrder();
            $order->loadItemsNewDigitalProducts();
            $dispatcher->trigger('onBeforeDisplayOrder', [&$order]);
            return [
                'allow_cancel'     => (bool)   $allow_cancel,
                'config_fields'    => (array)  $jshopConfig->getListFieldsRegisterType('address'),
                'delivery_address' => (bool)   $jshopConfig->getEnableDeliveryFiledRegistration('address'),
                'hide_subtotal'    => (bool)   $hide_subtotal,
                'image_path'       => (string) $jshopConfig->live_path . 'images',
                'order'            =>          $order,
                'show_percent_tax' => (bool)   $show_percent_tax,
                'text_total'       => (string) $text_total
            ];
        }

        public function orders(int $id, array $orders_ids): array {
            $res = [];
            sort($orders_ids);
            foreach ($orders_ids as $order_id) {
                $res[] = $this->order($id, $order_id);
            }
            return $res;
        }

    }
