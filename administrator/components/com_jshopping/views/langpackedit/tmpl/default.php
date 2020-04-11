<?php
displaySubmenuOptions();
$langs = $this->langs;
$translate = $this->translate;
$constants = $this->constants;
$list_files = $this->list_files;
$i=0;
?>
<form action = "index.php?option=com_jshopping&controller=langpackedit&task=save"method="post" name="adminForm" id="adminForm">        
     
      <fieldset class="adminform" >
          <table class="admintable">
              <tr>
                <td class="key">
                    <?php echo _JSHOP_LANG_PACK_SELECT; ?>
                </td>
                <td>
                    <?php echo $list_files; ?>
                </td>                 
              </tr>   
          </table>            
     <table>
         <thead>
         <th><?php echo  _JSHOP_LABEL_CONSTANT?></th>
            <?php foreach ($langs as $_lang) {?>
         <th><?php echo  $_lang?></th>
         <?php }?>
         </thead>
         
            <tr class="row<?php echo $i % 2;?>">
                <td>
                    <textarea name="constants" rows=20 style="width:400px;"><?php
                    foreach ($constants as $k=>$constant) {
                        echo $constant."\n";
                    }
                    ?></textarea>
                    
                </td>
            <?php foreach ($langs as $_lang) {?>
            <td>
                
				<textarea name="lang[<?php echo $_lang?>]" rows=20 style="width:400px;"><?php 
                foreach ($constants as $k=>$constant) {
                    echo htmlspecialchars(str_replace(array("\\\\","\'", "\n", "\r"), array("\\","'", '\n', ''), $translate[$_lang][$constant]))."\n";
                }
                ?></textarea>
				
             </td> 
            <?php }?>                
         </tr>         
         </table>
        
     </fieldset>
     <input type = "hidden" name = "hidemainmenu" value = "0" />
     <input type="hidden" name="task" value="" />
     <input type = "hidden" name = "boxchecked" value = "0" /> 
     <input type = "hidden" name = "fileheader" value = "<?php echo $this->header;?>" />        
     <input type = "hidden" name = "type" value = "<?php echo $this->type;?>" />
</form>