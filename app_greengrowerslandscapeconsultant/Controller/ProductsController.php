<?php
App::uses('CakeEmail', 'Network/Email');
class ProductsController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();		
		$this->Auth->allow('index', 'show', 'showAll', 'showFeatured', 'details', 'getRecentVists', 'getMostViewedProducts');
	}	
	
	public function index() {
		$productLinkActive = true;	
		$hideLeftMenu = true;	
		$showShoppingListInTopMenu = true;	
		$this->set(compact('hideLeftMenu', 'showShoppingListInTopMenu', 'productLinkActive'));	
	}
	
	/**
	 * Function to show category products
	 */
	function show($categoryID) {	
		if(!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', array('class'=>'error'));
			$this->redirect($this->request->referer());
		}
		
		$categoryDetails = $this->Product->getSiteCategoriesProductsImages(array('categoryConditions'=>array('Category.id'=>$categoryID), 'cols'=>'complete'));
		if(!empty($categoryDetails)) {
			$categoryInfo = $categoryDetails[0];
		}
		else {
			$this->Session->setFlash('Category Not Found', 'default', array('class'=>'error'));
			$this->redirect($this->request->referer());
		}
		
		$this->set(compact('categoryInfo'));		
	}
	
	
	/**
	 * Function to show all category products
	 */
	function showAll() {	
		$hideLeftMenu = true;	
		$showShoppingListInTopMenu = true;	
		
		$allCategories = $this->Product->getSiteCategoriesProductsImages(array('cols'=>'complete'));
		
		$this->set(compact('allCategories', 'hideLeftMenu', 'showShoppingListInTopMenu'));		
	}
	
	/**
	 * Function to show all featured products
	 */
	function showFeatured() {
		if(!$this->Session->read('Site.featured_products')) {
			$this->Session->setFlash('Featured products on this site have been disabled.', 'default', array('class'=>'notice'));
			$this->redirect($this->request->referer());
		}			
	}
	
	/**
	 * Function to show product details
	 */	
	function details($categoryID, $productID) {
		$errorMsg = array();
		
		if(!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', array('class'=>'error'));
			$this->redirect('/admin/categories/');
		}
		elseif(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));
			$categoryNameSlug = Inflector::slug($categoryInfo['Category']['name'], '-');	
			$this->redirect('/admin/products/show/'.$categoryID.'/'.$categoryNameSlug);
		}
		
		// update product visits
		$this->addProductVisit($categoryID, $productID, $categoryInfo['Category']['name'], $productInfo['Product']['name']); 
		$visits = $this->getProductVisits($categoryID, $productID); 
		
		// find product images
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$productImages = $this->Image->findAllByProductId($productID);
		
		$this->set(compact('productInfo', 'categoryInfo', 'productImages', 'visits'));
	}
	
	/**
	 * Function to get recent vists
	 */ 
	function getRecentVists() {		
		$products = $this->getRecentProductViewsByUser();
		return $products;
	} 
	/**
	 * Function to get most viewed products on the selected site
	 */ 
	function getMostViewedProducts() {		
		$products = $this->getMostViewedProductsList();
		return $products;
	} 
	
	function admin_index() {
		$productInfoLinkActive = true;
		$conditions = array('Product.site_id'=>$this->Session->read('Site.id'));
		
		$this->Product->bindModel(array('hasMany'=>array('CategoryProduct')));
		$this->Product->CategoryProduct->unbindModel(array('belongsTo'=>array('Product')));
		//$this->Product->Image->unbindModel(array('belongsTo'=>array('Product', 'Category')));
		
		$products = $this->Product->find('all', array('conditions'=>$conditions, 'order'=>'Product.name', 'recursive'=>'2'));			
		$this->set(compact('products', 'productInfoLinkActive'));
	}
	
	function admin_add() {
		$productInfoLinkActive = true;
		$errorMsg = array();
		if($this->request->isPost()) {
			$data = $this->request->data;

			// Validate name
			if(Validation::blank($data['Product']['name'])) {
				$errorMsg[] = 'Enter Product Name';
			}
			
			if(!is_array($data['Category']['id'])) {
				$errorMsg[] = 'Select atleast one category';
			}
			
			// Sanitize data
			$data['Product']['name'] = Sanitize::paranoid($data['Product']['name'], array(' ','-'));
						
			if(!$errorMsg) {
				$conditions = array('Product.site_id'=>$this->Session->read('Site.id'), 'Product.name'=>$data['Product']['name']);
				if($this->Product->find('first', array('conditions'=>$conditions))) {
					$errorMsg[] = 'Product "'.$data['Product']['name'].'" already exists';
				}
				else {
					$data['Product']['site_id'] = $this->Session->read('Site.id');
					if($this->Product->save($data)) {
						$productInfo = $this->Product->read();
						$productID = $productInfo['Product']['id'];
						
						// Save product categories
						App::uses('CategoryProduct', 'Model');
						$this->CategoryProduct = new CategoryProduct;
						foreach($data['Category']['id'] as $categoryID) {
							$tmp = array();
							$tmp['CategoryProduct']['id'] = null;
							$tmp['CategoryProduct']['product_id'] = $productID;
							$tmp['CategoryProduct']['category_id'] = $categoryID;
							$tmp['CategoryProduct']['site_id'] = $this->Session->read('Site.id');
							$this->CategoryProduct->save($tmp);
						}
						
						$this->Session->setFlash('Product successfully added', 'default', array('class'=>'success'));
						$this->redirect('/admin/products/edit/'.$productID);
					}
					else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}
			}		
		}
		
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('errorMsg', 'productInfoLinkActive'));
	}
	
	function admin_edit($productID, $categoryID=null) {
		$errorMsg = array();
		$productInfoLinkActive = true;
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));
			$this->redirect('/admin/categories/');
		}
		
		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$this->CategoryProduct->recursive = -1;
		$productCategories = $this->CategoryProduct->findAllByProductId($productID);		
		$selectedCategories = array();
		if(!empty($productCategories)) {
			foreach($productCategories as $row) {
				$selectedCategories[] = (int)$row['CategoryProduct']['category_id'];
			}
		}
		
		
		if($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;			
			
			// Validate name
			if(Validation::blank($data['Product']['name'])) {
				$errorMsg[] = 'Enter Product Name';
			}
			// Sanitize data
			$data['Product']['name'] = Sanitize::paranoid($data['Product']['name'], array(' ','-'));
			$data['Product']['meta_keywords'] = Sanitize::paranoid($data['Product']['meta_keywords'], array(' ','-', ','));
			$data['Product']['meta_description'] = Sanitize::paranoid($data['Product']['meta_description'], array(' ','-',','));
			
			
			if(!$errorMsg) {
				$conditions = array('Product.site_id'=>$this->Session->read('Site.id'), 'Product.name'=>$data['Product']['name'], 'Product.id NOT'=>$productID);
				if($this->Product->find('first', array('conditions'=>$conditions))) {
					$errorMsg[] = 'Product "'.$data['Product']['name'].'" already exists';
				}
				else {					
					$data['Product']['id'] = $productID;
					
					if($this->Product->save($data)) {						
						// Delete product categories
						$conditions = array();
						$conditions = array('CategoryProduct.product_id'=>$productID);
						$this->CategoryProduct->deleteAll($conditions);						
						// Save product categories
						foreach($data['Category']['id'] as $categoryID) {
							$tmp = array();
							$tmp['CategoryProduct']['id'] = null;
							$tmp['CategoryProduct']['product_id'] = $productID;
							$tmp['CategoryProduct']['category_id'] = $categoryID;
							$tmp['CategoryProduct']['site_id'] = $this->Session->read('Site.id');
							$this->CategoryProduct->save($tmp);
						}
						
						$this->Session->setFlash('Product information successfully updated', 'default', array('class'=>'success'));
						if(!empty($categoryID)) {
							$this->redirect('/admin/categories/showProducts/'.$categoryID);							
						}
						$this->redirect('/admin/products/index');
					}
					else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}
			}		
		}
		else {
			$tmp = array();
			$tmp['Product'] =  $productInfo['Product'];
			
			if(!empty($productCategories)) {
				foreach($productCategories as $row) {
					$tmp['Category']['id'][] = $row['CategoryProduct']['category_id'];					
				}
			}
			$this->data = $tmp;
		}
		
		$errorMsg = implode('<br>', $errorMsg);
		
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$productImages = $this->Image->findAllByProductId($productID);		
		
		$this->set(compact('errorMsg', 'productInfo', 'categoryID', 'productInfoLinkActive', 'selectedCategories', 'productImages'));
	}

	function admin_deleteProduct($productID, $categoryID = null) {
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));			
		}
		elseif(!empty($categoryID) and !$this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Product Category Not Found', 'default', array('class'=>'error'));			
		}
		else {
			
			$this->deleteProduct($productID, $categoryID);
			$this->Session->setFlash('Product Deleted Successfully', 'default', array('class'=>'success'));
		}
		
		// redirect
		if(!empty($categoryID)) {
			$this->redirect('/admin/categories/showProducts/'.$categoryID);
		}
		else {
			$this->redirect('/admin/products/');
		}
	}
	
	/**
	 * Function to deactivate a product
	 */
	function admin_setInactive($productID) {
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['active'] = '0';
			if($this->Product->save($tmp)) {
				$this->Session->setFlash('Product successfully deactivated.', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', array('class'=>'error'));				
			}
		}
		$this->redirect($this->request->referer());
	}
	
	/**
	 * Function to deactivate a product
	 */
	function admin_setActive($productID) {
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['active'] = '1';
			if($this->Product->save($tmp)) {
				$this->Session->setFlash('Product successfully activated.', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', array('class'=>'error'));				
			}
		}
		$this->redirect($this->request->referer());
	}
	
	/**
	 * Function to unset a featured product
	 */
	function admin_unsetFeatured($productID) {
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['featured'] = '0';
			if($this->Product->save($tmp)) {
				$this->Session->setFlash('Product successfully removed from featured list.', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', array('class'=>'error'));				
			}
		}
		$this->redirect($this->request->referer());
	}
	
	/**
	 * Function to deactivate a product
	 */
	function admin_setFeatured($productID) {
		if(!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', array('class'=>'error'));			
		}
		else {
			$tmp['Product']['id'] = $productID;
			$tmp['Product']['featured'] = '1';
			if($this->Product->save($tmp)) {
				$this->Session->setFlash('Product successfully added to featured list.', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', array('class'=>'error'));				
			}
		}
		$this->redirect($this->request->referer());
	}	
	
}
?>