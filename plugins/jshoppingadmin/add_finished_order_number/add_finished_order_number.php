<?php
defined('_JEXEC') or die();

class plgJshoppingAdminAdd_finished_order_number extends JPlugin {

    public function __construct(& $subject, $config){
        parent::__construct($subject, $config);        
    }
	
	public function onBeforeSaveConfig(&$post){
		if (isset($post['next_finished_order_number']) && $post['next_finished_order_number'] == ''){
			unset($post['next_finished_order_number']);
		}
	}

    public function onBeforeEditConfigCheckout(&$view){
        $jshopConfig = JSFactory::getConfig();
        JSFactory::loadExtLanguageFile('addon_finished_order_number');
        $view->etemplatevar .= "<tr>" .
                "<td class='key' style='width:280px!important;'>" . _JSHOP_FINISHED_ORDER_NUMBER . "</td>" .
                "<td><input type='text' name='next_finished_order_number' value=''/> (" . $jshopConfig->next_finished_order_number . ")</td>" .
                "</tr>";
        $view->etemplatevar .= "<tr>" .
                "<td class='key' style='width:280px!important;'>" . _JSHOP_FINISHED_ORDER_NUMBER_PREFIX . "</td>" .
                "<td><input type='text' name='finished_order_prefix' value='" . $jshopConfig->finished_order_prefix . "'/> </td>" .
                "</tr>";
    }
    
    public function onBeforeAdminFinishOrder($order){
        $jshopConfig = JSFactory::getConfig();
        $number = $jshopConfig->next_finished_order_number;
        $prefix = $jshopConfig->finished_order_prefix;
        if ($order->order_created==1 && $order->finished_number_used == 0){
            $order->invoice_number = $prefix.$order->formatOrderNumber($number);
            $this->updateNextInvoiceNumber();
            $order->finished_number_used = 1;
        }        
    }
    
    public function onBeforeSaveOrder(&$post){
        $order = JTable::getInstance('order', 'jshop');
        $jshopConfig = JSFactory::getConfig();
        $number = $jshopConfig->next_finished_order_number;
        $prefix = $jshopConfig->finished_order_prefix;
        if ($post['order_created']==1 && empty($post['order_id'])){
            $post['invoice_number'] = $prefix.$order->formatOrderNumber($number);
            $this->updateNextInvoiceNumber();
            $post['finished_number_used'] = 1;
        }
    }
    
    public function onBeforeShowOrderListView(&$view){
        JSFactory::loadExtLanguageFile('addon_finished_order_number');
        
        $view->_tmp_cols_1 .= '<th width="20">'._JSF_INVOIVE.'</th>';
        foreach($view->rows as $k=>$v){
            $view->rows[$k]->_tmp_cols_1 .= '<td>'.$v->invoice_number.'</td>';
        }
    }
    
    public function onBeforeShowOrder(&$view){
        JSFactory::loadExtLanguageFile('addon_finished_order_number');

        if ($view->order->finished_number_used){
            $view->tmp_html_info .= '<tr>';
            $view->tmp_html_info .= '<td><b>'._JSF_INVOIVE.'</b></td>';
            $view->tmp_html_info .= '<td>'.$view->order->invoice_number.'</td>';
            $view->tmp_html_info .= '</tr>';
        }
    }
    
    private function updateNextInvoiceNumber(){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);        
        $query->update($db->quoteName('#__jshopping_config'))
                ->set('`next_finished_order_number`=`next_finished_order_number`+1');
        $db->setQuery($query);
        $db->query();
    }

}