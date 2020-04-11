<?php
/**
* @version      4.1.0 28.02.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping Auction
* @copyright    Copyright (C) 2012 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die;

class plgJshoppingMenuJshopping_menu_builder extends JPlugin {
	
	function __construct(& $subject, $config){
		parent::__construct($subject, $config);
		$language = JFactory::getLanguage();
		$language->load('com_jshopping_addon_menu_builder', JPATH_ADMINISTRATOR, $language->getTag(), true);
	}
	
	function onBeforeAdminMenuDisplay(&$menu, &$vName) {
		$menu['menu_builder'] = array(JText::_("COM_JSHOP_MENU_BUILDER"), 'index.php?option=com_jshopping&controller=addon_menu_builder', $vName == 'menu_builder', 1);
	}
	
	function onBeforeAdminMainPanelIcoDisplay(&$menu) {
		$menu['menu_builder'] = array(JText::_("COM_JSHOP_MENU_BUILDER"), 'index.php?option=com_jshopping&controller=addon_menu_builder', 'jshop_country_list_b.png', 1);
	}
}
?>