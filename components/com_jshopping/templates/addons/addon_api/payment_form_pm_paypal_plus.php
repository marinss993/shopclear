<?php
    /*
    * @version      0.2.0 27.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $js_links  = $this->js_links;
    $js_config = $this->js_config;
?>
<?php foreach ($js_links as $link) { ?>
    <script src="<?php echo $link; ?>"></script>
<?php } ?>
<script>
    var ppp = PAYPAL.apps.PPP({
        <?php
            foreach ($js_config as $k => $v) {
                echo $k . ': \'' . $v . '\',' . "\n\t\t";
            }
        ?>
    });
</script>
<div id="<?php echo $js_config['placeholder']; ?>"></div>