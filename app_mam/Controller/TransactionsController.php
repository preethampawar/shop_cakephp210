<?php
App::uses('Sanitize', 'Utility');
class TransactionsController extends AppController {
    public $name = 'Transactions';
	
	function beforeRender() {
		
	}
	
    public function index() {       
		$conditions = array('Transaction.company_id'=>$this->Session->read('Company.id'), 'Transaction.business_type'=>'cash');		
		
		$this->paginate = array(
				'limit' => 25,
				'order' => array('Transaction.date' => 'desc'),
				'conditions' => $conditions
			);
		$transactions = $this->paginate();
		$this->set('transactions', $transactions);
    }
	
	public function add($category_id=null, $transaction_type='debit', $date=null) {		
		App::uses('Category', 'Model');
		$this->Category = new Category;		
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_cash'=>'1');
		$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
				
		$successMsg = null;
		$errorMsg = null;
		
		if($date == null) {
			$date = date('Y-m-d');
		}
		
		if($this->request->is('post')) {
			$data = $this->request->data['Transaction'];	
			$data['payment_method'] = 'Cash';
			$data['payment_amount'] = $data['total_amount'];
			$errorMsg = $this->validateData($data);			
				
			// Sanitize data
			$data['particular'] = htmlentities($data['particular'], ENT_QUOTES);
			
			if(!$errorMsg) {
				$data['company_id'] = $this->Session->read('Company.id');
				$data['company_name'] = $this->Session->read('Company.title');				
				$data['user_id'] = $this->Session->read('User.id');
				$data['user_name'] = $this->Session->read('User.name');				
				$data['category_name'] = $categoriesInfo[$data['category_id']];				
				$data['business_type'] = 'cash';				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];		
				
				if($dataID = $this->saveData($data)) {
					$successMsg = 'Record Created Successfully';
					$this->Session->setFlash('Your transaction details have been saved', 'default', array('class'=>'success'));					
					$this->redirect(array('action'=>'add', $data['category_id'], $data['transaction_type'], $data['date']));
				}
				else {
					$errorMsg = 'An error occured while communicating with the server';
				}
			}			
		}
		$dataID = null;		
		$transactions = $this->getTransactions(10);
		
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'transactions', 'category_id', 'transaction_type', 'date'));	
	}
	
	function edit($transactionID) {
		$dataID = $transactionID;
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '');
	
		$errorMsg = null;
		$transactionsInfo = $this->Transaction->find('first', array('conditions'=>array('Transaction.id'=>$transactionID)));
		
		if(empty($transactionsInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'message'));
			$this->redirect('/transactions/');
		}
		
		if($this->request->is('put')) {
			$data = $this->request->data['Transaction'];	
			$data['id'] = $transactionID;
			$data['payment_method'] = 'Cash';
			$data['payment_amount'] = $data['total_amount'];
			$errorMsg = $this->validateData($data);			
				
			// Sanitize data
			$data['particular'] = htmlentities($data['particular'], ENT_QUOTES);
			
			if(!$errorMsg) {
				$data['company_id'] = $this->Session->read('Company.id');
				$data['company_name'] = $this->Session->read('Company.title');				
				$data['user_id'] = $this->Session->read('User.id');
				$data['user_name'] = $this->Session->read('User.name');				
				$data['category_name'] = $categoriesInfo[$data['category_id']];				
				$data['business_type'] = 'cash';				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];		
				
				if($dataID = $this->saveData($data)) {
					$successMsg = 'Record Created Successfully';
					$this->Session->setFlash('Your transaction details have been saved', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'index'));
				}
				else {
					$errorMsg = 'An error occured while communicating with the server';
				}
			}			
		}
		else {
			$this->data = $transactionsInfo;
		}
		
		$this->set(compact('errorMsg','transactionsInfo','categories', 'dataID'));		
	}	
	
	function delete($id) {
		if(empty($id)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'message'));
		}
		else {
			if($data = $this->Transaction->findById($id)) {						
				if($this->Transaction->delete($id)) {				
					$tmp['Datalog'] = $data['Transaction'];
					$tmp['Datalog']['data_id'] = $id;
					$tmp['Datalog']['message'] = 'Deleted';
					$tmp['Datalog']['id'] = null;
					App::uses('Datalog', 'Model');
					$this->Datalog = new Datalog;
					$this->Datalog->save($tmp);	
				
					$this->Session->setFlash('Record Deleted Successfully', 'default', array('class'=>'success'));
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
				}
			}
			else {
					$this->Session->setFlash('Record not found', 'default', array('class'=>'message'));
			}
		}
		$this->redirect('/transactions/');
	}
	
	public function getTransactions($limit = 5) {       
		$conditions = array('Transaction.company_id'=>$this->Session->read('Company.id'), 'Transaction.business_type'=>'cash');		
		
		$transactions = $this->Transaction->find('all', array(
															'limit' => $limit,
															'order' => array('Transaction.created' => 'DESC'),
															'conditions' => $conditions
												));		
		return $transactions;
    }
}
?>
