<?php
	defined('_JEXEC') or die('Restricted access');
	$db = JFactory::getDbo();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'jshopping_menu_builder' AND folder = 'jshoppingmenu' AND `type` = 'plugin'");
	$db->query();
	
	$db->setQuery('DROP TABLE IF EXISTS `#__jshopping_menu_config`');
	$db->query();
		
	jimport('joomla.filesystem.folder');
	foreach(array(
		'plugins/jshoppingmenu/jshopping_menu_builder/',
		'administrator/components/com_jshopping/lang/addon_jshopping_menu_builder/',
		'administrator/components/com_jshopping/views/addon_menu_builder_edit/',
		'administrator/components/com_jshopping/views/addon_menu_builder_list/',
		'components/com_jshopping/addons/jshopping_menu_builder/'
	) as $folder){JFolder::delete(JPATH_ROOT.'/'.$folder);}
	
	jimport('joomla.filesystem.file');
	foreach(array(
		'administrator/components/com_jshopping/controllers/addon_menu_builder.php',
		'administrator/components/com_jshopping/models/addon_menu_builder.php',
		'administrator/language/en-GB/en-GB.com_jshopping_addon_menu_builder.ini',
		'administrator/language/de-DE/de-DE.com_jshopping_addon_menu_builder.ini',
		'administrator/language/ru-RU/ru-RU.com_jshopping_addon_menu_builder.ini'
	) as $file){JFile::delete(JPATH_ROOT.'/'.$file);}
?>