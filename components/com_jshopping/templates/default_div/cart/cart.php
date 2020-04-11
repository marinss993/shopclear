<?php defined( '_JEXEC' ) or die(); ?>
<?php $countprod = count($this->products);?>
<div class="jshop cart" id="comjshop">
<form action="<?php echo SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh')?>" method="post" name="updateCart">
<?php echo $this->_tmp_ext_html_cart_start?>
<?php if ($countprod>0){?>

<!-- Table markup-->

<table class="jshop cart">

<!-- Table header -->

	<thead>
		<tr>
			<th scope="col" class="width20" id="image"><?php echo _JSHOP_IMAGE ?></th>
			<th scope="col" class="width20" id="item"><?php echo _JSHOP_ITEM ?></th>  
    		<th scope="col" class="width15" id="singleprice"><?php echo _JSHOP_SINGLEPRICE ?></th>
    		<th scope="col" class="width15" id="number"><?php echo _JSHOP_NUMBER ?></th>
    		<th scope="col" class="width15" id="price_total"><?php echo _JSHOP_PRICE_TOTAL ?></th>
			<th scope="col" class="width10" id="remove"><?php echo _JSHOP_REMOVE ?></th>			
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
	<?php $i=1; foreach($this->products as $key_id=>$prod){ ?> 
		<tr class="jshop_prod_cart <?php if ($i%2==0) echo "even"; else echo "odd"?>">
			<td class="jshop_img_description_center" headers="image">
      			<a href="<?php echo $prod['href']?>">
        			<img src="<?php echo $this->image_product_path ?>/<?php if ($prod['thumb_image']) echo $prod['thumb_image']; else echo $this->no_image; ?>" alt="<?php echo htmlspecialchars($prod['product_name']);?>" class="jshop_img" />
      			</a>
			</td>
    		<td class="product_name" headers="item">
        		<a href="<?php echo $prod['href']?>"><?php echo $prod['product_name']?></a>
        		<?php if ($this->config->show_product_code_in_cart){?>
        		<span class="jshop_code_prod">(<?php echo $prod['ean']?>)</span>
        		<?php }?>
        		<?php if ($prod['manufacturer']!=''){?>
        		<div class="manufacturer"><?php echo _JSHOP_MANUFACTURER ?>: <span><?php echo $prod['manufacturer']?></span></div>
        		<?php }?>
        		<?php echo sprintAtributeInCart($prod['attributes_value']);?>
        		<?php echo sprintFreeAtributeInCart($prod['free_attributes_value']);?>
        		<?php echo sprintFreeExtraFiledsInCart($prod['extra_fields']);?>
        		<?php echo $prod['_ext_attribute_html']?>
    		</td>
    		<td class="price" headers="singleprice">
        		<?php echo formatprice($prod['price'])?>
        		<?php echo $prod['_ext_price_html']?>
        		<?php if ($this->config->show_tax_product_in_cart && $prod['tax']>0){?>
            	<span class="taxinfo"><?php echo productTaxInfo($prod['tax']);?></span>
        		<?php }?>
				<?php if ($this->config->cart_basic_price_show && $prod['basicprice']>0){?>
					<div class="basic_price"><?php print _JSHOP_BASIC_PRICE?>: <span><?php print sprintBasicPrice($prod);?></span></div>
				<?php }?>
    		</td>
    		<td class="qty" headers="number">
      			<input type = "text" name = "quantity[<?php echo $key_id ?>]" value = "<?php echo $prod['quantity'] ?>" class = "inputbox" style = "width: 25px" />
      			<?php echo $prod['_qty_unit'];?>
				<span class = "cart_reload"><img style="cursor:pointer" src="<?php print $this->image_path ?>/images/reload.png" title="<?php echo _JSHOP_UPDATE_CART ?>" alt = "<?php echo _JSHOP_UPDATE_CART ?>" onclick="document.updateCart.submit();" /></span>
    		</td>
    		<td class="price_summ" headers="price_total">
        		<?php echo formatprice($prod['price']*$prod['quantity']); ?>
        		<?php echo $prod['_ext_price_total_html']?>
        		<?php if ($this->config->show_tax_product_in_cart && $prod['tax']>0){?>
        		<span class="taxinfo"><?php echo productTaxInfo($prod['tax']);?></span>
        		<?php }?>
    		</td>
    		<td class="delete" headers="remove">
				<a href="<?php echo $prod['href_delete']?>" onclick="return confirm('<?php echo _JSHOP_CONFIRM_REMOVE ?>')"><img src = "<?php echo $this->image_path ?>images/remove.png" alt = "<?php echo _JSHOP_DELETE ?>" title = "<?php echo _JSHOP_DELETE ?>" /></a>
    		</td>			
		</tr>
	<?php $i++; } ?>
	</tbody>
</table>

  <?php if ($this->config->show_weight_order){?>  
	<div class="weightorder">
        <?php echo _JSHOP_WEIGHT_PRODUCTS ?>: <span><?php echo formatweight($this->weight);?></span>
    </div>
  <?php }?>
  
  <?php if ($this->config->summ_null_shipping>0){?>
    <div class="shippingfree">
        <?php echo sprintf(_JSHOP_FROM_PRICE_SHIPPING_FREE, formatprice($this->config->summ_null_shipping, null, 1));?>
    </div>
  <?php } ?> 
  
  <div class="cartdescr"><?php print $this->cartdescr?></div>
<table class="jshop jshop_subtotal">
  <?php if (!$this->hide_subtotal){?>
  <tr>
    <td class="name">
      <?php echo _JSHOP_SUBTOTAL ?>
    </td>
    <td class="value">
      <?php echo formatprice($this->summ);?><?php echo $this->_tmp_ext_subtotal?>
    </td>
  </tr>
  <?php } ?>
  <?php print $this->_tmp_html_after_subtotal?>
  <?php if ($this->discount > 0){ ?>
  <tr>
    <td class="name">
      <?php echo _JSHOP_RABATT_VALUE ?><?php print $this->_tmp_ext_discount_text?>
    </td>
    <td class="value">
      <?php echo formatprice(-$this->discount);?><?php echo $this->_tmp_ext_discount?>
    </td>
  </tr>
  <?php } ?>
  <?php if (!$this->config->hide_tax){?>
  <?php foreach($this->tax_list as $percent=>$value){ ?>
  <tr>
    <td class = "name">
      <?php echo displayTotalCartTaxName();?>
      <?php if ($this->show_percent_tax) echo formattax($percent)."%"?>
    </td>
    <td class = "value">
      <?php echo formatprice($value);?><?php echo $this->_tmp_ext_tax[$percent]?>
    </td>
  </tr>
  <?php } ?>
  <?php } ?>
  <tr class="total">
    <td class = "name">
      <?php echo _JSHOP_PRICE_TOTAL ?>
    </td>
    <td class = "value">
      <?php echo formatprice($this->fullsumm)?><?php echo $this->_tmp_ext_total?>
    </td>
  </tr>
  <?php print $this->_tmp_html_after_total?>
  <?php if ($this->config->show_plus_shipping_in_product){?>  
  <tr>
    <td colspan="2" align="right">    
        <span class="plusshippinginfo"><?php echo sprintf(_JSHOP_PLUS_SHIPPING, $this->shippinginfo);?></span>  
    </td>
  </tr>
  <?php }?>
  <?php if ($this->free_discount > 0){?>  
  <tr>
    <td colspan="2" align="right">    
        <span class="free_discount"><?php echo _JSHOP_FREE_DISCOUNT ?>: <?php echo formatprice($this->free_discount); ?></span>  
    </td>
  </tr>
  <?php }?>  
</table>
<?php }else{?>
<div class="cart_empty_text"><?php echo _JSHOP_CART_EMPTY ?></div>
<?php }?>

<?php print $this->_tmp_html_before_buttons?>
<div id="checkout"> 
    <div class="width50 td_1">
       <a href="<?php echo $this->href_shop ?>">
         <img src="<?php echo $this->image_path ?>/images/arrow_left.gif" alt="<?php echo _JSHOP_BACK_TO_SHOP ?>" />
         <?php echo _JSHOP_BACK_TO_SHOP ?>
       </a>
    </div>
    <div class="width50 td_2">
    <?php if ($countprod>0){?>
       <a href="<?php echo $this->href_checkout ?>">
         <?php echo _JSHOP_CHECKOUT ?>
         <img src="<?php echo $this->image_path ?>/images/arrow_right.gif" alt="<?php echo _JSHOP_CHECKOUT?>" />
       </a>
    <?php }?>
    </div>
</div>
<?php print $this->_tmp_html_after_buttons?>
</form>

<?php echo $this->_tmp_ext_html_before_discount?>
<?php if ($this->use_rabatt && $countprod>0){ ?>
<form name="rabatt" method="post" action="<?php echo SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave')?>">
<div class="jshop rabatt">
	<?php echo _JSHOP_RABATT ?>
	<input type="text" class="inputbox" name="rabatt" value="" />
	<input type="submit" class="button rabatt" value="<?php echo _JSHOP_RABATT_ACTIVE?>" />
</div>
</form>
<?php }?>
</div> 