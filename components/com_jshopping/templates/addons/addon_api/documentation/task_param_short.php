<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $addon   = $this->addon;
    $default = $this->default;
    $param   = $this->param;
    $type    = $this->type;
?>
<tr>
    <td class="variable">
        <?php echo $param; ?>
    </td>
    <td class="datatype">
        <?php echo $type; ?>
    </td>
    <td>
        <?php echo $default; ?>
    </td>
</tr>