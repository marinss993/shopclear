<?php
    /*
    * @version      1.0.0 20.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_connection extends JshoppingControllerAddon_api_subcontroller {

        public function close(): bool {
            return AddonApiConnection::getInst()->delete();
        }

        public function info(): array {
            return $this->getModel()->info();
        }

        public function open(): string {
            $model = $this->getModel();
            return $model->open($model->getAuthData())->getToken();
        }

        public function user(): array {
            return $this->getModel()->user();
        }

    }
