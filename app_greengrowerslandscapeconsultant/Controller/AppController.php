<?php
App::uses('Controller', 'Controller');
App::uses('Validation', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('AuthComponent', 'Controller/Component');
class AppController extends Controller {
	public $helpers = array('Html', 'Form', 'Session', 'Number', 'Text', 'Img', 'Js' => array('Jquery'));
	
	public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => '/admin/categories',
            'logoutRedirect' => '/',
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email')
				)
			)				
        )
    );

	public function beforeFilter() {
		Configure::write('Security.salt', '');
		Security::setHash('md5');
		if($this->request->isMobile()) {
			$this->Session->write('isMobile', true);
		}
		else {
			$this->Session->write('isMobile', false);
		}
		
		if(isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home')) {
			$this->Session->write('isHomePage', true);
		}
		else {
			$this->Session->write('isHomePage', false);			
		}
		
		
		// Parse http request and load the appropriate domain
		App::uses('Site', 'Model');
		$this->Site = new Site;
		$siteInfo = $this->Site->findById(1);
		$this->Session->write('Site', $siteInfo['Site']);
		
		// Set default configuration
		
		/* Domain Information */
		Configure::write('Domain', $this->request->domain());
		Configure::write('DomainName', $this->request->host());
		Configure::write('DomainUrl', 'http://'.$this->request->host());

		/* Site email configuration */
		$supportEmail = $this->Session->read('Site.contact_email');
		Configure::write('SupportEmail', $supportEmail);
		Configure::write('NoReply', array('name'=>$this->request->domain(), 'email'=>'noreply@'.$this->request->domain()));
		
		/* check if landing page is active. if so then redirect user to landing page */
		// if($this->here == '/') {
			// if($this->checkLandingPage()) {
				// if(!$this->Session->check('visitedLandingPage')) {	
					// $this->Session->write('visitedLandingPage', true);
					// $this->redirect('/showcase');
				// }
			// }
		// }
		
		/* Update Site Visits */
		$currentTime = time();
		if(!$this->Session->check('visitTime') or (!$this->Session->check('SiteVisits'))) {
			$this->updateSiteVisits();
			$this->Session->write('visitTime', $currentTime);
		}
		$sessionTime = $this->Session->read('visitTime');
		
		if((($currentTime>$sessionTime) and ((($currentTime-$sessionTime)/60)>1))) {
			$this->Session->write('visitTime', $currentTime);
			$this->updateSiteVisits();
		}
	}
	
	function updateSiteVisits() {
		App::uses('Site', 'Model');
		$this->Site = new Site;
		$this->Site->recursive = -1;
		$siteInfo = $this->Site->findById($this->Session->read('Site.id'));
		$visitCount = $siteInfo['Site']['views'];
		$tmp['Site']['id'] = $this->Session->read('Site.id');
		$tmp['Site']['views'] = $visitCount+1;
		$this->Site->save($tmp);
		$this->Session->write('SiteVisits', $visitCount);
		return $visitCount;
	}
	
	function checkSuperAdmin() {
		if(!$this->Session->read('SuperAdmin')) {
			$this->Session->setFlash('You are not authorized to view this page');
			$this->redirect('/');
		}
		else {
			return true;
		}
	}
	
	function checkAdmin() {
		if(!$this->Session->read('User.admin')) {
			$this->Session->setFlash('You are not authorized to view this page');
			$this->redirect('/');
		}
		else {
			return true;
		}
	}
	
	function checkLandingPage() {						
		$contentInfo = $this->getLandingPageInfo();
		if(isset($contentInfo['Content']['landing_page']) and !empty($contentInfo['Content']['landing_page'])) {
			return true;
		}
		return false;
	}
	
	/**
	 * Function to check if product is from selected site.
	 */
	function isSiteProduct($productID) {
		App::uses('Product', 'Model');
		$this->Product = new Product;
		$conditions = array('Product.site_id'=>$this->Session->read('Site.id'), 'Product.id'=>$productID);		
		$product = $this->Product->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));		
		return $product;
	}
	
	/**
	 * Function to check if category is from selected site.
	 */
	function isSiteImage($imageID) {
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$conditions = array('Image.site_id'=>$this->Session->read('Site.id'), 'Image.id'=>$imageID);		
		$image = $this->Image->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));		
		return $image;
	}
	
	/**
	 * Function to check if category is from selected site.
	 */
	function isSiteCategory($categoryID) {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$conditions = array('Category.site_id'=>$this->Session->read('Site.id'), 'Category.id'=>$categoryID);		
		$category = $this->Category->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));		
		return $category;
	}

	/**
	 * Function to check if content is from selected site.
	 */
	function isSiteContent($contentID) {
		App::uses('Content', 'Model');
		$this->Content = new Content;
		$conditions = array('Content.site_id'=>$this->Session->read('Site.id'), 'Content.id'=>$contentID);		
		$content = $this->Content->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));		
		return $content;
	}
	
	/**
	 * Function to check if blog article is from selected site.
	 */
	function isSiteBlog($blogID) {
		App::uses('Blog', 'Model');
		$this->Blog = new Blog;
		$conditions = array('Blog.site_id'=>$this->Session->read('Site.id'), 'Blog.id'=>$blogID);		
		$content = $this->Blog->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));		
		return $content;
	}
	
	/**
	 * Function to check valid image size
	 */
	function isValidImageSize($imgSize) {
		if($imgSize > 0) {
			$maxSize = Configure::read('MaxImageSize');
			if(ceil($imgSize/(1024*1024)) > $maxSize) {
				return false;
			}
			else {
				return true;
			}
		}
		return false;
	} 
	
	/**
	 * Function to check a valid image
	 */
	function isValidImage($image) {
		if(isset($image['tmp_name'])) {
			$info = getimagesize($image['tmp_name']);	
			$validImage = false;
			if ($info) {
				switch ($info[2]) {
					case IMAGETYPE_PNG:
						$validImage = true; break;
					case IMAGETYPE_JPEG:
						$validImage = true; break;
					case IMAGETYPE_GIF:
						$validImage = true; break;
					default:
						$validImage = false; break;
				}
			}
			return ($validImage) ? true : false;
		}
		return false;
	} 
	
	/**
	 * Function to delete image by id
	 */
	function deleteImage($imageID) {
		// remove from images cache folder
		$imageCachePath = 'img/imagecache/';
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$imgCacheDir = new Folder();		
				
		$imgCacheDir->path = $imageCachePath;
		$files = $imgCacheDir->find($imageID.'_.*');
		if(!empty($files)) {
			foreach($files as $file) {
				$cacheFilePath = $imageCachePath.$file;
				$file = new File($cacheFilePath);
				$file->delete();							
			}
		}
		
		// remove from images folder
		$imagePath = 'img/images/'.$imageID;
		$file = new File($imagePath);
		$file->delete();

		// remove from images table	
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->delete($imageID);
		return true;
	}

	/**
	 * Function to delete product by id
	 */
	function deleteProduct($productID, $categoryID=null) {
		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$this->CategoryProduct->recursive = -1;
		
		App::uses('ProductVisit', 'Model');
		$this->ProductVisit = new ProductVisit;		
		
		// remove this product from categories
		if(!empty($categoryID)) {
			// remove this product from the selected category
			$conditions = array('CategoryProduct.product_id'=>$productID, 'CategoryProduct.category_id'=>$categoryID);
			$this->CategoryProduct->deleteAll($conditions);
						
			// remove this product from product_visits table
			$conditions = array('ProductVisit.product_id'=>$productID, 'ProductVisit.category_id'=>$categoryID);
			$this->ProductVisit->deleteAll($conditions);
		}
		else {			
			// remove this product from all categories
			$conditions = array('CategoryProduct.product_id'=>$productID);
			$this->CategoryProduct->deleteAll($conditions);
			
			// remove this product from product_visits table
			$conditions = array('ProductVisit.product_id'=>$productID);
			$this->ProductVisit->deleteAll($conditions);
			
			// delete product images
			App::uses('Image', 'Model');
			$this->Image = new Image;
			$productImages = $this->Image->findAllByProductId($productID);
			if(!empty($productImages)) {
				foreach($productImages as $row) {
						$this->deleteImage($row['Image']['id']);
				}
			}
			
			// delete product from database
			App::uses('Product', 'Model');
			$this->Product = new Product;
			$this->Product->delete($productID);
		}	
		return true;
	}

	/**
	 * Function to delete a category by id
	 */
	function deleteCategory($categoryID) {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$this->Category->recursive = -1;
		$categoryInfo = $this->Category->findById($categoryID);
		
		// find all subcategories
		$allChildren = $this->Category->children($categoryID, false);						
		
		// merge main category with subcategories
		$allCategories = $allChildren;
		$allCategories[] = $categoryInfo;
		
		if(!empty($allCategories)) {
			App::uses('Image', 'Model');
			$this->Image = new Image;
			
			foreach($allCategories as $row) {
				$catId = $row['Category']['id'];
				
				// delete category image				
				$this->Image->recursive = -1;
				$image = $this->Image->findByCategoryId($catId);
				
				if(!empty($image)) {
					try {
						$this->deleteImage($image['Image']['id']);
					}
					catch(Exception $e) { 
					}
				}
				
				// delete category products
				App::uses('CategoryProduct', 'Model');
				$this->CategoryProduct = new CategoryProduct;
				$this->CategoryProduct->recursive = '-1';
				$categoryProducts = $this->CategoryProduct->findAllByCategoryId($catId);
				if(!empty($categoryProducts)) {
					foreach($categoryProducts as $row2) {						
						$productID = $row2['CategoryProduct']['product_id'];
						try{	
							$this->deleteProduct($productID);						
						}
						catch(Exception $e) { 
						}
					}				
				}
				
				// delete category from database
				try{
					$this->Category->delete($catId);
				}
				catch(Exception $e) { 
				}				
			}
		}		
		return true;		
	} 
	
	/** 
	 * Function to get shopping cart id for the current session
	 */
	function getShoppingCartID() {
		$shoppingCartID = null;
		if($this->Session->check('ShoppingCart.id')) {
			$shoppingCartID = $this->Session->read('ShoppingCart.id');
		}
		else {
			App::uses('ShoppingCart', 'Model');
			$this->ShoppingCart = new ShoppingCart;
			$tmp['ShoppingCart']['id'] = null;
			$tmp['ShoppingCart']['site_id'] = $this->Session->read('Site.id');
			if($this->ShoppingCart->save($tmp)) {
				$shoppingCartInfo = $this->ShoppingCart->read();
				$shoppingCartID = $shoppingCartInfo['ShoppingCart']['id'];
				$this->Session->write('ShoppingCart', $shoppingCartInfo['ShoppingCart']);
			}
		}
		return $shoppingCartID;
	} 
	
	/**
	 * Function to get shopping cart products
	 */
	function getShoppingCartProducts() {
		$shoppingCart = null;
		if($this->Session->check('ShoppingCart.id')) {
			App::uses('ShoppingCart', 'Model');
			$this->ShoppingCart = new ShoppingCart;
		
			$shoppingCartID = $this->Session->read('ShoppingCart.id');
			$this->ShoppingCart->bindModel(array('hasMany'=>array('ShoppingCartProduct'=>array('order'=>'ShoppingCartProduct.product_name'))));
			$shoppingCart = $this->ShoppingCart->find('first', array('conditions'=>array('ShoppingCart.id'=>$shoppingCartID)));
		}
		return $shoppingCart;
	} 
	
	/** 
	 * Function to update product visits
	 */
	function addProductVisit($categoryID, $productID, $categoryName, $productName) {
		App::uses('ProductVisit', 'Model');
		$this->ProductVisit = new ProductVisit;
		
		$conditions = array('ProductVisit.category_id'=>$categoryID, 'ProductVisit.product_id'=>$productID, 'ProductVisit.site_id'=>$this->Session->read('Site.id'));
		
		$visitInfo = array();
		try {
			if($visitInfo = $this->ProductVisit->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'))) {
				$data['ProductVisit']['id'] = $visitInfo['ProductVisit']['id'];
				$data['ProductVisit']['visit_count'] = $visitInfo['ProductVisit']['visit_count']+1;
				$this->ProductVisit->save($data);
			}
			else {
				$data['ProductVisit']['id'] = null;
				$data['ProductVisit']['visit_count'] = 1;
				$data['ProductVisit']['category_id'] = $categoryID;
				$data['ProductVisit']['product_id'] = $productID;
				$data['ProductVisit']['category_name'] = $categoryName;
				$data['ProductVisit']['product_name'] = $productName;
				$data['ProductVisit']['site_id'] = $this->Session->read('Site.id');
				$this->ProductVisit->save($data);
				$this->ProductVisit->recursive = -1;
				$visitInfo = $this->ProductVisit->read();
			}
		}
		catch(Exception $e) { 
			
		}
		
		return $visitInfo;
	}
	
	/** 
	 * Function to get product visits
	 */
	function getProductVisits($categoryID, $productID) {
		App::uses('ProductVisit', 'Model');
		$this->ProductVisit = new ProductVisit;
		
		$visits = 0;
		$conditions = array('ProductVisit.category_id'=>$categoryID, 'ProductVisit.product_id'=>$productID, 'ProductVisit.site_id'=>$this->Session->read('Site.id'));	
		$visitInfo = $this->ProductVisit->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
		if(!empty($visitInfo)) {
			$visits = $visitInfo['ProductVisit']['visit_count'];
		}	
		return $visits;
	}

	/** 
	 * Function to update product visits
	 */
	function getRecentProductViewsByUser() {
		App::uses('ProductVisit', 'Model');
		$this->ProductVisit = new ProductVisit;
		
		$conditions = array('ProductVisit.site_id'=>$this->Session->read('Site.id'));
		$fields = array('ProductVisit.id', 'ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name');
		
		$products = $this->ProductVisit->find('all', array('conditions'=>$conditions, 'order'=>array('ProductVisit.modified DESC'), 'fields'=>$fields, 'limit'=>'12'));
		if(!empty($products)) {
			App::uses('Image', 'Model');
			$this->Image = new Image;
			
			foreach($products as $index=>$row) {
				$image = $this->Image->find('first', array('conditions'=>array('Image.product_id'=>$row['Product']['id']), 'order'=>array('Image.highlight DESC'), 'recursive'=>'-1', 'fields'=>array('Image.id', 'Image.caption'), 'limit'=>'1'));
				$products[$index]['Image'] = $image['Image'];
			}	
		}
		return $products;		
	}
	
	/** 
	 * Function to get most viewed products on the site
	 */
	function getMostViewedProductsList() {
		App::uses('ProductVisit', 'Model');
		$this->ProductVisit = new ProductVisit;
		
		$conditions = array('ProductVisit.site_id'=>$this->Session->read('Site.id'));
		$fields = array('ProductVisit.id', 'ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name');
		
		$products = $this->ProductVisit->find('all', array('conditions'=>$conditions, 'order'=>array('ProductVisit.visit_count DESC'), 'fields'=>$fields, 'limit'=>'14'));
		if(!empty($products)) {
			App::uses('Image', 'Model');
			$this->Image = new Image;
			
			foreach($products as $index=>$row) {
				$image = $this->Image->find('first', array('conditions'=>array('Image.product_id'=>$row['Product']['id']), 'order'=>array('Image.highlight DESC'), 'recursive'=>'-1', 'fields'=>array('Image.id', 'Image.caption'), 'limit'=>'1'));
				$products[$index]['Image'] = $image['Image'];
			}	
		}
		return $products;		
	}	
	
	/**
	 * Function to get landing page information.
	 */
	function getLandingPageInfo() {
		App::uses('Content', 'Model');
		$this->Content = new Content;
		
		$conditions = array('Content.site_id'=>$this->Session->read('Site.id'), 'Content.landing_page'=>'1');		
		$contentInfo = $this->Content->find('first', array('conditions'=>$conditions));
		
		if(empty($contentInfo)) {
			$data = array();
			$data['Content']['id'] = null;
			$data['Content']['landing_page'] = '1';
			$data['Content']['site_id'] = $this->Session->read('Site.id');					
			$data['Content']['title'] = 'Landing Page';	
			if($this->Content->save($data)) {
				$contentInfo = $this->Content->read();
			}	
		}
		
		return $contentInfo;
	} 

	/**
	 * Function to get site category products
	 */
	function getSiteCategoriesProductsImages($options = array()) {
		App::uses('Product', 'Model');
		$this->Product = new Product;
		
		$data = $this->Product->getSiteCategoriesProductsImages($options = array());			
		return $data;
	}	
}
?>
