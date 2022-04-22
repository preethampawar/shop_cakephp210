<?php
class AvailableStockController extends AppController {

	var $name = 'AvailableStock';
		
	/**
	 * Function to list available stock
	 */
	public function index() {		
	 
		$conditions = array('AvailableStock.company_id'=>$this->Session->read('Company.id'));
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('AvailableStock.user_id'=>$this->Session->read('User.id'));		
		}	
		$this->paginate = array(
				'limit' => 50,
				'order' => array('AvailableStock.created' => 'desc'),
				'conditions' => $conditions,
				'recursive' => '0'
 			);
		$this->AvailableStock->unbindModel(array('belongsTo'=>array('Company')));
		$availableStock = $this->paginate();
		
		$this->set(compact('availableStock'));
    }	 
	
	/**
	 * Function to add inventory
	 */	
	function add($categoryID=null) {	
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		// $categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name ASC'));			
		
		
		// $allcategories = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id')), 'recursive'=>'-1'));
		
		// $categoryDetails = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), null, null, '');
	
		if(isset($this->request->data) and !empty($this->request->data) )
		{
			$errorMsg = null;
			$data['AvailableStock'] = $this->request->data['AvailableStock'];		
			
			// Validate data
			
			// check if category is selected
			if(!(isset($data['AvailableStock']['category_id']) and !empty($data['AvailableStock']['category_id'] ))) {
				$errorMsg[] = "Category not selected";
			}
			// check if valid available quantity entered
			if(!Validation::blank($data['AvailableStock']['available_quantity'])) {				
				if(!Validation::numeric($data['AvailableStock']['available_quantity'])) {
					$errorMsg[] = "Invalid Available Quantity Entered";
				}
				else {
					if($data['AvailableStock']['available_quantity'] < 0) {
						$errorMsg[] = "Available Quantity cannot be less than zero";
					}
				}
			}
			else {
				$errorMsg[] = 'Enter Available Quantity';
			}
			// check if valid sale quantity is entered
			if(!Validation::blank($data['AvailableStock']['quantity'])) {				
				if(!Validation::numeric($data['AvailableStock']['quantity'])) {
					$errorMsg[] = "Invalid Sale Qty. Entered";
				}
				else {
					if($data['AvailableStock']['quantity'] <= 0) {
						$errorMsg[] = "Sale Quantity cannot be less than zero";
					}
				}
			}
			else {
				$errorMsg[] = "Sale Quantity cannot be less than zero";
			}
			// check if valid stock in hand entered
			if(!Validation::blank($data['AvailableStock']['stockinhand'])) {				
				if(!Validation::numeric($data['AvailableStock']['stockinhand'])) {
					$errorMsg[] = "Invalid stock in hand value";
				}
				else {
					if($data['AvailableStock']['stockinhand'] <= 0) {
						$errorMsg[] = "Product is out of stock";
					}
				}
			}
			else {
				$errorMsg[] = "Product is out of stock";
			}
			// check stock in hand for the selected category/product
			$stockInHand = $this->getStockInHand($data['AvailableStock']['category_id']);
			if($stockInHand != $data['AvailableStock']['stockinhand']) {
				$errorMsg[] = 'Invalid stock in hand value. Actual stock in hand = '.$stockInHand;
			}			
			
			// check if valid total amount entered
			if(empty($data['AvailableStock']['total_amount'])) {
				$errorMsg[] = "Total Amount cannot be zero.";
			}	
			elseif(!(Validation::decimal($data['AvailableStock']['total_amount'])) and (!Validation::numeric($data['AvailableStock']['total_amount']))) {
				$errorMsg[] = "Invalid Total Amount";
			}
			// check if valid unit rate entered
			if(empty($data['AvailableStock']['unitrate'])) {
				$errorMsg[] = "Unit Price of product is not defined.";
			}	
			elseif(!(Validation::decimal($data['AvailableStock']['unitrate'])) and (!Validation::numeric($data['AvailableStock']['unitrate']))) {
				$errorMsg[] = "Invalid Unit Price";
			}
			// check if valid total payment amount entered
			if(!Validation::blank($data['AvailableStock']['payment_amount'])) {
				if(!(Validation::decimal($data['AvailableStock']['payment_amount'])) and (!Validation::numeric($data['AvailableStock']['payment_amount']))) {
					$errorMsg[] = "Invalid Received Amount";
				}
				else {
					if($data['AvailableStock']['total_amount'] < $data['AvailableStock']['payment_amount']) {
						$errorMsg[] = "Total Amount cannot be less than Received Amount";
					}
				}
			}
			// check if valid total amount entered
			if(empty($data['AvailableStock']['date'])) {
				$errorMsg[] = "Date field cannot be empty";
			}
			
			
			if(empty($errorMsg)) {
				$this->Session->write('PrevDate', $data['AvailableStock']['date']);		
				$this->Session->write('PrevCategory', $data['AvailableStock']['category_id']);
				
				$data['AvailableStock']['id'] = null;	
				$data['AvailableStock']['company_id'] = $this->Session->read('Company.id');
				$data['AvailableStock']['user_id'] = $this->Session->read('User.id');				
				
				$record = $data['AvailableStock'];
				$record['particular'] = 'By Available Stock';
				$record['payment_method'] = 'cash';
				$record['business_type'] = 'sale';
				$record['transaction_type'] = 'credit';
				$record['category_name'] = $categories[$record['category_id']];
				$record['user_name'] = $this->Session->read('User.name');
				$record['company_name'] = $this->Session->read('Company.title');
				$record['pending_amount'] = $record['total_amount']-$record['payment_amount'];
				
				unset($record['available_quantity']);
				unset($record['stockinhand']);
				unset($record['id']);
				
				$errorMsg = $this->validateData($record);
				if(empty($errorMsg)) {					
					if($dataID = $this->saveData($record)) {
						$data['AvailableStock']['data_id'] = $dataID;
						if($this->AvailableStock->save($data)) {							
							$this->Session->setFlash('Stock updated successfully', 'default', array('class'=>'success'));
							$this->redirect('/available_stock/add');
						}
						else {
							$this->deleteData($dataID);
							$errorMsg[] = 'An error occured while communicating with the server. Please try again later.';
						}
					}
					else {
						$errorMsg[] = 'An error occured while communicating with the server. Please try again later.';
					}
				}					
			}
		}	
			
		if(!empty($errorMsg)) {
			$errorMsg = implode('<br>', $errorMsg);
		}
		
		// get stock in hand for each category
		$stockInfo = array();
		if(!empty($categories)) {
			foreach($categories as $categoryID=>$row) {
				$stockInHand = $this->getStockInHand($categoryID);
				$categories[$categoryID]=$row.' ['.$stockInHand.']';
			}
		}
		
		// get last 5 added records.
		$conditions = array('AvailableStock.company_id'=>$this->Session->read('Company.id'));
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('AvailableStock.user_id'=>$this->Session->read('User.id'));		
		}
		
		$this->AvailableStock->unbindModel(array('belongsTo'=>array('Company')));
		$availableStock = $this->AvailableStock->find('all', array('conditions'=>$conditions, 'order'=>array('AvailableStock.created'=>'desc'), 'limit'=>5));
		
		$this->set(compact('categoryID', 'errorMsg', 'categories', 'availableStock', 'allcategories'));	
	}
	
	/**
	 * Function to delete available stock record
	 */	
	function delete($availableStockID = null) {
		if(!$availableStockID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
		}
		else {
			if(!($availableStockInfo = $this->AvailableStock->find('first', array('conditions'=>array('AvailableStock.id'=>$availableStockID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			}
			else {
				$this->deleteData($availableStockInfo['AvailableStock']['data_id']);
				$this->AvailableStock->delete($availableStockID);
				$this->Session->setFlash('Product stock has been removed successfully', 'default', array('class'=>'success'));				
			}
		}
		$this->redirect($this->request->referer());
	}	
	
}
?>
