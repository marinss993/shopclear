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
    /* @var $item  AddonApiUser */
    $addon    = $this->addon;
    $link     = $this->link;
    $item     = $this->item;
    $html_log = $this->html_log;
    $addon->addCss();
    JHtml::_('behavior.formvalidation');
    JHtml::_('formbehavior.chosen', 'select');
?>
<script>
    Joomla.submitbutton = function(task) {
        if( task == 'cancel' || document.formvalidator.isValid(document.id('adminForm')) ) {
            Joomla.submitform(task);
        }
    }
</script>
<div class="jshop_edit">
    <form id="adminForm" name="adminForm" action="<?php echo $link; ?>" method="post" enctype="multipart/form-data">
        <fieldset class="form-horizontal">
            <?php
                echo JHtml::_('bootstrap.startTabSet', 'myTab', ['active' => 'basic']);
                    echo JHtml::_('bootstrap.addTab', 'myTab', 'basic', JText::_('JDETAILS'));
                        echo $addon->renderField(
                            '
                                <field
                                    name="email"
                                    type="email"
                                    ' . ($item->getId() ? 'readonly="true"' : '') . '
                                    required="true"
                                    label="' . _JSHOP_EMAIL . '"
                                />
                            ',
                            $item->getEmail()
                        );
                        echo $addon->renderField(
                            '
                                <field
                                    name="password"
                                    type="text"
                                    required="true"
                                    label="' . _JSHOP_PASSWORD . '"
                                />
                            ',
                            $item->getPassword()
                        );
                        echo $addon->renderField(
                            '
                                <field
                                    name="state"
                                    label="JSTATUS"
                                    type="list"
                                    class="chzn-color-state"
                                >
                                    <option value="1">JON</option>
                                    <option value="0">JOFF</option>
                                </field>
                            ',
                            (int) $item->getState()
                        );
                        if ($item->getId()) {
                            echo $addon->renderField(
                                '
                                    <field
                                        name="creation_datetime"
                                        type="content"
                                        class="uneditable-input"
                                        label="' . _JSHOP_ADDON_API_CREATION_DATETIME . '"
                                    />
                                ',
                                $item->getCreationDatetime()
                            );
                        }
                    echo JHtml::_('bootstrap.endTab');
                    if ($html_log) {
                        echo JHtml::_('bootstrap.addTab', 'myTab', 'log', _JSHOP_LOGS);
                            echo $html_log;
                        echo JHtml::_('bootstrap.endTab');
                    }
                echo JHtml::_('bootstrap.endTabSet');
            ?>
        </fieldset>
        <input type="hidden" name="id"   value="<?php echo $item->getId(); ?>">
        <input type="hidden" name="task" value="">
    </form>
</div>