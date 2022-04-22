<?php
App::uses('Sanitize', 'Utility');
class PurchasesController extends AppController {
    public $name = 'Purchases';
	
    public function index() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
				
		$limit = 100;		
		$conditions[] = array('Purchase.company_id'=>$this->Session->read('Company.id'), 'Purchase.business_type'=>'purchase');		
		
		$pagination = true;
		if($this->request->isPost()) {
			$pagination = false;
			$data = $this->request->data;
			if(!empty($data['Purchase']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Purchase']['category_id']);
				$categoriesList[] = $data['Purchase']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Purchase.category_id'=>$categoriesList);
			}			
			if(!empty($data['Purchase']['startdate'])) {
				$conditions[] = array('Purchase.date >='=>$data['Purchase']['startdate']);
			}
			if(!empty($data['Purchase']['enddate'])) {
				$conditions[] = array('Purchase.date <='=>$data['Purchase']['enddate']);
			}
		}	
		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Purchase.user_id'=>$this->Session->read('User.id'));
		}		
		
		if($pagination) {
			$this->paginate = array(
					'limit' => $limit,
					'order' => array('Purchase.date' => 'desc', 'Purchase.created' => 'desc', ),
					'conditions' => $conditions
				);					
			$purchases = $this->paginate();
		}
		else {
			$purchases = $this->Purchase->find('all', array('conditions'=>$conditions, 'order'=>array('Purchase.date' => 'desc')));
		}
		
		// Get Group Information		
		if(!empty($purchases)) {
			$dataIDs = array();
			foreach($purchases as $row) {
				$dataIDs[] = $row['Purchase']['id'];
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
			foreach($purchases as $index=>$row) {
				$purchases[$index]['Group'] = isset($tmp[$row['Purchase']['id']]) ? $tmp[$row['Purchase']['id']] : array();
			}				
		}
		
		$this->set('purchases', $purchases);
		$this->set('categories', $categories);
		$this->set('pagination', $pagination);
    }
	
	public function add() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		App::uses('Invoice', 'Model');
		$this->Invoice = new Invoice;
		$invoices = $this->Invoice->find('list', array('order'=>'Invoice.created DESC', 'conditions'=>array('Invoice.company_id'=>$this->Session->read('Company.id'))));
		
		if(!($this->Session->read('Company.business_type')=='wineshop')) {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1');
			$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		}
		else {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1', 'Category.is_product'=>'1');
			$categories = $this->Category->find('list', array('conditions'=>$conditions, 'order'=>'Category.name ASC'));
		}		
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
		
		$allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));		
		
		$errorMsg = array();
		$successMsg = null;
		if($this->request->is('post')) {
			$data = $this->request->data['Purchase'];			
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
				
				$data['business_type'] = 'purchase';
				$data['transaction_type'] = 'debit';
				
				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];			
				$business_type = $this->Session->read('Company.business_type');
				if($business_type == 'wineshop') {
					$data['price_per_case'] = $data['unitrate'];
					$data['unitrate'] = $data['total_amount']/($data['no_of_cases'] * $data['quantity']);
					
					// check for duplicate entry
					$tmpConditions = array('Purchase.category_id'=>$data['category_id'], 'Purchase.no_of_cases'=>$data['no_of_cases'], 'Purchase.total_amount'=>$data['total_amount'], 'Purchase.payment_amount'=>$data['payment_amount'], 'Purchase.payment_method'=>$data['payment_method'], 'Purchase.date'=>$data['date'], 'Purchase.company_id'=>$data['company_id'], 'Purchase.transaction_type'=>$data['transaction_type']);
					if($duplicateRecord = $this->Purchase->find('first', array('conditions'=>$tmpConditions))) {						
						$errorMsg[] = 'Duplicate Entry. Record with this information already exists.';
					}			
				}
				if(empty($errorMsg)) {			
					if($dataID = $this->saveData($data)) {
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
		}
		$dataID = null;
		
		// get previous records
		$conditions = array('Purchase.company_id'=>$this->Session->read('Company.id'), 'Purchase.business_type'=>'purchase');		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Purchase.user_id'=>$this->Session->read('User.id'));
		}
		
		$params = array(
				'limit' => 5,
				'order' => array('Purchase.created' => 'desc'),
				'conditions' => $conditions
			);			
		$purchases = $this->Purchase->find('all', $params);		
		// Get Group Information		
		if(!empty($purchases)) {
			$dataIDs = array();
			foreach($purchases as $row) {
				$dataIDs[] = $row['Purchase']['id'];
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
			foreach($purchases as $index=>$row) {
				$purchases[$index]['Group'] = isset($tmp[$row['Purchase']['id']]) ? $tmp[$row['Purchase']['id']] : array();
			}				
		}		
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		
		if($this->Session->check('UserCompany')) {	
			$inventory = $this->Session->read('Company.business_type');
			if($inventory == 'inventory') {
				// get stock in hand for each category
				$stockInfo = array();
				if(!empty($categories)) {
					foreach($categories as $categoryID=>$row) {
						$stockInHand = $this->getStockInHand($categoryID);
						$categories[$categoryID]=$row.'&nbsp; ['.$stockInHand.']';
					}
				}	
			}
		}
		
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'purchases', 'allcategories', 'invoices'));	
	}
	
	public function edit($purchaseID) {
		$dataID = $purchaseID;
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		App::uses('Invoice', 'Model');
		$this->Invoice = new Invoice;
		$invoices = $this->Invoice->find('list', array('order'=>'Invoice.created DESC', 'conditions'=>array('Invoice.company_id'=>$this->Session->read('Company.id'))));
				
		if(!($this->Session->read('Company.business_type')=='wineshop')) {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1');
			$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		}
		else {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1', 'Category.is_product'=>'1');
			$categories = $this->Category->find('list', array('conditions'=>$conditions, 'order'=>'Category.name ASC'));
		}
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
		
		$purchaseInfo = $this->Purchase->find('first', array('conditions'=>array('Purchase.id'=>$purchaseID)));
	
		if(empty($purchaseInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'message'));
			$this->redirect('/purchases/');
		}
		
		$errorMsg = array();
		$successMsg = null;
		if($this->request->is('put')) {
			$data = $this->request->data['Purchase'];			
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
				
				$data['business_type'] = 'purchase';
				$data['transaction_type'] = 'debit';
				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];			
				$business_type = $this->Session->read('Company.business_type');
				if($business_type == 'wineshop') {
					$data['price_per_case'] = $data['unitrate'];
					$data['unitrate'] = $data['total_amount']/($data['no_of_cases'] * $data['quantity']);
				}
				$data['id'] = $dataID;
							
				if($dataID = $this->saveData($data)) {
					// Save group information
					if(isset($this->request->data['DataGroup'])) {
						$dataGroup = $this->request->data['DataGroup'];
						if(!empty($dataGroup) and !empty($dataGroup['id'])) {
							$this->Session->write('PrevDataGroups', $dataGroup);
							
							App::uses('DataGroup', 'Model');
							$this->DataGroup = new DataGroup;
							
							// Delete Previous Groups
							$conditions = array('DataGroup.data_id'=>$dataID);
							$this->DataGroup->deleteAll($conditions);		
							// Save group information
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
				
					$this->Session->setFlash('Record Modified Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'index'));
				}
				else {
					$errorMsg[] = 'An error occured while communicating with the server';
					// $this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
				}			
			}
		}
		else {
			$this->data = $purchaseInfo;
		}		
		
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'purchases', 'invoices'));		
	}
	
	function edit1($purchaseID) {		
		$dataID = $purchaseID;
	
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '');
	
		$errorMsg = null;
		$purchaseInfo = $this->Purchase->find('first', array('conditions'=>array('Purchase.id'=>$purchaseID)));
	
		if(empty($purchaseInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'message'));
			$this->redirect('/purchases/');
		}
		
		if($this->request->is('put')) {
			$data = $this->request->data['Purchase'];	
			
			$errorMsg = $this->validateData($data);	
						
			if(!$errorMsg) {			
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				$dataID = $data['id'] = $purchaseID;	
				if($this->saveData($data)) {
					
					App::uses('DataGroup', 'Model');
					$this->DataGroup = new DataGroup;
					// Delete Previous Groups
					$conditions = array('DataGroup.data_id'=>$dataID);
					$this->DataGroup->deleteAll($conditions);					
					// Save group information
					if(isset($this->request->data['DataGroup'])) {
						$dataGroup = $this->request->data['DataGroup'];
						if(!empty($dataGroup) and !empty($dataGroup['id'])) {
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
					$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
				}	
			}
		}
		else {
			$this->data = $purchaseInfo;
		}
		
		
		App::uses('Datalog', 'Model');
		$this->Datalog = new Datalog;
		$datalogs = $this->Datalog->find('all', array('conditions'=>array('Datalog.data_id'=>$purchaseID), 'order'=>'Datalog.created DESC', 'recursive'=>'-1'));
		
		$this->set(compact('errorMsg','purchaseInfo','categories', 'datalogs', 'dataID'));			
	}	
	
	function delete($id) {
		if(empty($id)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
		}
		else {			
			if($data = $this->Purchase->findById($id)) {	
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
		$this->redirect('/purchases/');
	}
	
}
?>
