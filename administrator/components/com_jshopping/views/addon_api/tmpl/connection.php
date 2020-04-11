<?php
    /*
    * @version      1.0.0 13.07.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    /* @var $addon AddonApi */
    /* @var $user  AddonApiUser */
    /* @var $item  AddonApiConnection */
    $addon    = $this->addon;
    $link     = $this->link;
    $item     = $this->item;
    $html_log = $this->html_log;
    $user     = $item->getApiUser();
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
                                    type="content"
                                    label="' . _JSHOP_USER . '"
                                />
                            ',
                            '<a href="' . $user->getLink() . '" target="_blank">' . $user->getEmail() . '</a>'
                        );
                        echo $addon->renderField(
                            '
                                <field
                                    type="radio"
                                    class="btn-group btn-group-yesno"
                                    label="JSTATUS"
                                    disabled="true"
                                >
                                    <option value="1">' . _JSHOP_ADDON_API_ACTIVE   . '</option>
                                    <option value="0">' . _JSHOP_ADDON_API_INACTIVE . '</option>
                                </field>
                            ',
                            (int) $item->getState()
                        );
                        echo $addon->renderField(
                            '
                                <field
                                    type="content"
                                    class="uneditable-input"
                                    label="' . _JSHOP_ADDON_API_TOKEN . '"
                                />
                            ',
                            $item->getToken()
                        );
                        echo $addon->renderField(
                            '
                                <field
                                    type="content"
                                    class="uneditable-input"
                                    label="' . _JSHOP_ADDON_API_OPENING_DATETIME . '"
                                />
                            ',
                            $item->getOpeningDatetime()
                        );
                        echo $addon->renderField(
                            '
                                <field
                                    type="content"
                                    class="uneditable-input"
                                    label="' . _JSHOP_ADDON_API_LAST_ACTIVITY_DATETIME . '"
                                />
                            ',
                            $item->getLastActivityDatetime()
                        );
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