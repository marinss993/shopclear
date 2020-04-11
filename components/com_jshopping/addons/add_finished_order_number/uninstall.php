<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
$db = JFactory::getDbo();

$query = "DELETE FROM `#__extensions` WHERE element='add_finished_order_number' AND folder='jshoppingadmin'";
$db-> setQuery($query);
$db->query();

$query = "DELETE FROM `#__extensions` WHERE element='finished_order_number' AND folder='jshoppingorder'";
$db->setQuery($query);
$db->query();

$query = "ALTER TABLE `#__jshopping_config` DROP COLUMN `next_finished_order_number`";
$db->setQuery($query);
$db->query();

$query = "ALTER TABLE `#__jshopping_orders` DROP COLUMN `finished_number_used`";
$db->setQuery($query);
$db->query();

$query = "DELETE FROM `#__jshopping_shipping_ext_calc` WHERE `alias`='add_finished_order_number'";
$db->setQuery($query);
$db->query();

JFolder::delete(JPATH_ROOT."/plugins/jshoppingadmin/add_finished_order_number");
JFolder::delete(JPATH_ROOT."/plugins/jshoppingorder/finished_order_number");
JFolder::delete(JPATH_ROOT."/components/com_jshopping/lang/addon_finished_order_number");
JFolder::delete(JPATH_ROOT."/components/com_jshopping/addons/add_finished_order_number");

?>