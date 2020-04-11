<?php
/**
* @version      4.1.0 28.02.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2012 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die;
jimport( 'joomla.application.component.view');

class JshoppingViewAddon_menu_builder_list extends JViewLegacy
{
    function display($tpl = null){
        JToolBarHelper::title( JText::_("COM_JSHOP_MENU_BUILDER"), 'menumgr.png' ); 
        JToolBarHelper::addNew();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::divider();
		JToolBarHelper::trash('trash');
		JToolBarHelper::deleteList();
		JToolBarHelper::divider();
		JToolBarHelper::makeDefault('setDefault', 'COM_MENUS_TOOLBAR_SET_HOME');
        parent::display($tpl);
	}
}
?>