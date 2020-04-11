<?php
/**
* @version      4.1.0 01.03.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2012 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelAddon_menu_builder extends JModelLegacy{

    function makeJoomla3Style($html) {    
         $html = str_replace('<div class="controls">', '', $html);    
         $html = str_replace('</div>', '', $html); 
         return $html;
    }
	
	function _recurseTree($item, $level, $all_items, &$items) {
		$item->level = $level;
		$items[] = $item;
		foreach ($all_items as $v) {
			if($v->parent_id == $item->id) {
				self::_recurseTree($v, ++$level, $all_items, $items);
				$level--;
			}
		}
	}

	function getCountAllItems($published = '', $menutype = ANY_MENUTYPE, $language = ANY_LANGUAGE, $js_items = true){
		$db = JFactory::getDBO();
		$where = '';
		if ($js_items) $where .= " AND m.`link` LIKE '%option=com_jshopping%' AND m.`type`='component'";
		if ($published != ANY_PUBLISHING) {
			if ($published == '') {
				$where .= ' AND m.published IN ('.UNPUBLISHED.','.PUBLISHED.')';
			} else {
				$where .= ' AND m.published='.$published;
			}
		}
		if ($menutype != ANY_MENUTYPE) $where .= " AND m.menutype='".$db->escape($menutype)."'";
		if ($language != ANY_LANGUAGE) $where .= " AND m.language='".$db->escape($language)."'";
		
		$query = "SELECT COUNT(m.`id`) 
				FROM `#__menu` AS m
				WHERE m.`menutype`<>'main'".$where;
		$db->setQuery($query);
		return $db->loadResult();
	}

	function getAllItems($limitstart = null, $limit = null, $level = ANY_LEVEL, $published = '', $menutype = ANY_MENUTYPE, $language = ANY_LANGUAGE, $js_items = true){
		$db = JFactory::getDBO();
		$where = '';
		if ($js_items) $where .= " AND m.`link` LIKE '%option=com_jshopping%' AND m.`type`='component'";
		if ($menutype != ANY_MENUTYPE) $where .= " AND m.menutype='".$db->escape($menutype)."'";
		if ($level != ANY_LEVEL) $where .= ' AND m.level='.$level;
		if ($language != ANY_LANGUAGE) $where .= " AND m.language='".$db->escape($language)."'";
		
		$query = "SELECT m.`id`, m.`menutype`, m.`title`, m.`alias`, m.`parent_id`, m.`level`, m.`link`, m.`published`, m.`lft`, m.`language`, m.`home`, l.`title` as `language_title`
				FROM `#__menu` AS m
				LEFT JOIN `#__languages` AS l ON l.`lang_code`=m.`language`
				WHERE m.`menutype`<>'main'".$where." 
				GROUP BY m.`id`
				ORDER BY m.`menutype`, m.`lft`";
		$db->setQuery($query);
		$all_items = $db->loadObjectList();
		
		$items = array();
		if(count($all_items)) {
			foreach ($all_items as $v) {
				if ((($level == ANY_LEVEL) && ($v->parent_id == 1)) || (($level != ANY_LEVEL) && ($v->level == $level))) {
					self::_recurseTree($v, 0, $all_items, $items);
				}
			}
		}
		unset($all_items);
		
		//$published filtering
		if ($published != ANY_PUBLISHING) {
			if ($published == '') {
				$publ = array(0,1);
			} else {
				$publ = array($published);
			}
			foreach ($items as $k=>$v) {
				if (!in_array($v->published, $publ)) unset($items[$k]);
			}
		}
		$items = array_values($items);
		
		if (($limitstart !== null) && ($limit !== null)) {
			$limit_items = array();
			for ($i = $limitstart; $i < $limitstart + $limit; $i++) {
				if (isset($items[$i]) && is_object($items[$i])) {
					$limit_items[] = $items[$i];
				}
			}
			$items = $limit_items;
		}
		
		if (JFactory::getApplication()->item_associations) {
			foreach ($items as $k=>$v) {
				$items[$k]->associations = self::_getAssociations($v->id);
				$items[$k]->association_items = self::_loadAssociationItems($items[$k]->associations);
			}
		}
		return $items;
	}
	
	function _loadAssociationItems($assoc_items) {
		$result = array();
		if (count($assoc_items)) {
			$ids = array();
			foreach ($assoc_items as $v) {
				$ids[] = $v;
			}
			$db = JFactory::getDBO();
			$query = 'SELECT m.`id`, m.`title`, m.`language`, mt.`title` as `menu_title`
					FROM `#__menu` AS m
					INNER JOIN `#__menu_types` AS mt ON mt.`menutype`=m.`menutype`
					WHERE m.`id` IN ('.implode(',', $ids).')
					GROUP BY m.`id`
					ORDER BY m.`lft`';
			$db->setQuery($query);
			$all_items = $db->loadObjectList();
			foreach ($all_items as $v) {
				$v->image = substr($v->language, 0, 2);
				$result[$v->id] = $v;
			}
		}
		return $result;
	}
	
	function _getComponentId() {
		$db = JFactory::getDBO();
		$query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='component' AND `element`='com_jshopping' LIMIT 1";
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	static function _getAllMenuTypes() {
		$db = JFactory::getDBO();
		$query = 'SELECT mt.`menutype`, mt.`title`
				FROM `#__menu_types` AS mt
				ORDER BY mt.`id`';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function _getAllLanguages() {
		$db = JFactory::getDBO();
		$query = 'SELECT l.`lang_code`, l.`title`
				FROM `#__languages` AS l
				WHERE l.`published`=1
				GROUP BY l.`lang_id`
				ORDER BY l.`ordering`';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function _getAllFrontendTemplates() {
		$db = JFactory::getDBO();
		$query = 'SELECT ts.`id`, ts.`template`, ts.`title`
				FROM `#__template_styles` AS ts
				WHERE ts.`client_id`=0
				ORDER BY ts.`template`';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function _getOrderingPositionForItem($all_items, $item) {
		if (!$item->lft) return ORDERING_LAST;
		$result = ORDERING_FIRST;
		$i = 0;
		while (($i < count($all_items)) && ($all_items[$i]->lft <= $item->lft)) {
			$result = $all_items[$i]->id;
			$i++;
		}
		return $result;
	}
	
	function _getAllJshoppingMenuTypes() {
		$db = JFactory::getDBO();
		$query = 'SELECT `id`, `name` FROM `#__jshopping_menu_config`';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function _getLabelName($name) {
		$const_name = str_replace(' ', '', strtoupper($name));
		$const_name = "COM_JSHOP_MENU_BUILDER_".str_replace('/', '_', strtoupper($const_name));
		if ( JText::_($const_name) == $const_name )
			return $name;
		else 
			return JText::_($const_name);
	}
	
	static function _getLabel($id = null, $class = null, $title = null, $for = null, $value = '', $required = false) {
		$result = '<label';
		if ($id) $result .= ' id="'.$id.'"';
		if ($class) $result .= ' class="'.$class.'"';
		if ($title) $result .= ' title="'.$title.'"';
		if ($for) $result .= ' for="'.$for.'"';
		$result .= '>'.$value;
		if ($required) $result .= '<span class="star"> *</span>';
		$result .= '</label>';
		return $result;
	}
	
	function _getInput($id = null, $class = null, $type = 'text', $readonly = false, $size = null, $value = '', $name = '', $required = false, $special_params = '') {
		$result = '<input';
		if ($id) $result .= ' id="'.$id.'"';
		if ($class) $result .= ' class="'.$class.'"';
		if ($type) $result .= ' type="'.$type.'"';
		if ($readonly) $result .= ' readonly="readonly"';
		if ($size) $result .= ' size="'.$size.'"';
		if ($value) $result .= ' value="'.$value.'"';
		if ($name) $result .= ' name="'.$name.'"';
		if ($required) $result .= ' required="required"';
		if ($special_params) $result .= $special_params;
		$result .= '/>';
		return $result;
	}
	
	function _getJoomShoppingSelect($name='category_id', $value='', $class='inputbox', $required=false, $empty_item=false, $empty_name='- - -', $empty_value='') {
		$parentTop = new stdclass();
		if ($required) $class .= ' required';
		if ($name == 'category_id') {
			$categories_select = buildTreeCategory(0, 1, 0);
			if ($empty_item) {
				$parentTop->name = $empty_name;
				$parentTop->category_id = $empty_value;
				array_unshift($categories_select, $parentTop);
			}
			return JHtml::_('select.genericlist', $categories_select, $name, ' class="'.$class.'"', 'category_id', 'name', $value);
		}
		if ($name == 'manufacturer_id') {
			$manufacturers_model = JModelLegacy::getInstance('Manufacturers', 'JshoppingModel');
			$manufacturers_select = $manufacturers_model->getAllManufacturers(0);
			if ($empty_item) {
				$parentTop->name = $empty_name;
				$parentTop->manufacturer_id = $empty_value;
				array_unshift($manufacturers_select, $parentTop);
			}
			return JHtml::_('select.genericlist', $manufacturers_select, $name, ' class="'.$class.'"', 'manufacturer_id', 'name', $value);
		}
		if ($name == 'label_id') {
			$labels_model = JModelLegacy::getInstance('productLabels', 'JshoppingModel');
			$labels_select = $labels_model->getList();
			if ($empty_item) {
				$parentTop->name = $empty_name;
				$parentTop->id = $empty_value;
				array_unshift($labels_select, $parentTop);
			}
			return JHtml::_('select.genericlist', $labels_select, $name, ' class="'.$class.'"', 'id', 'name', $value);
		}
		if ($name == 'vendor_id') {
			$vendors_model = JModelLegacy::getInstance('vendors', 'JshoppingModel');
			$vendors_select = $vendors_model->getAllVendorsNames(1);
			if ($empty_item) {
				$parentTop->name = $empty_name;
				$parentTop->id = $empty_value;
				array_unshift($vendors_select, $parentTop);
			}
			return JHtml::_('select.genericlist', $vendors_select, $name, ' class="'.$class.'"', 'id', 'name', $value);
		}
	}
	
	function _getJoomShoppingOptionsSelect($options, $name, $value='', $class='inputbox', $required=true, $empty_item=false, $empty_name='- - -', $empty_value='') {
		if ($required) $class .= ' required';
		$items = array();
		if ($empty_item) {
			$items[] = JHTML::_('select.option', $empty_value, $empty_name);
		}
		if (count($options)) {
			foreach ($options as $k=>$v) {
				$items[] = JHTML::_('select.option', $k, JText::_($v));
			}
		}
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $value);
	}
	
	function _getTextarea($id = null, $rows = 3, $cols = 40, $value = '', $name = '') {
		return '<textarea'.($id ? ' id="'.$id.'"' : '').' rows="'.$rows.'" cols="'.$cols.'" name="'.$name.'">'.$value.'</textarea>';
	}
	
	static function getMenuLocationSelect($menutype='', $class='inputbox required', $name='menutype', $empty_item=false, $empty_name='- - -', $empty_value='') {
		$result = '';
		$menutypes = self::_getAllMenuTypes();
		if (count($menutypes)) {
			$items = array();
			if ($empty_item) {
				$items[] = JHTML::_('select.option', $empty_value, $empty_name);
			}
			foreach ($menutypes as $v) {
				$items[] = JHTML::_('select.option', $v->menutype, $v->title);
			}
			$result = JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"'.($empty_item ? ' onchange="document.adminForm.submit();"' : ''), 'value', 'text', $menutype);
		}
		return $result;
	}
	
	function getStatusSelect($status=0, $class='inputbox', $name='published', $empty_item=false, $empty_name='- - -', $empty_value='') {
		$items = array();
		if ($empty_item) {
			$items[] = JHTML::_('select.option', $empty_value, $empty_name);
		}
		$items[] = JHTML::_('select.option', PUBLISHED, JText::_('JPUBLISHED'));
		$items[] = JHTML::_('select.option', UNPUBLISHED, JText::_('JUNPUBLISHED'));
		$items[] = JHTML::_('select.option', TRASHED, JText::_('JTRASHED'));
		if ($empty_item) {
			$items[] = JHTML::_('select.option', '*', JText::_('JALL'));
		}
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"'.($empty_item ? ' onchange="document.adminForm.submit();"' : ''), 'value', 'text', $status);
	}
	
	function getLanguageSelect($lang='', $class='inputbox', $name='language', $empty_item=false, $empty_name='- - -', $empty_value=ANY_LANGUAGE) {
		if ($lang == '') $lang = '*';
		$languages = self::_getAllLanguages();
		$items = array();
		if ($empty_item) {
			$items[] = JHTML::_('select.option', $empty_value, $empty_name);
		}
		$items[] = JHTML::_('select.option', '*', JText::_('JALL'));
		if (count($languages)) {
			foreach ($languages as $v) {
				$items[] = JHTML::_('select.option', $v->lang_code, $v->title);
			}
		}
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"'.($empty_item ? ' onchange="document.adminForm.submit();"' : ''), 'value', 'text', $lang);
	}
	
	function _getParentItemSelect($menutype='', $item, $class='inputbox', $name='parent_id') {
		$items = array(JHTML::_('select.option', 1, JText::_('COM_MENUS_ITEM_ROOT')));
		//if ($menutype) {
			$all_items = self::getAllItems(null, null, ANY_LEVEL, PUBLISHED, $menutype);
			if (count($all_items)) {
				foreach ($all_items as $v) {
					if ($v->id != $item->id) {
						$items[] = JHTML::_('select.option', $v->id, str_repeat('- ', $v->level + 1).$v->title);
					}
				}
			}
		//}
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $item->parent_id);
	}
	
	function _getOrderingSelect($menutype='', $item, $class='inputbox', $name='ordering') {
		$items = array(JHTML::_('select.option', ORDERING_FIRST, JText::_('COM_MENUS_ITEM_FIELD_ORDERING_VALUE_FIRST')));
		$ordering = -1;
		if ($menutype) {
			$all_items = self::getAllItems(null, null, ($item->level ? $item->level : 1), PUBLISHED, $menutype);
			if (count($all_items)) {
				foreach ($all_items as $v) {
					$items[] = JHTML::_('select.option', $v->id, $v->title);
				}
				$ordering = self::_getOrderingPositionForItem($all_items, $item);
			}
		}
		$items[] = JHTML::_('select.option', ORDERING_LAST, JText::_('COM_MENUS_ITEM_FIELD_ORDERING_VALUE_LAST'));
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $ordering);
	}
	
	function _getAccessSelect($access, $class='inputbox', $name='access') {
		$items = array();
		$items[] = JHTML::_('select.option', 1, JText::_("COM_JSHOP_MENU_BUILDER_PUBLIC"));
		$items[] = JHTML::_('select.option', 2, JText::_("COM_JSHOP_MENU_BUILDER_REGISTERED"));
		$items[] = JHTML::_('select.option', 3, JText::_("COM_JSHOP_MENU_BUILDER_SPECIAL"));
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $access);
	}
	
	function _getBrowserNavSelect($browserNav, $class='inputbox', $name='browserNav') {
		$items = array();
		$items[] = JHTML::_('select.option', 0, JText::_('COM_MENUS_FIELD_VALUE_PARENT'));
		$items[] = JHTML::_('select.option', 1, JText::_('COM_MENUS_FIELD_VALUE_NEW_WITH_NAV'));
		$items[] = JHTML::_('select.option', 2, JText::_('COM_MENUS_FIELD_VALUE_NEW_WITHOUT_NAV'));
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $browserNav);
	}
	
	function _getRobotsSelect($robots, $class='', $name='params[robots]') {
		$items = array();
		$items[] = JHTML::_('select.option', '', JText::_('JGLOBAL_USE_GLOBAL'));
		$items[] = JHTML::_('select.option', 'index, follow', JText::_('JGLOBAL_INDEX_FOLLOW'));
		$items[] = JHTML::_('select.option', 'noindex, follow', JText::_('JGLOBAL_NOINDEX_FOLLOW'));
		$items[] = JHTML::_('select.option', 'index, nofollow', JText::_('JGLOBAL_INDEX_NOFOLLOW'));
		$items[] = JHTML::_('select.option', 'noindex, nofollow', JText::_('JGLOBAL_NOINDEX_NOFOLLOW'));
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $robots);
	}
	
	function _getSecureSelect($secure, $class='', $name='params[secure]') {
		$items = array();
		$items[] = JHTML::_('select.option', -1, JText::_('JOFF'));
		$items[] = JHTML::_('select.option', 1, JText::_('JON'));
		$items[] = JHTML::_('select.option', 0, JText::_('COM_MENUS_FIELD_VALUE_IGNORE'));
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $secure);
	}
	
	function _getYesNoRadioSelect($fieldset_id = null, $class = null, $name = '', $value = 0) {
		$items = array();
		$items[] = JHTML::_('select.option', 1, JText::_('JYES'));
		$items[] = JHTML::_('select.option', 0, JText::_('JNO'));
		return '<fieldset'.($fieldset_id ? ' id="'.$fieldset_id.'"' : '').' class="'.$class.' btn-group">'.self::makeJoomla3Style(JHtml::_('select.radiolist', $items, $name, '', 'value', 'text', $value)).'</fieldset>';
	}
	
	function _getJshoppingMenuTypeSelect($id) {
		$menus = self::_getAllJshoppingMenuTypes();
		$items = array();
		if (count($menus)) {
			foreach ($menus as $v) {
				$items[] = JHTML::_('select.option', $v->id, self::_getLabelName($v->name));
			}
		}
		return JHTML::_('select.genericlist', $items, 'jshopping_menu', ' onchange="updateParamsFields(this.value);"', 'value', 'text', $id);
	}
	
	function _getTemplateStyleSelect($template=0, $class='inputbox', $name='template_style_id') {
		$templates = self::_getAllFrontendTemplates();
		$items = array(JHTML::_('select.option', 0, JText::_('JOPTION_USE_DEFAULT')));
		if (count($templates)) {
			foreach ($templates as $v) {
				$items[] = JHTML::_('select.optgroup', $v->template);
				$items[] = JHTML::_('select.option', $v->id, $v->title);
				$items[] = JHTML::_('select.optgroup', $v->template);
			}
		}
		return JHTML::_('select.genericlist', $items, $name, ' class="'.$class.'"', 'value', 'text', $template);
	}
	
	function _getParamsFromLink($link) {
		$result = array();
		parse_str(parse_url($link, PHP_URL_QUERY), $result);
		if (isset($result['controller']) && ($result['controller'] != '')) {
			$result['view'] = $result['controller'];
			unset($result['controller']);
		}
		return $result;
	}
	
	function _getJshoppingMenuType($link_params) {
		if (count($link_params) && ($link_params['controller'] || $link_params['view'])) {
			if ($link_params['controller']) {
				$controller = $link_params['controller'];
			} else {
				$controller = $link_params['view'];
			}
			$db = JFactory::getDBO();
			$query = "SELECT * FROM `#__jshopping_menu_config` WHERE `controller`='".$controller."' AND `task`='".$link_params['task']."'";
			$db->setQuery($query);
			$item = $db->loadObject();
			if ($item->params) $item->params = unserialize($item->params);
		} else {
			$item = new stdclass();
		}
		return $item;
	}
	
	function getJshoppingMenu($id) {
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM `#__jshopping_menu_config` WHERE `id`='.$id;
		$db->setQuery($query);
		$item = $db->loadObject();
		if ($item->params) $item->params = unserialize($item->params);
		return $item;
	}
	
	function _addJSCodeForButtons() {
		$script = array();
		$script[] = '	function jInsertFieldValue(value, id) {';
		$script[] = '		var old_value = document.id(id).value;';
		$script[] = '		if (old_value != value) {';
		$script[] = '			var elem = document.id(id);';
		$script[] = '			elem.value = value;';
		$script[] = '			elem.fireEvent("change");';
		$script[] = '			if (typeof(elem.onchange) === "function") {';
		$script[] = '				elem.onchange();';
		$script[] = '			}';
		$script[] = '			jMediaRefreshPreview(id);';
		$script[] = '		}';
		$script[] = '	}';
		$script[] = '	function jMediaRefreshPreview(id) {';
		$script[] = '		var value = document.id(id).value;';
		$script[] = '		var img = document.id(id + "_preview");';
		$script[] = '		if (img) {';
		$script[] = '			if (value) {';
		$script[] = '				img.src = "' . JURI::root() . '" + value;';
		$script[] = '				document.id(id + "_preview_empty").setStyle("display", "none");';
		$script[] = '				document.id(id + "_preview_img").setStyle("display", "");';
		$script[] = '			} else { ';
		$script[] = '				img.src = ""';
		$script[] = '				document.id(id + "_preview_empty").setStyle("display", "");';
		$script[] = '				document.id(id + "_preview_img").setStyle("display", "none");';
		$script[] = '			} ';
		$script[] = '		} ';
		$script[] = '	}';
		$script[] = '	function jMediaRefreshPreviewTip(tip)';
		$script[] = '	{';
		$script[] = '		tip.setStyle("display", "block");';
		$script[] = '		var img = tip.getElement("img.media-preview");';
		$script[] = '		var id = img.getProperty("id");';
		$script[] = '		id = id.substring(0, id.length - "_preview".length);';
		$script[] = '		jMediaRefreshPreview(id);';
		$script[] = '	}';
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
	}
	
	function _getHtmlForImage($id = 'menu_image', $class = 'input-medium', $type = 'text', $readonly = true, $size = null, $value = '', $name = 'menu_image', $showPreview = true, $showAsTooltip = true) {
		self::_addJSCodeForButtons();
		$attr_input = '';
		$attr_input .= $class ? ' class="' . (string) $class . '"' : '';
		$attr_input .= $size ? ' size="' . (int) $size . '"' : '';
		$html = array();
		$html[] = '<div class="input-prepend input-append">';
		
		$options = array('onShow' => 'jMediaRefreshPreviewTip');
		JHtml::_('behavior.tooltip', '.hasTipPreview', $options);
		if ($showPreview) {
			if ($value && file_exists(JPATH_ROOT . '/' . $value)) {
				$src = JURI::root() . $value;
			} else {
				$src = '';
			}
			$attr = array('id' => $id.'_preview', 'class' => 'media-preview', 'style' => 'max-width:160px; max-height:100px;');
			$img = JHtml::image($src, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $attr);
			$previewImg = '<div id="' . $id . '_preview_img"' . ($src ? '' : ' style="display:none"') . '>' . $img . '</div>';
			$previewImgEmpty = '<div id="' . $id . '_preview_empty"' . ($src ? ' style="display:none"' : '') . '>'. JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';
			$html[] = '<div class="media-preview add-on">';
			if ($showAsTooltip) {
				$tooltip = $previewImgEmpty . $previewImg;
				$options = array('title' => JText::_('JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE'), 'text' => '<i class="icon-eye"></i>', 'class' => 'hasTipPreview');
				$html[] = JHtml::tooltip($tooltip, $options);
			} else {
				$html[] = ' ' . $previewImgEmpty;
				$html[] = ' ' . $previewImg;
			}
			$html[] = '</div>';
		}
		$html[] = '<input id="' . $id . '" ' . $attr_input . ' type="text" readonly="readonly" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" name="' . $name . '">';
		$html[] = '<a class="modal btn" rel="{handler: \'iframe\', size: {x: 800, y: 500}}" href="' . ($readonly ? '' : ($link ? $link : 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_jshopping&amp;author=created_by&amp;fieldid=' . $id . '&amp;folder=')) . '" title="' . JText::_("JLIB_FORM_BUTTON_SELECT") . '"> ';
		$html[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';
		$html[] = '<a class="btn hasTooltip" onclick=" jInsertFieldValue(\'\', \''.$id.'\'); return false; " href="#" data-original-title="' . JText::_("JLIB_FORM_BUTTON_CLEAR") . '"><i class="icon-remove"></i></a></div>';



		return implode("\n", $html);
	}
	
	function getHtmlForItem($item_obj) {
		$result = '';
		JHtml::_('formbehavior.chosen', 'select');
		if ($item_obj->params) {
			$list_select = array('category_id', 'manufacturer_id', 'label_id', 'vendor_id');
			//$result .= '<table class="adminformlist">';
			foreach($item_obj->params as $v) {
				$result .= '<div class="control-group"><div class="control-label">'.self::_getLabel($v->key.'-lbl', 'hasTip', '', $v->key, self::_getLabelName($v->name), (bool)$v->require).'</div>';
				if ($v->type == 'text') {
					if (!in_array($v->key, $list_select)) {
						$result .= '<div class="controls">'.self::_getInput($v->key, 'inputbox', 'text', false, 20, '', $v->key,(bool)$v->require).'</div>';
					} else {
						$result .= '<div class="controls">'.self::_getJoomShoppingSelect($v->key, '', 'inputbox', (bool)$v->require, true).'</div>';
					}
				} elseif ($v->type == 'select') {
					$result .= '<div class="controls">'.self::_getJoomShoppingOptionsSelect($v->options, $v->key, '', 'inputbox', (bool)$v->require, true).'</div>';
				}
				$result .= '</div>';
			}
			//$result .= '</table>';
		}
		return $result;
	}
	
	function _getStandartFields($item) {
		$mainframe = JFactory::getApplication();
		$context = "jshoping.list.admin.menu_builder";
		$menutype = $item->menutype ? $item->menutype : $mainframe->getUserStateFromRequest( $context.'menutype', 'filter_menutype', '');
		
		$result = new stdclass();
		$result->title = new stdclass();
		$result->title->name = self::_getLabel('title-lbl', 'hasTip', '', 'jform_title', JText::_('JGLOBAL_TITLE'), true);
		$result->title->value = self::_getInput('jform_title', 'inputbox required', 'text', false, 40, $item->title, 'title', true, '  aria-required="true"');
		
		$result->alias = new stdclass();
		$result->alias->name = self::_getLabel('alias-lbl', 'hasTip', '', 'std_alias', JText::_('COM_MENUS_TYPE_ALIAS'));
		$result->alias->value = self::_getInput('std_alias', 'inputbox', 'text', false, 40, $item->alias, 'alias', true);
		
		$result->note = new stdclass();
		$result->note->name = self::_getLabel('note-lbl', 'hasTip', '', 'std_note', JText::_('JFIELD_NOTE_DESC'));
		$result->note->value = self::_getInput('std_note', 'inputbox', 'text', false, 40, $item->note, 'note');
		
		$result->link = new stdclass();
		$result->link->name = self::_getLabel('link-lbl', 'hasTip', '', 'std_link', JText::_('COM_MENUS_ITEM_FIELD_LINK_LABEL'));
		$result->link->value = self::_getInput('std_link', 'inputbox', 'text', true, 50, $item->link, 'link');
		
		$result->published = new stdclass();
		$result->published->name = self::_getLabel('published-lbl', 'hasTip', '', 'published', JText::_('JPUBLISHED'));
		$result->published->value = self::getStatusSelect($item->published ? $item->published : 1);
		
		$result->access = new stdclass();
		$result->access->name = self::_getLabel('access-lbl', 'hasTip', '', 'access', JText::_('JFIELD_ACCESS_LABEL'));
		$result->access->value = self::_getAccessSelect($item->access ? $item->access : 1);
		
		$result->menutype = new stdclass();
		$result->menutype->name = self::_getLabel('menutype-lbl', 'hasTip', '', 'menutype', JText::_('COM_MENUS_ITEM_FIELD_ASSIGNED_LABEL'), true);
		$result->menutype->value = self::getMenuLocationSelect($menutype);
		
		$result->parent_id = new stdclass();
		$result->parent_id->name = self::_getLabel('parent_id-lbl', 'hasTip', '', 'parent_id', JText::_('COM_MENUS_ITEM_FIELD_PARENT_LABEL'));
		$result->parent_id->value = self::_getParentItemSelect($menutype, $item);
		
		$result->ordering = new stdclass();
		$result->ordering->name = self::_getLabel('ordering-lbl', 'hasTip', '', 'ordering', JText::_('COM_MENUS_ITEM_FIELD_ORDERING_LABEL'));
		$result->ordering->value = self::_getOrderingSelect($menutype, $item);
		
		$result->browserNav = new stdclass();
		$result->browserNav->name = self::_getLabel('browserNav-lbl', 'hasTip', '', 'browserNav', JText::_('COM_MENUS_ITEM_FIELD_BROWSERNAV_LABEL'));
		$result->browserNav->value = self::_getBrowserNavSelect($item->browserNav ? $item->browserNav : 0);
		
		$result->home = new stdclass();
		$result->home->name = self::_getLabel('home-lbl', 'hasTip', '', 'home', JText::_('COM_MENUS_ITEM_FIELD_HOME_LABEL'));
		$result->home->value = self::_getYesNoRadioSelect('jform_home', 'radio', 'home', $item->home ? $item->home : 0);
		
		$result->language = new stdclass();
		$result->language->name = self::_getLabel('language-lbl', 'hasTip', '', 'language', _JSHOP_LANGUAGE_NAME);
		$result->language->value = self::getLanguageSelect($item->language ? $item->language : '*');
		
		$result->template_style_id = new stdclass();
		$result->template_style_id->name = self::_getLabel('template_style_id-lbl', 'hasTip', '', 'template_style_id', JText::_('COM_MENUS_ITEM_FIELD_TEMPLATE_LABEL'));
		$result->template_style_id->value = self::_getTemplateStyleSelect($item->template_style_id ? $item->template_style_id : 0);
		
		$result->id = new stdclass();
		$result->id->name = self::_getLabel('id-lbl', 'hasTip', '', 'std_id', JText::_('JGRID_HEADING_ID'));
		$result->id->value = self::_getInput('std_id', 'readonly', 'text', true, null, $item->id, 'id');
		return $result;
	}
	
	function _getJshoppingFields($item) {
		$result = new stdclass();
		$link_params = self::_getParamsFromLink($item->link);
		$jshopping_item = self::_getJshoppingMenuType($link_params);
		$result->jshop_menutype = new stdclass();
		$result->jshop_menutype->name = self::_getLabel('jshop_menutype-lbl', 'hasTip', '', 'jsh_menutype', JText::_("COM_JSHOP_MENU_BUILDER_JS_TYPE"), true);
		$result->jshop_menutype->value = self::_getJshoppingMenuTypeSelect($jshopping_item->id);
		if ($jshopping_item->params) {
			$list_select = array('category_id', 'manufacturer_id', 'label_id', 'vendor_id');
			foreach($jshopping_item->params as $v) {
				if (!is_object($result->{$v->key})) $result->{$v->key} = new stdclass();
				$result->{$v->key}->name = self::_getLabel($v->key.'-lbl', 'hasTip', '', $v->key, self::_getLabelName($v->name), (bool)$v->require);
				if ($v->type == 'text') {
					if (!in_array($v->key, $list_select)) {
						$result->{$v->key}->value = self::_getInput($v->key, 'inputbox', 'text', false, 20, isset($link_params[$v->key]) ? $link_params[$v->key] : '', $v->key, (bool)$v->require);
					} else {
						$result->{$v->key}->value = self::_getJoomShoppingSelect($v->key, isset($link_params[$v->key]) ? $link_params[$v->key] : '', 'inputbox', (bool)$v->require, true);
					}
				} elseif ($v->type == 'select') {
					$result->{$v->key}->value = self::_getJoomShoppingOptionsSelect($v->options, $v->key, isset($link_params[$v->key]) ? $link_params[$v->key] : '', 'inputbox', (bool)$v->require, true);
				}
			}
		}
		return $result;
	}
	
	function _getHiddenFields($item) {
		$result = new stdclass();
		$result->lft = $item->lft ? $item->lft : 0;
		$result->rgt = $item->rgt ? $item->rgt : 0;
		$result->img = $item->img ? $item->img : '';
		$result->component_id = $item->component_id ? $item->component_id : self::_getComponentId();
		$result->client_id = $item->client_id ? $item->client_id : 0;
		$result->type = 'component';
		return $result;
	}
	
	function _getLinkTypeFields($params) {
		$result = new stdclass();
		
		$result->anchor_title = new stdclass();
		$result->anchor_title->name = self::_getLabel('menu-anchor_title-lbl', 'hasTip', '', 'menu-anchor_title', JText::_('COM_MENUS_ITEM_FIELD_ANCHOR_TITLE_LABEL'));
		$result->anchor_title->value = self::_getInput('menu-anchor_title', 'inputbox', 'text', false, null, $params->{'menu-anchor_title'}, 'params[menu-anchor_title]');
		
		$result->anchor_css = new stdclass();
		$result->anchor_css->name = self::_getLabel('menu-anchor_css-lbl', 'hasTip', '', 'menu-anchor_css', JText::_('COM_MENUS_ITEM_FIELD_ANCHOR_CSS_LABEL'));
		$result->anchor_css->value = self::_getInput('menu-anchor_css', 'inputbox', 'text', false, null, $params->{'menu-anchor_css'}, 'params[menu-anchor_css]');
		
		$result->image = new stdclass();
		$result->image->name = self::_getLabel('menu_image-lbl', 'hasTip', '', 'menu_image', JText::_('COM_MENUS_ITEM_FIELD_MENU_IMAGE_LABEL'));
		$result->image->value = self::_getHtmlForImage('menu_image', 'input-medium', 'text', false, null, $params->menu_image, 'params[menu_image]');
		
		$result->title = new stdclass();
		$result->title->name = self::_getLabel('menu_text-lbl', 'hasTip', '', 'menu_text', JText::_('COM_MENUS_ITEM_FIELD_MENU_TEXT_LABEL'));
		$result->title->value = self::_getYesNoRadioSelect('menu_text', 'radio', 'params_menu_text', $params->menu_text ? $params->menu_text : 0);
		
		return $result;
	}
	
	function _getPageDisplayFields($params) {
		$result = new stdclass();

		$result->page_title = new stdclass();
		$result->page_title->name = self::_getLabel('page_title-lbl', 'hasTip', '', 'page_title', JText::_('COM_MENUS_ITEM_FIELD_PAGE_TITLE_LABEL'));
		$result->page_title->value = self::_getInput('page_title', 'inputbox', 'text', false, null, $params->page_title, 'params[page_title]');
		
		$result->show_page_heading = new stdclass();
		$result->show_page_heading->name = self::_getLabel('show_page_heading-lbl', 'hasTip', '', 'show_page_heading', JText::_('COM_MENUS_ITEM_FIELD_SHOW_PAGE_HEADING_LABEL'));
		$result->show_page_heading->value = self::_getYesNoRadioSelect('show_page_heading', 'radio', 'params_show_page_heading', $params->show_page_heading ? $params->show_page_heading : 0);
		
		$result->page_heading = new stdclass();
		$result->page_heading->name = self::_getLabel('page_heading-lbl', 'hasTip', '', 'page_heading', JText::_('COM_MENUS_ITEM_FIELD_PAGE_HEADING_LABEL'));
		$result->page_heading->value = self::_getInput('page_heading', 'inputbox', 'text', false, null, $params->page_heading, 'params[page_heading]');
		
		$result->pageclass_sfx = new stdclass();
		$result->pageclass_sfx->name = self::_getLabel('pageclass_sfx-lbl', 'hasTip', '', 'pageclass_sfx', JText::_('COM_MENUS_ITEM_FIELD_PAGE_CLASS_LABEL'));
		$result->pageclass_sfx->value = self::_getInput('pageclass_sfx', 'inputbox', 'text', false, null, $params->pageclass_sfx, 'params[pageclass_sfx]');
		
		return $result;
	}
	
	function _getMetadataFields($params) {
		$result = new stdclass();

		$result->meta_description = new stdclass();
		$result->meta_description->name = self::_getLabel('menu-meta_description-lbl', 'hasTip', '', 'menu-meta_description', JText::_('JFIELD_META_DESCRIPTION_LABEL'));
		$result->meta_description->value = self::_getTextarea('menu-meta_description', 3, 40, $params->{'menu-meta_description'}, 'params[menu-meta_description]');
		
		$result->meta_keywords = new stdclass();
		$result->meta_keywords->name = self::_getLabel('menu-meta_keywords-lbl', 'hasTip', '', 'menu-meta_keywords', JText::_('JFIELD_META_KEYWORDS_LABEL'));
		$result->meta_keywords->value = self::_getTextarea('menu-meta_keywords', 3, 40, $params->{'menu-meta_keywords'}, 'params[menu-meta_keywords]');
		
		$result->robots = new stdclass();
		$result->robots->name = self::_getLabel('robots-lbl', 'hasTip', '', 'robots', JText::_('JFIELD_METADATA_ROBOTS_LABEL'));
		$result->robots->value = self::_getRobotsSelect($params->robots ? $params->robots : '');
		
		$result->secure = new stdclass();
		$result->secure->name = self::_getLabel('secure-lbl', 'hasTip', '', 'secure', JText::_('COM_MENUS_ITEM_FIELD_SECURE_LABEL'));
		$result->secure->value = self::_getSecureSelect($params->secure ? $params->secure : 0);
		
		return $result;
	}
	
	function _getAssociations($pk) {
		$associations = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__menu as m');
		$query->innerJoin('#__associations as a ON a.id=m.id AND a.context='.$db->quote('com_menus.item'));
		$query->innerJoin('#__associations as a2 ON a.key=a2.key');
		$query->innerJoin('#__menu as m2 ON a2.id=m2.id');
		$query->where('m.id='.(int)$pk);
		$query->select('m2.language, m2.id');
		$db->setQuery($query);
		$menuitems = $db->loadObjectList('language');
		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
			return false;
		}
		foreach ($menuitems as $tag=>$item) {
			$associations[$tag] = $item->id;
		}
		return $associations;
	}
	
	function _deleteAllAssociations($associations) {
		if (count($associations)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__associations');
			$query->where('context='.$db->quote('com_menus.item'));
			$query->where('id IN ('.implode(',', $associations).')');
			$db->setQuery($query);
			$db->query();
			if ($error = $db->getErrorMsg()) {
				JError::raiseWarning('', $error);
				return false;
			}
		}
		return true;
	}
	
	function _getAssociationFields($menu_item) {
		$current_language = $menu_item->language;
		
		$languages = JLanguageHelper::getLanguages('lang_code');
		$menutypes = self::_getAllMenuTypes();
		$tmp_items = self::getAllItems(null, null, ANY_LEVEL, ANY_PUBLISHING, ANY_MENUTYPE, ANY_LANGUAGE, false);
		$associations = self::_getAssociations($menu_item->id);
		
		$items = array();
		if (count($tmp_items)) {
			foreach ($tmp_items as $item) {
				if (($item->language != '') && ($item->language != '*')) {
					$items[$item->language][$item->menutype][] = $item;
				}
			}
		}
		unset($tmp_items);
		
		$result = new stdclass();
		//if (($current_language != '') && ($current_language != '*')) {
			foreach ($languages as $tag => $language) {
				if ($tag != $current_language) {
					$select_items = array(JHTML::_('select.option', '', JText::_('COM_MENUS_ITEM_FIELD_ASSOCIATION_NO_VALUE')));
					foreach ($menutypes as $menutype) {
						$select_items[] = JHTML::_('select.optgroup', $menutype->menutype);
						if (count($items[$tag][$menutype->menutype])) {
							foreach ($items[$tag][$menutype->menutype] as $item) {
								$select_items[] = JHTML::_('select.option', $item->id, str_repeat('- ', $item->level + 1).$item->title);
							}
						}
						$select_items[] = JHTML::_('select.optgroup', $menutype->menutype);
					}
					
					$result->$tag = new stdclass();
					$result->$tag->name = self::_getLabel('associations-'.$tag.'-lbl', 'hasTip', '', 'associations-'.$tag, $language->title);
					$result->$tag->value = JHTML::_('select.genericlist', $select_items, 'associations['.$tag.']', ' class=""', 'value', 'text', $associations[$tag]);
				}
			}
		//}
		return $result;
	}
	
	function getMenuItem($id) {
		if ($id) {
			$db = JFactory::getDBO();
			$query = "SELECT m.* FROM `#__menu` AS m WHERE m.`id`=".$id;
			$db->setQuery($query);
			$item = $db->loadObject();
			if ($item->params) $item->params = json_decode($item->params);
		} else {
			$data = JRequest::get('post');
			$item = new stdclass();
			$keys = array('id', 'menutype', 'title', 'alias', 'note', 'path', 'link', 'type', 'published', 'parent_id', 'level', 'component_id', 'ordering', 'checked_out', 'checked_out_time', 'browserNav', 'access', 'img', 'template_style_id', 'lft', 'rgt', 'home', 'language', 'client_id');
			foreach ($keys as $key) {
				$item->$key = $data[$key];
			}
			unset($keys);
			$params = array('menu-anchor_title', 'menu-anchor_css', 'menu_image', 'menu_text', 'page_title', 'show_page_heading', 'page_heading', 'pageclass_sfx', 'menu-meta_description', 'menu-meta_keywords', 'robots', 'secure');
			$item->params = new stdclass();
			foreach ($params as $key) {
				$item->params->$key = $data['params'][$key];
			}
			unset($params);
		}
		$result = new stdclass();
		$result->standart = self::_getStandartFields($item);
		$result->jshopping = self::_getJshoppingFields($item);
		$result->linktype = self::_getLinkTypeFields($item->params);
		$result->pagedisplay = self::_getPageDisplayFields($item->params);
		$result->metadata = self::_getMetadataFields($item->params);
		if (JFactory::getApplication()->item_associations) {
			$result->association = self::_getAssociationFields($item);
		}
		$result->hidden = self::_getHiddenFields($item);
		return $result;
	}
	
	function updateParamsForMenu(&$post) {
		$jshopping_menu = self::getJshoppingMenu($post['jshopping_menu']);
		$post['link'] = 'index.php?option=com_jshopping';
		if ($jshopping_menu->controller) $post['link'] .= '&view='.$jshopping_menu->controller;
		if ($jshopping_menu->task) $post['link'] .= '&task='.$jshopping_menu->task;
		if ($jshopping_menu->params) {
			foreach ($jshopping_menu->params as $v) {
				if (isset($post[$v->key]) && strlen($post[$v->key])) {
					$post['link'] .= '&'.$v->key.'='.$post[$v->key];
				}
			}
		}
	}
	
	function saveMenuItem($id, $data) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$table = JTable::getInstance('Menu', 'JTable');
		$isNew	= true;
		if ($id > 0) {
			$table->load($id);
			$isNew = false;
		}
		if (!$isNew && $table->menutype == $data['menutype']) {
			if ($table->parent_id == $data['parent_id'] ) {
				if ($data['ordering'] == ORDERING_FIRST) {
					$table->setLocation($data['parent_id'], 'first-child');
				}
				elseif ($data['ordering'] == ORDERING_LAST) {
					$table->setLocation($data['parent_id'], 'last-child');
				}
				elseif ($data['ordering'] && $table->id != $data['ordering'] || empty($data['id'])) {
					$table->setLocation($data['ordering'], 'after');
				}
				elseif ( $data['ordering'] && $table->id ==  $data['ordering'])	{
					unset( $data['ordering']);
				}
			}
			else {
				$table->setLocation($data['parent_id'], 'last-child');
			}
		}
		elseif ($isNew) {
			$table->setLocation($data['parent_id'], 'last-child');
		}
		else  {
			$table->setLocation(1, 'last-child');
		}
		if (!($table->bind($data) && $table->check() && $table->store() && $table->rebuildPath($table->id))) {
			JError::raiseWarning('', $table->getError());
			return false;
		}
		
		if (JFactory::getApplication()->item_associations) {
			$all_language = $table->language == '*';
			$associations = $data['associations'];
			foreach ($associations as $tag=>$id) {
				if (empty($id)) {
					unset($associations[$tag]);
				}
			}
			if ($all_language && !empty($associations)) {
				JError::raiseNotice(403, JText::_('COM_MENUS_ERROR_ALL_LANGUAGE_ASSOCIATED'));
			} else {
				self::_deleteAllAssociations(self::_getAssociations($table->id));
				$associations[$table->language]=$table->id;
				foreach ($associations as $v) {
					self::_deleteAllAssociations(self::_getAssociations($v));
				}
				
				if (!$all_language && count($associations)>1) {
					$key = md5(json_encode($associations));
					$query->clear();
					$query->insert('#__associations');
					foreach ($associations as $tag=>$id) {
						$query->values($id.','.$db->quote('com_menus.item').','.$db->quote($key));
					}
					$db->setQuery($query);
					$db->query();
					if ($error = $db->getErrorMsg()) {
						JError::raiseWarning('', $error);
						return false;
					}
				}
			}
		}
		return $table->id;
	}
	
	function setHome(&$pks, $value = 1) {
		$table = JTable::getInstance('Menu', 'JTable');
		$pks = (array) $pks;
		$languages = array();
		$onehome = false;
		foreach ($pks as $i=>$pk) {
			if ($table->load($pk)) {
				if (!array_key_exists($table->language, $languages)) {
					$languages[$table->language] = true;
					if ($table->home == $value) {
						unset($pks[$i]);
						JError::raiseNotice(403, JText::_('COM_MENUS_ERROR_ALREADY_HOME'));
					} else {
						$table->home = $value;
						if ($table->language == '*') {
							$table->published = 1;
						}
						if (!$table->check()) {
							unset($pks[$i]);
							JError::raiseWarning(403, $table->getError());
						}
						elseif (!$table->store()) {
							unset($pks[$i]);
							JError::raiseWarning(403, $table->getError());
						}
					}
				}
				else {
					unset($pks[$i]);
					if (!$onehome) {
						$onehome = true;
						JError::raiseNotice(403, JText::sprintf('COM_MENUS_ERROR_ONE_HOME'));
					}
				}
			}
		}
		return true;
	}
	
}
?>