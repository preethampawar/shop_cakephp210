<?php
App::uses('CakeEmail', 'Network/Email');

class ProductsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		//$this->Auth->allow('index', 'show', 'showAll', 'showFeatured', 'details', 'getRecentVists', 'getMostViewedProducts');
	}

	public function index()
	{
		$productLinkActive = true;
		$hideLeftMenu = true;
		$showShoppingListInTopMenu = true;
		$this->set(compact('hideLeftMenu', 'showShoppingListInTopMenu', 'productLinkActive'));
	}

	/**
	 * Function to show category products
	 */
	function show($categoryID)
	{
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->errorMsg('Category not found');
			$this->redirect($this->request->referer());
		}

		$categoryDetails = $this->Product->getSiteCategoriesProducts(['categoryConditions' => ['Category.id' => $categoryID], 'cols' => 'complete'], $this->Session->read('Site.id'));

		if (!empty($categoryDetails)) {
			$categoryInfo = $categoryDetails[0];
		} else {
			$this->errorMsg('Category not found');
			$this->redirect($this->request->referer());
		}

		$this->set(compact('categoryInfo'));
	}


	/**
	 * Function to show all category products
	 */
	public function showAll()
	{
		$allCategories = $this->Product->getAllProducts($this->Session->read('Site.id'));
		$allProducts = [];
		if($allCategories) {
			$allProducts = [];
			foreach ($allCategories as $row) {
				if(!isset($allProducts[$row['Category']['id']])) {
					$allProducts[$row['Category']['id']]['Category'] = $row['Category'];
				}
			}

			foreach ($allProducts as $categoryId => &$category) {
				foreach($allCategories as $row) {
					if($row['Category']['id'] == $categoryId) {
						unset($row['Category']);
						$category['CategoryProducts'][] = $row;
					}
				}
			}
		}
		unset($allCategories);

		$this->set(compact('allProducts'));
	}

	/**
	 * Function to show all featured products
	 */
	public function showFeatured()
	{
		if (!$this->Session->read('Site.featured_products')) {
			$this->Session->setFlash('Featured products on this site have been disabled.', 'default', ['class' => 'notice']);
			$this->redirect($this->request->referer());
		}
	}

	/**
	 * Function to show product details
	 */
	public function details($categoryID, $productID)
	{
		$errorMsg = [];

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'error']);
			$this->redirect('/admin/categories/');
		} else if (!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
			$categoryNameSlug = Inflector::slug($categoryInfo['Category']['name'], '-');
			$this->redirect('/admin/products/show/' . $categoryID . '/' . $categoryNameSlug);
		}

		// update product visits
		$this->addProductVisit($categoryID, $productID, $categoryInfo['Category']['name'], $productInfo['Product']['name']);
		$visits = $this->getProductVisits($categoryID, $productID);

		// find product images
		App::uses('Image', 'Model');
		$imageModel = new Image;
		$productImages = $imageModel->findAllByProductId($productID);

		$this->set(compact('productInfo', 'categoryInfo', 'productImages', 'visits'));
	}

	/**
	 * Function to show product details in popup
	 */
	public function getDetails($categoryID, $productID)
	{
		$isAjax = false;
		if ($this->request->query('isAjax')) {
			$this->layout = false;
			$isAjax = true;
		}
		$errorMsg = [];

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->errorMsg('Category not found');
			$this->redirect('/admin/categories/');
		} elseif (!$productInfo = $this->isSiteProduct($productID)) {
			$this->errorMsg('Product not found');
			$categoryNameSlug = Inflector::slug($categoryInfo['Category']['name'], '-');
			$this->redirect('/admin/products/show/' . $categoryID . '/' . $categoryNameSlug);
		}

		// update product visits
//		$this->addProductVisit($categoryID, $productID, $categoryInfo['Category']['name'], $productInfo['Product']['name']);
//		$visits = $this->getProductVisits($categoryID, $productID);

		$this->set(compact('productInfo', 'categoryInfo', 'isAjax'));
	}

	/**
	 * Function to get recent vists
	 */
	public function getRecentVists()
	{
		$products = $this->getRecentProductViewsByUser();
		return $products;
	}

	/**
	 * Function to get most viewed products on the selected site
	 */
	public function getMostViewedProducts()
	{
		$products = $this->getMostViewedProductsList();
		return $products;
	}

	public function admin_index()
	{
		$productInfoLinkActive = true;
		$conditions = ['Product.site_id' => $this->Session->read('Site.id')];

		$this->Product->bindModel(['hasMany' => ['CategoryProduct']]);
		$this->Product->CategoryProduct->unbindModel(['belongsTo' => ['Product']]);
		//$this->Product->Image->unbindModel(array('belongsTo'=>array('Product', 'Category')));

		$products = $this->Product->find('all', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '2']);
		$this->set(compact('products', 'productInfoLinkActive'));
	}

	public function admin_add($categoryId = null)
	{
		if ($this->productsLimitExceeded()) {
			$this->errorMsg('You cannot add new products. You have reached your quota (max '.$this->Session->read('Site.products_limit').') of adding products in your store.');
			$this->redirect($this->request->referer());
		}

		$productInfoLinkActive = true;
		$errorMsg = [];
		if ($this->request->isPost()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Product']['name'])) {
				$errorMsg[] = 'Enter Product Name';
			}

			if (!is_array($data['Category']['id'])) {
				$errorMsg[] = 'Select atleast one category';
			}

			// Sanitize data
			$data['Product']['name'] = Sanitize::paranoid($data['Product']['name'], [' ', '-']);

			if (!$errorMsg) {
				$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.name' => $data['Product']['name']];
				$this->Product->recursive = -1;
				if ($this->Product->find('first', ['conditions' => $conditions])) {
					$errorMsg[] = 'Product "' . $data['Product']['name'] . '" already exists';
				} else {
					$data['Product']['site_id'] = $this->Session->read('Site.id');
					if ($this->Product->save($data)) {
						$productInfo = $this->Product->read();
						$productID = $productInfo['Product']['id'];

						// Save product categories
						App::uses('CategoryProduct', 'Model');
						$categoryProductModel = new CategoryProduct;
						foreach ($data['Category']['id'] as $categoryID) {
							$tmp = [];
							$tmp['CategoryProduct']['id'] = null;
							$tmp['CategoryProduct']['product_id'] = $productID;
							$tmp['CategoryProduct']['category_id'] = $categoryID;
							$tmp['CategoryProduct']['site_id'] = $this->Session->read('Site.id');
							$categoryProductModel->save($tmp);
						}

						$this->successMsg('Product successfully added');
						$this->redirect('/admin/products/edit/' . $productID . '/' . $categoryId);
					} else {
						$errorMsg[] = 'An error occurred while communicating with the server';
					}
				}
			}
		}

		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('errorMsg', 'productInfoLinkActive', 'categoryId'));
	}

	public function admin_edit($productID, $categoryID = null)
	{
		$errorMsg = [];
		$productInfoLinkActive = true;
		if (!$productInfo = $this->isSiteProduct($productID)) {
			$this->errorMsg('Product Not Found');
			$this->redirect('/admin/categories/');
		}

		App::uses('CategoryProduct', 'Model');
		$categoryProductModel = new CategoryProduct;
		$categoryProductModel->recursive = -1;
		$productCategories = $categoryProductModel->findAllByProductId($productID);

		$selectedCategories = [];
		if (!empty($productCategories)) {
			foreach ($productCategories as $row) {
				$selectedCategories[] = (int)$row['CategoryProduct']['category_id'];
			}
		}

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Product']['name'])) {
				$errorMsg[] = 'Enter Product Name';
			}
			// Sanitize data
			$data['Product']['name'] = Sanitize::paranoid($data['Product']['name'], [' ', '-']);
			$data['Product']['meta_keywords'] = Sanitize::paranoid($data['Product']['meta_keywords'], [' ', '-', ',']);
			$data['Product']['meta_description'] = Sanitize::paranoid($data['Product']['meta_description'], [' ', '-', ',']);


			if (!$errorMsg) {
				$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.name' => $data['Product']['name'], 'Product.id NOT' => $productID];
				$this->Product->recursive = -1;
				if ($this->Product->find('first', ['conditions' => $conditions])) {
					$errorMsg[] = 'Product "' . $data['Product']['name'] . '" already exists';
				} else {
					$data['Product']['id'] = $productID;

					if ($this->Product->save($data)) {
						// Delete product categories
						$conditions = ['CategoryProduct.product_id' => $productID];

						$categoryProductModel->deleteAll($conditions);

						if ($categoryID && $data['Category']['id']) {
							if (!in_array($categoryID, $data['Category']['id'])) {
								$data['Category']['id'][] = $categoryID;
							}
						}

						if ($data['Category']['id']) {
							// Save product categories
							foreach ($data['Category']['id'] as $catId) {
								$tmp = [];
								$tmp['CategoryProduct']['id'] = null;
								$tmp['CategoryProduct']['product_id'] = $productID;
								$tmp['CategoryProduct']['category_id'] = $catId;
								$tmp['CategoryProduct']['site_id'] = $this->Session->read('Site.id');
								$categoryProductModel->save($tmp);
							}
						}

						$this->successMsg('Product information successfully updated');
						if (!empty($categoryID)) {
							$this->redirect('/admin/categories/showProducts/' . $categoryID);
						}
						$this->redirect('/admin/products/index');
					} else {
						$errorMsg[] = 'An error occurred while communicating with the server';
					}
				}
			}
		} else {
			$tmp = [];
			$tmp['Product'] = $productInfo['Product'];

			if (!empty($productCategories)) {
				foreach ($productCategories as $row) {
					$tmp['Category']['id'][] = $row['CategoryProduct']['category_id'];
				}
			}
			$this->data = $tmp;
		}

		if ($errorMsg) {
			$errorMsg = implode('<br>', $errorMsg);
			$this->errorMsg($errorMsg);
		}

		App::uses('Category', 'Model');
		$categoryModel = new Category;
		$categoryModel->recursive = -1;
		$categoryInfo = $categoryModel->findById($categoryID);

		$this->set(compact('errorMsg', 'productInfo', 'categoryID', 'productInfoLinkActive', 'selectedCategories', 'categoryInfo'));
	}

	public function admin_deleteProduct($productID, $categoryID = null)
	{
		if (!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		} elseif (!empty($categoryID) and !$this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Product Category Not Found', 'default', ['class' => 'error']);
		} else {
			$this->deleteProduct($productID, $categoryID);
			$this->Session->setFlash('Product Deleted Successfully', 'default', ['class' => 'success']);
		}

		// redirect
		if (!empty($categoryID)) {
			$this->redirect('/admin/categories/showProducts/' . $categoryID);
		} else {
			$this->redirect('/admin/products/');
		}
	}

	/**
	 * Function to deactivate a product
	 */
	public function admin_setInactive($productID)
	{
		if ($productInfo = $this->isSiteProduct($productID)) {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['active'] = '0';
			if ($this->Product->save($tmp)) {
				$this->successMsg('Product successfully deactivated.');
			} else {
				$this->errorMsg('An error occurred while communicating with the server.');
			}
		} else {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		}
		$this->redirect($this->request->referer());
	}

	/**
	 * Function to deactivate a product
	 */
	public function admin_setActive($productID)
	{
		if ($productInfo = $this->isSiteProduct($productID)) {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['active'] = '1';
			if ($this->Product->save($tmp)) {
				$this->successMsg('Product successfully activated.');
			} else {
				$this->errorMsg('An error occurred while communicating with the server.');
			}
		} else {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		}
		$this->redirect($this->request->referer());
	}

	/**
	 * Function to unset a featured product
	 */
	public function admin_unsetFeatured($productID)
	{
		if ($productInfo = $this->isSiteProduct($productID)) {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['featured'] = '0';
			if ($this->Product->save($tmp)) {
				$this->Session->setFlash('Product successfully removed from featured list.', 'default', ['class' => 'success']);
			} else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', ['class' => 'error']);
			}
		} else {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		}
		$this->redirect($this->request->referer());
	}

	/**
	 * Function to deactivate a product
	 */
	public function admin_setFeatured($productID)
	{
		if ($productInfo = $this->isSiteProduct($productID)) {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['featured'] = '1';
			if ($this->Product->save($tmp)) {
				$this->Session->setFlash('Product successfully added to featured list.', 'default', ['class' => 'success']);
			} else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', ['class' => 'error']);
			}
		} else {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		}
		$this->redirect($this->request->referer());
	}

	/**
	 * @param $productId
	 *
	 * @throws Exception
	 */
	public function admin_updateImage($productId)
	{
		$this->layout = false;
		$msg = 'Invalid request';
		$error = true;

		$isImageUrlSet = $this->request->data['imagePath'] ?? false;

		if ($isImageUrlSet && ($this->request->isPost() || $this->request->isPut())) {
			if ($productInfo = $this->isSiteProduct($productId)) {
				$images = [];

				if ($productInfo['Product']['images']) {
					$images = json_decode($productInfo['Product']['images']);
				}
				$images[] = [
					'imagePath' => $this->request->data['imagePath'],
					'type' => $this->request->data['imageType'],
					'commonId' => $this->request->data['commonId'] ?? rand(1, 10000),
					'caption' => '',
					'highlight' => 0,
				];

				$tmp['Product']['id'] = $productId;
				$tmp['Product']['images'] = json_encode($images);

				if ($this->Product->save($tmp)) {
					$error = false;
					$msg = 'Product image updated successfully';
				} else {
					$msg = 'Product image update failed';
				}
			} else {
				$msg = 'Product not found';
			}
		}

		$this->response->header('Content-type', 'application/json');
		$this->response->body(json_encode([
				'error' => $error,
				'msg' => $msg,
			], JSON_THROW_ON_ERROR)
		);
		$this->response->send();
		exit;
	}

	public function admin_highlightImage($productId, $imageCommonId)
	{
		$redirectURL = $this->request->referer();
		if (!$productInfo = $this->isSiteProduct($productId)) {
			$this->errorMsg('Image not found');
		} else {

			if (!$productInfo['Product']['images']) {
				$this->redirect($redirectURL);
			}

			$images = json_decode($productInfo['Product']['images']);

			foreach ($images as &$image) {
				$image->highlight = 0;
				if ($image->commonId == $imageCommonId) {
					$image->highlight = 1;
				}
			}

			$tmp['Product']['id'] = $productId;
			$tmp['Product']['images'] = json_encode($images);

			if ($this->Product->save($tmp)) {
				$msg = 'Product image updated successfully';
				$this->successMsg($msg);
			} else {
				$msg = 'Product image update failed';
				$this->errorMsg($msg);
			}

		}

		$this->redirect($redirectURL);
	}

	public function admin_deleteImage($productId, $imageCommonId)
	{
		$redirectURL = $this->request->referer();
		if (!$productInfo = $this->isSiteProduct($productId)) {
			$this->errorMsg('Image not found');
		} else {

			if (!$productInfo['Product']['images']) {
				$this->redirect($redirectURL);
			}

			$images = json_decode($productInfo['Product']['images']);
			$tmpImages = [];

			foreach ($images as $index => $image) {
				if ($image->commonId != $imageCommonId) {
					$tmpImages[] = $image;
				}
			}

			$tmp['Product']['id'] = $productId;
			$tmp['Product']['images'] = $tmpImages ? json_encode($tmpImages) : null;
			if ($this->Product->save($tmp)) {
				$msg = 'Product image updated successfully';
				$this->successMsg($msg);
			} else {
				$msg = 'Product image update failed';
				$this->errorMsg($msg);
			}

		}
		$this->redirect($redirectURL);
	}

}

?>
