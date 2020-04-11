<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class JshoppingViewAddon_menu_builder_edit extends JViewLegacy
{
    function display($tpl = null){
		JToolBarHelper::title( $temp = ($this->edit) ? JText::_("COM_JSHOP_MENU_BUILDER_EDIT") : JText::_("COM_JSHOP_MENU_BUILDER_NEW"), 'menu-add.png' ); 
        JToolBarHelper::apply();
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        parent::display($tpl);
	}
}
?>