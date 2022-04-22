<?php
App::uses('Sanitize', 'Utility');
class CashController extends AppController {
    public $name = 'Cash';
	
	function beforeRender() {
		
	}
	
    public function index() {
        // $cash = $this->Cash->find('all', array('order'=>'Cash.date DESC', 'conditions'=>array('Cash.company_id'=>$this->Session->read('Company.id'), 'Cash.business_type'=>'cash')));
		
		$conditions = array('Cash.company_id'=>$this->Session->read('Company.id'), 'Cash.business_type'=>'cash');
		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Cash.user_id'=>$this->Session->read('User.id'));
		}
		
		$this->paginate = array(
				'limit' => 25,
				'order' => array('Cash.date' => 'desc'),
				'conditions' => $conditions
			);
		$cash = $this->paginate();
		
		// Get Group Information		
		if(!empty($cash)) {
			$dataIDs = array();
			foreach($cash as $row) {
				$dataIDs[] = $row['Cash']['id'];
			}
			
			App::uses('DataGroup', 'Model');
			$this->DataGroup = new DataGroup;
			$conditions = array('DataGroup.data_id'=>$dataIDs);
			$this->DataGroup->unbindModel(array('belongsTo'=>array('Data')));
			$datagroups = $this->DataGroup->find('all', array('conditions'=>$conditions));
			
			$tmp = array();
			if(!empty($datagroups)) {
				foreach($datagroups as $row) {
					$tmp[$row['DataGroup']['data_id']][] = $row['Group'];
				}
			}				
			foreach($cash as $index=>$row) {
				$cash[$index]['Group'] = isset($tmp[$row['Cash']['id']]) ? $tmp[$row['Cash']['id']] : array();
			}				
		}
		
		$this->set('cash', $cash);
    }
	
	public function add() {	
	
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		App::uses('Invoice', 'Model');
		$this->Invoice = new Invoice;
		$invoices = $this->Invoice->find('list', array('order'=>'Invoice.created DESC', 'conditions'=>array('Invoice.company_id'=>$this->Session->read('Company.id'))));
		
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_cash'=>'1');
		$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
				
		$successMsg = null;
		$errorMsg = array();
		if($this->request->is('post')) {
			$data = $this->request->data['Cash'];				
			$data['payment_method'] = 'Cash';
			$errorMsg = $this->validateData($data);			
				
			// Sanitize data
			$data['particular'] = htmlentities($data['particular'], ENT_QUOTES);
			
			if(empty($errorMsg)) {
				$this->Session->write('PrevDate', $data['date']);		
				$this->Session->write('PrevCategory', $data['category_id']);
				
				$data['company_id'] = $this->Session->read('Company.id');
				$data['company_name'] = $this->Session->read('Company.title');
				
				$data['user_id'] = $this->Session->read('User.id');
				$data['user_name'] = $this->Session->read('User.name');
				
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				
				$data['business_type'] = 'cash';
				
				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];		
				
				if($dataID = $this->saveData($data, false, false)) {
					// Save group information
					if(isset($this->request->data['DataGroup'])) {	
						$dataGroup = $this->request->data['DataGroup'];
						if(!empty($dataGroup) and !empty($dataGroup['id'])) {
							$this->Session->write('PrevDataGroups', $dataGroup);
							
							App::uses('DataGroup', 'Model');
							$this->DataGroup = new DataGroup;
							foreach($dataGroup['id'] as $group_id) {
								if($group_id > 0) {
									$tmp = array();
									$tmp['DataGroup']['id'] = null;
									$tmp['DataGroup']['group_id'] = $group_id;
									$tmp['DataGroup']['data_id'] = $dataID;
									$tmp['DataGroup']['company_id'] = $this->Session->read('Company.id');
									$this->DataGroup->save($tmp);
								}
							}
						}
					}
				
					$successMsg = 'Record Created Successfully';
					$this->Session->setFlash('Record Created Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'add'));
				}
				else {
					$errorMsg[] = 'An error occured while communicating with the server';
					// $this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
				}
			}			
		}
		$dataID = null;
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		
		// show recent 5 cash records		
		$conditions = array('Cash.company_id'=>$this->Session->read('Company.id'), 'Cash.business_type'=>'cash');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Cash.user_id'=>$this->Session->read('User.id'));
		}
		$params = array(
				'limit' => 5,
				'order' => array('Cash.created' => 'desc'),
				'conditions' => $conditions
			);
		$cash = $this->Cash->find('all', $params);		
		// Get Group Information		
		if(!empty($cash)) {
			$dataIDs = array();
			foreach($cash as $row) {
				$dataIDs[] = $row['Cash']['id'];
			}			
			App::uses('DataGroup', 'Model');
			$this->DataGroup = new DataGroup;
			$conditions = array('DataGroup.data_id'=>$dataIDs);
			$this->DataGroup->unbindModel(array('belongsTo'=>array('Data')));
			$datagroups = $this->DataGroup->find('all', array('conditions'=>$conditions));
			
			$tmp = array();
			if(!empty($datagroups)) {
				foreach($datagroups as $row) {
					$tmp[$row['DataGroup']['data_id']][] = $row['Group'];
				}
			}				
			foreach($cash as $index=>$row) {
				$cash[$index]['Group'] = isset($tmp[$row['Cash']['id']]) ? $tmp[$row['Cash']['id']] : array();
			}				
		}		
		$this->set('cash', $cash);		
		
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'invoices'));	
	}
	
	function edit($cashID) {
		$dataID = $cashID;
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '');
	
		$errorMsg = array();
		$cashInfo = $this->Cash->find('first', array('conditions'=>array('Cash.id'=>$cashID)));
		
		if(empty($cashInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'message'));
			$this->redirect('/cash/');
		}
		
		App::uses('Invoice', 'Model');
		$this->Invoice = new Invoice;
		$invoices = $this->Invoice->find('list', array('order'=>'Invoice.created DESC', 'conditions'=>array('Invoice.company_id'=>$this->Session->read('Company.id'))));
		
		if($this->request->is('put')) {
			$data = $this->request->data['Cash'];	
			$data['payment_method'] = 'Cash';
			
			$errorMsg = $this->validateData($data);	
						
			if(empty($errorMsg)) {			
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				$data['id'] = $cashID;	
				if($dataID = $this->saveData($data, false, false)) {
					App::uses('DataGroup', 'Model');
					$this->DataGroup = new DataGroup;
					// Delete Previous Groups
					$conditions = array('DataGroup.data_id'=>$dataID);
					$this->DataGroup->deleteAll($conditions);					
					// Save group information
					if(isset($this->request->data['DataGroup'])) {
						$dataGroup = $this->request->data['DataGroup'];
						if(!empty($dataGroup) and !empty($dataGroup['id'])) {
							$this->Session->write('PrevDataGroups', $dataGroup);
							
							foreach($dataGroup['id'] as $group_id) {
								if($group_id > 0) {
									$tmp = array();
									$tmp['DataGroup']['id'] = null;
									$tmp['DataGroup']['group_id'] = $group_id;
									$tmp['DataGroup']['data_id'] = $dataID;
									$tmp['DataGroup']['company_id'] = $this->Session->read('Company.id');
									$this->DataGroup->save($tmp);
								}
							}
						}
					}
					$this->Session->setFlash('Record Updated Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'index'));
				}
				else {
					$errorMsg[] = 'An error occured while communicating with the server';
				}	
			}
		}
		else {
			$this->data = $cashInfo;
		}
		
		App::uses('Datalog', 'Model');
		$this->Datalog = new Datalog;
		$datalogs = $this->Datalog->find('all', array('conditions'=>array('Datalog.data_id'=>$cashID), 'order'=>'Datalog.created DESC', 'recursive'=>'-1'));
		
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		$this->set(compact('errorMsg','cashInfo','categories', 'datalogs', 'dataID', 'invoices'));		
	}	
	
	function delete($id) {
		if(empty($id)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
		}
		else {			
			if($data = $this->Cash->findById($id)) {	
				$errorMsg = $this->deleteData($id);			
				if(!empty($errorMsg)) {
					$this->Session->setFlash($errorMsg, 'default', array('class'=>'error'));
				}
				else {
					$this->Session->setFlash('Record Deleted Successfully', 'default', array('class'=>'success'));
				}				
			}
			else {
					$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
			}
		}
		$this->redirect('/cash/');
	}
	
}
?>
