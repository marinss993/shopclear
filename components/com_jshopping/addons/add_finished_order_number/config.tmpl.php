<?php 
JSFactory::loadExtLanguageFile('addon_finished_order_number');
?>

<fieldset class = "adminform">
    <table class = "admintable">
    <tr>
        <td style="width:280px">
            <div>Order status ID</div>
            <div>(Example: 5,6,7)</div>
            (Works only with configuration:<br>
            <b>Send invoice manually: Yes</b>)
        </td>
        <td>
            <input type='text' name='params[order_status_ids]' value='<?php print $this->params['order_status_ids']?>'>
        </td>
    </tr>
    </table>
</fieldset>