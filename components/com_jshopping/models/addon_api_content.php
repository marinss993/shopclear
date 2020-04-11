<?php
    /*
    * @version      1.0.1 18.10.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingModelAddon_api_content extends JshoppingModelAddon_api {

        public function ids(): array {
            $db = JFactory::getDbo();
            $db->setQuery('
                SELECT ' . $db->qn('alias') . '
                FROM '   . $db->qn('#__jshopping_config_statictext')
            );
            $res = (array) $db->loadColumn();
            sort($res);
            return $res;
        }

        /**
         * @throws AddonApiException
         */
        public function item(
            string $id,
            int    $order_id = 0,
            bool   $cart     = false
        ): array {
            if ($order_id) {
                $order = JSFactory::getTable('order', 'jshop');
                $order->load($order_id);
                if (!$order->order_id) {
                    throw new AddonApiException_order(1, ' ' . $order_id);
                }
                if ($order->user_id != JFactory::getUser()->id) {
                    throw new AddonApiException_user(74, '. Order id ' . $order_id);
                }
            }
            $model   = JSFactory::getModel('contentPage', 'jshop');
            $seodata = JshopHelpersMetadata::content($id);
            $model->setSeodata($seodata);
            $text    = $model->load($id, $order_id, (int) $cart);
            if ($text === false) {
                throw new AddonApiException_content(1, ' \'' . $id . '\'');
            }
            $view    = $this->getView('content');
            $res     = [
                'text' => (string) $text
            ];
            $this->triggerView('onBeforeDisplayContentView', $res, [&$view]);
            return $res;
        }

    }
