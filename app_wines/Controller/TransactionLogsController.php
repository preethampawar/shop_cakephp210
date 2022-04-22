<?php
App::uses('Validation', 'Utility');
class TransactionLogsController extends AppController {

	var $name = 'TransactionLogs';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->checkStoreInfo();
	}
		
	/**
	 * Function to show list of Tag products
	 */
	 public function index() {		
		$conditions = array('TransactionLog.store_id'=>$this->Session->read('Store.id'));					
		
		$this->paginate = array(
							'conditions' => $conditions,
							'order' => array('TransactionLog.payment_date' => 'DESC', 'TransactionLog.created' => 'DESC'),
							'limit' => 100,
							'recursive' => '-1'
							);
		$logs = $this->paginate();
		
		// find tags
		App::uses('Tag', 'Model');
		$this->Tag = new Tag();
		$tags = $this->Tag->find('list', array('conditions'=>array('store_id'=>$this->Session->read('Store.id'))));
		
		$this->set(compact('logs', 'tags'));
    } 
	
	function addTransactionLogFormValidation($data=null) {
		$error = null;
		
		if($data) {					
			if((!isset($data['TransactionLog']['amount'])) OR (!Validation::decimal($data['TransactionLog']['amount'])) OR ($data['TransactionLog']['amount']<=0)) {
				$error = 'Amount should be greater than 0';
			}
		}
		else {
			$error = 'Empty Transaction Log details';					
		}
		return $error;
	}
	
	function add() {
		$error = null;
		
		$payment_date = date('Y-m-d');
		$tag_id = null;
		
		if($this->Session->check('TransactionLog.payment_date')) {
			$payment_date = $this->Session->read('TransactionLog.payment_date');
		}
		if($this->Session->check('TransactionLog.tag_id')) {
			$tag_id = $this->Session->read('TransactionLog.tag_id');
		}
		
		// save data
		if($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			if(!$error = $this->addTransactionLogFormValidation($data)) {			
				$payment_date = $data['TransactionLog']['payment_date']['year'].'-'.$data['TransactionLog']['payment_date']['month'].'-'.$data['TransactionLog']['payment_date']['day'];				
				$data['TransactionLog']['payment_date'] = $payment_date;
				$data['TransactionLog']['store_id'] = $this->Session->read('Store.id'); 
				$data['TransactionLog']['id'] = null; 
				
				$this->Session->write('TransactionLog.payment_date', $payment_date); 
				$this->Session->write('TransactionLog.tag_id', $data['TransactionLog']['tag_id']); 
				
				if($this->TransactionLog->save($data)) {
					$msg = 'Success! New tag has been created.';
					$this->Session->setFlash($msg, 'default', array('class'=>'success'));
					$this->redirect(array('controller'=>'TransactionLogs', 'action'=>'add'));
				} else {
					$msg = 'Error! An error occurred while connecting with the server. Please try again later.';
					$this->Session->setFlash($msg, 'default', array('class'=>'error'));
				}
			} else {				
				$this->Session->setFlash($error, 'default', array('class'=>'error'));
			}
		}
		
		// find tags
		App::uses('Tag', 'Model');
		$this->Tag = new Tag();
		$tags = $this->Tag->find('list', array('conditions'=>array('store_id'=>$this->Session->read('Store.id'))));
		
		// find recent counter balance sheet records
		$conditions = array('TransactionLog.store_id'=>$this->Session->read('Store.id'));
		$logs = $this->TransactionLog->find('all', array('conditions'=>$conditions, 'order'=>'TransactionLog.created DESC', 'recursive'=>'-1', 'limit'=>'10'));
		
		if($error) {$this->Session->setFlash($error);}
		$this->set(compact('logs', 'payment_date', 'tags', 'tag_id'));
	}
	
	
	public function remove($TransactionLogID=null) {
		if($this->request->isPost()) {
			if($TransactionLogInfo = $this->TransactionLog->findById($TransactionLogID)) {
				$this->TransactionLog->delete($TransactionLogID);
				$this->Session->setFlash('Success! Record has been deleted.', 'default', array('class'=>'success'));
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
	
	function addTag() {
		$error = null;
		if(isset($this->request->data) and !empty($this->request->data) )
		{	
			App::uses('Tag', 'Model');
			$this->Tag = new Tag();
			App::uses('Validation', 'Utility');
			
			$data['Tag'] = $this->request->data['Tag'];
			$data['Tag']['name'] = trim($data['Tag']['name']);			
				
			if(!empty($data['Tag']['name'])) {
				if(!Validation::between($data['Tag']['name'], 2, 55)) {
					$error = 'Tag name should be between 2 and 55 characters';
				}			
				$data['Tag']['name'] = htmlentities($data['Tag']['name'], ENT_QUOTES);			
				
				//find if a similar Tag exists for the selected store
				$conditions = array('Tag.name'=>$data['Tag']['name'], 'Tag.store_id'=>$this->Session->read('Store.id'));
				if($this->Tag->find('first', array('conditions'=>$conditions))) {
					$error = 'Tag "'.$data['Tag']['name'].'" already exists';
				}
			}
			else {
				$error = 'Tag name cannot be empty';
			}
			
			if(!$error) {
				$data['Tag']['id'] = null;
				$data['Tag']['store_id'] = $this->Session->read('Store.id');				
				if($this->Tag->save($data))
				{						
					$this->Session->setFlash('Tag "'.$data['Tag']['name'].'" Created Successfully', 'default', array('class'=>'success'));
				}
				else
				{
					$error = 'An error occurred while creating a new Tag';
				}
			}
		}
		if($error) {$this->Session->setFlash($error, 'default', array('class'=>'error'));}
		
		$this->redirect($this->request->referer());
	}
	

}
?>
