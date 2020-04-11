<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgJshoppingMenuAddon_lang_translator extends JPlugin {
	public function __construct(& $subject, $config){
            parent::__construct($subject, $config);
            JSFactory::loadExtLanguageFile('addon_lang_translator');
	}
	
    function onBeforeAdminOptionPanelMenuDisplay(&$menu)
    	{
            $menu['langtransl'] = array(_JSHOP_LANG_PACK_EDITING, 'index.php?option=com_jshopping&controller=langpackedit', 'jshop_report_b.png', 1);   	
    	}
    function onBeforeAdminOptionPanelIcoDisplay(&$menu)
    	{
            $menu['langtransl'] = array(_JSHOP_LANG_PACK_EDITING, 'index.php?option=com_jshopping&controller=langpackedit', 'jshop_report_b.png', 1);    	
    	}
}