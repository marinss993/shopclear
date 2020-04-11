<?php
    /*
    * @version      0.2.1 28.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_checkout extends JshoppingControllerBase {

        public function __construct($config = []) {
            parent::__construct($config);
            JPluginHelper::importPlugin('jshoppingcheckout');
            JPluginHelper::importPlugin('jshoppingorder');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerCheckout', [&$this]);
        }

        public function step7() {
            $addon    = AddonApi::getInst();
            $act      = (string) $this->input->getCmd('act');
            $back_url = \Joomla\CMS\Uri\Uri::getInstance(
                urldecode($this->input->getString('back_url'))
            );
            if (
                !$addon->getModel('checkout')->step7save(
                    (int)    $this->input->getInt('order_id'),
                    $act,
                    (string) $this->input->getCmd('js_paymentclass'),
                    (bool)   $this->input->getBool('no_lang')
                )
            ) {
                $act = 'error';
            }
            $back_url->setVar('act', $act);
            $addon->redirect($back_url->toString());
        }

    }
