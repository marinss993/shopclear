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

class plgJshoppingAdminGenerate_Alias extends JPlugin {

	private function generateAlias($type) {
		if (!isset($_POST['task']) || $_POST['task']!='generateAlias') {
			return;
		}
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'array');
		if (!count($cid)) {
			$app->enqueueMessage(JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'), 'error');
			return;
		}

		if ($type == 'category') {
			$table_name = '`#__jshopping_categories`';
		} else if ($type == 'manufacturer') {
			$table_name = '`#__jshopping_manufacturers`';
		} else {
			$table_name = '`#__jshopping_products`';
		}
		$select_id = $type.'_id';

		$replace = $this->params->get('replace', 0);
		$add_id = $this->params->get('add_id', 0);
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
				->select('language')
				->from('#__jshopping_languages')
				->where('1');
		$db->setQuery($query);
		$allLanguage = $db->loadColumn();
		if (!count($allLanguage)) {
			$app->enqueueMessage(JText::_('PLG_JSHOPPINGADMIN_GENERATE_ALIAS_NO_LANGUAGE'), 'warning');
			return;
		}

		$select = array();
		foreach ($allLanguage as $language) {
			$select[] = '`name_'.$language.'`';
			$select[] = '`alias_'.$language.'`';
		}
		$query = $db->getQuery(true)
				->select($select_id.' as id,'.implode(',', $select))
				->from($table_name)
				->where($select_id.' IN ('.implode(',', $cid).')');
		$db->setQuery($query);
		$elements = $db->loadObjectList();
		
		if (!count($elements)) {
			$app->enqueueMessage(JText::_('PLG_JSHOPPINGADMIN_GENERATE_ALIAS_NO_ELEMENTS'), 'warning');
			return;
		}

		$query = 'CREATE TEMPORARY TABLE `jshopping_temp_table_for_generate_aliases` (id INT, '.implode(' VARCHAR(255),', $select).' VARCHAR(255), PRIMARY KEY (`id`)) DEFAULT CHARACTER SET utf8';
		$db->setQuery($query);
		$db->execute();
		
		$insertValues = $updateValues = array();
		$generated = 0;
		foreach ($elements as $element) {
			$insertValue = array();
			$insertValue[] = $element->id;
			foreach ($allLanguage as $language) {
				$name = $element->{'name_'.$language}.($add_id ? '-'.$element->id : '');
				$alias = 'alias_'.$language;
				$insertValue[] = $db->getEscaped($name);
				if ($element->$alias == '' || $replace) {
					if ($name != '') {
						$insertValue[] = JApplication::stringURLSafe($name);
						$generated++;
					} else {
						$insertValue[] = '';
					}
				} else {
					$insertValue[] = $element->$alias;
				}
				$updateValues[] = $table_name.'.'.'`'.$alias.'` = jshopping_temp_table_for_generate_aliases.`'.$alias.'`';
			}
			$insertValues[] = '("'.implode('","', $insertValue).'")';
		}
		$query = 'INSERT INTO jshopping_temp_table_for_generate_aliases VALUES '.implode(',', $insertValues);
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true)
				->update($table_name.', jshopping_temp_table_for_generate_aliases')
				->set($updateValues)
				->where('jshopping_temp_table_for_generate_aliases.id = '.$table_name.'.'.$select_id);
		$db->setQuery($query);
		$result = $db->execute();
		
		$app->enqueueMessage(JText::_('PLG_JSHOPPINGADMIN_GENERATE_ALIAS_UPDATE').$generated);
	}

	private function init($type) {
		JFactory::getLanguage()->load('plg_jshoppingadmin_generate_alias', dirname(__FILE__));
		JToolBarHelper::custom('generateAlias', 'refresh', 'refresh', JText::_('PLG_JSHOPPINGADMIN_GENERATE_ALIAS_BUTTON'), true);
		$this->generateAlias($type);
	}

	function onBeforeDisplayListCategoryView(&$view) {
		$this->init('category');
	}

	function onBeforeDisplayManufacturers(&$view) {
		$this->init('manufacturer');
	}

	function onBeforeDisplayListProductsView(&$view) {
		$this->init('product');
	}

}
?>