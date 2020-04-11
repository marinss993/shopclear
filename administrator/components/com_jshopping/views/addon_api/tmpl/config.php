<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    JHtml::_('behavior.tabstate');
    JHtml::_('behavior.formvalidation');
    JHtml::_('formbehavior.chosen', 'select');
    /* @var $addon AddonApi */
    $addon          = $this->addon;
    $html_sidebar   = $this->html_sidebar;
    $html_menu      = $this->html_menu;
    $link           = $this->link;
    $item           = $this->item;
    $clear_log_link = $this->clear_log_link;
    $html_log       = $this->html_log;
    $menu_items     = $this->menu_items;
    $addon->addCss();
?>
<script>
    Joomla.submitbutton = function(task) {
        if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task);
        }
    }
</script>
<div id="j-sidebar-container" class="span2">
    <?php echo $html_sidebar; ?>
</div>
<div id="j-main-container" class="span10">
    <?php
        displaySubmenuOptions('addon_api');
        echo $html_menu;
    ?>
    <form id="adminForm" name="adminForm" action="<?php echo $link; ?>" method="post" enctype="multipart/form-data">
        <fieldset class="form-horizontal">
            <?php
                $radio_tail = ' type="radio" class="btn-group btn-group-yesno"><option value="1">JYES</option><option value="0">JNO</option></field>';
                echo JHtml::_('bootstrap.startTabSet', 'myTab', ['active' => 'general']);
                    echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('JGLOBAL_FIELDSET_BASIC'));
                        echo $addon->renderField('<field name="params[test_mode]" label="'                             . _JSHOP_ADDON_API_TEST_MODE                     . '"' . $radio_tail);
                        echo $addon->renderField('<field name="params[logging]" label="'                               . _JSHOP_ADDON_API_LOGGING                       . '"' . $radio_tail);
                        echo $addon->renderField('<field name="params[connections_checking_interval_minutes]" label="' . _JSHOP_ADDON_API_CONNECTIONS_CHECKING_INTERVAL . ' (' . _JSHOP_ADDON_API_MINUTES . ')' . '" type="text" />');
                        $options = '';
                        foreach ($menu_items as $key => $menu_item) {
                            $options .= '<option value="' . $key . '">' . $menu_item->name . '</option>';
                        }
                        echo $addon->renderField('<field name="params[default_menu_item]" label="' . _JSHOP_ADDON_API_DEFAULT_MENU_ITEM . '" type="list"> ' . $options . '</field>');
                    echo JHtml::_('bootstrap.endTab');
                    echo JHtml::_('bootstrap.addTab', 'myTab', 'token', _JSHOP_ADDON_API_TOKEN);
                        echo $addon->renderField('<field name="params[token][length]"           label="' . _JSHOP_ADDON_API_LENGTH   . '" type="text" />');
                        echo $addon->renderField('<field name="params[token][lifetime_minutes]" label="' . _JSHOP_ADDON_API_LIFETIME . ' (' . _JSHOP_ADDON_API_MINUTES . ')' . '" type="text" />');
                    echo JHtml::_('bootstrap.endTab');
                    if ($html_log) {
                        echo JHtml::_('bootstrap.addTab', 'myTab', 'log', _JSHOP_LOGS);
                            echo $html_log;
                        echo JHtml::_('bootstrap.endTab');
                    }
                echo JHtml::_('bootstrap.endTabSet');
            ?>
        </fieldset>
        <input type="hidden" value="" name="task">
        <input type="hidden" value="<?php echo $addon->getId(); ?>" name="id">
    </form>
</div>