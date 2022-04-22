<?php
App::uses('Validation', 'Utility');
class BanksController extends AppController {

	var $name = 'Banks';
	
	function beforeFilter() { 
		parent::beforeFilter();
		$this->checkStoreInfo();
	}
		
	/**
	 * Function to show list of category products
	 */
	public function index() {		
		$hideSideBar = false;
		
		$conditions = array('Bank.store_id'=>$this->Session->read('Store.id'));					
		
		$this->paginate = array(
							'conditions' => $conditions,
							'order' => array('Bank.payment_date' => 'DESC', 'Bank.created' => 'DESC'),
							'limit' => 100,
							'recursive' => '-1'
							);
		$records = $this->paginate();
		if($this->Session->check('paymentDate')) {
			$data['Bank']['payment_date'] = $this->Session->read('paymentDate');
			$this->data = $data;
		}
		$this->set(compact('records', 'hideSideBar'));
    } 
	
	function addBankFormValidation($data=null) {
		$error = null;
		
		if($data) {				
			if((!isset($data['Bank']['amount'])) OR (!Validation::decimal($data['Bank']['amount'])) OR ($data['Bank']['amount']<=0)) {
				$error = 'Amount should be greater than 0';
			}
		}
		else {
			$error = 'Empty record';					
		}
		return $error;
	}
	
	function add($categoryID = null) {
		$error = null;
		
		if($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			
			$paymentDate = $data['Bank']['payment_date']['year'].'-'.$data['Bank']['payment_date']['month'].'-'.$data['Bank']['payment_date']['day'];
			$data['Bank']['payment_date'] = $paymentDate;
			
			$error = $this->addBankFormValidation($data);
			// check if category is available
			if(!$error) {				
				// check if duplicate record is entered.
				$conditions = array('Bank.payment_date'=>$paymentDate, 'Bank.amount'=>$data['Bank']['amount'], 'Bank.title'=>$data['Bank']['title']);				
			}			
			
			if(!$error) {				
				$data['Bank']['id'] = null;
				$data['Bank']['store_id'] = $this->Session->read('Store.id');
				$data['Bank']['payment_date'] = $paymentDate;
				
				if($this->Bank->save($data)) {					
					$this->Session->write('paymentDate', $paymentDate);
					$msg = 'New record added in Bank book';
					$this->Session->setFlash($msg, 'default', array('class'=>'success'));
					$this->redirect(array('controller'=>'banks', 'action'=>'add'));
				}
			}
		}
		else {
			if($this->Session->check('paymentDate')) {
				$data['Bank']['payment_date'] = $this->Session->read('paymentDate');
				$this->data = $data;
			}
		}
		
		if($error) {$this->Session->setFlash($error);}
		$this->redirect($this->request->referer());
	}
	
	
	public function remove($recordID=null) {
		if($this->request->isPost()) {
			if($bankInfo = $this->Bank->findById($recordID)) {
				$this->Bank->delete($recordID);
				$this->Session->setFlash('Bank information, dated "'.date('d M Y',strtotime($bankInfo['Bank']['payment_date'])).'" has been removed from the list', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('Record not found');
			}
		}
		else {
				$this->Session->setFlash('Invalid request');		
		}		
		
		$this->redirect($this->request->referer());
	}
	

}
?>
