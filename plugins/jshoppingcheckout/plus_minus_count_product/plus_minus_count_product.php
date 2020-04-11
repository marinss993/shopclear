<?php
/**
 * @package Joomla.JoomShopping.Products
 * @version 1.6.0
 * @author Linfuby (Meling Vadim)
 * @website http://dell3r.ru/
 * @email support@dell3r.ru
 * @copyright Copyright by Linfuby. All rights reserved.
 * @license The MIT License (MIT); See \components\com_jshopping\addons\jshopping_plus_minus_count_product\license.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class plgJshoppingCheckoutPlus_Minus_Count_Product extends JPlugin{

	function onBeforeDisplayCartView(&$view){
		foreach($view->products as $key => $product){
			$view->products[$key]['_qty_unit'] =
			'&nbsp;<input class = "product_minus" type = "button" onclick = "
			var qty_el = document.getElementsByName(\'quantity['.$key.']\');
			for ( keyVar in qty_el) {
			if( !isNaN( qty_el[keyVar].value ) && qty_el[keyVar].value > 1) qty_el[keyVar].value--;
			}return false;">
			<input class = "product_plus" type = "button" onclick = "
			var qty_el = document.getElementsByName(\'quantity['.$key.']\');
			for ( keyVar in qty_el) {
			if( !isNaN( qty_el[keyVar].value )) qty_el[keyVar].value++;
			}return false;">';
		}
	}
}