<?php
App::uses('Controller', 'Controller');
App::uses('Validation', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('AuthComponent', 'Controller/Component');
class AppController extends Controller {
	public $helpers = array('Html', 'Form', 'Session', 'Number', 'Js' => array('Jquery'));
	
	public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'companies', 'action' => 'selectCompany'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email')
				)
			)				
        )
    );

	public function beforeFilter() {	
		if(!$this->Session->check('User')) {
			/* only these actions are allowed when the user in not logged in */
			$allowed = array('login', 'register', 'confirm', 'forgotpassword', 'resetpassword');	
		
			if(!in_array($this->action, $allowed)) {			
				$this->redirect('/users/login');			
			}
			
		}
		else {
		
			// Check Admin Access
			if(isset($this->params['admin']) and ($this->params['admin'] == '1')) {
				$this->layout = 'admin';
				if($this->Session->read('User.admin') != 1) {
					$this->Session->setFlash('You are not authorized to view this page [Restricted Access]', 'default', array('class'=>'error'));
						$this->redirect($this->referer());
						exit;
				}
			}
		
			$this->set('CUR', $this->Session->read('Company.currency'));
						
			if($this->Session->read('User.admin') != 1) {
				if($this->Session->check('UserCompany')) {	
					// Check User access
					if($this->Session->check('UserCompany')) {
						$companyInfo = $this->Session->read('Company');
						if($companyInfo['business_type'] != 'personal') {			
							$no_of_days = (strtotime($companyInfo['subscription_end_date']) - strtotime(date('Y-m-d')))/(60*60*24);
							
							if($no_of_days<0) {
								$this->Session->setFlash('This account has been expired. Renew your subscription to continue using this account.', 'default', array('class'=>'error'));
								$this->Session->write('UserCompany.user_level', '1');
							}							
						}
						
						if(!($this->checkUserAccess())) {						
							$this->Session->setFlash('You are not authorized to view this page [Restricted Access]', 'default', array('class'=>'error'));
							$this->redirect($this->referer());
							exit;
						}
					}
							
					// Screening Delete/Edit actions
					if(!$this->checkCompanyAccess()) {
						$this->Session->setFlash('You are not authorized to perform this action [Restricted Access]', 'default', array('class'=>'error'));
						$this->redirect($this->referer());
						exit;
					}
				}
				else { 
					/* only these actions are allowed when the user is logged in and has not selected any company */
					if($this->Session->read('User.admin') != '1') {
						$allowed = array('selectCompany', 'logout', 'switchCompany', 'add', 'requestNewAccount');
						if(!in_array($this->action, $allowed)) {
							$this->redirect(array('controller'=>'companies', 'action'=>'selectCompany'));
						}	
					}
				}
			}		
		}				
		
	}
	
	
	/**
	 * Function to give user access to specific controllers and actions
	 */
	public function checkUserAccess() {
		App::uses('AccessController', 'Controller');
		$AccessController = new AccessController;
		$AccessController->constructClasses();
		
		$controller = $this->name;
		$action = $this->action;
		$allowed = $AccessController->checkUserAccess($controller, $action);		
		return $allowed;	
	}
	
	/**
	 * Restrict users from changing other company records. 
	 */
	public function checkCompanyAccess() {
		$userLevel = $this->Session->read('UserCompany.user_level');
		// Users cannot edit/delete other company records
		$checkActions = array('edit', 'delete', 'changeUserAccess', 'remove', 'editDamagedStock', 'deleteDamagedStock');
		// $skipController = array('Companies');
		
		if(in_array($this->action, $checkActions)) {
			if(isset($this->passedArgs[0]) and !empty($this->passedArgs[0])) {
				$id = $this->passedArgs[0];				
				$model = $this->modelClass;
				
				if($model != 'User') {
					$conditions = array($model.'.id'=>$id, $model.'.company_id'=>$this->Session->read('Company.id'));
					$info = $this->$model->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
					if(!empty($info)) {
						return true;
					}								
				}
				else {
					App::uses('UserCompany', 'Model');
					$this->UserCompany = new UserCompany;
					$conditions = array('UserCompany.user_id'=>$id, 'UserCompany.company_id'=>$this->Session->read('Company.id'));
					$info = $this->UserCompany->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
					if(!empty($info)) {
						return true;
					}					
				}
			}
			return false;
		}				
		return true;
	}
	
	
	/**
	 * Function to validate data
	 */
	function validateData($record) {			
		// Check if all the required fields are present.
		$requiredFields = array('category_id', 'particular', 'total_amount', 'payment_amount', 'payment_method', 'date');		
		$errorMsg = array();
		if(!empty($record)) {
			// Trim extra spaces
			foreach($record as $field=>$value) {
				$record[$field] = trim($value);
			}
			
			foreach($requiredFields as $field) {
				
				if(!isset($record[$field])) {
					$errorMsg[] = 'Data not sufficient';
					break;
				}
			}
		}
		else {
			$errorMsg[] = 'Data not sufficient';
		}
		// validate data
		if(empty($errorMsg)) {		

			if(Validation::blank($record['category_id'])) {
				$errorMsg[] = 'Select Category';
			}
			if(!Validation::numeric($record['category_id'])) {
				$errorMsg[] = 'Invalid Category';
			}
			if(Validation::blank($record['particular'])) {
				$errorMsg[] = 'Enter Particular';
			}	
			if(isset($record['quantity'])) {				
				if(isset($record['unitrate']) and !empty($record['unitrate'])) {
					if(empty($record['quantity'])) {
						$errorMsg[] = "Quantity should be greater than zero";
					}
					
					if(!(Validation::decimal($record['unitrate'])) and (!Validation::numeric($record['unitrate']))) {
						$errorMsg[] = "Invalid Unit Price";
					}
				}
				if(!Validation::blank($record['quantity'])) {
					if(!Validation::numeric($record['quantity'])) {
						$errorMsg[] = "Invalid Quantity Entered";
					}
					else {
						if($record['quantity'] < '0') {
							$errorMsg[] = "Quantity cannot be less than zero";
						}
					}
				}
			}		
			if(empty($record['total_amount'])) {
				$errorMsg[] = "Enter Total Amount";
			}	
			elseif(!(Validation::decimal($record['total_amount'])) and (!Validation::numeric($record['total_amount']))) {
				$errorMsg[] = "Invalid Total Amount";
			}
			elseif($record['total_amount']<=0) {
				$errorMsg[] = "Total Amount should be greater than zero";
			}
			if(!Validation::blank($record['payment_amount'])) {
				if(!(Validation::decimal($record['payment_amount'])) and (!Validation::numeric($record['payment_amount']))) {
					$errorMsg[] = "Invalid Paid/Received Amount";
				}
				else {
					if($record['total_amount'] < $record['payment_amount']) {
						$errorMsg[] = "Total Amount cannot be less than Payment Amount";
					}
				}
			}
			elseif(Validation::blank($record['payment_method'])) {
				$errorMsg[] = 'Enter Payment Amount';
			}
			if(Validation::blank($record['date'])) {
				$errorMsg[] = 'Select Date';
			}	
			
		}
		
		return $errorMsg; 		
	}
	
	/**
	 * Function to save sales/purchases/cash data
	 */
	function saveData($data, $damagedStock=false, $stockUpdate=true) {
		App::uses('Data', 'Model');
		$this->Data = new Data;
		$dataID = null;
		try {
			if($this->Data->save($data)) {
				$dataInfo = $this->Data->read();				
				
				$dataID = $dataInfo['Data']['id'];
				$dataLog['Datalog'] = $dataInfo['Data'];
				
				unset($dataLog['Datalog']['created']);
				unset($dataLog['Datalog']['modified']);
				
				$dataLog['Datalog']['id'] = null;			
				$dataLog['Datalog']['data_id'] = $dataInfo['Data']['id'];
				$dataLog['Datalog']['message'] = (isset($data['id'])) ? ((isset($data['message'])) ? $data['message'] : '') : '####Created####';		
				
				try {
					App::uses('Datalog', 'Model');
					$this->Datalog = new Datalog;
					$this->Datalog->save($dataLog);	
					
					// create/update stock in inventory table
					if($stockUpdate) {
						App::uses('Category', 'Model');
						$this->Category = new Category;
						$categoryInfo = $this->Category->findById($dataInfo['Data']['category_id']);
						if(!empty($categoryInfo) and ($categoryInfo['Category']['is_product'] == '1')) {
							App::uses('Inventory', 'Model');
							$this->Inventory = new Inventory;					
							if($inventoryInfo = $this->Inventory->findByDataId($dataID)) {		
								$tmp['Inventory']['id'] = $inventoryInfo['Inventory']['id'];
							}
							else {							
								$tmp['Inventory']['id'] = null;
								$tmp['Inventory']['user_id'] = $this->Session->read('User.id');
							}
							
							$tmp['Inventory']['data_id'] = $dataID;
							$tmp['Inventory']['quantity'] = $dataInfo['Data']['quantity'];
							$tmp['Inventory']['date'] = $dataInfo['Data']['date'];		
							$tmp['Inventory']['category_id'] = $dataInfo['Data']['category_id'];		
							$tmp['Inventory']['company_id'] = $this->Session->read('Company.id');								
							$tmp['Inventory']['unitrate'] = $dataInfo['Data']['unitrate'];
							if($damagedStock) {
								$tmp['Inventory']['type'] = 'damaged';
							}	
							else {
								if($dataInfo['Data']['transaction_type'] == 'credit') {
									$tmp['Inventory']['type'] = 'out';
								}
								else{
									$tmp['Inventory']['type'] = 'in';							
								}								
							}
							$this->Inventory->save($tmp);	
							
							// update stock movement info
							$dataInfo['Data']['id'] = $dataID;
							$this->updateStockMovementInfo($dataInfo, $damagedStock);	
						}
					}
				}
				catch(Exception $ex) {
					
				}				
			}
		}
		catch(Exception $ex) {
			
		}
		return $dataID;
	}
	
	/**
	 * Function to update stock movement info
	 */
	function updateStockMovementInfo($data, $damagedStock=false) {
		$dataID = $data['Data']['id'];
		
		// update stock movement info
		App::uses('StockMovement', 'Model');		
		$this->StockMovement = new StockMovement;					
		
		$deleteConditions = array('StockMovement.data_id'=>$dataID);
		$this->StockMovement->deleteAll($deleteConditions);
				
		$tmp['StockMovement']['id'] = null;
		$tmp['StockMovement']['data_id'] = $dataID;
		$tmp['StockMovement']['quantity'] = $data['Data']['quantity'];
		$tmp['StockMovement']['date'] = $data['Data']['date'];		
		$tmp['StockMovement']['category_id'] = $data['Data']['category_id'];		
		$tmp['StockMovement']['company_id'] = $this->Session->read('Company.id');								
		$tmp['StockMovement']['user_id'] = $this->Session->read('User.id');
		
		
		if(($data['Data']['transaction_type'] == 'credit') or $damagedStock) {
			// get available stock in shop
			$stockInHand = $this->getStockInHandInShop($data['Data']['category_id']);				
			
			if($stockInHand >= $data['Data']['quantity']) {
				// ie: shop has enough quantity to sale product. so no need to get stock from godown					
				if($damagedStock) {
					$tmp['StockMovement']['type'] = 'damaged';
					$tmp['StockMovement']['place'] = 'shop';
					$tmp['StockMovement']['message'] = 'By Damaged Stock';
					$this->StockMovement->save($tmp);
				}
				else {	
					$tmp['StockMovement']['type'] = 'out';
					$tmp['StockMovement']['place'] = 'shop';
					$tmp['StockMovement']['message'] = 'By Sale';
					$this->StockMovement->save($tmp);
				}
			}
			else {
				// ie: need to get stock from godown. 
				$tmp3 = $tmp2 = $tmp; // tmp for stock out from godown, tmp2 for stock in to shop, tmp3 for stock out from shop.
				
				// update stock movement out of godown
				$getStockFromGodown = ($data['Data']['quantity']-$stockInHand);					
				$tmp['StockMovement']['quantity'] = $getStockFromGodown;
				if($damagedStock) {
					$tmp['StockMovement']['type'] = 'out';
					$tmp['StockMovement']['place'] = 'godown';
					$tmp['StockMovement']['message'] = 'By Damaged Stock';						
				}
				else {
					$tmp['StockMovement']['type'] = 'out';
					$tmp['StockMovement']['place'] = 'godown';
					$tmp['StockMovement']['message'] = 'By Sale';
				}
				$stockMovementInfo = $this->StockMovement->save($tmp);			
				$stockMovementID = $stockMovementInfo['StockMovement']['id'];
				
				// update stock movement in to shop
				$tmp2['StockMovement']['quantity'] = $getStockFromGodown;
				
				if($damagedStock) {
					$tmp2['StockMovement']['type'] = 'in';
					$tmp2['StockMovement']['place'] = 'shop';
					$tmp2['StockMovement']['message'] = 'By Damaged Stock';						
				}
				else {
					$tmp2['StockMovement']['type'] = 'in';
					$tmp2['StockMovement']['place'] = 'shop';
					$tmp2['StockMovement']['message'] = 'By Sale';
				}
				$tmp2['StockMovement']['reference_id'] = $stockMovementID;
				$stockMovementInfo = $this->StockMovement->save($tmp2);
				$stockMovementID = $stockMovementInfo['StockMovement']['id'];
				
				// update stock movement out of shop
				if($damagedStock) {
					$tmp3['StockMovement']['type'] = 'damaged';
					$tmp3['StockMovement']['place'] = 'shop';
					$tmp3['StockMovement']['message'] = 'By Damaged Stock';						
				}
				else {
					$tmp3['StockMovement']['type'] = 'out';
					$tmp3['StockMovement']['place'] = 'shop';
					$tmp3['StockMovement']['message'] = 'By Sale';
				}
				$tmp3['StockMovement']['reference_id'] = $stockMovementID;
				$this->StockMovement->save($tmp3);
			}				
		}
		else{
			$tmp['StockMovement']['type'] = 'in';							
			$tmp['StockMovement']['place'] = 'godown';	
			$tmp['StockMovement']['message'] = 'By Purchase';	
			$this->StockMovement->save($tmp);
		}								
			
	} 
	
	/**
	 * Function to delete sale/purchase/cash data
	 */
	function deleteData($dataID) {
		$errorMsg = null;
		if(empty($dataID)) {
			// $errorMsg = 'Record not found';
		}
		else {
			App::uses('Data', 'Model');
			$this->Data = new Data;	
		
			if($data = $this->Data->findById($dataID)) {			
				$tmp['Datalog'] = $data['Data'];
				$tmp['Datalog']['data_id'] = $dataID;
				$tmp['Datalog']['message'] = 'Deleted';
				$tmp['Datalog']['id'] = null;
				App::uses('Datalog', 'Model');
				$this->Datalog = new Datalog;
				$this->Datalog->save($tmp);				
				
				if($this->Data->delete($dataID)) {
					// Delete data Groups
					App::uses('DataGroup', 'Model');
					$this->DataGroup = new DataGroup;
					$conditions = array('DataGroup.data_id'=>$dataID);
					$this->DataGroup->deleteAll($conditions);	
					
					// Delete information from inventory
					App::uses('Inventory', 'Model');
					$this->Inventory = new Inventory;
					$conditions = array('Inventory.data_id'=>$dataID);
					$this->Inventory->deleteAll($conditions);
					
					// Delete information from available_stock table
					App::uses('AvailableStock', 'Model');
					$this->AvailableStock = new AvailableStock;
					$conditions = array('AvailableStock.data_id'=>$dataID);
					$this->AvailableStock->deleteAll($conditions);	
					
					// Delete information from stock movement
					App::uses('StockMovement', 'Model');
					$this->StockMovement = new StockMovement;
					$conditions = array('StockMovement.data_id'=>$dataID);
					$this->StockMovement->deleteAll($conditions);	
					
				}
				else {
					$errorMsg = 'An error occured while communicating with the server';
				}
			}
			else {
				// $errorMsg = 'Record not found';
			}
		}
		
		return $errorMsg;
	}	

	/** 
	 * Function to return available stock for a product
	 */
	function getStockInHand($categoryID) {
		$stockInHand = 0;
		if($categoryID > 0) {
			App::uses('Inventory', 'Model');
			$this->Inventory = new Inventory;
			
			$inventoryConditions = array('Inventory.category_id'=>$categoryID);							
			
			$group = array('Inventory.category_id', 'Inventory.type');
			$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type');
			$stockResults = $this->Inventory->find('all', array('conditions'=>$inventoryConditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));			
			
			if(!empty($stockResults)) {
				$stockIn = 0;
				$stockOut = 0;
				$stockDamaged = 0;
				
				foreach($stockResults as $row) {
					if($row['Inventory']['type'] == 'in') {
						$stockIn = $row[0]['Quantity'];
					}
					elseif($row['Inventory']['type'] == 'out') {
						$stockOut = $row[0]['Quantity'];
					}
					elseif($row['Inventory']['type'] == 'damaged') {
						$stockDamaged = $row[0]['Quantity'];
					}
				}					
				$stockInHand = $stockIn - $stockOut - $stockDamaged;
				if($stockInHand < 0) {
					$stockInHand = 0;
				}
				
				
				
			}
			else {
				$errorMsg[] = 'Product out of stock.';
			}
		}
		return $stockInHand;
	} 
	
	/** 
	 * Function to return available stock for a product in shop
	 */
	function getStockInHandInShop($categoryID) {
		$stockInHand = 0;
		if($categoryID > 0) {
			App::uses('StockMovement', 'Model');
			$this->StockMovement = new StockMovement;
			
			$stockMovementConditions = array('StockMovement.category_id'=>$categoryID, 'StockMovement.place'=>'shop');							
			
			$group = array('StockMovement.category_id', 'StockMovement.place', 'StockMovement.type');
			$fields = array('SUM(StockMovement.quantity) as Quantity', 'StockMovement.category_id', 'StockMovement.type');
			$stockResults = $this->StockMovement->find('all', array('conditions'=>$stockMovementConditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));			
			$stockInHand = 0;
			
			if(!empty($stockResults)) {
				$stockIn = 0;
				$stockOut = 0;
				$stockDamaged = 0;
				
				foreach($stockResults as $row) {
					if($row['StockMovement']['type'] == 'in') {
						$stockIn = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'out') {
						$stockOut = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'damaged') {
						$stockDamaged = $row[0]['Quantity'];
					}
				}					
				$stockInHand = $stockIn - $stockOut - $stockDamaged;
				if($stockInHand < 0) {
					$stockInHand = 0;
				}
			}
		}
		return $stockInHand;
	} 
	
	
	/** 
	 * Function to return available stock for a product in godown
	 */
	function getStockInHandInGodown($categoryID) {
		$stockInHand = 0;
		if($categoryID > 0) {
			App::uses('StockMovement', 'Model');
			$this->StockMovement = new StockMovement;
			
			$stockMovementConditions = array('StockMovement.category_id'=>$categoryID, 'StockMovement.place'=>'godown');							
			
			$group = array('StockMovement.category_id', 'StockMovement.place', 'StockMovement.type');
			$fields = array('SUM(StockMovement.quantity) as Quantity', 'StockMovement.category_id', 'StockMovement.type');
			$stockResults = $this->StockMovement->find('all', array('conditions'=>$stockMovementConditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));			
			$stockInHand = 0;
			if(!empty($stockResults)) {
				$stockIn = 0;
				$stockOut = 0;
				$stockDamaged = 0;
				
				foreach($stockResults as $row) {
					if($row['StockMovement']['type'] == 'in') {
						$stockIn = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'out') {
						$stockOut = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'damaged') {
						$stockDamaged = $row[0]['Quantity'];
					}
				}					
				$stockInHand = $stockIn - $stockOut - $stockDamaged;
				if($stockInHand < 0) {
					$stockInHand = 0;
				}
			}
		}
		return $stockInHand;
	} 
	
	
	/** 
	 * Function to return stock info for a product from stock_movement table
	 */
	function getStockInfoFromStockMovement($categoryID, $conditions=array(), $place) {
		
		App::uses('StockMovement', 'Model');
		$this->StockMovement = new StockMovement;
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$stockInHand = 0;		
		$stockIn = 0;
		$stockOut = 0;
		$stockDamaged = 0;
		$closingStock = 0;
		
		if($categoryID > 0) {		
			
			$conditions[] =  array('StockMovement.category_id'=>$categoryID);				
			$conditions[] =  array('StockMovement.place'=>$place);				
			
			$group = array('StockMovement.category_id', 'StockMovement.place', 'StockMovement.type');
			$fields = array('SUM(StockMovement.quantity) as Quantity', 'StockMovement.category_id', 'StockMovement.type');
			$stockResults = $this->StockMovement->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));
			
			$stockInHand = 0;
			if(!empty($stockResults)) {				
				foreach($stockResults as $row) {
					if($row['StockMovement']['type'] == 'in') {
						$stockIn = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'out') {
						$stockOut = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'damaged') {
						$stockDamaged = $row[0]['Quantity'];
					}
				}					
				$stockInHand = $stockIn - $stockOut - $stockDamaged;
				if($stockInHand < 0) {
					$stockInHand = 0;
				}				
			}			

		}
		
		$stockInfo['stockIn'] = $stockIn;
		$stockInfo['stockOut'] = $stockOut;
		$stockInfo['stockDamaged'] = $stockDamaged;
		
			
		return $stockInfo;
	}
	
	/** 
	 * Function to return opening stock for a product from stock_movement table
	 */
	function getOpeningStockInfoFromStockMovement($categoryID, $place, $date) {
		
		App::uses('StockMovement', 'Model');
		$this->StockMovement = new StockMovement;
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
				
		$openingStock = 0;		
		
		if($categoryID > 0) {		
			
			$conditions[] =  array('StockMovement.category_id'=>$categoryID);	
			$conditions[] =  array('StockMovement.place'=>$place);	
			$conditions[] =  array('StockMovement.date <'=>$date);			
			
			$group = array('StockMovement.category_id', 'StockMovement.place', 'StockMovement.type');
			$fields = array('SUM(StockMovement.quantity) as Quantity', 'StockMovement.category_id', 'StockMovement.type');
			$stockResults = $this->StockMovement->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));			
			$openingStock = 0;
			if(!empty($stockResults)) {
				$stockIn = 0;
				$stockOut = 0;
				$stockDamaged = 0;
				
				foreach($stockResults as $row) {
					if($row['StockMovement']['type'] == 'in') {
						$stockIn = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'out') {
						$stockOut = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'damaged') {
						$stockDamaged = $row[0]['Quantity'];
					}
				}					
				$openingStock = $stockIn - $stockOut - $stockDamaged;
				if($openingStock < 0) {
					$openingStock = 0;
				}
			}			
		}
		return $openingStock;
	}
	
	/** 
	 * Function to return closing stock for a product from stock_movement table
	 */
	function getClosingStockInfoFromStockMovement($categoryID, $place, $date) {
		
		App::uses('StockMovement', 'Model');
		$this->StockMovement = new StockMovement;
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
				
		$closingStock = 0;		
		
		if($categoryID > 0) {		
			
			$conditions[] =  array('StockMovement.category_id'=>$categoryID);	
			$conditions[] =  array('StockMovement.place'=>$place);	
			$conditions[] =  array('StockMovement.date <='=>$date);			
			
			$group = array('StockMovement.category_id', 'StockMovement.place', 'StockMovement.type');
			$fields = array('SUM(StockMovement.quantity) as Quantity', 'StockMovement.category_id', 'StockMovement.type');
			$stockResults = $this->StockMovement->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));			
			$closingStock = 0;
			if(!empty($stockResults)) {
				$stockIn = 0;
				$stockOut = 0;
				$stockDamaged = 0;
				
				foreach($stockResults as $row) {
					if($row['StockMovement']['type'] == 'in') {
						$stockIn = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'out') {
						$stockOut = $row[0]['Quantity'];
					}
					elseif($row['StockMovement']['type'] == 'damaged') {
						$stockDamaged = $row[0]['Quantity'];
					}
				}					
				$closingStock = $stockIn - $stockOut - $stockDamaged;
				if($closingStock < 0) {
					$closingStock = 0;
				}
			}			
		}
		return $closingStock;
	}
	
	
	
}
?>
