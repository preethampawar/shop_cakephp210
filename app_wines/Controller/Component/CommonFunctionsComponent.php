<?php
App::uses('Component', 'Controller');
class CommonFunctionsComponent extends Component {
    var $components = array('Session', 'Auth');

	/** Function to get Product Category info **/
	public function getProductCategoryInfo($productCategoryID=null) {
		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory;

		if(!$productCategoryID) {
			return array();
		}
		else {
			$conditions = array('ProductCategory.id'=>$productCategoryID, 'ProductCategory.store_id'=>$this->Session->read('Store.id'));
			if($productCategoryInfo = $this->ProductCategory->find('first', array('conditions'=>$conditions))) {
				return $productCategoryInfo;
			}
		}
		return array();
	}

	/** Function to get Product info **/
	public function getProductInfo($productID=null, $categoryID=null) {
		App::uses('Product', 'Model');
		$this->Product = new Product;

		if(!$productID) {
			return array();
		}
		else {
			if($categoryID) {
				$conditions = array('Product.id'=>$productID, 'Product.product_category_id'=>$categoryID, 'Product.store_id'=>$this->Session->read('Store.id'));
			}
			else {
				$conditions = array('Product.id'=>$productID, 'Product.store_id'=>$this->Session->read('Store.id'));
			}

			$this->Product->bindModel(array('belongsTo'=>array('Brand')));
			if($productInfo = $this->Product->find('first', array('conditions'=>$conditions))) {
				return $productInfo;
			}
		}
		return array();
	}

	/** Function to get Purchase info **/
	public function getPurchaseInfo($purchaseID=null) {
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase;

		if(!$purchaseID) {
			return array();
		}
		else {
			$conditions = array('Purchase.id'=>$purchaseID, 'Purchase.store_id'=>$this->Session->read('Store.id'));
			if($purchaseInfo = $this->Purchase->find('first', array('conditions'=>$conditions))) {
				return $purchaseInfo;
			}
		}
		return array();
	}

	/** Function to get Sale info **/
	public function getSaleInfo($saleID=null) {
		App::uses('Sale', 'Model');
		$this->Sale = new Sale;

		if(!$saleID) {
			return array();
		}
		else {
			$conditions = array('Sale.id'=>$saleID, 'Sale.store_id'=>$this->Session->read('Store.id'));
			if($saleInfo = $this->Sale->find('first', array('conditions'=>$conditions))) {
				return $saleInfo;
			}
		}
		return array();
	}

	/** Function to get Breakage info **/
	public function getBreakageInfo($breakageID=null) {
		App::uses('Breakage', 'Model');
		$this->Breakage = new Breakage;

		if(!$breakageID) {
			return array();
		}
		else {
			$conditions = array('Breakage.id'=>$breakageID, 'Breakage.store_id'=>$this->Session->read('Store.id'));
			if($breakageInfo = $this->Breakage->find('first', array('conditions'=>$conditions))) {
				return $breakageInfo;
			}
		}
		return array();
	}

	/** Function to get Employee info **/
	public function getEmployeeInfo($employeeID=null) {
		App::uses('Employee', 'Model');
		$this->Employee = new Employee;

		if(!$employeeID) {
			return array();
		}
		else {
			$conditions = array('Employee.id'=>$employeeID, 'Employee.store_id'=>$this->Session->read('Store.id'));
			if($employeeInfo = $this->Employee->find('first', array('conditions'=>$conditions))) {
				return $employeeInfo;
			}
		}
		return array();
	}

	/** Function to get Category info **/
	public function getCategoryInfo($categoryID=null) {
		App::uses('Category', 'Model');
		$this->Category = new Category;

		if(!$categoryID) {
			return array();
		}
		else {
			$conditions = array('Category.id'=>$categoryID, 'Category.store_id'=>$this->Session->read('Store.id'));
			if($categoryInfo = $this->Category->find('first', array('conditions'=>$conditions))) {
				return $categoryInfo;
			}
		}
		return array();
	}

	/** Function to get Dd info **/
	public function getDdInfo($ddID=null) {
		App::uses('Dd', 'Model');
		$this->Dd = new Dd;

		if(!$ddID) {
			return array();
		}
		else {
			$conditions = array('Dd.id'=>$ddID, 'Dd.store_id'=>$this->Session->read('Store.id'));
			if($categoryInfo = $this->Dd->find('first', array('conditions'=>$conditions))) {
				return $categoryInfo;
			}
		}
		return array();
	}

	/** Function to remove category and its products from the list */
	public function removeProductCategory($categoryID) {
		if(!$this->getProductCategoryInfo($categoryID)) {
			return false;
		}

		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory;
		// remove category info
		$this->ProductCategory->delete($categoryID);

		// App::uses('Product', 'Model');
		// $this->Product = new Product;
		// // remove category products
		// $conditions = array('Product.product_category_id'=>$categoryID, 'Product.store_id'=>$this->Session->read('Store.id'));
		// $this->Product->deleteAll($conditions);

		return true;
	}

	/** Function to delete category and its products from the list, sales and purchases */
	public function deleteProductCategory($categoryID) {
		if(!$this->getProductCategoryInfo($categoryID)) {
			return false;
		}

		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory;

		App::uses('Product', 'Model');
		$this->Product = new Product;

		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase;

		App::uses('Sale', 'Model');
		$this->Sale = new Sale;

		// remove category products
		$conditions = array('Product.product_category_id'=>$categoryID, 'Product.store_id'=>$this->Session->read('Store.id'));
		$this->Product->deleteAll($conditions);

		// remove from purchases
		$conditions = array('Purchase.product_category_id'=>$categoryID, 'Purchase.store_id'=>$this->Session->read('Store.id'));
		$this->Purchase->deleteAll($conditions);

		// remove from sales
		$conditions = array('Sale.product_category_id'=>$categoryID, 'Sale.store_id'=>$this->Session->read('Store.id'));
		$this->Sale->deleteAll($conditions);

		// remove category info
		$this->ProductCategory->delete($categoryID);

		return true;
	}

	/** Function to delete products sales and purchases and product info */
	public function deleteProduct($productID) {
		if(!$this->getProductInfo($productID)) {
			return false;
		}

		App::uses('Product', 'Model');
		$this->Product = new Product;

		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase;

		App::uses('Sale', 'Model');
		$this->Sale = new Sale;

		App::uses('Breakage', 'Model');
		$this->Breakage = new Breakage;


		// remove from purchases
		$conditions = array('Purchase.product_id'=>$productID, 'Purchase.store_id'=>$this->Session->read('Store.id'));
		$this->Purchase->deleteAll($conditions);

		// remove from sales
		$conditions = array('Sale.product_id'=>$productID, 'Sale.store_id'=>$this->Session->read('Store.id'));
		$this->Sale->deleteAll($conditions);

		// remove from breakages
		$conditions = array('Breakage.product_id'=>$productID, 'Breakage.store_id'=>$this->Session->read('Store.id'));
		$this->Breakage->deleteAll($conditions);

		// remove product info
		$this->Product->delete($productID);

		return true;
	}

	/** Function to get Dealer info **/
	public function getDealerInfo($dealerID=null) {
		App::uses('Dealer', 'Model');
		$this->Dealer = new Dealer;

		if(!$dealerID) {
			return array();
		}
		else {
			$conditions = array('Dealer.id'=>$dealerID, 'Dealer.store_id'=>$this->Session->read('Store.id'));
			if($dealerInfo = $this->Dealer->find('first', array('conditions'=>$conditions))) {
				return $dealerInfo;
			}
		}
		return array();
	}

	/** Function to get Brand info **/
	public function getBrandInfo($dealerID=null) {
		App::uses('Brand', 'Model');
		$this->Brand = new Brand;

		if(!$dealerID) {
			return array();
		}
		else {
			$conditions = array('Brand.id'=>$dealerID, 'Brand.store_id'=>$this->Session->read('Store.id'));
			if($brandInfo = $this->Brand->find('first', array('conditions'=>$conditions))) {
				return $brandInfo;
			}
		}
		return array();
	}


	/** Function to get Transaction Category info **/
	public function getTransactionCategoryInfo($transactionCategoryId = null)
	{
		App::uses('TransactionCategory', 'Model');
		$this->TransactionCategory = new TransactionCategory();

		if (!$transactionCategoryId) {
			return [];
		} else {
			$conditions = ['TransactionCategory.id' => $transactionCategoryId, 'TransactionCategory.store_id' => $this->Session->read('Store.id')];
			if ($categoryInfo = $this->TransactionCategory->find('first', ['conditions' => $conditions])) {
				return $categoryInfo;
			}
		}
		return [];
	}

}
