<?php
    /*
    * @version      0.2.0 27.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class AddonApiObserver_payment_method extends JTableObserver {

        public static function createObserver(JObservableInterface $observableObject, $params = []) {
            return new self($observableObject);
        }

        public function onAfterLoad(&$result, $row) {
            if ($this->table->payment_class === 'pm_paypal_plus') {
                $params                         = (new parseString($this->table->payment_params))->parseStringToParams();
                $params['payment_select_step4'] = 0;
                $this->table->payment_params    = (new parseString($params))->splitParamsToString();
            }
        }

    }
