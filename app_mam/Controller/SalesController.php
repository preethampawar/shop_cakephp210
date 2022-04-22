<?php
App::uses('Sanitize', 'Utility');
class SalesController extends AppController {
    public $name = 'Sales';
	
    public function index() {
        App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
				
		$limit = 100;		
		$conditions[] = array('Sale.company_id'=>$this->Session->read('Company.id'), 'Sale.business_type'=>'sale');		
		
		$pagination = true;
		if($this->request->isPost()) {
			$pagination = false;
			$data = $this->request->data;
			if(!empty($data['Sale']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Sale']['category_id']);
				$categoriesList[] = $data['Sale']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Sale.category_id'=>$categoriesList);
			}			
			if(!empty($data['Sale']['startdate'])) {
				$conditions[] = array('Sale.date >='=>$data['Sale']['startdate']);
			}
			if(!empty($data['Sale']['enddate'])) {
				$conditions[] = array('Sale.date <='=>$data['Sale']['enddate']);
			}
		}	
		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Sale.user_id'=>$this->Session->read('User.id'));
		}		
		
		if($pagination) {
			$this->paginate = array(
					'limit' => $limit,
					'order' => array('Sale.date' => 'desc', 'Sale.created' => 'desc', ),
					'conditions' => $conditions
				);					
			$sales = $this->paginate();
		}
		else {			
			$sales = $this->Sale->find('all', array('conditions'=>$conditions, 'order'=>array('Sale.date' => 'desc', 'Sale.created' => 'desc')));			
		}
		
		// Get Group Information		
		if(!empty($sales)) {
			$dataIDs = array();
			foreach($sales as $row) {
				$dataIDs[] = $row['Sale']['id'];
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
			foreach($sales as $index=>$row) {
				$sales[$index]['Group'] = isset($tmp[$row['Sale']['id']]) ? $tmp[$row['Sale']['id']] : array();
			}				
		}
		
		$this->set('sales', $sales);
		$this->set('categories', $categories);
		$this->set('pagination', $pagination);		
    }
	
	public function add() {			
		App::uses('Category', 'Model');
		$this->Category = new Category;		
		
		if(!($this->Session->read('Company.business_type')=='wineshop')) {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_sales'=>'1');
			$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		}
		else {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_sales'=>'1', 'Category.is_product'=>'1');
			$categories = $this->Category->find('list', array('conditions'=>$conditions, 'order'=>'Category.name ASC'));
		}
		
		
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
		$successMsg = null;
		$errorMsg = array();
		if($this->request->is('post')) {
			$data = $this->request->data['Sale'];				
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
				
				$data['business_type'] = 'sale';
				$data['transaction_type'] = 'credit';
				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];		
				
				// check if stock is available for the product
				// if the business type is inventory
				if($this->Session->check('UserCompany')) {	
					$inventory = $this->Session->read('Company.business_type');
					if($inventory == 'inventory') {
						$availableStock = $this->getStockInHand($data['category_id']);				
						if($availableStock > 0) {
							if($data['quantity'] > $availableStock) {
								$errorMsg[] = 'Quantity cannot be greater than '.$availableStock;		
							}
						}
						else {
							$errorMsg[] = 'Product out of stock.';
						}
					}
				}
				// end of check stock
				
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
		
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		
		$dataID = null;
		
		// get previous sales records
		$conditions = array('Sale.company_id'=>$this->Session->read('Company.id'), 'Sale.business_type'=>'sale');		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Sale.user_id'=>$this->Session->read('User.id'));
		}		
		$params = array(
				'limit' => 5,
				'order' => array('Sale.created' => 'desc'),
				'conditions' => $conditions
			);
		$sales = $this->Sale->find('all', $params);		
		// Get Group Information		
		if(!empty($sales)) {
			$dataIDs = array();
			foreach($sales as $row) {
				$dataIDs[] = $row['Sale']['id'];
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
			foreach($sales as $index=>$row) {
				$sales[$index]['Group'] = isset($tmp[$row['Sale']['id']]) ? $tmp[$row['Sale']['id']] : array();
			}				
		}		
		
		// get stock in hand for each category
		if($this->Session->check('UserCompany')) {	
			$inventory = $this->Session->read('Company.business_type');
			if($inventory == 'inventory') {
				$stockInfo = array();
				if(!empty($categories)) {
					foreach($categories as $categoryID=>$row) {
						$stockInHand = $this->getStockInHand($categoryID);
						$categories[$categoryID]=$row.'&nbsp; ['.$stockInHand.']';
					}
				}
			}
		}
		
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'sales'));		
	}
	
	function edit($saleID) {
		$dataID = $saleID;
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		if(!($this->Session->read('Company.business_type')=='wineshop')) {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_sales'=>'1');
			$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		}
		else {
			$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_sales'=>'1', 'Category.is_product'=>'1');
			$categories = $this->Category->find('list', array('conditions'=>$conditions, 'order'=>'Category.name ASC'));				
		}
		
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
		$successMsg = null;
		$errorMsg = array();
		
		
		$errorMsg = null;
		$saleInfo = $this->Sale->find('first', array('conditions'=>array('Sale.id'=>$saleID)));
		if(isset($saleInfo['AvailableStock']['id']) and !empty($saleInfo['AvailableStock']['id'])) {
			$this->Session->setFlash('Cannot edit this record. This record has been added automatically.', 'default', array('class'=>'error'));
			$this->redirect('/sales/');
		}
					
		if(empty($saleInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
			$this->redirect('/sales/');
		}		
				
		if($this->request->is('put')) {
			$data = $this->request->data['Sale'];				
			$errorMsg = $this->validateData($data);			
			
			// Sanitize data
			$data['particular'] = htmlentities($data['particular'], ENT_QUOTES);
			
			if(empty($errorMsg)) {
				$this->Session->write('PrevDate', $data['date']);		
				$this->Session->write('PrevCategory', $data['category_id']);
				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				$data['id'] = $saleID;	
				
				if($this->saveData($data)) {
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
			$this->data = $saleInfo;
		}
		
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}	
		App::uses('Datalog', 'Model');
		$this->Datalog = new Datalog;
		$datalogs = $this->Datalog->find('all', array('conditions'=>array('Datalog.data_id'=>$saleID), 'order'=>'Datalog.created DESC', 'recursive'=>'-1'));
		
		// get stock in hand for each category
		if($this->Session->check('UserCompany')) {	
			$inventory = $this->Session->read('Company.business_type');
			if($inventory == 'inventory') {
				$stockInfo = array();
				if(!empty($categories)) {
					foreach($categories as $categoryID=>$row) {
						$stockInHand = $this->getStockInHand($categoryID);
						$categories[$categoryID]=$row.'&nbsp; ['.$stockInHand.']';
					}
				}	
			}
		}
		
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'datalogs', 'saleInfo'));		
	}	
	
	function edit1($saleID) {
		$dataID = $saleID;
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '');
	
		$errorMsg = null;
		$saleInfo = $this->Sale->find('first', array('conditions'=>array('Sale.id'=>$saleID)));
		if(isset($saleInfo['AvailableStock']['id']) and !empty($saleInfo['AvailableStock']['id'])) {
			$this->Session->setFlash('Cannot edit this record. This record has been added automatically.', 'default', array('class'=>'error'));
			$this->redirect('/sales/');
		}
					
		if(empty($saleInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
			$this->redirect('/sales/');
		}		
				
		if($this->request->is('put')) {
			$data = $this->request->data['Sale'];	
				
			$errorMsg = $this->validateData($data);				
			
			if(!$errorMsg) {
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				$data['id'] = $saleID;	
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
			else {
				$this->Session->setFlash($errorMsg, 'default', array('class'=>'error'));
			}
		}
		else {
			$this->data = $saleInfo;
		}
		
		App::uses('Datalog', 'Model');
		$this->Datalog = new Datalog;
		$datalogs = $this->Datalog->find('all', array('conditions'=>array('Datalog.data_id'=>$saleID), 'order'=>'Datalog.created DESC', 'recursive'=>'-1'));
		
		$this->set(compact('errorMsg','saleInfo','categories', 'datalogs', 'dataID'));				
	}	
	
	function delete($id) {
		if(empty($id)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
		}
		else {			
			if($data = $this->Sale->findById($id)) {	
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
		$this->redirect($this->request->referer());
	}
}
?>
