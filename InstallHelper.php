<?php

class InstallHelper 
{

	/**
	*	@param array $data - `module` names rows table
	* 	@param integer $assignment - 0 = all pages, 1 = main page, 2 = except main page
	*	@return number 1 - if query success, or 0 - if false
	*/	
	public static function installJoomlaModule(array $data, $assignment = 0)
	{
		$db = JFactory::getDbo();
		$extension = JSFactory::getTable('module', 'JTable');
		
	    $extension->bind($data);

	    if ($extension->check()){
	        $extension->store();

	        if ( $assignment == 1 ) {
	        	$db->setQuery('INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES (' . $extension->id . ', ' . static::getMainPageId() . ')');
	        } elseif( $assignment == 2 ) {
	        	$db->setQuery('INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES (' . $extension->id . ', ' . -static::getMainPageId() . ')');
	        } else {
	        	$db->setQuery('INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) VALUES (' . $extension->id . ', 0)');
	        }

            $db->query();

            return 1;

	    }else{
	        return 0;
	    }
	}

	/**
	*	@param string $templateName - Template name
	*	@param string $templateDescription - Description for template
	*/
	public static function installTemplateData($templateName, $templateDescription)
	{
		$db = JFactory::getDbo();

		$db->setQuery("SELECT id FROM `#__template_styles` WHERE template='" . $templateName . "'");
		$exid = (int)$db->loadResult();	

		if ( !$exid  ) {
			$db->setQuery("UPDATE `#__template_styles` SET `home` = 0 WHERE `template` != 'isis' AND `template` != 'hathor' ");
			$db->query();

			$db->setQuery("INSERT INTO `#__template_styles` (`template`, `title`, `home`) VALUES ('" . $templateName . "', '" . $templateDescription . "', 1)");
			$db->query();
				
			$db->setQuery("UPDATE `#__jshopping_config` SET `template` = '" . $templateName . "' WHERE id=1");
			$db->query();
		}

	}	

	/**
	*	@return integer - Main menu id
	*/
	protected static function getMainPageId()
	{
		$db = JFactory::getDbo();

		$query = "SELECT * FROM `#__menu` WHERE `home` = 1";
		$db->setQuery($query);

		return $db->loadObject()->id;
	}

	public static function isEnable($title, $module)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('published'))
			  ->from($db->qn('#__modules'))
			  ->where($db->qn('title') .'='. $db->q($title), 'AND')
			  ->where($db->qn('module') .'='. $db->q($module));
	  	$db->setQuery($query);
	  	return $db->loadResult();
	}

	public static function setDefaultTemplateForJoomshopping()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->qn('#__jshopping_config'))
			  ->set($db->qn('template') .'='. $db->q('default'));
	  	$db->setQuery($query);
	  	return $db->execute();
	}

}
 