<?php
    /*
    * @version      1.0.0 20.07.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    JHtml::_('formbehavior.chosen', 'select');

    /* @var $addon AddonApi */
    /* @var $item  AddonApiUser */
    $addon            = $this->addon;
    $html_sidebar     = $this->html_sidebar;
    $html_menu        = $this->html_menu;
    $link             = $this->link;
    $items            = $this->items;
    $pagination       = $this->pagination;
    $filter_order     = $this->filter_order;
    $filter_order_Dir = $this->filter_order_Dir;
    $filter_publish   = $this->filter_publish;
    $saveOrder        = $filter_order == 'ordering' && $filter_order_Dir == 'asc';
    $total            = count($items);
    $states           = [
        [
            'active_class' => 'unpublish',
            'active_title' => _JSHOP_ADDON_API_UNBLOCK_USER,
            'task'         => 'publish',
            'tip'          => true
        ],
        [
            'active_class' => 'publish',
            'active_title' => _JSHOP_ADDON_API_BLOCK_USER,
            'task'         => 'unpublish',
            'tip'          => true
        ]
    ];
    $addon->addCss();
?>
<script>
    Joomla.submitbutton = function(task) {
        if (task == 'remove' && !confirm('<?php echo _JSHOP_ADDON_API_SURE; ?>')) {
            return false;
        }
        Joomla.submitform(task, document.getElementById('adminForm'));
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
    <form id="adminForm" name="adminForm" class="jshop_addon_api" action="<?php echo $link; ?>" method="post">
        <div class="jshop_block_filter">
            <select name="filter_publish" onchange="document.adminForm.submit();">
                <option value="">- <?php echo JText::_('JSTATUS'); ?> -</option>
                <?php
                    foreach([
                        1 => _JSHOP_ADDON_API_UNBLOCKED,
                        0 => _JSHOP_ADDON_API_BLOCKED
                    ] as $value => $name) {
                        echo '<option value="' . $value . '"' . (strcmp($value, $filter_publish) ? '' : ' selected') . '>' . $name . '</option>';
                    }
                ?>
            </select>
            <button type="button" class="btn" onclick="jQuery('.jshop_block_filter select').val(''); document.adminForm.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
			</button>
        </div>
        <?php if ($total) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="2.5%" class="center">
                            #
                        </th>
                        <th width="2.5%" class="center">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)">
                        </th>
                        <th align="left">
                            <?php echo JHtml::_('grid.sort', _JSHOP_EMAIL, 'email', $filter_order_Dir, $filter_order); ?>
                        </th>
                        <th width="<?php echo $saveOrder ? 10 : 7.5; ?>%" class="center" colspan="<?php echo $saveOrder ? 3 : 1; ?>">
                            <?php echo JHtml::_('grid.sort', _JSHOP_ORDERING, 'ordering', $filter_order_Dir, $filter_order); ?>
                            <?php echo $saveOrder ? JHtml::_('grid.order',  $items, 'filesave.png', 'saveorder') : ''; ?>
                        </th>
                        <th width="5%" class="center">
                            <?php echo JHtml::_('grid.sort', JText::_('JSTATUS'), 'state', $filter_order_Dir, $filter_order); ?>
                        </th>
                        <th width="2.5%" class="center">
                            <?php echo _JSHOP_EDIT; ?>
                        </th>
                        <th width="2.5%" class="center">
                            <?php echo _JSHOP_DELETE; ?>
                        </th>
                        <th width="2.5%" class="center">
                            <?php echo JHtml::_('grid.sort', _JSHOP_ID, 'id', $filter_order_Dir, $filter_order); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $i => $item) { ?>
                        <tr class="item<?php echo $i % 2; ?>">
                            <td>
                                <?php echo $pagination->getRowOffset($i); ?>
                            </td>
                            <td>
                                <?php echo JHtml::_('grid.id', $i, $item->getId()); ?>
                            </td>
                            <td>
                                <a
                                    class="hasTooltip"
                                    title="<?php echo _JSHOP_EDIT; ?>"
                                    href="<?php echo $item->getLink(); ?>"
                                >
                                    <?php echo $item->getEmail(); ?>
                                </a>
                            </td>
                            <?php if ($saveOrder) { ?>
                                <td>
                                    <span>
                                        <?php echo $i ? JHtml::_('jgrid.orderUp', $i, 'orderup') : ''; ?>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <?php echo ($i < $total - 1) ? JHtml::_('jgrid.orderDown', $i, 'orderdown') : ''; ?>
                                    </span>
                                </td>
                                <td class="center">
                                    <input name="order[]" type="text" value="<?php echo $item->getOrdering(); ?>" style="width: 25px; text-align: center" id="ord<?php echo $item->getId(); ?>">
                                </td>
                            <?php } else { ?>
                                <td class="center">
                                    <input name="order[]" type="text" value="<?php echo $item->getOrdering(); ?>" style="width: 25px; text-align: center" disabled>
                                </td>
                            <?php } ?>
                            <td class="center">
                                <?php echo JHtml::_('jgrid.state', $states, (int) $item->getState(), $i); ?>
                            </td>
                            <td class="center">
                                <a
                                    class="btn btn-micro hasTooltip"
                                    title="<?php echo _JSHOP_EDIT; ?>"
                                    href="<?php echo $link; ?>&task=edit&id=<?php print $item->getId(); ?>"
                                >
                                    <i class="icon-edit"></i>
                                </a>
                            </td>
                            <td class="center">
                                 <a
                                    class="btn btn-micro hasTooltip"
                                    title="<?php echo _JSHOP_DELETE; ?>"
                                    href="index.php?option=com_jshopping&controller=addon_api_users&task=remove&cid[]=<?php echo $item->getId(); ?>"
                                    onclick="return confirm('<?php echo _JSHOP_ADDON_API_SURE; ?>');"
                                >
                                     <i class="icon-delete"></i>
                                 </a>
                            </td>
                            <td class="center">
                                <?php print $item->getId(); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            <div class = "jshop_list_footer"><?php echo $pagination->getListFooter(); ?></div>
                            <div class = "jshop_limit_box"><?php   echo $pagination->getLimitBox();   ?></div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" name="filter_order"     value="<?php echo $filter_order; ?>">
            <input type="hidden" name="filter_order_Dir" value="<?php echo $filter_order_Dir; ?>">
        <?php } else { ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php } ?>
        <input type="hidden" name="task"         value="<?php echo JFactory::getApplication()->input->getCmd('task'); ?>">
        <input type="hidden" name="hidemainmenu" value="0">
        <input type="hidden" name="boxchecked"   value="0">
    </form>
</div>