<?php
    /*
    * @version      1.0.0 01.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    /* @var $addon AddonApi */
    $addon      = $this->addon;
    $log        = $this->log;
    $link_clear = $this->link_clear;
    $addon->addCss();
    if (!$log) {
        return;
    }
?>
<div class="jshop_addon_api">
    <div class="log">
        <div class="buttons">
            <?php if ($link_clear) { ?>
                <a href="<?php echo $link_clear; ?>" class="btn" onclick="if (!confirm('<?php echo _JSHOP_ADDON_API_SURE; ?>')) return false; ">
                    <?php echo JText::_('JCLEAR'); ?>
                </a>
            <?php } ?>
        </div>
        <ul>
            <?php foreach ($log as $datetime => $entry) { ?>
                <li class="entry<?php if (isset($entry['type'])) echo ' ' . $entry['type']; ?>">
                    <div class="control-group datetime">
                        <div class="control-label">
                            <?php echo formatdate($datetime, true); ?>
                        </div>
                    </div>
                    <?php
                        $pad_length = max(array_map('strlen', array_keys($entry)));
                        foreach ($entry as $key => $val) {
                            if ($key == 'type') {
                                continue;
                            }
                            ?>
                                <div class="control-group <?php echo $key; ?>">
                                    <div class="control-label">
                                        <?php echo ucfirst($key); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $val; ?>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>