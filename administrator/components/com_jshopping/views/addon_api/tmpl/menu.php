<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;
?>
<div class="jssubmenu" style="margin-top: -1em;">
    <ul id="submenu">
        <?php foreach ($displayData['items'] as $item) { ?>
            <li>
                <a href="<?php echo $item->link; ?>"<?php if ($item->active) echo ' class="active"'; ?>><?php echo $item->name; ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="clr"></div>
</div>