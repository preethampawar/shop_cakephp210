<?php
App::uses('Sanitize', 'Utility');
class InvoicesController extends AppController {
    public $name = 'Invoices';
	
	public function checkInvoice($invoiceID) {
		$conditions = array('Invoice.id'=>$invoiceID, 'Invoice.company_id'=>$this->Session->read('Company.id'));
		$this->Invoice->bindModel(array('hasMany'=>array('Data'=>array('className'=>'Data', 'order'=>'Data.created ASC'))));
		$invoiceInfo = $this->Invoice->find('first', array('conditions'=>$conditions));
		return $invoiceInfo;
	}
	
	public function index() {
		$invoices = $this->Invoice->find('all', array('order'=>'Invoice.created DESC', 'conditions'=>array('Invoice.company_id'=>$this->Session->read('Company.id'))));
		$this->set(compact('invoices'));
	}
	
	public function details($invoiceID) {
		$this->layout='print';
		if(!($invoiceInfo = $this->checkInvoice($invoiceID))) {
			$this->Session->setFlash('Invoice not found', 'default', array('class'=>'error'));
			$this->redirect($this->request->referer());
		}
		
		$this->set(compact('invoiceInfo'));
	}
	
	public function add() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1', 'Category.is_product'=>'1');
		$categories = $this->Category->find('all', array('conditions'=>$conditions, 'order'=>'Category.name ASC', 'fields'=>array('Category.id', 'Category.name', 'Category.cost_price', 'Category.selling_price', 'Category.qty_per_case'), 'recursive'=>'-1'));
		// debug($categories);
		$errorMsg = array();
		if($this->request->isPost()) {
			$invoiceInfo['Invoice'] = $this->request->data['Invoice'];
			
			
			$invoiceInfo['Invoice']['invoice_date'] = $invoiceInfo['Invoice']['date'];
			unset($invoiceInfo['Invoice']['date']);
			
			if(empty($invoiceInfo['Invoice']['name'])) {
				$errorMsg[] = 'Invoice name cannot be empty';
			}
			if(empty($invoiceInfo['Invoice']['invoice_date'])) {
				$errorMsg[] = 'Invoice date cannot be empty';
			}
			
			App::uses('Invoice', 'Model');
			$this->Invoice = new Invoice;		
			$invoiceInfo['Invoice']['name'] = htmlentities($invoiceInfo['Invoice']['name'], ENT_QUOTES);				
			if($this->Invoice->findByName($invoiceInfo['Invoice']['name'])) {
				$errorMsg[] = 'Invoice already exists';
			}
				
			if(empty($errorMsg)) {
				$invoiceInfo['Invoice']['user_id'] = $this->Session->read('User.id');
				$invoiceInfo['Invoice']['company_id'] = $this->Session->read('Company.id');
				
				if($this->Invoice->save($invoiceInfo)) {
					$invoiceDetails = $this->Invoice->read();
					if(!empty($invoiceDetails)) {
						$this->Session->setFlash('Invoice successfully created', 'default', array('class'=>'success'));
						$this->redirect('/invoices/edit/'.$invoiceDetails['Invoice']['id']);
					}
					else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}	
			}
			
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('categories', 'errorMsg'));
	}
	
	public function edit($invoiceID) {
		if(!($invoiceInfo = $this->checkInvoice($invoiceID))) {
			$this->Session->setFlash('Invoice not found', 'default', array('class'=>'error'));
			$this->redirect($this->request->referer());
		}
		// debug($invoiceInfo);
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		App::uses('Data', 'Model');
		$this->Data = new Data;
		
		// Categories tree list
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1', 'Category.is_product NOT'=>'1');
		$categoriesTreeList = $this->Category->generateTreeList($conditions, null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		// Categories products list
		$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.show_in_purchases'=>'1', 'Category.is_product'=>'1');
		$categoryProducts = $this->Category->find('all', array('conditions'=>$conditions, 'order'=>'Category.name ASC'));
		$categoryProductsList = array();
		if(!empty($categoryProducts)) {
			foreach($categoryProducts as $row) {
				$categoryProductsList[$row['Category']['id']] = $row['Category']['name'];	
			}
		}
		
		$errorMsg = array();
		if($this->request->isPut()) {
			$data = $this->request->data;
			if($data['Form']['type'] == 'AddProduct') {
				$this->Session->write('PrevCategory', $data['Product']['category_id']);
			
				$categoryID = $data['Product']['category_id'];
				$categoryName = $data['Product']['name'];
				$qty_per_case = $data['Product']['qty_per_case'];
				$no_of_cases = $data['Product']['no_of_cases'];
				$price_per_case = $data['Product']['price_per_case'];
				$total_amount = $no_of_cases*$price_per_case;
								
				$productData = null;
				$productData['particular'] = $invoiceInfo['Invoice']['name'];									
				$productData['quantity'] = $no_of_cases*$qty_per_case;
				$productData['unitrate'] = ($price_per_case/$qty_per_case);
				$productData['date'] = $invoiceInfo['Invoice']['invoice_date'];
				$productData['category_id'] = $categoryID;
				$productData['company_id'] = $this->Session->read('Company.id');
				$productData['user_id'] = $this->Session->read('User.id');
				$productData['business_type'] = 'purchase';
				$productData['transaction_type'] = 'debit';
				$productData['category_name'] = $categoryName;
				$productData['user_name'] = $this->Session->read('User.name');
				$productData['company_name'] = $this->Session->read('Company.title');
				$productData['payment_method'] = 'cash';					
				$productData['pending_amount'] = null;
				$productData['total_amount'] = $total_amount;
				$productData['payment_amount'] = $total_amount;
				$productData['no_of_cases'] = $no_of_cases;
				$productData['price_per_case'] = $price_per_case;						
				$productData['invoice_id'] = $invoiceInfo['Invoice']['id'];
				$productData['invoice_name'] = $invoiceInfo['Invoice']['name'];
				
				$errorMsg = $this->validateData($productData);
				
				// check for duplicate entry
				$tmpConditions = array('Data.category_id'=>$productData['category_id'], 'Data.no_of_cases'=>$productData['no_of_cases'], 'Data.total_amount'=>$productData['total_amount'], 'Data.payment_amount'=>$productData['payment_amount'], 'Data.payment_method'=>$productData['payment_method'], 'Data.date'=>$productData['date'], 'Data.company_id'=>$productData['company_id'], 'Data.transaction_type'=>$productData['transaction_type']);
				if($duplicateRecord = $this->Data->find('first', array('conditions'=>$tmpConditions))) {						
					$errorMsg[] = 'Duplicate Entry. Record with this information already exists.';
				}	
				
				
				if(empty($errorMsg)) {
					if($dataID = $this->saveData($productData)) {
						$this->Session->setFlash('Product successfully added', 'default', array('class'=>'success'));
						$this->redirect($this->request->referer());
					}
					else {
						$errorMsg[] = 'Product could not be added';
					}
				}
				$tmpData['Invoice'] = $invoiceInfo['Invoice'];
				$tmpData['Data'] = $invoiceInfo['Data'];
				$tmpData['Product'] = $data['Product'];
				$this->data = $tmpData;
			}
			
			// Save Invoice Info
			if($data['Form']['type'] == 'EditInvoice') {
				$data = $this->request->data;
				$data['Invoice']['name'] = trim($data['Invoice']['name']);
				if(empty($data['Invoice']['name'])) {
					$errorMsg[] = 'Invoice no. cannot be empty';
				}				
				
				$data['Invoice']['name'] = htmlentities($data['Invoice']['name'], ENT_QUOTES);
				// check for duplicate entry
				$tmpConditions = array('Invoice.id NOT'=>$invoiceID, 'Invoice.name'=>$data['Invoice']['name']);
				if($duplicateRecord = $this->Invoice->find('first', array('conditions'=>$tmpConditions))) {						
					$errorMsg[] = 'Duplicate Entry. Invoice with this information already exists.';
				}
				
				if(empty($errorMsg)) {
					$data['Invoice']['id'] = $invoiceID;
					
					if($this->Invoice->save($data)) {
						if(!empty($invoiceInfo['Data'])) {
							App::uses('Data', 'Model');
							$this->Data = new Data;
							
							App::uses('Datalog', 'Model');
							$this->Datalog = new Datalog;
							
							foreach($invoiceInfo['Data'] as $row) {
								$tmpData = array();
								$tmpData['Data']['id'] = $row['id'];
								$tmpData['Data']['particular'] = $data['Invoice']['name'];
								$this->Data->save($tmpData);
								
								$tmpData = array();
								$tmpData['Datalog'] = $row;
								
								$tmpData['Datalog']['id'] = null;
								unset($tmpData['Datalog']['created']);
								unset($tmpData['Datalog']['modified']);
								$tmpData['Datalog']['particular'] = $data['Invoice']['name'];								
								
								$this->Datalog->save($tmpData);
							}
						}
					
						$this->Session->setFlash('Invoice information saved successfully', 'default', array('class'=>'success'));
						$this->redirect('/invoices/edit/'.$invoiceID);
					}
					else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}
				$tmpData['Invoice'] = $this->data['Invoice'];
				$tmpData['Data'] = $invoiceInfo['Data'];				
				$tmpData['Form'] = $this->data['Form'];				
				$this->data = $tmpData;
			}
		}
		else {
			$this->data = $invoiceInfo;
		}
		
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('invoiceInfo', 'categoriesTreeList', 'categoryProducts', 'categoryProductsList', 'errorMsg'));
	}

	public function deleteProduct($invoiceID, $dataID) {
		// check invoice info
		if(!($invoiceInfo = $this->checkInvoice($invoiceID))) {
			$this->Session->setFlash('Invoice not found', 'default', array('class'=>'error'));			
		}
		// check if product belongs to invoice
		if(!empty($invoiceInfo['Data'])) {
			$productFound = false;
			foreach($invoiceInfo['Data'] as $row) {								
				if($dataID == $row['id']) {
					$productFound = true;
					$this->deleteData($dataID);
					break;
				}
			}	
			if(!$productFound) {
				$this->Session->setFlash('Product not found', 'default', array('class'=>'error'));			
			}
			else {
				$this->Session->setFlash('Product successfully deleted', 'default', array('class'=>'success'));			
			}
		}
		else {
			$this->Session->setFlash('Product not found', 'default', array('class'=>'error'));
		}
		
		$this->redirect($this->request->referer());
	}	
}
