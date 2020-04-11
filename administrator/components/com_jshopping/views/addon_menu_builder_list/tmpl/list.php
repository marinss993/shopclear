<?php
 /**
* @version      4.1.0 26.02.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die;

$show_associations = JFactory::getApplication()->item_associations;

JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

?>
<?php echo helperMB::htmlAdminSidebar('start'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=addon_menu_builder');?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="btn-group">&nbsp;</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<div class="btn-group pull-right hidden-phone"><?php echo $this->filter['language'];?></div>
		<div class="btn-group pull-right hidden-phone"><?php echo $this->filter['published'];?></div>
		<div class="btn-group pull-right"><?php echo $this->filter['menutype'];?></div>
	</div>
	
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%">
					#
				</th>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<?php echo JText::_('JGLOBAL_TITLE'); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('JSTATUS'); ?>
				</th>
				<th width="11%">
					<?php echo JText::_('COM_MENUS_ITEM_FIELD_ASSIGNED_LABEL'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_MENUS_ITEM_FIELD_HOME_LABEL'); ?>
				</th>
				<?php if ($show_associations) { ?>
				<th width="10%">
					<?php echo JText::_('COM_MENUS_HEADING_ASSOCIATION'); ?>
				</th>
				<?php } ?>
				<th width="5%">
					<?php echo _JSHOP_LANGUAGE_NAME; ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JText::_('JGRID_HEADING_ID'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo 8 + $show_associations;?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			foreach ($this->rows as $i=>$row) { ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo $i + $this->pagination->limitstart + 1; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $row->id); ?>
				</td>
				<td>
					<div class="small">
						<?php echo str_repeat('<span class="gi">|&mdash;</span>', $row->level); ?>
						<a href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=addon_menu_builder&task=edit&id='.$row->id) ?>" title="<?php echo $row->menutype;?>"><?php echo $row->title; ?></a>
						<p class="smallsub">
							<?php echo str_repeat('<span class="gtr">&mdash;</span>', $row->level); ?>
							<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $row->alias);?>
						</p>
					</div>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $row->published, $i, '', 1, 'cb', $item->publish_up, $item->publish_down); // evpadallas edit?>
				</td>
				<td class="center hidden-phone">
					<a href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=addon_menu_builder&task=edit&id='.$row->id) ?>" title="<?php echo $row->menutype;?>"><?php echo $row->menutype; ?></a>
				</td>
				<td class="small center hidden-phone">
				<?php if ($row->language=='*' || $row->home=='0') { ?>
					<?php echo JHtml::_('jgrid.isdefault', $row->home, $i, '', ($row->language != '*' || !$row->home));?>
				<?php } else { ?>
					<a href="<?php echo JRoute::_('index.php?option=com_jshopping&controller=addon_menu_builder&task=unsetDefault&cid[]='.$row->id.'&'.JSession::getFormToken().'=1');?>">
						<?php echo JHtml::_('image', 'mod_languages/'.substr($row->language, 0, 2).'.gif', $row->language, array('title'=>JText::sprintf('COM_MENUS_GRID_UNSET_LANGUAGE', $row->language)), true);?>
					</a>
				<?php } ?>
				</td>
				<?php if ($show_associations) { ?>
				<td class="small center hidden-phone">
					<?php if ($row->associations) {
						$text = array();
						foreach ($row->associations as $tag=>$associated) {
							if ($associated != $row->id) {
								$text[] = JText::sprintf('COM_MENUS_TIP_ASSOCIATED_LANGUAGE', JHtml::_('image', 'mod_languages/'.$row->association_items[$associated]->image.'.gif', $row->association_items[$associated]->language, array('title'=>$row->association_items[$associated]->language), true), $row->association_items[$associated]->title, $row->association_items[$associated]->menu_title);
							}
						}
						echo JHtml::_('tooltip', implode('<br />', $text), JText::_('COM_MENUS_TIP_ASSOCIATION'), 'admin/icon-16-links.png');
					} ?>
				</td>
				<?php } ?>
				<td class="small center hidden-phone">
					<?php echo ($row->language == '*') ? JText::_('JALL') : $row->language_title; ?>
				</td>
				<td class="small center hidden-phone">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div>
	<form action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=addon_menu_builder&task=automenu')?>" method="post" name="fmAuto" id="fmAuto">
		<h4><?php echo JText::_('COM_JSHOP_MENU_BUILDER_AUTOMENU_HDR')?></h4>
		<?php
			$mainframe = JFactory::getApplication();
			$context = "jshoping.list.admin.menu_builder";
			$menutype = $mainframe->getUserStateFromRequest( $context.'menutype', 'filter_menutype', '');
			
			$mt = new stdclass();
			$mt->name = JshoppingModelAddon_menu_builder::_getLabel('menutype-lbl', 'hasTip', '', 'menutype', JText::_('COM_MENUS_ITEM_FIELD_ASSIGNED_LABEL'), false);
			$mt->value = JshoppingModelAddon_menu_builder::getMenuLocationSelect($menutype);
		?>
		<div class="control-group">
			<div class="control-label"><?php echo $mt->name;?></div>
			<div class="controls"><?php echo $mt->value; ?></div>
		</div>
		<input type=submit value="<?php echo JText::_('COM_JSHOP_MENU_BUILDER_AUTOMENU_GEN')?>">
	</form>
</div>

<?php echo helperMB::htmlAdminSidebar('end'); ?>