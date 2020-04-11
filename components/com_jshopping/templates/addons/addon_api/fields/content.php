<?php
    /*
    * @version      1.0.0 21.04.2017
    * @author       MAXXmarketing GmbH
    * @package      content.php
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    JFormHelper::loadFieldClass('text');

    class JFormFieldContent extends JFormFieldText {

        public $type = 'Content';

        protected function getInput() {
            ob_start();
            ?>
                <div<?php echo empty($this->class) ? '' : (' class="' . $this->class . '"'); ?>>
                    <?php echo $this->value; ?>
                </div>
            <?php
            return ob_get_clean();
        }

    }
