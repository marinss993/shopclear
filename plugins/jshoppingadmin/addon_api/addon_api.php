<?php
    /*
    * @version      1.0.0 20.07.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class plgJshoppingAdminAddon_api extends JPlugin {

        public function onAfterUpdateShop(&$extractdir) {
            AddonApi::getInst()->getModel('documentation')->generate();
        }

    }
