<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $addon = $this->addon;
    $code  = $this->code;
    $descr = $this->descr;
?>
<tr>
    <td>
        <?php echo $code; ?>
    </td>
    <td>
        <?php echo $descr; ?>
    </td>
</tr>