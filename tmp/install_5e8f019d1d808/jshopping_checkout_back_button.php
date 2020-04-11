<?php
    /*
    * @version      1.0.3 19.12.2017
    * @author       MAXXmarketing GmbH
    * @package      jshopping_checkout_back_button
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class plgJshoppingCheckoutJshopping_checkout_back_button extends JPlugin {

        public function onBeforeDisplayCheckoutStep2View(&$view) {
            $this->display($view, 2);
        }

        public function onBeforeDisplayCheckoutStep3View(&$view) {
            $this->display($view, 3);
        }

        public function onBeforeDisplayCheckoutStep4View(&$view) {
            $this->display($view, 4);
        }

        public function onBeforeDisplayCheckoutStep5View(&$view) {
            $this->display($view, 5);
        }

        private function display(&$view, $step) {
            switch ($step) {
                case 2:
                    $link = JRoute::_(
                        'index.php?option=com_jshopping&controller=cart&task=view',
                        1
                    );
                    break;
                default:
                    $link = SEFLink(
                        (
                            'index.php?option=com_jshopping&controller=checkout&task=step' .
                            $this->getPreviousStep($step)
                        ),
                        0,
                        0,
                        JSFactory::getConfig()->use_ssl
                    );
            }
            ob_start();
            include JPluginHelper::getLayoutPath($this->_type, $this->_name);
            $view->{(string) $this->params->get('output_positions_step_' . $step)} .= ob_get_clean();
        }

        private function getPreviousStep($step) {
            $steps = array_values($this->getSteps());
            $key   = array_search($step, $steps);
            if ($key === false) {
                return 2;
            }
            $key--;
            if (!key_exists($key, $steps)) {
                return 2;
            }
            return $steps[$key];
        }

        private function getSteps() {
            $jshopConfig = JSFactory::getConfig();
            $steps       = [
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
            return $steps;
        }

    }
