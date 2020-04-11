<?php
    /*
    * @version      0.2.6 08.12.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingControllerAddon_api_subcontroller_shop extends JshoppingControllerAddon_api_subcontroller {

        public function config(): jshopConfig {
            return $this->getModel()->config();
        }

    }
