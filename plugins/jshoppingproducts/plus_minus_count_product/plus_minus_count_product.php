<?php
/**
 * @package Joomla.JoomShopping.Products
 * @version 1.7.0
 * @author Linfuby (Meling Vadim)
 * @website http://dell3r.ru/
 * @email support@dell3r.ru
 * @copyright Copyright by Linfuby. All rights reserved.
 * @license The MIT License (MIT); See \components\com_jshopping\addons\jshopping_plus_minus_count_product\license.txt
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class plgJshoppingProductsPlus_Minus_Count_Product extends JPlugin{

	function onBeforeDisplayProductListView(&$view){
		foreach($view->rows as $key => $product){
			if($view->rows[$key]->buy_link){
				$view->rows[$key]->_tmp_var_buttons = '<br>
				<input class = "product_minus" type = "button" onclick = "
				var qty_el = document.getElementById(\'quantity'.$product->product_id.'\');
				var qty = qty_el.value;
				if( !isNaN( qty ) && qty > 1) qty_el.value--;
				var url_el = document.getElementById(\'productlink'.$product->product_id.'\');
				url_el.href=\''.$view->rows[$key]->buy_link.'&quantity=\'+qty_el.value;reloadPriceInList('.$product->product_id.',qty_el.value);return false;" />
				<input type = "text" name = "quantity'.$product->product_id.'" id = "quantity'.$product->product_id.'"
				style = "min-width:20px; max-width:100px;" class = "inputbox" value = "1" onkeyup="
				var qty_el = document.getElementById(\'quantity'.$product->product_id.'\');
				var url_el = document.getElementById(\'productlink'.$product->product_id.'\');
				url_el.href=\''.$view->rows[$key]->buy_link.'&quantity=\'+qty_el.value;reloadPriceInList('.$product->product_id.',qty_el.value);return false;" />
				<input class = "product_plus" type = "button" onclick = "
				var qty_el = document.getElementById(\'quantity'.$product->product_id.'\');
				var qty = qty_el.value;
				if( !isNaN( qty )) qty_el.value++;
				var url_el = document.getElementById(\'productlink'.$product->product_id.'\');
				url_el.href=\''.$view->rows[$key]->buy_link.'&quantity=\'+qty_el.value;reloadPriceInList('.$product->product_id.',qty_el.value);return false;" />
				<script>
					function reloadPriceInList(product_id, qty){
						var data = {};
						data["change_attr"] = 0;
						data["qty"] = qty;
						if (prevAjaxHandler){
							prevAjaxHandler.abort();
						}
						prevAjaxHandler = jQuery.getJSON(
							"index.php?option=com_jshopping&controller=product&task=ajax_attrib_select_and_price&product_id=" + product_id + "&ajax=1",
							data,
							function(json){
								jQuery(".product.productitem_"+product_id+" .jshop_price span").html(json.price);
							}
						);
					}
				</script>';
				$view->rows[$key]->buy_link .= "\" Id = \"productlink".$product->product_id;
			}
		}
	}
	function onBeforeDisplayProductView(&$view){
		$view->_tmp_qty_unit =
			'&nbsp;<input class = "product_minus" type = "button" onclick = "
			var qty_el = document.getElementById(\'quantity\');
			var qty = qty_el.value;
			if( !isNaN( qty ) && qty > 1) qty_el.value--;reloadPrices();return false;">
			<input class = "product_plus" type = "button" onclick = "
			var qty_el = document.getElementById(\'quantity\');
			var qty = qty_el.value;
			if( !isNaN( qty )) qty_el.value++;reloadPrices();return false;">';
	}
}