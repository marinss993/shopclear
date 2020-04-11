<?php
    /*
    * @version      1.0.1 23.02.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $addon  = $this->addon;
    $params = $this->params;
?>
<section>
    <header>Parameters</header>
    <table>
        <tbody>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Default value</th>
                <th>Description</th>
            </tr>
            <?php echo $params; ?>
        </tbody>
    </table>
</section>