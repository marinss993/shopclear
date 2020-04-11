<?php
/**
 * Module Check status Order by number. ModSon 2.0 (02.02.2017)
 * @package    Joomla
 * @subpackage JoomShopping
 * @author     Vadim Meling (Linfuby)
 * @authorSite https://linfuby.com/
 * @email      support@linfuby.com
 * @copyright  Copyright by Linfuby. All rights reserved.
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @var \Joomla\Registry\Registry $params
 * @var string $statusName
 */
defined('_JEXEC') or die;
?>
<div style="text-align:center" class="jshop">
    <form name="getStatusOrder" action="" method="post">
        <label for="modSonOrderId">
            <?php echo JText::_('MODSON_ENTER_ORDER_NUMBER'); ?>
        </label>
        <br/>
        <input type="text" id="modSonOrderId" name="modSonOrderId" class="input-small" value="">
        <br/>
        <input type="submit" value="<?php echo JText::_('MODSON_VERIFY'); ?>">
    </form>
    <div class="modSonStatus">
        <?php echo $statusName; ?>
    </div>
</div>