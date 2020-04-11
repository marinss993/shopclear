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

class plgJshoppingadminTransliteration_Upload_Files extends JPlugin {

    function _stringURLSafe($name){
		if ($_FILES[$name]['name'] != '') {
			$path_parts = pathinfo($_FILES[$name]['name']);
			$_FILES[$name]['name'] = JApplication::stringURLSafe($path_parts['filename']);
			if ($path_parts['extension'] != '') {
				$_FILES[$name]['name'] .= '.'.strtolower($path_parts['extension']);
			}
		}
    }
    
	function onAfterSaveProduct(&$product){
		$jshopConfig = JSFactory::getConfig();
		setlocale(LC_ALL,'en_US.UTF-8'); 

		if ($this->params->get('video') && $jshopConfig->admin_show_product_video && $product->parent_id==0) {
			for($i=0; $i<$jshopConfig->product_video_upload_count; $i++) {
				$this->_stringURLSafe('product_video_'.$i);
			}
		}

		if ($this->params->get('image')) {
			for($i=0; $i<$jshopConfig->product_image_upload_count; $i++) {
				$this->_stringURLSafe('product_image_'.$i);
			}
		}

		if ($this->params->get('file') && $jshopConfig->admin_show_product_files){
			for($i=0; $i<$jshopConfig->product_file_upload_count; $i++) {
				$this->_stringURLSafe('product_demo_file_'.$i);
				$this->_stringURLSafe('product_file_'.$i);
			}
		}
	}

}
?>