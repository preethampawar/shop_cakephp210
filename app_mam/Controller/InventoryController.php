<?php
class InventoryController extends AppController {

	var $name = 'Inventory';
		
	/**
	 * Function to list inventory
	 */
	public function index() {		
	 
		$conditions = array('Inventory.company_id'=>$this->Session->read('Company.id'));
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));		
		}	
		$this->paginate = array(
				'limit' => 100,
				'order' => array('Inventory.created' => 'desc'),
				'conditions' => $conditions,
				'recursive' => '0'
 			);
		$this->Inventory->unbindModel(array('belongsTo'=>array('Company')));
		$inventory = $this->paginate();
		
		$this->set(compact('inventory'));
    }	 
		
	/**
	 * Function to list stock movement
	 */
	public function showMovedStock() {		
		App::uses('StockMovement', 'Model');
		$this->StockMovement = new StockMovement;
		
		$conditions = array('StockMovement.company_id'=>$this->Session->read('Company.id'));
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('StockMovement.user_id'=>$this->Session->read('User.id'));		
		}	
		$this->paginate = array(
				'limit' => 50,
				'order' => array('StockMovement.created' => 'desc', 'StockMovement.place' => 'desc', 'StockMovement.type' => 'desc'),
				'conditions' => $conditions,
				'recursive' => '0'
 			);
		
		$this->StockMovement->unbindModel(array('belongsTo'=>array('Company')));
		$stockMovement = $this->paginate('StockMovement');
		
		$this->set(compact('stockMovement'));
    }	 
	
	/**
	 * Function to add inventory
	 */	
	function add($categoryID=null) {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		if(isset($this->request->data) and !empty($this->request->data) )
		{		
			$errorMsg = null;
			$data['Inventory'] = $this->request->data['Inventory'];			
			
			if(!Validation::blank($data['Inventory']['quantity'])) {
				if(!Validation::numeric($data['Inventory']['quantity'])) {
					$errorMsg = "Invalid Quantity Entered";
				}
				else {
					if($data['Inventory']['quantity'] <= 0) {
						$errorMsg = "Quantity cannot be less than zero";
					}
				}
			}
			else {
				$errorMsg = 'Enter Quantity';
			}
			
			if(!Validation::blank($data['Inventory']['unitrate'])) {
				if($data['Inventory']['unitrate'] <= 0) {
					$errorMsg = "Unit rate cannot be less than or equal to zero";
				}
			}
			else {
				$errorMsg = 'Enter unit rate';
			}
			
			if(!$errorMsg) {
				$this->Session->write('PrevDate', $data['Inventory']['date']);		
				$this->Session->write('PrevCategory', $data['Inventory']['category_id']);
				
				// get category info
				$selectedCategoryInfo = $this->Category->find('first', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1', 'Category.id'=>$data['Inventory']['category_id']));
				
				if(!empty($selectedCategoryInfo)) {
					$total_amount = $data['Inventory']['quantity']*$data['Inventory']['unitrate'];
				
					$tmpData = null;
					$tmpData['particular'] = 'Opening Stock';									
					$tmpData['quantity'] = $data['Inventory']['quantity'];
					$tmpData['unitrate'] = $data['Inventory']['unitrate'];
					$tmpData['date'] = $data['Inventory']['date'];
					$tmpData['category_id'] = $data['Inventory']['category_id'];
					$tmpData['company_id'] = $this->Session->read('Company.id');
					$tmpData['user_id'] = $this->Session->read('User.id');
					$tmpData['business_type'] = 'purchase';
					$tmpData['transaction_type'] = 'debit';
					$tmpData['category_name'] = $selectedCategoryInfo['Category']['name'];
					$tmpData['user_name'] = $this->Session->read('User.name');
					$tmpData['company_name'] = $this->Session->read('Company.title');
					$tmpData['payment_method'] = 'cash';					
					$tmpData['pending_amount'] = null;
					$tmpData['total_amount'] = $total_amount;
					$tmpData['payment_amount'] = $total_amount;
					$tmpData['no_of_cases'] = null;
					$tmpData['price_per_case'] = null;						
					$tmpData['invoice_id'] = null;
					$tmpData['invoice_name'] = null;
					
					$errorMsg = $this->validateData($tmpData);
					if(empty($errorMsg)) {
						if($dataID = $this->saveData($tmpData)) {
							$this->Session->setFlash($tmpData['category_name'].'['.$tmpData['quantity'].']'.' - Stock Added Successfully', 'default', array('class'=>'success'));
							$this->redirect('/inventory/');	
						}
						else {
							$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'success'));
							$this->redirect('/inventory/');	
						}
					}
				}
				else {
					$errorMsg = 'Product information not found';
				}
				
				// $data['Inventory']['id'] = null;	
				// $data['Inventory']['company_id'] = $this->Session->read('Company.id');
				// $data['Inventory']['user_id'] = $this->Session->read('User.id');
				// $data['Inventory']['category_id'] = $data['Inventory']['category_id'];
				
				// if($this->Inventory->save($data))
				// {
					// $this->Session->setFlash('Stock Added Successfully', 'default', array('class'=>'success'));
					// // $this->redirect('/inventory/');
				// }
				// else
				// {
					// $this->set('errorMsg', 'An error occured while adding stock');
				// }
			}
		}	
		
		
		// $categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');		
		$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name ASC'));		
		
		
		
		// Categories products list
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1');
		$categoryProducts = $this->Category->find('all', array('conditions'=>$conditions, 'order'=>'Category.name ASC', 'recursive'=>'-1'));
		$categoryProductsList = array();
		if(!empty($categoryProducts)) {
			foreach($categoryProducts as $row) {
				$categoryProductsList[$row['Category']['id']] = $row['Category']['name'];	
			}
		}
		
		
		$allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));
				
		$this->set(compact('categoryID', 'errorMsg', 'categories', 'allcategories', 'categoryProductsList', 'categoryProducts'));	
	}
	
	/**
	 * Function to edit stock
	 */	
	function edit($inventoryID=null) {	
		$errorMsg = null;
		if(!$inventoryID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
		}
		else {
			if(!($inventory = $this->Inventory->find('first', array('conditions'=>array('Inventory.id'=>$inventoryID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			}
		}
	
		if(isset($this->request->data) and !empty($this->request->data) )
		{
			$error = null;
			$data['Inventory'] = $this->request->data['Inventory'];			
			
			if(!Validation::blank($data['Inventory']['quantity'])) {
				if(!Validation::numeric($data['Inventory']['quantity'])) {
					$errorMsg = "Invalid Quantity Entered";
				}
				else {
					if($data['Inventory']['quantity'] <= 0) {
						$errorMsg = "Quantity cannot be less than zero";
					}
				}
			}
			else {
				$errorMsg = 'Enter Quantity';
			}
			
			if(!$errorMsg) {
				$data['Inventory']['id'] = $inventoryID;	
				$data['Inventory']['category_id'] = $data['Inventory']['category_id'];
				
				if($this->Inventory->save($data))
				{
					$this->Session->setFlash('Changes Saved Successfully', 'default', array('class'=>'success'));
					$this->redirect('/inventory/');
				}
				else
				{
					$this->set('errorMsg', 'An error occured while updating stock');
				}
			}
		}
		else {
			$this->data = $inventory;
		}
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name ASC'));	
		// $categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');		
		$allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));
		$this->set('categories', $categories);		
		
		$this->set(compact('inventoryID', 'errorMsg', 'categories', 'inventory', 'allcategories'));	
	}
	
	/**
	 * Function to delete stock
	 */	
	function delete($inventoryID = null) {
		if(!$inventoryID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
		}
		else {
			if(!($inventoryInfo = $this->Inventory->find('first', array('conditions'=>array('Inventory.id'=>$inventoryID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			}
			else {
				if($this->Inventory->delete($inventoryID)) {
					$this->Session->setFlash('Category / Product stock has been removed successfully', 'default', array('class'=>'success'));
				}
			}
		}
		$this->redirect('/inventory/');
	}	
	
	
	/**
	 * Function to show damaged stock
	 */
	public function showDamagedStock() {		
	 
		$conditions = array('Inventory.company_id'=>$this->Session->read('Company.id'), 'Inventory.type'=>'damaged');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));		
		}	
		$this->paginate = array(
				'limit' => 50,
				'order' => array('Inventory.date' => 'desc'),
				'conditions' => $conditions,
				'recursive' => '0'
 			);
		$this->Inventory->unbindModel(array('belongsTo'=>array('Company')));
		$inventory = $this->paginate();		
		$this->set(compact('inventory'));
    }	 
	
	/**
	 * Function to add damaged stock
	 */
	public function addDamagedStock() {	
	
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_cash'=>'1', 'Category.is_product'=>'1');
		// $categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categories = $this->Category->find('list', array('conditions'=>$conditions, 'order'=>'Category.name ASC'));	
		
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
		$allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));
				
		$successMsg = null;
		$errorMsg = array();
		if($this->request->is('post')) {
			$data = $this->request->data['Cash'];				
			$data['payment_method'] = 'Cash';
			$data['payment_amount'] = $data['total_amount'];
			$data['transaction_type'] = 'debit';
			$data['particular'] = 'By Damaged Stock';
			$data['date'] = date('Y-m-d');
			
			$errorMsg = $this->validateData($data);		

			// check if stock is available for the product
			$availableStock = $this->getStockInHand($data['category_id']);				
			if($availableStock > 0) {
				if($data['quantity'] > $availableStock) {
					$errorMsg[] = 'Quantity cannot be greater than '.$availableStock;		
				}
			}
			else {
				$errorMsg[] = 'Product out of stock.';
			}
			// end of check stock	
							
			if(empty($errorMsg)) {
				$data['company_id'] = $this->Session->read('Company.id');
				$data['company_name'] = $this->Session->read('Company.title');
				
				$data['user_id'] = $this->Session->read('User.id');
				$data['user_name'] = $this->Session->read('User.name');
				
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				
				$data['business_type'] = 'cash';
				
				
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];		
				
				$damagedStock = true;
				if($dataID = $this->saveData($data, $damagedStock)) {			
					$successMsg = 'Record Created Successfully';
					$this->Session->setFlash('Record Created Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'showDamagedStock'));
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
		
		// get stock in hand for each category
		$stockInfo = array();
		if(!empty($categories)) {
			foreach($categories as $categoryID=>$row) {
				$stockInHand = $this->getStockInHand($categoryID);
				$categories[$categoryID]=$row.'&nbsp; ['.$stockInHand.']';
			}
		}	
		
		
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'allcategories'));	
	}
	
	/**
	 * Function to edit damaged stock
	 */
	public function editDamagedStock($inventoryID, $dataID) {	
	
		App::uses('Cash', 'Model');
		$this->Cash = new Cash;
		$cashInfo = $this->Cash->findById($dataID);
		if(empty($cashInfo)) {
			$this->Session->setFlash('Record not found', 'default', array('class'=>'error'));
			$this->redirect('/inventory/showDamagedStock');
		}		
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_cash'=>'1');
		$categories = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesInfo = $this->Category->generateTreeList($conditions, null, null, '');
		$allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));
				
		$successMsg = null;
		$errorMsg = array();
		if($this->request->is('put')) {
			$data = $this->request->data['Cash'];		
			$data['id'] = $dataID;	
			$data['payment_method'] = 'Cash';
			$data['payment_amount'] = $data['total_amount'];
			$data['transaction_type'] = 'debit';
			$data['particular'] = 'By Damaged Stock';
			
			$errorMsg = $this->validateData($data);			
				
			// check if stock is available for the product
			$availableStock = $this->getStockInHand($data['category_id'], array('toDate'=>$data['date']));				
			if($availableStock > 0) {
				if($data['quantity'] > $availableStock) {
					$errorMsg[] = 'Quantity cannot be greater than '.$availableStock;		
				}
			}
			else {
				$date = date('d-M-Y', strtotime($data['date']));
				$errorMsg[] = 'Product out of stock as on '.$date;
			}
			// end of check stock		
				
			if(empty($errorMsg)) {				
				$data['category_name'] = $categoriesInfo[$data['category_id']];
				$data['pending_amount'] = $data['total_amount']-$data['payment_amount'];		
				
				$damagedStock = true;
				if($dataID = $this->saveData($data, $damagedStock)) {			
					$successMsg = 'Record Created Successfully';
					$this->Session->setFlash('Record Created Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'showDamagedStock'));
				}
				else {
					$errorMsg[] = 'An error occured while communicating with the server';
					// $this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
				}
			}			
		}
		else {
			$this->data = $cashInfo;
		}
		$dataID = null;
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		$this->set(compact('categories', 'errorMsg', 'successMsg', 'dataID', 'allcategories'));	
	}
	
	/**
	 * Function to delete stock
	 */	
	function deleteDamagedStock($inventoryID = null, $dataID = null) {
		if(!$inventoryID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
		}
		else {
			if(!($inventoryInfo = $this->Inventory->find('first', array('conditions'=>array('Inventory.id'=>$inventoryID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			}
			else {
				$this->Inventory->delete($inventoryID);
				$this->deleteData($dataID);					
				$this->Session->setFlash('Category / Product stock record has been removed successfully', 'default', array('class'=>'success'));
			}
			
		}
		$this->redirect('/inventory/showDamagedStock');
	}	
	
	
	/**
	 * Function to delete stock movement data
	 */	
	function deleteMovedStock($stockMovementID = null) {
	
		App::uses('StockMovement', 'Model');
		$this->StockMovement = new StockMovement;
	
		if(empty($stockMovementID)) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
		}
		else {
			if(!($inventoryInfo = $this->StockMovement->find('first', array('conditions'=>array('StockMovement.id'=>$stockMovementID, 'StockMovement.company_id'=>$this->Session->read('Company.id')))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			}
			else {
				$this->StockMovement->delete($stockMovementID);
				
				$conditions = array('StockMovement.reference_id'=>$stockMovementID);
				$this->StockMovement->deleteAll($conditions);				
				
				$this->Session->setFlash('Record has been removed successfully', 'default', array('class'=>'success'));
			}
			
		}
		$this->redirect($this->request->referer());
	}	
	
	
	/**
	 * Function to move stock to shop from godown
	 */	
	function moveStockToShop($categoryID=null) {		
		if(isset($this->request->data) and !empty($this->request->data))
		{
			App::uses('StockMovement', 'Model');
			$this->StockMovement = new StockMovement;
		
			$errorMsg = null;
			$data['Inventory'] = $this->request->data['Inventory'];			
			
			if(!Validation::blank($data['Inventory']['quantity'])) {
				if(!Validation::numeric($data['Inventory']['quantity'])) {
					$errorMsg = "Invalid Quantity Entered";
				}
				else {
					if($data['Inventory']['quantity'] <= 0) {
						$errorMsg = "Quantity cannot be less than zero";
					}
					else {
						$godownStock = $this->getStockInHandInGodown($data['Inventory']['category_id']);
						if($godownStock > 0) {
							if($data['Inventory']['quantity'] > $godownStock) {
								$errorMsg = "Quantity cannot be greater than ".$godownStock;
							}
						}
						else {
							$errorMsg = "Product is out of stock";
						}
					}
				}
			}
			else {
				$errorMsg = 'Enter Quantity';
			}
			
			
			if(!$errorMsg) {
				$data['Inventory']['id'] = null;	
				$data['Inventory']['company_id'] = $this->Session->read('Company.id');
				$data['Inventory']['user_id'] = $this->Session->read('User.id');
				$data['Inventory']['category_id'] = $data['Inventory']['category_id'];
				
				$tmp1['StockMovement'] = $data['Inventory'];
				$tmp2['StockMovement'] = $data['Inventory'];
				
				// Move stock out of godown
				$tmp1['StockMovement']['type'] = 'out';
				$tmp1['StockMovement']['place'] = 'godown';
				$tmp1['StockMovement']['date'] = date('Y-m-d');				
				$tmp1['StockMovement']['message'] = 'Manual';				
				if($this->StockMovement->save($tmp1)) {
					$stockOutInfo = $this->StockMovement->read();
					
					// Move stock in to shop
					$tmp2['StockMovement']['reference_id'] = $stockOutInfo['StockMovement']['id'];
					$tmp2['StockMovement']['type'] = 'in';
					$tmp2['StockMovement']['place'] = 'shop';
					$tmp2['StockMovement']['date'] = date('Y-m-d');	
					$tmp2['StockMovement']['message'] = 'Manual';					
					if($this->StockMovement->save($tmp2)) {
						$this->Session->setFlash('Stock Moved To Shop', 'default', array('class'=>'success'));
						$this->redirect('/inventory/moveStockToShop');
					}
					else {
						$this->StockMovement->delete($stockOutInfo['StockMovement']['id']);
						$this->set('errorMsg', 'An error occured while moving stock in to shop');
					}						
				}
				else {
					$this->set('errorMsg', 'An error occured while moving stock from godown');
				}
				
			}
		}	
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');		
		$allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));
		if(!empty($allcategories)) {
			foreach($allcategories as $index=>$row) {
				$godownStock = $this->getStockInHandInGodown($row['Category']['id']);
				$shopStock = $this->getStockInHandInShop($row['Category']['id']);
				
				$allcategories[$index]['Category']['stock_in_godown'] = $godownStock;
				$allcategories[$index]['Category']['stock_in_shop'] = $shopStock;
			}
		}				
		$this->set(compact('categoryID', 'errorMsg', 'categories', 'allcategories'));	
	}
	
}
?>
