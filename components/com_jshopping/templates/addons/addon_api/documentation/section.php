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
    $section = $this->section;
    $tasks   = $this->tasks;
?>
<section id="section_<?php echo $section; ?>">
    <table>
        <tbody>
            <tr>
                <th id="section_<?php echo $section; ?>" colspan="4"><?php echo $section; ?></th>
            </tr>
            <?php echo $tasks; ?>
        </tbody>
    </table>
</section>