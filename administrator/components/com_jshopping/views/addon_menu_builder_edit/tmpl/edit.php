<?php
 /**
* @version      4.1.0 26.02.2013
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die;

jimport('joomla.html.pane');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');

$standart = $this->item->standart;
$jshopping = $this->item->jshopping;
$hidden = $this->item->hidden;
$linktype = $this->item->linktype;
$pagedisplay = $this->item->pagedisplay;
$metadata = $this->item->metadata;

$uri = JURI::getInstance();
$liveurlhost = $uri->toString( array("scheme",'host', 'port'));
$url = "index.php?option=com_jshopping&controller=addon_menu_builder&task=getAjaxParams&ajax=1";
?>
<script type="text/javascript">
	var jshop_prevAjaxQuery = null;
	
	function updateParamsFields(value) {
		jQuery('#jshop_menu_params').html('');
		var data = {};
		data['jshop_id'] = value;
		if (jshop_prevAjaxQuery){
			jshop_prevAjaxQuery.abort();
		}
		jshop_prevAjaxQuery = jQuery.ajax({
			url: '<?php echo $url;?>',
			dataType: 'json',
			data: data,
			type: 'post',    
			success: function (json) {
				jQuery('#jshop_menu_params').html(json.html);
			}
		});
	}
	
	Joomla.submitbutton = function(task, type) {
		if (task == 'cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.id('item-form'));
		}
	}
</script>

<?php echo helperMB::htmlAdminSidebar('start'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_jshopping&controller=addon_menu_builder'); ?>" method="post" name="adminForm" id="item-form" class="form-validate form-horizontal" autocomplete="off">
	<ul class="nav nav-tabs">    
		<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_MENUS_ITEM_DETAILS');?></a></li>
		<li><a href="#settings" data-toggle="tab"><?php echo JText::_('COM_MENUS_REQUEST_FIELDSET_LABEL');?></a></li>
		<li><a href="#linktype" data-toggle="tab"><?php echo JText::_('COM_MENUS_LINKTYPE_OPTIONS_LABEL');?></a></li>
		<li><a href="#pagedisplay" data-toggle="tab"><?php echo JText::_('COM_MENUS_PAGE_OPTIONS_LABEL');?></a></li>
		<li><a href="#metadata" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_METADATA_OPTIONS');?></a></li>
		<?php if (JFactory::getApplication()->item_associations) { ?>
		<li><a href="#associations" data-toggle="tab"><?php echo JText::_('COM_MENUS_ITEM_ASSOCIATIONS_FIELDSET_LABEL');?></a></li>
		<?php } ?>
	</ul>
	
	<div id="editdata-document" class="tab-content">
		<div id="general" class="tab-pane active">
			<div class="col100">
				<?php foreach ($standart as $k=>$v) { ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $v->name;?>
					</div>
					<div class="controls">
						<?php echo $v->value; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>

		<div id="settings" class="tab-pane">
			<div class="col100">
				<div class="control-group">
					<div class="control-label">
					<?php echo $jshopping->jshop_menutype->name;?>
					</div>
					<div class="controls">
					<?php echo $jshopping->jshop_menutype->value; ?>
					</div>
				</div>
				<div id="jshop_menu_params">
				<?php foreach ($jshopping as $k=>$v) {
					if ($k != 'jshop_menutype') { ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $v->name;?>
					</div>
					<div class="controls">
						<?php echo $v->value;?>
					</div>
				</div>
				<?php } 
				} ?>
				</div>
			</div>
		</div>
		
		<div id="linktype" class="tab-pane">
			<div class="col100">
				<?php foreach ($linktype as $k=>$v) { ?>
				<div class="control-group">
					<div class="control-label">
					<?php echo $v->name;?>
					</div>
					<div class="controls">
					<?php echo $v->value; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		
		<div id="pagedisplay" class="tab-pane">
			<div class="col100">
				<?php foreach ($pagedisplay as $k=>$v) { ?>
				<div class="control-group">
					<div class="control-label">
					<?php echo $v->name;?>
					</div>
					<div class="controls">
					<?php echo $v->value; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		
		<div id="metadata" class="tab-pane">
			<div class="col100">
				<?php foreach ($metadata as $k=>$v) { ?>
				<div class="control-group">
					<div class="control-label">
					<?php echo $v->name;?>
					</div>
					<div class="controls">
					<?php echo $v->value; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		
		<?php if (JFactory::getApplication()->item_associations) {
			$association = $this->item->association; ?>
		<div id="associations" class="tab-pane">
			<div class="col100">
				<p class="tip"><?php echo JText::_('COM_MENUS_ITEM_ASSOCIATIONS_FIELDSET_DESC');?></p><div class="clr"></div>
				<?php foreach ($association as $k=>$v) { ?>
				<div class="control-group">
					<div class="control-label">
					<?php echo $v->name;?>
					</div>
					<div class="controls">
					<?php echo $v->value; ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php foreach ($hidden as $k=>$v) { ?>
		<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>" />
	<?php } ?>
	<input type = "hidden" name = "task" value = "<?php echo JRequest::getVar('task')?>" />
	<input type = "hidden" name = "edit" value = "<?php echo $this->edit;?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php echo helperMB::htmlAdminSidebar('end'); ?>