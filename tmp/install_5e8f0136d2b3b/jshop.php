<?php
defined('_JEXEC') or die;

class PlgQuickiconJshop extends JPlugin{
	
	public function onGetIcons($context){

		return array(
			array(
				'link'  => 'index.php?option=com_jshopping',
				'image' => 'asterisk',
				'icon'  => 'header/icon-48-extension.png',
				'text'  => JText::_('JoomShopping'),
				'id'    => 'plg_quickicon_jshop',
                'access' => array('core.manage', 'com_jshopping'),
				'group' => 'MOD_QUICKICON_EXTENSIONS'
			)
		);
	}
}
