<?php
defined('_JEXEC') or die();

class plgJshoppingOrderFinished_order_number extends JPlugin {

    public function __construct(&$subject, $config){
        $this->loadLanguage();
        parent::__construct($subject, $config);
    }
    
    public function onBeforeCreateOrder(&$order){
        $this->setInvoiceNumber($order);
    }
    
    public function onStep7OrderCreated(&$order, &$res, &$checkout, &$pmconfigs){
        $this->setInvoiceNumber($order);
    }
    
    public function onBeforeDisplayOrdersView(&$view){
        JSFactory::loadExtLanguageFile('addon_finished_order_number');
        foreach($view->orders as $k=>$v){
            if ($v->finished_number_used){
                $view->orders[$k]->order_number .= '</span> /
                 <b>'._JSF_INVOIVE.':</b> <span>'.$v->invoice_number;
            }   
        }
    }
    
    public function onBeforeDisplayOrderView(&$view){
        JSFactory::loadExtLanguageFile('addon_finished_order_number');
        $order = $view->order;
        if ($order->finished_number_used){
            $view->_tmp_html_start.= '<div class = "order_number" style="float:left">
                <div class = "span12">
                    <b>'._JSF_INVOIVE.':</b> 
                    <span>'.$order->invoice_number.'</span>
                </div>
            </div>';
        }
    }
    
    public function onBeforeCreateTemplateOrderMail(&$view){
        JSFactory::loadExtLanguageFile('addon_finished_order_number');
        $order = $view->order;
        if ($order->finished_number_used && !isset($order->invoice_nr_print)){
            $order->invoice_nr_print = 1;
            $view->info_shop .= '<table width="100%">
            <tr>
                <td width="50%"><b>'._JSF_INVOIVE.'</b>:</td>
                <td>'.$order->invoice_number.'</td>
            </tr>
            </table>';
        }
    }
    
    public function onBeforeCreatePdfOrder(&$order){
        $order->orig_order_number = $order->order_number;
        if ($order->invoice_number){
            $order->order_number = $order->invoice_number;
        }
    }
    
    public function onBeforeCreatePdfOrderAfterEndTotal(&$order, &$pdf, &$y){
        if ($order->invoice_number){
            $pdf->SetFont('freesans','',7);
            $pdf->SetXY(20, $y+18);
            $pdf->MultiCell(170, 4, _JSHOP_ORDER_NUMBER.": ".$order->orig_order_number, '0','L');
            $y = $y + 5;
        }
    }
        
    public function onAfterSaveOrder(&$_order){
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($_order->order_id);
        $this->setInvoiceNumber($order);
        $order->store();
    }

    public function onAfterChangeOrderStatus(&$order_id){
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $this->setInvoiceNumber($order);
        $order->store();
    }

    public function onAfterChangeOrderStatusAdmin(&$order_id){
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
        $this->setInvoiceNumber($order);
        $order->store();
    }
    
    protected function setInvoiceNumber($order){
        $db = JFactory::getDBO();
        $jshopConfig = JSFactory::getConfig();
       
        $statusCreateInvoice = $this->getStatusCreateInvoice();
        if (
            $order->order_created==1 &&
            $order->finished_number_used==0 &&
            (empty($statusCreateInvoice) || in_array($order->order_status, $statusCreateInvoice))
        ){
            $db->lockTable('#__jshopping_config');            
            $prefix = $jshopConfig->finished_order_prefix;
            $number = $this->getNextInvoiceNumber();            
            $order->invoice_number = $prefix.$order->formatOrderNumber($number);
            $this->updateNextInvoiceNumber();
            $db->unlockTables();
            $order->finished_number_used = 1;
        }
    }
    
    private function getNextInvoiceNumber(){
        $db = JFactory::getDBO();
        $query = "select next_finished_order_number from `#__jshopping_config`";
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    private function updateNextInvoiceNumber(){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);        
        $query->update($db->quoteName('#__jshopping_config'))
                ->set('`next_finished_order_number`=`next_finished_order_number`+1');
        $db->setQuery($query);
        $db->query();
    }

    private function getStatusCreateInvoice(){
        $addon = JTable::getInstance("addon", "jshop");
        $addon->loadAlias('add_finished_order_number');
        $params = $addon->getParams();
        if ($params['order_status_ids']==''){
            return array();
        }else{
            $statusid = explode(',', $params['order_status_ids']);
            foreach($statusid as $k=>$v){
                $statusid[$k] = intval($v);
            }
            return $statusid;
        }
    }

}