<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_user extends JshoppingControllerAddon_api_subcontroller {

        public function __construct() {
            parent::__construct();
            JPluginHelper::importPlugin('jshoppingcheckout');
            JPluginHelper::importPlugin('jshoppingorder');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerUser', [&$this]);
        }

        public function activate(string $token): jshopUserShop {
            return $this->getModel()->activate($token);
        }

        public function cancelOrder(int $id, int $order_id): bool {
            return $this->getModel()->cancelOrder($id, $order_id);
        }

        public function changePassword(
            int    $id,
            string $old_password,
            string $new_password
        ): bool {
            return $this->getModel()->changePassword($id, $old_password, $new_password);
        }

        public function create(array $input): jshopUserShop {
            if (JSFactory::getModel('userregister', 'jshop')->getUserParams()->get('allowUserRegistration') == 0) {
                throw new AddonApiException_user(70);
            }
            return $this->getModel()->create($input);
        }

        public function createInfo(): array {
            if (JSFactory::getModel('userregister', 'jshop')->getUserParams()->get('allowUserRegistration') == 0) {
                throw new AddonApiException_user(70);
            }
            return $this->getModel()->createInfo();
        }

        public function edit(int $id, array $input): bool {
            return $this->getModel()->edit($id, $input);
        }

        public function editInfo(): array {
            return $this->getModel()->editInfo();
        }

        public function groups(): array {
            return $this->getModel()->groups();
        }

        public function ids(): array {
            return $this->getModel()->ids();
        }

        public function item(int $id = 0): jshopUserShopBase {
            return parent::_item($id ? $id : JFactory::getUser()->id);
        }

        public function items(array $ids): array {
            return parent::_items($ids);
        }

        public function login(string $username, string $password): bool {
            $id = JFactory::getUser()->id;
			$this->getModel()->login($username, $password);
            if ($id == JFactory::getUser()->id) {
                $this->setCode(2);
                $this->setNote('Current user is already logged in');
            }
            return true;
        }

        public function logout(): bool {
            if (!JFactory::getUser()->id) {
                $this->setCode(2);
                $this->setNote('Current user is already logged out');
                return true;
            }
            return $this->getModel()->logout();
        }

        public function order(int $id, int $order_id): array {
            return $this->getModel()->order($id, $order_id);
        }

        public function orders(int $id, array $orders_ids): array {
            $this->getModel('adapter')->checkArrayArg($orders_ids, 'int', 'orders_ids');
            return $this->getModel()->orders($id, $orders_ids);
        }

    }
