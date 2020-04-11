<?php
	defined('_JEXEC') or die('Restricted access');
	$db = JFactory::getDbo();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'jshopping_product_seo' AND folder = 'jshoppingrouter' AND `type` = 'plugin'");
	$db->query();
	
	$db->setQuery("DELETE FROM `#__extensions` WHERE element = 'jshopping_product_seo' AND folder = 'jshoppingadmin' AND `type` = 'plugin'");
	$db->query();
	
	jimport('joomla.filesystem.folder');
	foreach(array(
		'plugins/jshoppingrouter/jshopping_product_seo/',
		'plugins/jshoppingadmin/jshopping_product_seo/',
		'components/com_jshopping/addons/jshopping_product_seo/',
		'components/com_jshopping/lang/addon_jshopping_product_seo/'
	) as $folder){JFolder::delete(JPATH_ROOT.'/'.$folder);}
?>