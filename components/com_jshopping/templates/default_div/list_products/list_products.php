<?php defined('_JEXEC') or die(); ?>
<div class="jshop list_product" id="comjshop_list_product">
<?php foreach ($this->rows as $k=>$product){?>
<?php if ($k%$this->count_product_to_row==0) echo "<div class='list_product_row'>";?>
    <div class="width<?php echo round(100/$this->count_product_to_row, 0)?> block_product">
        <?php include(dirname(__FILE__)."/".$product->template_block_product);?>
    </div>
    <?php if ($k%$this->count_product_to_row==$this->count_product_to_row-1){?>
    </div>
    <div class="product_list_hr clear"></div>
    <?php }?>
<?php }?>
<?php if ($k%$this->count_product_to_row!=$this->count_product_to_row-1) echo "</div>";?>
</div>