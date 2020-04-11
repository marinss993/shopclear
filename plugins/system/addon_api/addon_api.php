<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class PlgSystemAddon_api extends JPlugin {

        public function onAfterInitialise() {
            include_once JPATH_SITE . '/components/com_jshopping/lib/' . $this->_name . '/autoload.php';
        }

        public function onAfterRoute() {
            if (JFactory::getApplication()->isClient('administrator')) {
                return;
            }
            include_once JPATH_ROOT . '/components/com_jshopping/lib/factory.php';
            include_once JPATH_ROOT . '/components/com_jshopping/loadparams.php';
            $addon = AddonApi::getInst();
            if ($addon->getParam('test_mode') || !$addon->isApiRequest()) {
                return;
            }
            JObserverMapper::addObserverClassToClass('AddonApiObserver_payment_method', 'jshopPaymentMethod');
            set_exception_handler(
                function(Throwable $e) use($addon) {
                    $addon->getModel('reply')->replyFailure(
                        'Uncaught exception: ' . $e->getMessage(),
                        $e->getFile(),
                        $e->getLine(),
                        get_class($e)
                    );
                }
            );
            register_shutdown_function(
                function() use($addon) {
                    $error        = error_get_last();
                    $fatal_errors = [
                        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
                        E_CORE_ERROR    => 'E_CORE_ERROR',
                        E_ERROR         => 'E_ERROR',
                        E_USER_ERROR    => 'E_USER_ERROR'
                    ];
                    if (
                        !isset($error['type']) ||
                        !key_exists($error['type'], $fatal_errors)
                    ) {
                        return;
                    }
                    $addon->getModel('reply')->replyFailure(
                        'Fatal error. ' . $error['message'],
                        $error['file'],
                        $error['line'],
                        $fatal_errors[$error['type']]
                    );
                }
            );
        }

    }
