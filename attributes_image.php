<?php
/**
* @package Joomla
* @subpackage JoomShopping
* @author Dmitry Stashenko
* @website http://nevigen.com/
* @email support@nevigen.com
* @copyright Copyright © Nevigen.com. All rights reserved.
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @license agreement http://nevigen.com/license-agreement.html
**/

defined( '_JEXEC' ) or die;

class plgJshoppingAdminAttributes_Image extends JPlugin {

	function onBeforeDisplayEditProductView($view) {
		if (isset($view->lists['attribs'])) {
			$jshopConfig = JSFactory::getConfig();
			if (!isset($view->dep_attr_td_header)) {
				$view->dep_attr_td_header = '';
			}
			$view->dep_attr_td_header .= '<th width="120" id="list_attr_value_image">'._JSHOP_IMAGE.'</th>';
			if (!isset($view->dep_attr_td_row_empty)) {
				$view->dep_attr_td_row_empty = '';
			}
			$view->dep_attr_td_row_empty .= '<td></td>';
			foreach($view->lists['attribs'] as $k=>$attr) {
				$fullImage = $thumbImage = '';
	            $product_attr = JTable::getInstance('productAttribut', 'jshop');
				$product_attr->load($attr->product_attr_id);
				if ($product_attr->ext_attribute_product_id){
					$product = JTable::getInstance('product', 'jshop');
					$product->load($product_attr->ext_attribute_product_id);
					if (property_exists($product, 'image')) {
						$fullImage = 'full_'.$product->image;
						$thumbImage = 'thumb_'.$product->image;
					} else {
						$fullImage = $product->product_full_image;
						$thumbImage = $product->product_thumb_image;
					}
				}
				if (!isset($view->dep_attr_td_row[$k])) {
					$view->dep_attr_td_row[$k] = '';
				}
				if ($thumbImage) {
					$view->dep_attr_td_row[$k] .= '<td><a class="modal" href="'.$jshopConfig->image_product_live_path.'/'.$fullImage.'"><img class="prod_attr_image" src="'.$jshopConfig->image_product_live_path.'/'.$thumbImage.'" /></a></td>';
				} else {
					$view->dep_attr_td_row[$k] .= '<td></td>';
				}
			}
			$view->lists['dep_attr_button_add'] = str_replace('addAttributValue', 'addAttributValue();addAttributImageTR', $view->lists['dep_attr_button_add']);
			JFactory::getDocument()->addScriptDeclaration("
			function addAttributImageTR(){
				var cellIndex = jQuery('#list_attr_value_image')[0].cellIndex - 1;
				var ceilQty = jQuery('#list_attr_value_image').parent().children().length;
				jQuery('#attr_row_end').parent().children().each(function (i) {
					var td = jQuery(this).children();
					if (td.length < ceilQty) {
						td.eq(cellIndex).after('<td></td>');
					}
				});
			}");
		}
	}

}
?>