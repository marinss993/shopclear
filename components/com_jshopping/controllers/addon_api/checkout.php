<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_checkout extends JshoppingControllerAddon_api_subcontroller {

        public function __construct() {
            parent::__construct();
            JPluginHelper::importPlugin('jshoppingcheckout');
            JPluginHelper::importPlugin('jshoppingorder');
            JDispatcher::getInstance()->trigger('onConstructJshoppingControllerCheckout', [&$this]);
        }

        public function step2(): array {
            $model = $this->getModel();
            return $model->checkStep(2) ? $model->step2() : [];
        }

        public function step2save(array $input = []): bool {
            $model = $this->getModel();
            return $model->checkStep(2) && $model->step2save($input);
        }

        public function step3(): array {
            $model = $this->getModel();
            return $model->checkStep(3) ? $model->step3() : [];
        }

        public function step3save(int $payment_id, array $extra_params = []): bool {
            $model = $this->getModel();
            return $model->checkStep(3) && $model->step3save($payment_id, $extra_params);
        }

        public function step4(): array {
            $model = $this->getModel();
            return $model->checkStep(4) ? $model->step4() : [];
        }

        public function step4save(int $shipping_id, array $extra_params = []): bool {
            $model = $this->getModel();
            return $model->checkStep(4) && $model->step4save($shipping_id, $extra_params);
        }

        public function step5(): array {
            $model = $this->getModel();
            return $model->checkStep(5) ? $model->step5() : [];
        }

        public function step5save(int $confirmation, string $payment_back_link, array $extra_params = []): array {
            $model = $this->getModel();
            return $model->checkStep(5) ? $model->step5save((bool) $confirmation, $payment_back_link, $extra_params) : [];
        }

        public function stepNumber(): int {
            return $this->getModel()->getStepNumber();
        }

        public function steps(): array {
            return $this->getModel()->steps();
        }

    }
