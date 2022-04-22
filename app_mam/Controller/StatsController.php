<?php
App::uses('Sanitize', 'Utility');
class StatsController extends AppController {
    public $name = 'Stats';
	
    public function index() {      
		
		App::uses('MonthlyTransaction', 'Model');
		$this->MonthlyTransaction = new MonthlyTransaction;
		
		//this month stats
		$params = array(
						'fields' => array('SUM(MonthlyTransaction.total_amount) as price', 'transaction_type', 'business_type'),
						'group' => array('MonthlyTransaction.transaction_type', 'MonthlyTransaction.business_type'),
						'conditions'=> array('MonthlyTransaction.company_id'=>$this->Session->read('Company.id'), 'MonthlyTransaction.mm'=>date('m')) 
					);
		$thisMonthStatInfo = $this->MonthlyTransaction->find('all', $params);			
		
		// all stats
		$params = array(
						'fields' => array('SUM(MonthlyTransaction.total_amount) as price', 'transaction_type', 'business_type'),
						'group' => array('MonthlyTransaction.transaction_type', 'MonthlyTransaction.business_type'),
						'conditions'=> array('MonthlyTransaction.company_id'=>$this->Session->read('Company.id')) 
					);
		$statInfo = $this->MonthlyTransaction->find('all', $params);	
		
		$this->set(compact('thisMonthStatInfo', 'statInfo'));
		
		exit;
		
		if(!empty($cashInfo)) {
			$debit = 0;
			$credit = 0;
			foreach($cashInfo as $row) {
				if($row['MonthlyTransaction']['transaction_type'] == 'debit') {
					$debit+=$row[0]['price'];
				}
				if($row['MonthlyTransaction']['transaction_type'] == 'credit') {
					$credit+=$row[0]['price'];
				}				
			}			
		}
		$total = $credit-$debit;
		
		$transactionInfo['credit'] = $credit;
		$transactionInfo['debit'] = $debit;
		$transactionInfo['total'] = $total;
		
		$this->set('cash', $cash);
		$this->set('transactionInfo', $transactionInfo);
    }	
}
?>
