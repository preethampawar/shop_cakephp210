<?php
App::uses('Validation', 'Utility');
class BreakagesController extends AppController {

	var $name = 'Breakages';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->checkStoreInfo();
	}
		
	/**
	 * Function to show list of category products
	 */
	public function index() {		
		$conditions = array('Breakage.store_id'=>$this->Session->read('Store.id'));					
		$this->paginate = array(
							'conditions' => $conditions,
							'order' => array('Breakage.breakage_date' => 'DESC', 'Breakage.created' => 'DESC'),
							'limit' => 100,
							'recursive' => '-1'
							);
		$breakages = $this->paginate();
		
		$this->set(compact('breakages'));
    } 
	
	public function removeProduct($breakageID=null) {
		if($this->request->isPost()) {
			if($breakageInfo = $this->CommonFunctions->getBreakageInfo($breakageID)) {
				$this->Breakage->delete($breakageID);
				$this->Session->setFlash('"'.$breakageInfo['Breakage']['product_name'].'" removed from the list', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('Product not found');
			}
		}
		else {
				$this->Session->setFlash('Invalid request');		
		}		
		
		$this->redirect($this->request->referer());
	}
		
	/**
	 * Function to add closing stock
	 */
	public function addBreakageStock() {		
		$error = null;
		
		App::uses('Product', 'Model');
		$this->Product = new Product;
		$conditions = array('Product.store_id'=>$this->Session->read('Store.id'));
		
		$this->Product->unbindModel(array('belongsTo'=>array('ProductCategory')));
		$this->Product->bindModel(array('hasOne'=>array('ProductStockReport')));
		$productsInfo = $this->Product->find('all', array('conditions'=>$conditions, 'order'=>'Product.name', 'recursive'=>'1'));
		$productsList = $this->Product->find('list', array('conditions'=>$conditions, 'order'=>'Product.name', 'recursive'=>'-1'));
		
		if(!empty($productsInfo)) {
			foreach($productsInfo as $row) {
				$bal_qty = $row['ProductStockReport']['balance_qty'];
				$productsList[$row['Product']['id']] = $row['Product']['name'].' &nbsp;&nbsp;&nbsp;['.$bal_qty.']';
			}
		}
		
		
		if($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;			
			$breakageDate = $data['Breakage']['breakage_date']['year'].'-'.$data['Breakage']['breakage_date']['month'].'-'.$data['Breakage']['breakage_date']['day'];
			$data['Breakage']['breakage_date'] = $breakageDate;
			$this->Session->delete('selectedProductID');
			
			$error = $this->addBreakageStockFormValidation($data);
			// check if product is available
			if(!$error) {
				$this->Product->bindModel(array('belongsTo'=>array('ProductCategory')));				
				if(!$productInfo = $this->Product->findById($data['Breakage']['product_id'])) {
					$error = 'Product not found.';
				}
			}			
			
			// check if stock is available for the selected product
			if(!$error) {			
				App::uses('ProductStockReport', 'Model');
				$this->ProductStockReport = new ProductStockReport;
				$conditions = array('ProductStockReport.product_id'=>$data['Breakage']['product_id']);
				if($tmp = $this->ProductStockReport->find('first', array('conditions'=>$conditions))) {
					$bal_qty = $tmp['ProductStockReport']['balance_qty'];
					$input_qty = $data['Breakage']['total_units'];
					if($bal_qty<=0) {
						$error = '"'.$productInfo['Product']['name'].'" is out of stock';						
					}
					elseif($input_qty>$bal_qty) {
						$error = 'No. of Units cannot be greater than '.$bal_qty;											
					}					
				}
			}				
			
			if(!$error) {				
				$data['Breakage']['id'] = null;
				$data['Breakage']['product_code'] = $productInfo['Product']['product_code'];
				$data['Breakage']['product_category_id'] = $productInfo['ProductCategory']['id'];
				$data['Breakage']['store_id'] = $this->Session->read('Store.id');
				$data['Breakage']['breakage_date'] = $breakageDate;
				$data['Breakage']['product_name'] = $productInfo['Product']['name'];
				$data['Breakage']['category_name'] = $productInfo['ProductCategory']['name'];
				$data['Breakage']['store_name'] = $this->Session->read('Store.name');
				
				if($this->Breakage->save($data)) {
					$this->Session->write('selectedProductID', $productInfo['Product']['id']);
					$this->Session->write('breakageDate', $breakageDate);
					$msg = $productInfo['Product']['name'].' successfully added to Breakage list';
					$this->Session->setFlash($msg, 'default', array('class'=>'success'));
					$this->redirect(array('controller'=>'breakages', 'action'=>'addBreakageStock'));
				}
			}
		}
		else {
			if($this->Session->check('selectedProductID')) {
				$data['Breakage']['product_id'] = $this->Session->read('selectedProductID');
				$data['Breakage']['breakage_date'] = $this->Session->read('breakageDate');
				$this->data = $data;
			}
		}
		
		// find recent breakage(breakage stock) products
		$conditions = array('Breakage.store_id'=>$this->Session->read('Store.id'));
		$breakageProducts = $this->Breakage->find('all', array('conditions'=>$conditions, 'order'=>'Breakage.created DESC', 'recursive'=>'-1', 'limit'=>'10'));
		
		if($error) {$this->Session->setFlash($error);}
		$this->set(compact('productsInfo', 'productsList', 'breakageProducts'));
	}
	
	function addBreakageStockFormValidation($data=null) {
		$error = null;
		
		if($data) {
			if(!isset($data['Breakage']['product_id'])) {
				$error = 'Product not found';
			}
			if((!isset($data['Breakage']['total_units'])) OR ($data['Breakage']['total_units']<=0)) {
				$error = 'No. of Units should be greater than 0';
			}			
		}
		else {
			$error = 'Empty product details';					
		}
		return $error;
	}
	
	
	/**
	 * Function to show list of category products
	 */
	 public function viewBreakageStock() {		
		$conditions = array('Breakage.store_id'=>$this->Session->read('Store.id'));					
		$this->paginate = array(
							'conditions' => $conditions,
							'order' => array('Breakage.breakage_date' => 'DESC', 'Breakage.created' => 'DESC'),
							'limit' => 100,
							'recursive' => '-1'
							);
		$breakages = $this->paginate();
		
		$this->set(compact('breakages'));
    }
	
}
?>
