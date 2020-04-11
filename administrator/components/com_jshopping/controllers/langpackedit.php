<?php

/**
 * @version      1.0.4 20.04.2016
 * @author       MAXXmarketing GmbH
 * @package      Jshopping
 * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class JshoppingControllerLangpackedit extends JControllerLegacy {

    function __construct($config = array()) {
        JSFactory::loadExtLanguageFile('addon_lang_translator');
        parent::__construct($config);
        $this->registerTask('apply', 'save');
        addSubmenu("other");
    }

    function display($cachable = false, $urlparams = false) {
        $model = $this->getModel('langpack');
        $langpath = JPATH_ROOT . '/components/com_jshopping/lang';
        $_folders = $model->getFiles();
        foreach ($_folders as $v) {
            if ($v['fullname'] == $langpath) {
                $mainfile = $v['key'];
            }
        }
        $langfolder = JRequest::getVar('langfolder') ? JRequest::getVar('langfolder') : $mainfile;
        $list_files = JHTML::_('select.genericlist', $_folders, 'langfile', 'onchange="window.location=\'' . JUri::root() . 'administrator/index.php?option=com_jshopping&controller=langpackedit&langfolder=\'+this.options[this.selectedIndex].value"', 'key', 'name', $langfolder);

        $_data = $model->getFileConstants($_folders, $langfolder);        
        $translate = $_data['translate'];
        $langs = $_data['langs'];
        $header = $_data['header'];
        $constants = $_data['constants'];

        $view = $this->getView("langpackedit", 'html');
        $view->assign("list_files", $list_files);
        $view->assign("translate", $translate);
        $view->assign("langs", $langs);
        $view->assign("header", $header);
        $view->assign("constants", $constants);
        $view->assign("type", $_data['type']);
        $view->display();
    }

    function save() {        
        $langfolder = JRequest::getVar('langfile');
        $langs = JRequest::getVar('lang');
        $constants = JRequest::getVar('constants');
        $fileheader = JRequest::getVar('fileheader');
        $type = JRequest::getVar('type');

        $model = $this->getModel('langpack');        
        $model->save($langfolder, $constants, $langs, $fileheader, $type);
        
        $mainframe = JFactory::getApplication();
        $mainframe->redirect("index.php?option=com_jshopping&controller=langpackedit&langfolder=" . $langfolder, _JSHOP_COMPLETED);
    }

}