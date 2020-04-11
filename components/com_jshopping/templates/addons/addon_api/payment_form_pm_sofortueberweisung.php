<?php
    /*
    * @version      0.2.2 29.11.2017
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $inputs = $this->inputs;
?>
<form id="paymentform" action="https://www.sofortueberweisung.de/payment/start" name="paymentform" method="post">
    <?php
        foreach ($inputs as $name => $value) {
            if ($name !== 'project_password' && $value !== '') {
                echo '<input type="hidden" name="' . $name . '" value="' . $value . '">' . "\n\t\t\t\t";
            }
        }
    ?>
</form>
<script>
    document.getElementById('paymentform').submit();
</script>