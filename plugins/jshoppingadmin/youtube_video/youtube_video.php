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

class plgJshoppingadminYouTube_Video extends JPlugin {

	function onBeforeDisplayEditProduct(&$product, &$related_products, &$lists, &$listfreeattributes, &$tax_value) {
		$preview_size = $this->params->get('preview_size','default');
		foreach ($lists['videos'] as $video) {
			if (strpos($video->video_code, 'youtu') !== false) {
				preg_match('/youtube.com\/embed\/([^?]*)/', $video->video_code, $res);
				if (!isset($res[1])) {
					preg_match('/youtube.com\/watch\?v=([^&]*)/', $video->video_code, $res);
				}
				if (!isset($res[1])) {
					preg_match('/youtube.com\/v\/([^?]*)/', $video->video_code, $res);
				}
				if (!isset($res[1])) {
					preg_match('/youtu.be\/([^?]*)/', $video->video_code, $res);
				}
				if (isset($res[1])) {
					$video->video_name = '#" onclick="window.open(\'index.php?option=com_jshopping&controller=products&task=getvideocode&video_id='.$video->video_id.'\',\'_blank\');return false"><img src="http://img.youtube.com/vi/'.$res[1].'/'.$preview_size.'.jpg" /></a><a href="#" style="display:none';
					$video->video_code = '';
				}
			}
		}
	}

}
?>