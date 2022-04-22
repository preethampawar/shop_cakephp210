<?php
App::uses('Sanitize', 'Utility');
class LedgerController extends AppController {
    public $name = 'Ledger';
	
    public function index() {      
		
		$thisYear = date('Y');
		$startDate = $thisYear.'-01-01';
		$endDate = $thisYear.'-12-31';
		
		// get ledger account for purchases
		$purchases = $this->Ledger->find('all', array('order'=>'Ledger.date DESC', 'conditions'=>array('Ledger.company_id'=>$this->Session->read('Company.id'), 'Ledger.business_type'=>'purchase', 'Ledger.date <='=>$endDate, 'Ledger.date >='=>$startDate), 'recursive'=>'-1', 'fields'=>array('Ledger.particular', 'Ledger.amount', 'Ledger.date', 'Ledger.category_name', 'Ledger.pending_payment', 'Ledger.pending_amount')));
		
		// get ledger account for sales
		$sales = $this->Ledger->find('all', array('order'=>'Ledger.date DESC', 'conditions'=>array('Ledger.company_id'=>$this->Session->read('Company.id'), 'Ledger.business_type'=>'sale', 'Ledger.date <='=>$endDate, 'Ledger.date >='=>$startDate), 'recursive'=>'-1', 'fields'=>array('Ledger.particular', 'Ledger.amount', 'Ledger.date', 'Ledger.category_name', 'Ledger.pending_payment', 'Ledger.pending_amount')));
		
		// get ledger account for cash
		$cash = $this->Ledger->find('all', array('order'=>'Ledger.date DESC', 'conditions'=>array('Ledger.company_id'=>$this->Session->read('Company.id'), 'Ledger.business_type'=>'cash', 'Ledger.date <='=>$endDate, 'Ledger.date >='=>$startDate), 'recursive'=>'-1', 'fields'=>array('Ledger.particular', 'Ledger.amount', 'Ledger.date', 'Ledger.category_name', 'Ledger.transaction_type', 'Ledger.pending_payment', 'Ledger.pending_amount')));
		
		$this->set(compact('purchases', 'sales', 'cash'));		
		
    }
	
    public function today() {      
		
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		
		// get ledger account for purchases
		$purchases = $this->Ledger->find('all', array('order'=>'Ledger.date DESC', 'conditions'=>array('Ledger.company_id'=>$this->Session->read('Company.id'), 'Ledger.business_type'=>'purchase', 'Ledger.date <='=>$endDate, 'Ledger.date >='=>$startDate), 'recursive'=>'-1', 'fields'=>array('Ledger.particular', 'Ledger.amount', 'Ledger.date', 'Ledger.category_name', 'Ledger.pending_payment', 'Ledger.pending_amount')));
		
		// get ledger account for sales
		$sales = $this->Ledger->find('all', array('order'=>'Ledger.date DESC', 'conditions'=>array('Ledger.company_id'=>$this->Session->read('Company.id'), 'Ledger.business_type'=>'sale', 'Ledger.date <='=>$endDate, 'Ledger.date >='=>$startDate), 'recursive'=>'-1', 'fields'=>array('Ledger.particular', 'Ledger.amount', 'Ledger.date', 'Ledger.category_name', 'Ledger.pending_payment', 'Ledger.pending_amount')));
		
		// get ledger account for cash
		$cash = $this->Ledger->find('all', array('order'=>'Ledger.date DESC', 'conditions'=>array('Ledger.company_id'=>$this->Session->read('Company.id'), 'Ledger.business_type'=>'cash', 'Ledger.date <='=>$endDate, 'Ledger.date >='=>$startDate), 'recursive'=>'-1', 'fields'=>array('Ledger.particular', 'Ledger.amount', 'Ledger.date', 'Ledger.category_name', 'Ledger.transaction_type', 'Ledger.pending_payment', 'Ledger.pending_amount')));
		
		$this->set(compact('purchases', 'sales', 'cash'));		
		
    }	
}
?>
