<?php defined('_JEXEC') or die(); ?>
<?php $order=$this->order;?>
<div class="jshop">
	<?php if ($this->config->order_send_pdf_client){?>
	<div class="downlod_order_invoice">
    	<a target="_blank" href="<?php echo $this->config->pdf_orders_live_path."/".$order->pdf_file;?>"><?php echo _JSHOP_DOWNLOAD_INVOICE ?></a>
	</div>
	<?php } ?>


<div id="order_summary">
	<div>
		<span class="strong"><?php echo _JSHOP_ORDER_NUMBER ?>:</span> <?php echo $order->order_number ?>
    </div>
	<div>
		<span class="strong"><?php echo _JSHOP_ORDER_STATUS ?>:</span> <?php echo $order->status_name ?>
	</div>
	<div>
		<span class="strong"><?php echo _JSHOP_ORDER_DATE?>:</span> <?php echo formatdate($order->order_date, 0) ?>
	</div>
	<div>
		<span class="strong"><?php echo _JSHOP_PRICE_TOTAL?>:</span> <?php echo formatprice($order->order_total, $order->currency_code); ?>
	</div>
</div>



<table class="width100" id="order_summary_user_data">
	<tr>
		<td class="width50" id="jshop_user_data">
		
		
			<table class="jshop user_data">
				<thead>
					<tr>
          				<th colspan=2 scope="colgroup">
							<span class="strong"><?php echo _JSHOP_EMAIL_BILL_TO ?></span>
						</th>
        			</tr>
				</thead>
				<tbody>
	        		<?php if ($this->config_fields['firma_name']['display']){?>
    	    		<tr>
        	  			<td class="name"><?php echo _JSHOP_FIRMA_NAME?>:</td>
          				<td class="value"><?php echo $this->order->firma_name?></td>
        			</tr>
        			<?php } ?>
        		
					<?php if ($this->config_fields['f_name']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_FULL_NAME?>:</td>
          				<td class="value"><?php echo $this->order->f_name?> <?php echo $this->order->l_name?> <?php print $this->order->m_name?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['client_type']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_CLIENT_TYPE?>:</td>
    	      			<td class="value"><?php echo $this->order->client_type_name;?></td>
        			</tr>
        			<?php } ?>
        			<?php if ($this->config_fields['firma_code']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_FIRMA_CODE?>:</td>
          				<td class="value"><?php echo $this->order->firma_code?></td>
        			</tr>
        			<?php } ?>
					<?php if ($this->config_fields['tax_number']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_VAT_NUMBER?>:</td>
          				<td class="value"><?php echo $this->order->tax_number?></td>
        			</tr>
        			<?php } ?>
	        		<?php if ($this->config_fields['birthday']['display']){?>
					<tr>
						<td><?php print _JSHOP_BIRTHDAY?>:</td>
						<td><?php print $this->order->birthday?></td>
					</tr>
					<?php } ?>
					<?php if ($this->config_fields['home']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_HOME?>:</td>
          				<td class="value"><?php echo $this->order->home?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['apartment']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_APARTMENT?>:</td>
          				<td class="value"><?php echo $this->order->apartment?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['street']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_STREET_NR?>:</td>
          				<td class="value"><?php echo $this->order->street?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['city']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_CITY?>:</td>
          				<td class="value"><?php echo $this->order->city?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['state']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_STATE?>:</td>
          				<td class="value"><?php echo $this->order->state?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['zip']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_ZIP?>:</td>
          				<td class="value"><?php echo $this->order->zip?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['country']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_COUNTRY?>:</td>
          				<td class="value"><?php echo $this->order->country?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['phone']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_TELEFON?>:</td>
          				<td class="value"><?php echo $this->order->phone?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['mobil_phone']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_MOBIL_PHONE?>:</td>
          				<td class="value"><?php echo $this->order->mobil_phone?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['fax']['display']){?>
        			<tr>
	        	  		<td class="name"><?php echo _JSHOP_FAX?>:</td>
          				<td class="value"><?php echo $this->order->fax?></td>
        			</tr>
        			<?php } ?>
	        		
					<?php if ($this->config_fields['email']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EMAIL?>:</td>
          				<td class="value"><?php echo $this->order->email?></td>
        			</tr>
        			<?php } ?>
	        
        			<?php if ($this->config_fields['ext_field_1']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EXT_FIELD_1?>:</td>
          				<td class="value"><?php echo $this->order->ext_field_1?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['ext_field_2']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EXT_FIELD_2?>:</td>
          				<td class="value"><?php echo $this->order->ext_field_2?></td>
        			</tr>
        			<?php } ?>
	        		
					<?php if ($this->config_fields['ext_field_3']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EXT_FIELD_3?>:</td>
          				<td class="value"><?php echo $this->order->ext_field_3?></td>
        			</tr>
        			<?php } ?>  
				</tbody>                      
        	</table>
		
		</td>

		<td class="width50" id="jshop_delivery_address">

    	<?php if ($this->count_filed_delivery >0) {?>
        
			<table class="jshop delivery_data">
        		<thead>
					<tr>
        				<td colspan=2 scope="colgroup"><span class="strong"><?php echo _JSHOP_EMAIL_SHIP_TO?></span></td>
        			</tr>
        		</thead>
				<tbody>
					<?php if ($this->config_fields['d_firma_name']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_FIRMA_NAME?>:</td>
            			<td class="value"><?php echo $this->order->d_firma_name?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['d_f_name']['display']){?>
					<tr>
						<td width = "40%"><?php print _JSHOP_FULL_NAME?> </td>
						<td width = "60%"><?php print $this->order->d_f_name?> <?php print $this->order->d_l_name?> <?php print $this->order->d_m_name?> <?php print $this->order->d_m_name?></td>
					</tr>
					<?php } ?>
					
					<?php if ($this->config_fields['d_birthday']['display']){?>
					<tr>
						<td><?php print _JSHOP_BIRTHDAY?>:</td>
						<td><?php print $this->order->d_birthday?></td>
					</tr>
					<?php } ?>
					
        			<?php if ($this->config_fields['d_home']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_HOME?>:</td>
          				<td class="value"><?php echo $this->order->d_home?></td>
        			</tr>
        			<?php } ?>
					
        			<?php if ($this->config_fields['d_apartment']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_APARTMENT?>:</td>
          				<td class="value"><?php echo $this->order->d_apartment?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_street']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_STREET_NR?>:</td>
            			<td class="value"><?php echo $this->order->d_street?><br></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_city']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_CITY?>:</td>
            			<td class="value"><?php echo $this->order->d_city?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_state']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_STATE?>:</td>
            			<td class="value"><?php echo $this->order->d_state?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_zip']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_ZIP?>:</td>
            			<td class="value"><?php echo $this->order->d_zip ?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_country']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_COUNTRY?>:</td>
            			<td class="value"><?php echo $this->order->d_country ?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_phone']['display']){?>
        			<tr>
	            		<td class="name"><?php echo _JSHOP_TELEFON?>:</td>
            			<td class="value"><?php echo $this->order->d_phone ?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_mobil_phone']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_MOBIL_PHONE?>:</td>
          				<td class="value"><?php echo $this->order->d_mobil_phone?></td>
        			</tr>	
        			<?php } ?>
	        	
					<?php if ($this->config_fields['d_fax']['display']){?>
        			<tr>
	        			<td class="name"><?php echo _JSHOP_FAX?>:</td>
        				<td class="value"><?php echo $this->order->d_fax ?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_email']['display']){?>
        			<tr>
	        			<td class="name"><?php echo _JSHOP_EMAIL?>:</td>
        				<td class="value"><?php echo $this->order->d_email ?></td>
        			</tr>
        			<?php } ?>                            
	        
					<?php if ($this->config_fields['d_ext_field_1']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EXT_FIELD_1?>:</td>
          				<td class="value"><?php echo $this->order->d_ext_field_1?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_ext_field_2']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EXT_FIELD_2?>:</td>
          				<td class="value"><?php echo $this->order->d_ext_field_2?></td>
        			</tr>
        			<?php } ?>
	        
					<?php if ($this->config_fields['d_ext_field_3']['display']){?>
        			<tr>
	          			<td class="name"><?php echo _JSHOP_EXT_FIELD_3?>:</td>
          				<td class="value"><?php echo $this->order->d_ext_field_3?></td>
        			</tr>
        			<?php } ?>
				</tbody>
      		</table>
    	<?php } ?>  
    	</td>
	</tr>
</table>

<!-- Table markup-->

<table class="jshop cart order">

<!-- Table header -->

	<thead>
		<tr>
			<th scope="col" id="item"><?php echo _JSHOP_ITEM; ?></th>  
    		<?php if ($this->config->show_product_code_in_order){?>
			<th scope="col" class="width15" id="ean"><?php echo _JSHOP_EAN_PRODUCT?></th>
    		<?php }?>			
    		<th scope="col" class="width15" id="singleprice"><?php echo _JSHOP_SINGLEPRICE?></th>
    		<th scope="col" class="width15" id="number"><?php echo _JSHOP_NUMBER?></th>
			<th scope="col" class="width15" id="price_total"><?php echo _JSHOP_PRICE_TOTAL?></th>			
		</tr>
 	</thead>

<!-- Table footer -->

	<tfoot>
		<tr>
			<td class="tfoot" colspan="6"></td>
		</tr>
	</tfoot>

<!-- Table body -->

	<tbody>
	<?php $i=1; $countprod=count($order->items);
	foreach($order->items as $key_id=>$prod){
		$files=unserialize($prod->files);
  	?> 
  		<tr class="jshop_prod_cart <?php if ($i%2==0) echo "even"; else echo "odd"?>">
    		<td class="product_name" headers="item">
	        	<div class="name"><?php echo $prod->product_name?></div>
    	    	<?php if ($prod->manufacturer!=''){?>
        		<div class="manufacturer"><?php echo _JSHOP_MANUFACTURER?>: <span><?php echo $prod->manufacturer?></span></div>
        		<?php }?>
        		<div>            
		            <?php echo sprintAtributeInOrder($prod->product_attributes).sprintFreeAtributeInOrder($prod->product_freeattributes);?>
    		        <?php echo $prod->_ext_attribute_html;?>
        		</div>
        		<?php if (count($files)){?>
    	        	<?php foreach($files as $file){?>
       	        	<div><?php echo $file->file_descr?> <a href="<?php echo JURI::root()?>index.php?option=com_jshopping&controller=product&task=getfile&oid=<?php echo $this->order->order_id?>&id=<?php echo $file->id?>&hash=<?php echo $this->order->file_hash;?>"><?php echo _JSHOP_DOWNLOAD?></a></div>
	           		<?php }?>
         		<?php }?>   
                        <?php print $prod->_ext_file_html;?>                        
    		</td>
    		<?php if ($this->config->show_product_code_in_order){?>
    		<td class="prod_ean" headers="ean">
	        	<?php echo $prod->product_ean?>
    		</td>
    		<?php } ?>
    		<td class="product_singleprice" headers="singleprice">
      		<?php echo formatprice($prod->product_item_price, $order->currency_code) ?>
      		<?php echo $prod->_ext_price_html?>
      		<?php if ($this->config->show_tax_product_in_cart && $prod->product_tax>0){?>
	        	<span class="taxinfo"><?php echo productTaxInfo($prod->product_tax, $order->display_price);?></span>
        	<?php }?>
			<?php if ($this->config->cart_basic_price_show && $prod->basicprice>0){?>
				<div class="basic_price"><?php print _JSHOP_BASIC_PRICE?>: <span><?php print sprintBasicPrice($prod);?></span></div>
			<?php }?>
    		</td>
    		<td class="qty" headers="number">
	      		<?php echo formatqty($prod->product_quantity);?><?php echo $prod->_qty_unit;?>
    		</td>
    		<td class="price" headers="price_total">
     		<?php echo formatprice($prod->product_item_price * $prod->product_quantity, $order->currency_code); ?>
      		<?php echo $prod->_ext_price_total_html?>
      		<?php if ($this->config->show_tax_product_in_cart && $prod->product_tax>0){?>
	        	<span class="taxinfo"><?php echo productTaxInfo($prod->product_tax, $order->display_price);?></span>
        	<?php }?>
    		</td>
		</tr>
  	<?php $i++; } ?>
	</tbody>  
</table>


<?php if ($this->config->show_weight_order){?>  
<div class="weightorder">
	<?php echo _JSHOP_WEIGHT_PRODUCTS?>: <span><?php echo formatweight($this->order->weight);?></span>
</div>
<?php }?>

  
<table class="jshop jshop_subtotal">
<?php if (!$this->hide_subtotal){?>
	<tr>    
    	<td class="name">
      		<?php echo _JSHOP_SUBTOTAL ?>
    	</td>
    	<td class="value">
      		<?php echo formatprice($order->order_subtotal, $order->currency_code);?><?php echo $this->_tmp_ext_subtotal?>
    	</td>
  	</tr>
  	<?php } ?>
  	<?php print $this->_tmp_html_after_subtotal?>
	<?php if ($order->order_discount > 0){ ?>
  	<tr>
    	<td class="name">
      		<?php echo _JSHOP_RABATT_VALUE?>
    	</td>
    	<td class="value">
      		<?php echo formatprice(-$order->order_discount, $order->currency_code);?><?php echo $this->_tmp_ext_discount?>
    	</td>
  	</tr>
  	<?php } ?>
  
  	<?php if (!$this->config->without_shipping || $order->order_shipping > 0){?>
  	<tr>
	    <td class="name">
    		<?php echo _JSHOP_SHIPPING_PRICE?>
    	</td>
    	<td class="value">
      	<?php echo formatprice($order->order_shipping, $order->currency_code);?><?php echo $this->_tmp_ext_shipping?>
    	</td>
  	</tr>
  	<?php } ?>
	<?php if (!$this->config->without_shipping && ($order->order_package>0 || $this->config->display_null_package_price)){?>
  	<tr>
    	<td class="name"><?php echo _JSHOP_PACKAGE_PRICE?></td>
    	<td class="value"><?php echo formatprice($order->order_package, $order->currency_code); ?><?php echo $this->_tmp_ext_shipping_package?></td>
  	</tr>
 	<?php } ?>  
  	<?php if ($this->order->order_payment > 0){?>
  	<tr>
	    <td class="name">
         	<?php echo $this->order->payment_name;?>
    	</td>
    	<td class="value">
      		<?php echo formatprice($this->order->order_payment, $order->currency_code);?><?php echo $this->_tmp_ext_payment?>
    	</td>
  	</tr>
  	<?php } ?>  
	
  	<?php if (!$this->config->hide_tax){ ?>
  	<?php foreach($order->order_tax_list as $percent=>$value){?>
  	<tr>
    	<td class="name">
      		<?php echo displayTotalCartTaxName($order->display_price);?>
      		<?php if ($this->show_percent_tax) echo formattax($percent)."%"?>
    	</td>
    	<td class="value">
      		<?php echo formatprice($value, $order->currency_code);?><?php echo $this->_tmp_ext_tax[$percent]?>
    	</td>
  	</tr>
  	<?php }?>
	<?php }?>
  	<tr>
    	<td class="name">
      		<?php echo $this->text_total; ?>
    	</td>
    	<td class="value">
      		<?php echo formatprice($order->order_total, $order->currency_code);?><?php echo $this->_tmp_ext_total?>
    	</td>
  	</tr>
	<?php print $this->_tmp_html_after_total?>
</table>


<?php if (!$this->config->without_shipping){?>
<div id="shipping_information">
	<div><span class="strong"><?php echo _JSHOP_SHIPPING_INFORMATION?></span></div>
	<div><?php echo nl2br($order->shipping_info);?></div>
        <div class="order_shipping_params">
            <?php print nl2br($order->shipping_params);?>
        </div>        
		<?php if ($order->delivery_time_name){?>
			<div class="delivery_time"><?php echo _JSHOP_DELIVERY_TIME.": ".$order->delivery_time_name?></div>
		<?php }?>
	    <?php if ($order->delivery_date_f){?>
    	    <div class="delivery_date"><?php echo _JSHOP_DELIVERY_DATE.": ".$order->delivery_date_f?></div>
    	<?php }?>
</div>
<?php }?>

<?php if (!$this->config->without_payment){?>
<div id="payment_information">
    <div><span class="strong"><?php echo _JSHOP_PAYMENT_INFORMATION?></span></div>
    <div><?php echo $order->payment_name;?></div>
    <div class="order_payment_params">
        <?php echo nl2br($order->payment_params);?>
        <?php echo $order->payment_description;?>
    </div>
</div>
<?php }?>

<?php if ($order->order_add_info){ ?>
<table class="jshop" id="order_comment">
	<tr>
  		<td class="width100">
    		<span class="strong"><?php echo _JSHOP_ORDER_COMMENT?></span><br />
    		<?php echo $order->order_add_info ?>
  		</td>
	</tr>
</table>
<?php } ?>

<table class="jshop" id="order_history">
	<tr>
  		<td class="width100">
    		<span class="strong"><?php echo _JSHOP_ORDER_HISTORY?></span><br />
    		<table class="order_history">
    			<?php foreach($order->history as $history){?>
       			<tr>
         			<td>
           				<?php  echo formatdate($history->status_date_added, 0); ?>
         			</td>
         			<td>
           				<?php echo $history->status_name ?>
         			</td>
         			<td>
           				<?php print nl2br($history->comments) ?>
         			</td>
       			</tr>
    			<?php } ?>
    		</table>
  		</td>
	</tr>
</table>

<?php if ($this->allow_cancel){?>
    <a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=user&task=cancelorder&order_id='.$order->order_id)?>"><?php echo _JSHOP_CANCEL_ORDER?></a>
<?php }?>
</div>