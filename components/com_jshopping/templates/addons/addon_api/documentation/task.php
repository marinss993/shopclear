<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $addon        = $this->addon;
    $descr        = $this->descr;
    $params       = $this->params;
    $params_short = $this->params_short;
    $task         = $this->task;
    $type         = $this->type;
?>
<tr>
    <td class="variable"><?php echo $task; ?></td>
    <td>
        <?php echo $params_short; ?>
    </td>
    <td class="datatype">
        <?php echo $type; ?>
    </td>
    <td class="details-btn"></td>
</tr>
<tr class="details">
    <td colspan="4">
        <section>
            <header>Name</header>
            <p class="variable">
                <?php echo $task; ?>
            </p>
        </section>
        <section>
            <header>Description</header>
            <p>
                <?php echo $descr; ?>
            </p>
        </section>
            <?php echo $params; ?>
        <section>
            <header>Return type</header>
            <p class="datatype">
                <?php echo $type; ?>
            </p>
        </section>
    </td>
</tr>