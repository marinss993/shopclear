<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    /* @var $addon AddonApi */
    $addon        = $this->addon;
    $html_sidebar = $this->html_sidebar;
    $html_menu    = $this->html_menu;
    $author_email = $addon->getParam('author_email');
    $author_url   = $addon->getParam('author_url');
    $doc_file     = $addon->getAlias() . '_documentation.htm';
    $doc_link     = $addon->getParam('dirs_links[files]') . $doc_file;
    $addon->addCss();
?>
<div id="j-sidebar-container" class="span2">
    <?php echo $html_sidebar; ?>
</div>
<div id="j-main-container" class="span10">
    <?php
        displaySubmenuOptions('addon_api');
        echo $html_menu;
    ?>
    <div class="jshop_addon_api">
        <div class="form-horizontal about">
            <h1>
                <img src="<?php echo JUri::base(); ?>components/com_jshopping/images/<?php echo $addon->getAlias(); ?>/logo.png" />
                <span>
                    <?php echo $addon->getParam('name'); ?>
                </span>
            </h1>
            <?php
                echo $addon->renderField(
                    '<field label="' . JText::_('JVERSION') . '" type="content" />',
                    $addon->getParam('version')
                );
                echo $addon->renderField(
                    '<field label="' . JText::_('JDATE') . '" type="content" />',
                    $addon->getParam('date')
                );
                echo $addon->renderField(
                    '<field label="' . JText::_('JAUTHOR') . '" type="content" />',
                    $addon->getParam('author')
                );
                echo $addon->renderField(
                    '<field label="' . _JSHOP_ADDON_API_AUTHOR_EMAIL . '" type="content" />',
                    '<a href="mailto: ' . $author_email . '">' . $author_email . '</a>'
                );
                echo $addon->renderField(
                    '<field label="' . _JSHOP_ADDON_API_AUTHOR_SITE . '" type="content" />',
                    '<a href="' . $author_url . '" target="_blank">' . $author_url . '</a>'
                );
                echo $addon->renderField(
                    '<field label="' . _JSHOP_ADDON_API_DOCUMENTATION . '" type="content" />',
                    '<a href="' . $doc_link . '" target="_blank">' . $doc_file . '</a>'
                );
            ?>
        </div>
    </div>
</div>