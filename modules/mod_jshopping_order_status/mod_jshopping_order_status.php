<?php
/**
 * Module Check status Order by number. ModSon 2.0 (02.02.2017)
 * @package    Joomla
 * @subpackage JoomShopping
 * @author     Vadim Meling (Linfuby)
 * @authorSite https://linfuby.com/
 * @email      support@linfuby.com
 * @copyright  Copyright by Linfuby. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die;
error_reporting(E_ALL & ~E_NOTICE);

if (!file_exists(JPATH_ROOT . '/components/com_jshopping/jshopping.php')) {
    throw new \Exception('Please install component "JoomShopping"', 500);
}

$db = JFactory::getDBO();
$jshopConfig = JSFactory::getConfig();
$jshopLang = JSFactory::getLang();
$app = JFactory::getApplication();
if ($orderId = $app->input->post->get('modSonOrderId')) {
    $query = $db->getQuery(true);
    $query->select($db->qn('orderStatuses.' . $jshopLang->get('name'), 'name'));
    $query->from($db->qn('#__jshopping_order_status', 'orderStatuses'));
    $query->innerJoin($db->qn('#__jshopping_orders',
            'orders') . ' ON (' . $db->qn('orders.order_status') . ' = ' . $db->qn('orderStatuses.status_id') . ')');
    $query->where($db->qn('orders.order_number') . ' = ' . $db->q(outputDigit($orderId,
            $jshopConfig->get(strtolower('orderNumberLength')))));
    $db->setQuery($query);
    $statusName = $db->loadResult();
}
require(JModuleHelper::getLayoutPath('mod_jshopping_order_status'));