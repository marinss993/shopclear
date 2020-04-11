<?php
    /*
    * @version      1.0.0 20.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    class JshoppingViewAddon_api extends JViewLegacy {

        public function display($tpl = null) {
            if (isset($this->tool_bar['title'])) {
                call_user_func_array('JToolBarHelper::title', $this->tool_bar['title']);
            }
            if ($this->tool_bar['buttons']) {
                foreach ($this->tool_bar['buttons'] as $button) {
                    if (method_exists('JToolbarHelper', $button[0])) {
                        call_user_func_array('JToolbarHelper::' . $button[0], array_slice($button, 1));
                        continue;
                    }
                    call_user_func_array('JToolbarHelper::custom', $button);
                }
            }
            parent::display($tpl);
        }

    }
