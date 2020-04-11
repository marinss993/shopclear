<?php
    /*
    * @version      1.0.0 13.07.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    JHtml::_('bootstrap.tooltip');
    JHtml::_('formbehavior.chosen', 'select');

    /* @var $addon AddonApi */
    /* @var $item  AddonApiConnection */
    $addon            = $this->addon;
    $html_sidebar     = $this->html_sidebar;
    $html_menu        = $this->html_menu;
    $link             = $this->link;
    $items            = $this->items;
    $api_users        = $this->api_users;
    $pagination       = $this->pagination;
    $filter_api_user  = $this->filter_api_user;
    $filter_publish   = $this->filter_publish;
    $filter_order     = $this->filter_order;
    $filter_order_Dir = $this->filter_order_Dir;
    $states           = [
        [
            'inactive_class' => 'unpublish',
            'inactive_title' => _JSHOP_ADDON_API_INACTIVE,
            'tip'            => true
        ],
        [
            'inactive_class' => 'publish',
            'inactive_title' => _JSHOP_ADDON_API_ACTIVE,
            'tip'            => true,
        ]
    ];
    $addon->addCss();
?>
<script>
    Joomla.submitbutton = function(task) {
        if( task == 'remove' && !confirm('<?php echo _JSHOP_ADDON_API_SURE; ?>') ) {
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
            <select name="filter_api_user" onchange="document.adminForm.submit();">
                <option value="">- <?php echo _JSHOP_USERS; ?> -</option>
                <?php
                    foreach($api_users as $api_user_id => $api_user_email) {
                        echo '<option value="' . $api_user_id . '"' . (strcmp($api_user_id, $filter_api_user) ? '' : ' selected') . '>' . $api_user_email . '</option>';
                    }
                ?>
            </select>
            <select name="filter_publish" onchange="document.adminForm.submit();">
                <option value="">- <?php echo JText::_('JSTATUS'); ?> -</option>
                <?php
                    foreach([
                        1 => _JSHOP_ADDON_API_ACTIVE,
                        0 => _JSHOP_ADDON_API_INACTIVE
                    ] as $value => $name) {
                        echo '<option value="' . $value . '"' . (strcmp($value, $filter_publish) ? '' : ' selected') . '>' . $name . '</option>';
                    }
                ?>
            </select>
            <button type="button" class="btn" onclick="jQuery('.jshop_block_filter select').val(''); document.adminForm.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
			</button>
        </div>
        <?php if ($count = count($items)) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="2%" class="title">
                            #
                        </th>
                        <th width="2%">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)">
                        </th>
                        <th align="left">
                            <?php echo JHtml::_('grid.sort', _JSHOP_ADDON_API_TOKEN, 'token', $filter_order_Dir, $filter_order); ?>
                        </th>
                        <th class="center">
                            <?php echo JHtml::_('grid.sort', _JSHOP_USER, 'api_user_id', $filter_order_Dir, $filter_order); ?>
                        </th>
                        <th class="center">
                            <?php echo JHtml::_('grid.sort', _JSHOP_ADDON_API_OPENING_DATETIME, 'opening_datetime', $filter_order_Dir, $filter_order); ?>
                        </th>
                        <th class="center">
                            <?php echo JHtml::_('grid.sort', _JSHOP_ADDON_API_LAST_ACTIVITY_DATETIME, 'last_activity_datetime', $filter_order_Dir, $filter_order); ?>
                        </th>
                        <th width="5%" class="center">
                            <?php echo JText::_('JSTATUS'); ?>
                        </th>
                        <th width="5%" class="center">
                            <?php echo _JSHOP_DELETE; ?>
                        </th>
                        <th width="2%" class="center">
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
                                <a href="<?php echo $item->getLink(); ?>">
                                    <?php echo $item->getToken(); ?>
                                </a>
                            </td>
                            <td class="center">
                                <a
                                    class="hasTooltip"
                                    title="<?php echo _JSHOP_EDIT; ?>"
                                    href="<?php echo $item->getApiUser()->getLink(); ?>"
                                    target="_blank"
                                >
                                    <?php echo $item->getApiUser()->getEmail(); ?>
                                </a>
                            </td>
                            <td class="center">
                                <?php echo formatdate($item->getOpeningDatetime(), true); ?>
                            </td>
                            <td class="center">
                                <?php echo formatdate($item->getLastActivityDatetime(), true); ?>
                            </td>
                            <td class="center">
                                <?php echo JHtml::_('jgrid.state', $states, (int) $item->getState(), $i, 'connections.', false); ?>
                            </td>
                            <td class="center">
                                 <a
                                    class="btn btn-micro hasTooltip"
                                    title="<?php echo _JSHOP_DELETE; ?>"
                                    href="index.php?option=com_jshopping&controller=addon_api_connections&task=remove&cid[]=<?php echo $item->getId(); ?>"
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
        <input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0)?>">
        <input type="hidden" name="hidemainmenu" value="0">
        <input type="hidden" name="boxchecked" value="0">
    </form>
</div>