<?php	

defined('_JEXEC') or die('Restricted access');

$addon = JSFactory::getTable('addon', 'jshop');

$addon->deleteFolders([
	'/templates/joomshopping_black_template/',
	'/components/com_jshopping/addons/addon_joomshopping_black_template',
	'/modules/mod_jshopping_bestseller_products/',
	'/modules/mod_jshopping_cart_ext/',
	'/modules/mod_jshopping_categories/',
	'/modules/mod_jshopping_currencies/',
	'/modules/mod_jshopping_label_products/',
	'/modules/mod_jshopping_latest_products/',
	'/modules/mod_jshopping_manufacturers/',
	'/modules/mod_jshopping_search/',
	'/modules/mod_jshopping_top_rating/',
	'/modules/mod_jshopping_tophits_products/',
	'/modules/mod_jshopping_wishlist/',
	'/modules/mod_recent_comments/'
]);

$addon->deleteFiles([
	'/images/joomshoppingpdemo.png',
	'InstallHelper.php',

	'/language/de-DE/de-DE.mod_jshopping_bestseller_products.ini',
	'/language/de-DE/de-DE.mod_jshopping_cart_ext.ini',
	'/language/de-DE/de-DE.mod_jshopping_categories.ini',
	'/language/de-DE/de-DE.mod_jshopping_label_products.ini',
	'/language/de-DE/de-DE.mod_jshopping_latest_products.ini',
	'/language/de-DE/de-DE.mod_jshopping_manufacturers.ini',
	'/language/de-DE/de-DE.mod_jshopping_search.ini',
	'/language/de-DE/de-DE.mod_jshopping_top_rating.ini',
	'/language/de-DE/de-DE.mod_jshopping_tophits_products.ini',
	'/language/de-DE/de-DE.mod_jshopping_wishlist.ini',

	'/language/en-GB/en-GB.mod_jshopping_bestseller_products.ini',
	'/language/en-GB/en-GB.mod_jshopping_cart_ext.ini',
	'/language/en-GB/en-GB.mod_jshopping_categories.ini',
	'/language/en-GB/en-GB.mod_jshopping_label_products.ini',
	'/language/en-GB/en-GB.mod_jshopping_latest_products.ini',
	'/language/en-GB/en-GB.mod_jshopping_manufacturers.ini',
	'/language/en-GB/en-GB.mod_jshopping_search.ini',
	'/language/en-GB/en-GB.mod_jshopping_top_rating.ini',
	'/language/en-GB/en-GB.mod_jshopping_tophits_products.ini',
	'/language/en-GB/en-GB.mod_jshopping_wishlist.ini',

	'/language/ru-RU/ru-RU.mod_jshopping_bestseller_products.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_cart_ext.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_categories.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_label_products.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_latest_products.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_manufacturers.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_search.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_top_rating.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_tophits_products.ini',
	'/language/ru-RU/ru-RU.mod_jshopping_wishlist.ini'
]);

$queryesArr = [
	"UPDATE `#__jshopping_config` SET `template` = 'protostar' WHERE  `template` = 'joomshopping_black_template'",
	"UPDATE `#__template_styles` SET `home` = '1' WHERE  `template` = 'protostar'",
	"DELETE FROM `#__template_styles` WHERE `template` = 'joomshopping_black_template'",
	"DELETE FROM `#__modules` WHERE `position` = 'home-image' AND `module` = 'mod_custom'",
	"DELETE FROM `#__modules` WHERE `position` = 'login-menu' AND `module` = 'mod_menu'",
	"DELETE FROM `#__modules` WHERE `position` = 'main-menu' AND `module` = 'mod_menu'",
	"DELETE FROM `#__modules` WHERE `position` = 'position-2' AND `module` = 'mod_breadcrumbs'",
	"DELETE FROM `#__modules` WHERE `position` = 'position-7' AND `module` = 'mod_login'",
	"DELETE FROM `#__modules` WHERE `position` = 'shop' AND `module` = 'mod_custom'",
	"DELETE FROM `#__modules` WHERE `position` = 'language-change' AND `module` = 'mod_languages'",

];

$addon->unInstallJoomlaModule('mod_jshopping_bestseller_products');
$addon->unInstallJoomlaModule('mod_jshopping_cart_ext');
$addon->unInstallJoomlaModule('mod_jshopping_categories');
$addon->unInstallJoomlaModule('mod_jshopping_currencies');
$addon->unInstallJoomlaModule('mod_jshopping_label_products');
$addon->unInstallJoomlaModule('mod_jshopping_latest_products');
$addon->unInstallJoomlaModule('mod_jshopping_manufacturers');
$addon->unInstallJoomlaModule('mod_jshopping_search');
$addon->unInstallJoomlaModule('mod_jshopping_top_rating');
$addon->unInstallJoomlaModule('mod_jshopping_tophits_products');
$addon->unInstallJoomlaModule('mod_jshopping_wishlist');
$addon->unInstallJoomlaModule('mod_recent_comments');

$db = JFactory::getDbo();
foreach ($queryesArr as $ky => $query) {
	$db->setQuery($query);
	$db->query();
}