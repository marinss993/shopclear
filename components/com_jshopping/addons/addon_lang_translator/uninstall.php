<?php
	defined('_JEXEC') or die('Restricted access');
	$db = JFactory::getDbo();
	
	$name = "Joomshopping language pack editor";
	$element = "addon_lang_translator";
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE `element`='".$element."' AND `folder`='jshoppingmenu' AND `type`='plugin'");
        $db->query();
    
	// delete folder
	jimport('joomla.filesystem.folder');
	foreach(array(
		'components/com_jshopping/addons/'.$element.'/',
		'components/com_jshopping/lang/'.$element.'/',
		'administrator/components/com_jshopping/views/langpackedit/',
		'plugins/jshoppingmenu/'.$element.'/'
	) as $folder){JFolder::delete(JPATH_ROOT.'/'.$folder);}
	// delete file
	jimport('joomla.filesystem.file');
	foreach(array(
		'administrator/components/com_jshopping/controllers/langpackedit.php'
	) as $file){JFile::delete(JPATH_ROOT.'/'.$file);}
?>