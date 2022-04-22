<?php
App::uses('Component', 'Controller');

class CommonFunctionsComponent extends Component
{
	public $components = ['Session', 'Auth'];

	/** Function to get Purchase info **/
	public function getPurchaseInfo($purchaseID = null)
	{
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase();

		if (!$purchaseID) {
			return [];
		} else {
			$conditions = ['Purchase.id' => $purchaseID, 'Purchase.store_id' => $this->Session->read('Store.id')];
			if ($purchaseInfo = $this->Purchase->find('first', ['conditions' => $conditions])) {
				return $purchaseInfo;
			}
		}
		return [];
	}

	/** Function to get Sale info **/
	public function getSaleInfo($saleID = null)
	{
		App::uses('Sale', 'Model');
		$this->Sale = new Sale();

		if (!$saleID) {
			return [];
		} else {
			$conditions = ['Sale.id' => $saleID, 'Sale.store_id' => $this->Session->read('Store.id')];
			if ($saleInfo = $this->Sale->find('first', ['conditions' => $conditions])) {
				return $saleInfo;
			}
		}
		return [];
	}

	/** Function to get Breakage info **/
	public function getBreakageInfo($breakageID = null)
	{
		App::uses('Breakage', 'Model');
		$this->Breakage = new Breakage();

		if (!$breakageID) {
			return [];
		} else {
			$conditions = ['Breakage.id' => $breakageID, 'Breakage.store_id' => $this->Session->read('Store.id')];
			if ($breakageInfo = $this->Breakage->find('first', ['conditions' => $conditions])) {
				return $breakageInfo;
			}
		}
		return [];
	}

	/** Function to get Employee info **/
	public function getEmployeeInfo($employeeID = null)
	{
		App::uses('Employee', 'Model');
		$this->Employee = new Employee();

		if (!$employeeID) {
			return [];
		} else {
			$conditions = ['Employee.id' => $employeeID, 'Employee.store_id' => $this->Session->read('Store.id')];
			if ($employeeInfo = $this->Employee->find('first', ['conditions' => $conditions])) {
				return $employeeInfo;
			}
		}
		return [];
	}

	/** Function to get Category info **/
	public function getCategoryInfo($categoryID = null)
	{
		App::uses('Category', 'Model');
		$this->Category = new Category();

		if (!$categoryID) {
			return [];
		} else {
			$conditions = ['Category.id' => $categoryID, 'Category.store_id' => $this->Session->read('Store.id')];
			if ($categoryInfo = $this->Category->find('first', ['conditions' => $conditions])) {
				return $categoryInfo;
			}
		}
		return [];
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

	/** Function to get Dd info **/
	public function getDdInfo($ddID = null)
	{
		App::uses('Dd', 'Model');
		$this->Dd = new Dd();

		if (!$ddID) {
			return [];
		} else {
			$conditions = ['Dd.id' => $ddID, 'Dd.store_id' => $this->Session->read('Store.id')];
			if ($categoryInfo = $this->Dd->find('first', ['conditions' => $conditions])) {
				return $categoryInfo;
			}
		}
		return [];
	}

	/** Function to remove category and its products from the list */
	public function removeProductCategory($categoryID)
	{
		if (!$this->getProductCategoryInfo($categoryID)) {
			return false;
		}

		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();
		// remove category info
		$this->ProductCategory->delete($categoryID);

		// App::uses('Product', 'Model');
		// $this->Product = new Product;
		// // remove category products
		// $conditions = array('Product.product_category_id'=>$categoryID, 'Product.store_id'=>$this->Session->read('Store.id'));
		// $this->Product->deleteAll($conditions);

		return true;
	}

	/** Function to get Product Category info **/
	public function getProductCategoryInfo($productCategoryID = null)
	{
		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();

		if (!$productCategoryID) {
			return [];
		} else {
			$conditions = ['ProductCategory.id' => $productCategoryID, 'ProductCategory.store_id' => $this->Session->read('Store.id')];
			if ($productCategoryInfo = $this->ProductCategory->find('first', ['conditions' => $conditions])) {
				return $productCategoryInfo;
			}
		}
		return [];
	}

	/** Function to delete category and its products from the list, sales and purchases */
	public function deleteProductCategory($categoryID)
	{
		if (!$this->getProductCategoryInfo($categoryID)) {
			return false;
		}

		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();

		App::uses('Product', 'Model');
		$this->Product = new Product();

		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase();

		App::uses('Sale', 'Model');
		$this->Sale = new Sale();

		// remove category products
		$conditions = ['Product.product_category_id' => $categoryID, 'Product.store_id' => $this->Session->read('Store.id')];
		$this->Product->deleteAll($conditions);

		// remove from purchases
		$conditions = ['Purchase.product_category_id' => $categoryID, 'Purchase.store_id' => $this->Session->read('Store.id')];
		$this->Purchase->deleteAll($conditions);

		// remove from sales
		$conditions = ['Sale.product_category_id' => $categoryID, 'Sale.store_id' => $this->Session->read('Store.id')];
		$this->Sale->deleteAll($conditions);

		// remove category info
		$this->ProductCategory->delete($categoryID);

		return true;
	}

	/** Function to delete products sales and purchases and product info */
	public function deleteProduct($productID)
	{
		if (!$this->getProductInfo($productID)) {
			return false;
		}

		App::uses('Product', 'Model');
		$this->Product = new Product();

		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase();

		App::uses('Sale', 'Model');
		$this->Sale = new Sale();

		App::uses('Breakage', 'Model');
		$this->Breakage = new Breakage();


		// remove from purchases
		$conditions = ['Purchase.product_id' => $productID, 'Purchase.store_id' => $this->Session->read('Store.id')];
		$this->Purchase->deleteAll($conditions);

		// remove from sales
		$conditions = ['Sale.product_id' => $productID, 'Sale.store_id' => $this->Session->read('Store.id')];
		$this->Sale->deleteAll($conditions);

		// remove from breakages
		$conditions = ['Breakage.product_id' => $productID, 'Breakage.store_id' => $this->Session->read('Store.id')];
		$this->Breakage->deleteAll($conditions);

		// remove product info
		$this->Product->delete($productID);

		return true;
	}

	/** Function to get Product info **/
	public function getProductInfo($productID = null, $categoryID = null)
	{
		App::uses('Product', 'Model');
		$this->Product = new Product();

		if (!$productID) {
			return [];
		} else {
			if ($categoryID) {
				$conditions = ['Product.id' => $productID, 'Product.product_category_id' => $categoryID, 'Product.store_id' => $this->Session->read('Store.id')];
			} else {
				$conditions = ['Product.id' => $productID, 'Product.store_id' => $this->Session->read('Store.id')];
			}

			$this->Product->bindModel(['belongsTo' => ['Brand']]);
			if ($productInfo = $this->Product->find('first', ['conditions' => $conditions])) {
				return $productInfo;
			}
		}
		return [];
	}

	/** Function to get Dealer info **/
	public function getDealerInfo($dealerID = null)
	{
		App::uses('Dealer', 'Model');
		$this->Dealer = new Dealer();

		if (!$dealerID) {
			return [];
		} else {
			$conditions = ['Dealer.id' => $dealerID, 'Dealer.store_id' => $this->Session->read('Store.id')];
			if ($dealerInfo = $this->Dealer->find('first', ['conditions' => $conditions])) {
				return $dealerInfo;
			}
		}
		return [];
	}

	/** Function to get Brand info **/
	public function getBrandInfo($dealerID = null)
	{
		App::uses('Brand', 'Model');
		$this->Brand = new Brand();

		if (!$dealerID) {
			return [];
		} else {
			$conditions = ['Brand.id' => $dealerID, 'Brand.store_id' => $this->Session->read('Store.id')];
			if ($brandInfo = $this->Brand->find('first', ['conditions' => $conditions])) {
				return $brandInfo;
			}
		}
		return [];
	}

}
