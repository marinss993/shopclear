<?php
/**
* @package Joomla
* @subpackage JoomShopping
* @author Nevigen.com
* @website http://nevigen.com/
* @email support@nevigen.com
* @copyright Copyright © Nevigen.com. All rights reserved.
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @license agreement http://nevigen.com/license-agreement.html
**/

defined( '_JEXEC' ) or die;

class plgJshoppingProductsJshoppingOrderBy extends JPlugin {
    
	function onBeforeQueryGetProductList( $type, &$adv_result, &$adv_from, &$adv_query, &$order_query )	{
		$adv_result .= ', IF(prod.product_quantity>0,1,0) as qflag';
		if (strpos($order_query, 'ORDER BY') === false) {
			$order_query = 'ORDER BY qflag DESC, name ASC';
		} else {
			$order_query = str_replace('ORDER BY', 'ORDER BY qflag DESC, ', $order_query);
		}
	}
}
?>