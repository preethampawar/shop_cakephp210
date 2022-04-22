<?php
App::uses('Validation', 'Utility');

class ProductsController extends AppController
{

	public $name = 'Products';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	/**
	 * Function to show list of category products
	 */
	public function index()
	{
		App::uses('ProductCategory', 'Model');
		$productCategory = new ProductCategory();
		$fields = ['ProductCategory.id', 'ProductCategory.name', 'ProductCategory.created'];
		$conditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
		$categories = $productCategory->find('all', [
			'fields' => $fields,
			'order' => ['ProductCategory.name' => 'ASC'],
			'conditions' => $conditions,
			'recursive' => '1',
		]);

		$this->set(compact('categories'));
	}

	/**
	 * Function to show list of category products
	 */
	public function index_old($productCategoryID = null)
	{
		$hideSideBar = true;
		if (!$productCategoryInfo = $this->CommonFunctions->getProductCategoryInfo($productCategoryID)) {
			//$this->redirect(['controller' => 'product_categories', 'action' => 'index']);
		}

		$conditions = ['Product.store_id' => $this->Session->read('Store.id'), 'Product.product_category_id' => $productCategoryID];

		$this->Product->bindModel(['belongsTo' => ['Brand']]);
		$products = $this->Product->find('all', [
			'order' => ['Product.name' => 'ASC'],
			'conditions' => $conditions,
			'recursive' => '0',
		]);

		App::uses('ProductCategory', 'Model');
		$productCategory = new ProductCategory();
		$fields = ['ProductCategory.id', 'ProductCategory.name', 'ProductCategory.created'];
		$conditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
		$categories = $productCategory->find('all', [
			'fields' => $fields,
			'order' => ['ProductCategory.name' => 'ASC'],
			'conditions' => $conditions,
			'recursive' => '1',
		]);

		$this->set(compact('products', 'productCategoryInfo', 'hideSideBar', 'categories'));
	}

	public function add($productCategoryID = null)
	{
		$hideSideBar = true;
		if (!$productCategoryInfo = $this->CommonFunctions->getProductCategoryInfo($productCategoryID)) {
			$this->Session->setFlash('Category not found.');
			$this->redirect(['controller' => 'product_categories', 'action' => 'index']);
		}

		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			$data['Product'] = $this->request->data['Product'];
			$data['Product']['name'] = trim($data['Product']['name']);

			$error = $this->productFormValidation($data);

			//find if a similar product exists for the selected category in the store
			if (!$error) {
				$data['Product']['name'] = htmlentities($data['Product']['name'], ENT_QUOTES);
				$conditions = ['Product.name' => $data['Product']['name'], 'Product.product_category_id' => $productCategoryID, 'Product.store_id' => $this->Session->read('Store.id')];
				if ($this->Product->find('first', ['conditions' => $conditions])) {
					$error = 'Product ' . $data['Product']['name'] . ' already exists';
				}
			}

			if (!$error) {
				$data['Product']['id'] = null;
				$data['Product']['product_category_id'] = $productCategoryID;
				$data['Product']['store_id'] = $this->Session->read('Store.id');

				if ($this->Product->save($data)) {
					$this->Session->setFlash('Product Created Successfully', 'default', ['class' => 'success']);
					unset($data['Product']['product_code']);
					unset($data['Product']['box_qty']);
					unset($data['Product']['box_buying_price']);
					unset($data['Product']['unit_selling_price']);
					$this->data = $data;
				} else {
					$error = 'An error occured while creating a new category';
				}
			}
		}
		if ($error) {
			$this->Session->setFlash($error);
		}

		// recently added products
		$conditions = ['Product.product_category_id' => $productCategoryID, 'Product.store_id' => $this->Session->read('Store.id')];
		$this->Product->bindModel(['belongsTo' => ['Brand']]);
		$products = $this->Product->find('all', ['conditions' => $conditions, 'order' => 'Product.created DESC', 'recursive' => '-1', 'limit' => '5']);

		App::uses('Brand', 'Model');
		$this->Brand = new Brand();
		$brands = $this->Brand->find('list', ['conditions' => ['Brand.store_id' => $this->Session->read('Store.id')]]);

		$this->set(compact('productCategoryInfo', 'products', 'hideSideBar', 'brands'));
	}

	public function productFormValidation($data = null)
	{
		$error = null;
		if ($data) {
			if ((!isset($data['Product']['name'])) OR ((!$data['Product']['name']) OR !Validation::custom($data['Product']['name'], "/^[a-zA-Z0-9][a-zA-Z0-9\s\-\._#()]{1,55}$/i"))) {
				$error = 'Invalid Name (Or) Product name should be between 2 and 55 chars';
			}
			if (isset($data['Product']['product_code']) AND ($data['Product']['product_code'] AND !Validation::alphaNumeric($data['Product']['product_code']))) {
				$error = 'Product Code should contain Alphanumeric values';
			}
			if (isset($data['Product']['box_qty']) AND ($data['Product']['box_qty'] AND !Validation::naturalNumber($data['Product']['box_qty']))) {
				$error = 'Units per Box should contain natural numbers. eg: 1,2,3,4,5,... etc';
			}
			if (isset($data['Product']['box_buying_price']) AND $data['Product']['box_buying_price'] AND (!Validation::decimal($data['Product']['box_buying_price']) OR ($data['Product']['box_buying_price'] <= 0))) {
				$error = 'Box buying price should be greater than 0. eg: 100, 1000, 5000, etc';
			}
			if (isset($data['Product']['unit_selling_price']) and $data['Product']['unit_selling_price'] and (!Validation::decimal($data['Product']['unit_selling_price']) OR ($data['Product']['unit_selling_price'] <= 0))) {
				$error = 'Invalid Box buying price (Or) Unit selling price should be greater than 0. eg: 100, 1000, 5000, etc';
			}
		} else {
			$error = 'Empty product details';
		}
		return $error;
	}

	public function edit($productCategoryID = null, $productID = null)
	{
		$hideSideBar = true;

		if (!$productCategoryInfo = $this->CommonFunctions->getProductInfo($productID, $productCategoryID)) {
			$this->Session->setFlash('Product not found.');
			$this->redirect(['controller' => 'product_categories', 'action' => 'index', $productCategoryID]);
		}

		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			$data['Product'] = $this->request->data['Product'];
			$data['Product']['name'] = trim($data['Product']['name']);

			$error = $this->productFormValidation($data);

			//find if a similar product exists for the selected category in the store
			if (!$error) {
				$data['Product']['name'] = htmlentities($data['Product']['name'], ENT_QUOTES);
				$conditions = ['Product.name' => $data['Product']['name'], 'Product.product_category_id' => $productCategoryID, 'Product.store_id' => $this->Session->read('Store.id'), 'Product.id <>' => $productID];
				if ($this->Product->find('first', ['conditions' => $conditions])) {
					$error = 'Product ' . $data['Product']['name'] . ' already exists';
				}
			}

			if (!$error) {
				$data['Product']['id'] = $productID;

				if ($this->Product->save($data)) {
					$this->Session->setFlash('Product Updated Successfully', 'default', ['class' => 'success']);
					$this->redirect('/product_categories/index/' . $productCategoryID);
				} else {
					$error = 'An error occured while updating product details';
				}
			}
		} else {
			$this->data = $productCategoryInfo;
		}
		if ($error) {
			$this->Session->setFlash($error);
		}

		App::uses('Brand', 'Model');
		$this->Brand = new Brand();
		$brands = $this->Brand->find('list', ['conditions' => ['Brand.store_id' => $this->Session->read('Store.id')]]);

		$this->set(compact('productCategoryInfo', 'hideSideBar', 'brands'));
	}

	public function delete($productID = null)
	{
		if ($this->CommonFunctions->deleteProduct($productID)) {
			$this->Session->setFlash('Product deleted successfully', 'default', ['class' => 'success']);
		} else {
			$this->Session->setFlash('Product not found', 'default', ['class' => 'error']);
		}
		$this->redirect($this->request->referer());
	}

}

?>
