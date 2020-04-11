<?php
/**
* @version      4.0.1 20.12.2012
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

    defined('_JEXEC') or die('Restricted access');
    error_reporting(error_reporting() & ~E_NOTICE);
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    }

    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/jtableauto.php');
    require_once (JPATH_SITE.'/components/com_jshopping/tables/config.php'); 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');

    JSFactory::loadCssFiles();

    JTable::addIncludePath(JPATH_SITE.'/components/com_jshopping/tables');
    $currency = JTable::getInstance('currency', 'jshop');

    $currencis_list = $currency->getAllCurrencies('1');

    $jshopConfig = JSFactory::getConfig();   
    $jshopConfig->loadCurrencyValue();
    
    $url = JRoute::_("index.php?option=com_jshopping&id_currency=idcurval&back=idbackval");
    $url = str_replace("idbackval", urlencode($_SERVER['REQUEST_URI']), $url);
    $url = str_replace("idcurval", "'+this.value+'", $url);    
    $currencies_display_list = JHTML::_('select.genericlist',$currencis_list,'id_currency','class = "inputbox" size = "1" onchange = "location.href=\''.$url.'\'"' ,'currency_id','currency_name',$jshopConfig->cur_currency);
    
    require(JModuleHelper::getLayoutPath('mod_jshopping_currencies'));   
?>