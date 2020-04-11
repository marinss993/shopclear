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
?>
<li>
    <a href="#section_<?php echo $section; ?>" title="<?php echo $section; ?>">
        <?php echo $section; ?>
    </a>
</li>