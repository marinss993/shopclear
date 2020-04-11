<?php
/**
* @version      4.0.1 20.12.2012
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
error_reporting(error_reporting() & ~E_NOTICE);

if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
    JError::raiseError(500,"Please install component \"joomshopping\"");
}
    
$db = JFactory::getDBO();

require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');        
JSFactory::loadCssFiles();
JSFactory::loadLanguageFile();
$jshopConfig = JSFactory::getConfig();
$lang = JSFactory::getLang();

$count = $params->get('count');
 
$query = "SELECT rev.user_name, rev.time, rev.review, rev.mark, pr.`".$lang->get('name')."` as pr_name, cat.`".$lang->get('name')."` as cat_name, pr.product_id as pr_id, pr_cat.category_id as cat_id
			FROM `#__jshopping_products` AS pr
			INNER JOIN `#__jshopping_products_reviews` AS rev
			INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = pr.product_id
			LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
			WHERE pr.product_id = rev.product_id and rev.publish = 1 and pr.product_publish = '1' AND cat.category_publish='1'
			GROUP BY rev.review_id
			ORDER BY rev.review_id desc LIMIT ".$count;
$db->setQuery($query);
$review = $db->loadObjectList();

$max_mark = $jshopConfig->max_mark;
if ($max_mark % 2) $max_mark -= 1;
$wid = 8 * $max_mark;

foreach ($review as $key => $value){
	?>
	<div class="review_item"> 
		<?php
			$cat_id = $value->cat_id;
			$pr_id = $value->pr_id;
			$url = SEFLink("index.php?option=com_jshopping&controller=product&task=view&category_id=$cat_id&product_id=$pr_id",1);
			echo $value->cat_name." >> <a href='$url'>".$value->pr_name."</a><br>";
            			
			echo "<b>".$value->user_name."</b>, ".date("d.m.Y", strtotime($value->time))."<br>";
		
			echo $value->review."<br>";
			
			if ($value->mark > 0){
				$mark = $value->mark;
				$wid_a = $wid * $mark / $max_mark;
				echo "<div class='review_mark'><div class='stars_no_active' style='width:".$wid."px'><div class='stars_active' style='width:".$wid_a."px'></div></div></div>";
			}
		?>
	 </div> 
<?php
}
?>