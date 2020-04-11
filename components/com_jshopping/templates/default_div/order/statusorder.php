<?php defined('_JEXEC') or die(); ?>
<?php echo _JSHOP_HI?> <?php echo $this->order->f_name;?> <?php echo $this->order->l_name;?>,
<?php printf(_JSHOP_YOUR_ORDER_STATUS_CHANGE, $this->order->order_number);?>

<?php echo _JSHOP_NEW_STATUS_IS ?>: <?php echo $this->order_status?>
<?php if ($this->order_detail){?>
<?php echo _JSHOP_ORDER_DETAILS ?>: <?php echo $this->order_detail?>
<?php }?>
 
<?php echo $this->vendorinfo->company_name?> 
<?php echo $this->vendorinfo->adress?> 
<?php echo $this->vendorinfo->zip?> <?php echo $this->vendorinfo->city?> 
<?php echo $this->vendorinfo->country?> 
<?php echo _JSHOP_CONTACT_PHONE?>: <?php echo $this->vendorinfo->phone?> 
<?php echo _JSHOP_CONTACT_FAX?>: <?php echo $this->vendorinfo->fax?>


 <?php echo _JSHOP_HI?> <?php echo $this->order->f_name;?> <?php echo $this->order->l_name;?>,
 <?php printf(_JSHOP_YOUR_ORDER_STATUS_CHANGE, $this->order->order_number);?>
 


  
