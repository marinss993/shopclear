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
    $changes = $this->changes;
    $date    = $this->date;
    $version = $this->version;
?>
<li id="version_history_<?php echo $version; ?>">
    <div class="version_and_date">
        <div class="version">
            <?php echo $version; ?>
        </div>
        <div class="separator">
            |
        </div>
        <div class="date">
            <?php echo $date; ?>
        </div>
    </div>
    <ol class="changes">
        <?php echo $changes; ?>
    </ol>
</li>