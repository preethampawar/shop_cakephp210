<?php
App::uses('Controller', 'Controller');
App::uses('Validation', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('AuthComponent', 'Controller/Component');

class AppController extends Controller
{
	public $helpers = ['Html', 'Form', 'Session', 'Number', 'Text', 'Img', 'Js' => ['Jquery'], 'App'];

	public $components = [
		'Session',
	];

	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->checkDomain();
		$this->setDomainConfiguration();
		$this->setMobileAppConfiguration();
		$this->setLayout();
		$this->parseURL();
		$this->setCacheKeys();
		$this->loadSiteConfiguration();

		if ($this->setSplash()) {
			return;
		}

		$this->setTheme();
		$this->generateCategoryList();
		// get featured products list
		//$this->generateFeaturedProductsList();
	}

	private function checkDomain()
	{
		if ($this->request->domain() == $this->request->host()) {
			$this->redirect('//www.' . $this->request->domain(), ['status' => 301]);
			exit;
		}
	}

	private function setDomainConfiguration()
	{
		/* Domain Information */
		Configure::write('Domain', $this->request->domain());
		Configure::write('DomainName', $this->request->host());
		Configure::write('DomainUrl', '//' . $this->request->host());
		Configure::write('AssetDomainUrl', '/');
		Configure::write('Security.salt', '');

		return true;
	}

	private function setSplash()
	{
		$appSource = $this->request->query['s'] ?? null;

		if ($appSource && $appSource === 'mobile') {
			$this->layout = 'splash';
			$splashInfo = $this->Session->check('splashInfo') ? $this->Session->read('splashInfo') : $this->loadSplashConfiguration();
			$this->Session->write('splashInfo', $splashInfo);
			$this->set('splashInfo', $splashInfo);

			return true;
		}

		return false;
	}

	private function setMobileAppConfiguration()
	{
		if ($this->Session->check('isMobileApp')) {
			return true;
		}

		$appSource = $this->request->query['s'] ?? null;

		if ($appSource && $appSource === 'mobile') {
			$this->Session->write('isMobileApp', true);
		}
	}

	private function setLayout()
	{
		if (isset($this->request->params['admin']) && $this->request->params['admin'] === true) {
			if (!$this->Session->check('User.id') || $this->Session->read('User.id') === false) {
				$this->redirect('/users/logout');
			}

			if (!$this->isSellerForThisSite()) {
				$this->errorMsg("You don't have permissions to access this location");
				$this->redirect('/users/setView/buyer');
			}

			if (!$this->Session->read('inSellerView')) {
				$this->setView('seller');
			}
		}

		// set layout
		$this->layout = 'buyer';
		if ($this->Session->read('inSellerView')) {
			$this->layout = 'seller';
		}
		if ($this->Session->read('inDeliveryView')) {
			$this->layout = 'delivery';
		}

		return true;
	}

	private function setCacheKeys()
	{
		if ($this->Session->check('CacheKeys')) {
			return true;
		}

		$keys = [
			'siteInfo' => $this->getCacheKey('siteInfo'),
			'catList' => $this->getCacheKey('catList'),
			'featuredProducts' => $this->getCacheKey('featuredProducts'),
		];

		$this->Session->write('CacheKeys', $keys);

		return true;
	}

	public function setView($userType = 'buyer')
	{
		$this->Session->write('inBuyerView', false);
		$this->Session->write('inSellerView', false);
		$this->Session->write('inAdminView', false);
		$this->Session->write('inDeliveryView', false);

		switch ($userType) {
			case 'seller':
				$this->Session->write('inSellerView', true);
				$this->redirect('/admin/sites/home');
				break;
			case 'admin':
				$this->Session->write('inAdminView', true);
				break;
			case 'delivery':
				$this->Session->write('inDeliveryView', true);
				$this->redirect('/deliveries/home');
				break;
				break;
			default:
				$this->Session->write('inBuyerView', true);
				break;
		}

		$this->redirect('/');
	}

	protected function isSellerForThisSite()
	{
		if ($this->Session->read('User.superadmin') == 1) {
			return true;
		}

		if ($this->isSeller()) {
			return true;
		}

		if ($this->Session->read('User.id') == $this->Session->read('Site.user_id')) {
			return true;
		}

		return false;
	}

	protected function isSeller()
	{
		if ($this->Session->read('User.superadmin') == 1) {
			return true;
		}

		if ($this->Session->read('User.type') == 'seller') {
			return true;
		}
		return false;
	}

	/**
	 * Function to parse request URL
	 */
	protected function parseURL()
	{
		$dName = $this->request->host();
		$uri = $this->request->here();
		$params = ($uri == '/') ? null : $uri;

		$defaultDomainInfo = $this->getDefaultDomainInfo();

		if (!$defaultDomainInfo) {
			$this->layout = 'ajax';
			throw new Exception("The website you are looking for could not be found.");
		}

		// check if default domain
		if ($defaultDomainInfo['Domain']['name'] !== $dName) {
			$redirectLink = '//' . $defaultDomainInfo['Domain']['name'] . $params;

			$this->redirect($redirectLink);
		}

		// check if site is active
		if (!$defaultDomainInfo['Site']['active']) {
			throw new Exception('This store does not exits');
		}

		// check if site is suspended
		if ($defaultDomainInfo['Site']['suspended']) {
			$allowedActionsInMaintenance = [
				'suspended',
			];

			if (
				isset($this->request->params['controller'])
				&& $this->request->params['controller'] === 'sites'
				&& !in_array($this->request->params['action'], $allowedActionsInMaintenance, true)
			) {
				$this->redirect('/sites/suspended');
			}
		}

		// check if site is under maintenance
		if (!isset($this->request->params['admin']) && $defaultDomainInfo['Site']['under_maintenance']) {
			$allowedActionsInMaintenance = [
				'under_maintenance',
				'getTopNavContent',
				'login',
				'logout',
				'forgotpassword',
				'verifyLoginOtp',
				'setView',
			];

			$this->layout = 'textonly';

			if (!in_array($this->request->params['action'], $allowedActionsInMaintenance, true)) {
				$this->redirect('/sites/under_maintenance');
			}
		}

		// if all is right, then write domain info to session so next time there's no need for site checks
		$this->Session->write('Site', $defaultDomainInfo['Site']);
		$this->Session->write('Domain', $defaultDomainInfo['Domain']);
	}

	private function getDefaultDomainInfo()
	{
		$siteInfo = $this->getSiteInfoFromCache();

		if ($siteInfo && isset($siteInfo['Domain']) && isset($siteInfo['Site'])) {
			return $siteInfo;
		}

		$siteInfo = [];
		$dName = $this->request->host();

		App::import('Model', 'Domain');
		$domainModel = new Domain;
		$sitedomain = $domainModel->findByName($dName);

		if (!empty($sitedomain)) {

			if ((bool)$sitedomain['Domain']['default'] === true) {
				$siteInfo['Domain'] = $sitedomain['Domain'];
				$siteInfo['Site'] = $sitedomain['Site'];
			} else {
				// find all domains related to the selected site and get the default domain
				$sitedomains = $domainModel->findAllBySiteId($sitedomain['Domain']['site_id']);

				App::uses('User', 'Model');
				$userModel = new User;
				$userModel->recursive = '-1';
				$userModel->unbindModel(['hasOne' => ['Site']]);

				foreach ($sitedomains as $row) {
					if ($row['Domain']['default']) {
						// $siteUser = $userModel->findById($row['Site']['user_id']);

						$siteInfo['Domain'] = $row['Domain'];
						$siteInfo['Site'] = $row['Site'];
						// $siteInfo['Site']['Account'] = $siteUser['User'] ?? null;

						break;
					}
				}
			}

			$this->writeSiteInfoToCache($siteInfo);
		}

		return $siteInfo;
	}

	protected function noticeMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/notice');
		}
		return true;
	}

	protected function successMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/success');
		}

		return true;
	}

	protected function errorMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/error');
		}
		return true;
	}

	protected function checkSuperAdmin()
	{
		if (!$this->Session->read('SuperAdmin')) {
			$this->Session->setFlash('You are not authorized to view this page');
			$this->redirect('/');
		} else {
			return true;
		}
	}

	protected function checkSeller()
	{
		if ($this->Session->read('User.superadmin') == 1) {
			return true;
		}

		if ($this->Session->read('User.type') !== 'seller') {
			$this->errorMsg('You are not authorized to view this page');
			$this->redirect('/');
		} else {
			return true;
		}
	}

	protected function getHighlightImage($data)
	{
		$highlightImage = [];

		if ($data) {
			$data = $this->getRearrangedImages($data);

			foreach ($data as $row) {
				$image = $row['thumb'];
				if ($image->highlight) {
					$highlightImage = $row;
					break;
				}
			}

			if (!$highlightImage) {
				$highlightImage = $data[array_key_last($data)];
			}
		}

		return $highlightImage;
	}

	protected function getRearrangedImages($data)
	{
		if (!is_array($data) and !empty($data)) {
			$data = json_decode($data);
		}

		$images = [];
		if ($data) {
			foreach ($data as $row) {
				$images[$row->commonId][$row->type] = $row;
			}
		}

		return $images;
	}

	/**
	 * Function to check if category is from selected site.
	 */
	protected function isSiteImage($imageID)
	{
		App::uses('Image', 'Model');
		$imageModel = new Image;
		$conditions = ['Image.site_id' => $this->Session->read('Site.id'), 'Image.id' => $imageID];
		return $imageModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	/**
	 * Function to check if category is from selected site.
	 */
	protected function isSiteCategory($categoryID)
	{
		App::uses('Category', 'Model');
		$categoryModel = new Category;
		$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.id' => $categoryID];
		return $categoryModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	/**
	 * Function to check if content is from selected site.
	 */
	protected function isSiteContent($contentID)
	{
		App::uses('Content', 'Model');
		$contentModel = new Content;
		$conditions = ['Content.site_id' => $this->Session->read('Site.id'), 'Content.id' => $contentID];
		$content = $contentModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		return $content;
	}

	/**
	 * Function to check if blog article is from selected site.
	 */
	protected function isSiteBlog($blogID)
	{
		App::uses('Blog', 'Model');
		$blogModel = new Blog;
		$conditions = ['Blog.site_id' => $this->Session->read('Site.id'), 'Blog.id' => $blogID];
		$content = $blogModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		return $content;
	}

	/**
	 * Function to check if banner is from selected site.
	 */
	protected function isSiteBanner($bannerId)
	{
		App::uses('Banner', 'Model');
		$bannerModel = new Banner;
		$conditions = ['Banner.site_id' => $this->Session->read('Site.id'), 'Banner.id' => $bannerId];
		$content = $bannerModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		return $content;
	}

	/**
	 * Function to check if testimonial is from selected site.
	 */
	protected function isSiteTestimonial($testimonialId)
	{
		App::uses('Testimonial', 'Model');
		$testimonialModel = new Testimonial;
		$conditions = ['Testimonial.site_id' => $this->Session->read('Site.id'), 'Testimonial.id' => $testimonialId];
		$content = $testimonialModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		return $content;
	}

	/**
	 * Function to check if testimonial is from selected site.
	 */
	protected function isSiteSupplier($supplierId)
	{
		App::uses('Supplier', 'Model');
		$supplierModel = new Supplier;
		$conditions = ['Supplier.site_id' => $this->Session->read('Site.id'), 'Supplier.id' => $supplierId];
		$content = $supplierModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);

		return $content;
	}

	protected function isSiteGroup($groupId)
	{
		App::uses('Group', 'Model');
		$groupModel = new Group;
		$conditions = ['Group.site_id' => $this->Session->read('Site.id'), 'Group.id' => $groupId];
		$content = $groupModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);

		return $content;
	}

	/**
	 * Function to check if testimonial is from selected site.
	 */
	protected function isSitePromoCode($promoCodeId)
	{
		App::uses('PromoCode', 'Model');
		$promoCodeModel = new PromoCode;
		$conditions = ['PromoCode.site_id' => $this->Session->read('Site.id'), 'PromoCode.id' => $promoCodeId];
		$content = $promoCodeModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		return $content;
	}

	/**
	 * Function to check valid image size
	 */
	protected function isValidImageSize($imgSize, $maxSize = null)
	{
		if ($imgSize > 0) {
			if ((int)$maxSize <= 0) {
				$maxSize = Configure::read('MaxImageSize');
			}

			if ((int)$maxSize <= 0) {
				$maxSize = 5; // set default maxsize if config is not specified or maxsize is not passed in param
			}

			if (ceil($imgSize / (1024 * 1024)) > $maxSize) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}

	/**
	 * Function to check a valid image
	 */
	protected function isValidImage($image)
	{
		if (isset($image['tmp_name'])) {
			$info = getimagesize($image['tmp_name']);
			$validImage = false;
			if ($info) {
				switch ($info[2]) {
					case IMAGETYPE_PNG:
					case IMAGETYPE_JPEG:
					case IMAGETYPE_GIF:
						$validImage = true;
						break;
					default:
						$validImage = false;
						break;
				}
			}

			return $validImage;
		}
		return false;
	}

	protected function deleteFile($image)
	{
		if (file_exists($image)) {
			unlink($image);
			return true;
		}

		return false;
	}

	/**
	 * Function to delete a category by id
	 */
	protected function deleteCategory($categoryID)
	{
		App::uses('Category', 'Model');
		$categoryModel = new Category;

		$categoryModel->recursive = -1;
		$categoryInfo = $categoryModel->findById($categoryID);

		// find all subcategories
		$allChildren = $categoryModel->children($categoryID, false);

		// merge main category with subcategories
		$allCategories = $allChildren;
		$allCategories[] = $categoryInfo;

		if (!empty($allCategories)) {
			App::uses('Image', 'Model');
			$imageModel = new Image;

			App::uses('CategoryProduct', 'Model');
			$categoryProductModel = new CategoryProduct;

			foreach ($allCategories as $row) {
				$catId = $row['Category']['id'];

				// delete category image
				$imageModel->recursive = -1;
				$image = $imageModel->findByCategoryId($catId);

				if (!empty($image)) {
					try {
						$this->deleteImage($image['Image']['id']);
					} catch (Exception $e) {
					}
				}

				// delete category products
				$categoryProductModel->recursive = '-1';
				$categoryProducts = $categoryProductModel->findAllByCategoryId($catId);

				if (!empty($categoryProducts)) {
					foreach ($categoryProducts as $row2) {
						$productID = $row2['CategoryProduct']['product_id'];
						try {
							$this->deleteProduct($productID, $catId);
							$categoryProductModel->delete($row2['CategoryProduct']['id']);
						} catch (Exception $e) {
						}
					}
				}

				// delete category from database
				try {
					$categoryModel->delete($catId);
				} catch (Exception $e) {
				}
			}
		}
		return true;
	}

	/**
	 * Function to delete image by id
	 */
	protected function deleteImage($imageID)
	{
		// remove from images cache folder
		$imageCachePath = 'img/imagecache/';
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$imgCacheDir = new Folder();

		$imgCacheDir->path = $imageCachePath;
		$files = $imgCacheDir->find($imageID . '_.*');
		if (!empty($files)) {
			foreach ($files as $file) {
				$cacheFilePath = $imageCachePath . $file;
				$file = new File($cacheFilePath);
				$file->delete();
			}
		}

		// remove from images folder
		$imagePath = 'img/images/' . $imageID;
		$file = new File($imagePath);
		$file->delete();

		// remove from images table
		App::uses('Image', 'Model');
		$imageModel = new Image;
		$imageModel->delete($imageID);
		return true;
	}

	/**
	 * Function to delete product by id
	 */
	protected function deleteProduct($productID, $categoryID = null)
	{
		App::uses('CategoryProduct', 'Model');
		$categoryProductModel = new CategoryProduct;
		$categoryProductModel->recursive = -1;

		App::uses('ProductVisit', 'Model');
		$productVisitModel = new ProductVisit;

		// check if product belongs to one or more categories.
		$belongsToMultipleCategories = false;
		$results = $categoryProductModel->findAllByProductId($productID);

		if (count($results) > 1) {
			$belongsToMultipleCategories = true;
		}

		if ($categoryID && $belongsToMultipleCategories) {
			// delete product only from the selected category
			$conditions = ['CategoryProduct.product_id' => $productID, 'CategoryProduct.category_id' => $categoryID];
			$categoryProductModel->deleteAll($conditions);

			// remove this product from product_visits table
			$conditions = ['ProductVisit.product_id' => $productID, 'ProductVisit.category_id' => $categoryID];
			$productVisitModel->deleteAll($conditions);
		} else { // completely delete the product

			// remove this product from all categories
			$conditions = ['CategoryProduct.product_id' => $productID];
			$categoryProductModel->deleteAll($conditions);

			// remove this product from product_visits table
			$conditions = ['ProductVisit.product_id' => $productID];
			$productVisitModel->deleteAll($conditions);

			// delete product images
			$baseUrl = Router::url('/', true);
			$productInfo = $this->isSiteProduct($productID);
			$productImages = $this->getRearrangedImages($productInfo['Product']['images']);

			foreach ($productImages as $row) {
				$image = $row['thumb'];
				$imageOri = $row['ori'];

				$deleteImages = [
					$image->imagePath,
					$imageOri->imagePath,
				];
				$deleteImages = base64_encode(json_encode($deleteImages));
				$deleteImagesUrl = $baseUrl . 'deleteImage.php?images=' . $deleteImages . '&i=' . time();
				$resp = file_get_contents("$deleteImagesUrl");
			}

			// delete product from database
			App::uses('Product', 'Model');
			$productModel = new Product;
			$productModel->delete($productID);
		}

		return true;
	}

	/**
	 * Function to check if product is from selected site.
	 */
	protected function isSiteProduct($productID)
	{
		App::uses('Product', 'Model');
		$productModel = new Product;
		$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.id' => $productID];
		return $productModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	/**
	 * Function to get shopping cart id for the current session
	 */
	protected function getOrderId()
	{
		$orderId = null;

		if ($this->Session->check('Order.id')) {
			$orderId = $this->Session->read('Order.id');
		} else {
			App::uses('Order', 'Model');
			$orderModel = new Order;
			$tmp['Order']['id'] = null;
			$tmp['Order']['status'] = Order::ORDER_STATUS_DRAFT;
			$tmp['Order']['site_id'] = $this->Session->read('Site.id');
			$tmp['Order']['log'] = json_encode([[
				'orderStatus' => Order::ORDER_STATUS_DRAFT,
				'date' => time()
			]]);

			if ($orderModel->save($tmp)) {
				$orderInfo = $orderModel->read();
				$orderId = $orderInfo['Order']['id'];
				$this->Session->write('Order', $orderInfo['Order']);
			}
		}

		return $orderId;
	}

	/**
	 * Function to get shopping cart id for the current session
	 */
	protected function getShoppingCartID()
	{
		$shoppingCartID = null;
		if ($this->Session->check('ShoppingCart.id')) {
			$shoppingCartID = $this->Session->read('ShoppingCart.id');
		} else {
			App::uses('ShoppingCart', 'Model');
			$shoppingCartModel = new ShoppingCart;
			$tmp['ShoppingCart']['id'] = null;
			$tmp['ShoppingCart']['site_id'] = $this->Session->read('Site.id');
			if ($shoppingCartModel->save($tmp)) {
				$shoppingCartInfo = $shoppingCartModel->read();
				$shoppingCartID = $shoppingCartInfo['ShoppingCart']['id'];
				$this->Session->write('ShoppingCart', $shoppingCartInfo['ShoppingCart']);
			}
		}
		return $shoppingCartID;
	}

	/**
	 * Function to get shopping cart products
	 */
	protected function getShoppingCartProducts()
	{
		$shoppingCart = null;
		if ($this->Session->check('ShoppingCart.id')) {
			App::uses('ShoppingCart', 'Model');
			$shoppingCartModel = new ShoppingCart;

			$shoppingCartID = $this->Session->read('ShoppingCart.id');
			$shoppingCartModel->bindModel(['hasMany' => ['ShoppingCartProduct' => ['order' => 'ShoppingCartProduct.product_name']]]);
			$shoppingCart = $shoppingCartModel->find('first', ['conditions' => ['ShoppingCart.id' => $shoppingCartID]]);
		}
		return $shoppingCart;
	}

	/**
	 * Function to update product visits
	 */
	protected function addProductVisit($categoryID, $productID, $categoryName, $productName)
	{
		App::uses('ProductVisit', 'Model');
		$productVisitModel = new ProductVisit;

		$conditions = ['ProductVisit.category_id' => $categoryID, 'ProductVisit.product_id' => $productID, 'ProductVisit.site_id' => $this->Session->read('Site.id')];

		$visitInfo = [];
		try {
			if ($visitInfo = $productVisitModel->find('first', ['conditions' => $conditions, 'recursive' => '-1'])) {
				$data['ProductVisit']['id'] = $visitInfo['ProductVisit']['id'];
				$data['ProductVisit']['visit_count'] = $visitInfo['ProductVisit']['visit_count'] + 1;
				$productVisitModel->save($data);
			} else {
				$data['ProductVisit']['id'] = null;
				$data['ProductVisit']['visit_count'] = 1;
				$data['ProductVisit']['category_id'] = $categoryID;
				$data['ProductVisit']['product_id'] = $productID;
				$data['ProductVisit']['category_name'] = $categoryName;
				$data['ProductVisit']['product_name'] = $productName;
				$data['ProductVisit']['site_id'] = $this->Session->read('Site.id');
				$productVisitModel->save($data);
				$productVisitModel->recursive = -1;
				$visitInfo = $productVisitModel->read();
			}
		} catch (Exception $e) {
		}

		return $visitInfo;
	}

	/**
	 * Function to get product visits
	 */
	protected function getProductVisits($categoryID, $productID)
	{
		App::uses('ProductVisit', 'Model');
		$productVisitModel = new ProductVisit;

		$visits = 0;
		$conditions = ['ProductVisit.category_id' => $categoryID, 'ProductVisit.product_id' => $productID, 'ProductVisit.site_id' => $this->Session->read('Site.id')];
		$visitInfo = $productVisitModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		if (!empty($visitInfo)) {
			$visits = $visitInfo['ProductVisit']['visit_count'];
		}
		return $visits;
	}

	/**
	 * Function to update product visits
	 */
	protected function getRecentProductViewsByUser()
	{
		App::uses('ProductVisit', 'Model');
		$productVisitModel = new ProductVisit;

		$conditions = ['ProductVisit.site_id' => $this->Session->read('Site.id')];
		$fields = ['ProductVisit.id', 'ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name'];

		$products = $productVisitModel->find('all', ['conditions' => $conditions, 'order' => ['ProductVisit.modified DESC'], 'fields' => $fields, 'limit' => '12']);
		if (!empty($products)) {
			App::uses('Image', 'Model');
			$imageModel = new Image;

			foreach ($products as $index => $row) {
				$image = $imageModel->find('first', ['conditions' => ['Image.product_id' => $row['Product']['id']], 'order' => ['Image.highlight DESC'], 'recursive' => '-1', 'fields' => ['Image.id', 'Image.caption'], 'limit' => '1']);
				$products[$index]['Image'] = $image['Image'];
			}
		}
		return $products;
	}

	/**
	 * Function to get most viewed products on the site
	 */
	protected function getMostViewedProductsList()
	{
		App::uses('ProductVisit', 'Model');
		$productVisitModel = new ProductVisit;

		$conditions = ['ProductVisit.site_id' => $this->Session->read('Site.id')];
		$fields = ['ProductVisit.id', 'ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name'];

		$products = $productVisitModel->find('all', ['conditions' => $conditions, 'order' => ['ProductVisit.visit_count DESC'], 'fields' => $fields, 'limit' => '14']);
		if (!empty($products)) {
			App::uses('Image', 'Model');
			$imageModel = new Image;

			foreach ($products as $index => $row) {
				$image = $imageModel->find('first', ['conditions' => ['Image.product_id' => $row['Product']['id']], 'order' => ['Image.highlight DESC'], 'recursive' => '-1', 'fields' => ['Image.id', 'Image.caption'], 'limit' => '1']);
				$products[$index]['Image'] = $image['Image'];
			}
		}
		return $products;
	}

	/**
	 * Function to get site category products
	 */
	protected function getSiteCategoriesProductsImages($options = [])
	{
		App::uses('Product', 'Model');
		$productModel = new Product;

		$data = $productModel->getSiteCategoriesProducts($options = [], $this->Session->read('Site.id'));
		return $data;
	}

	protected function sendSMS($toNumber, $message = null)
	{
		try {
			$message = trim($message);
			if (!empty($message)) {
				// Use the REST API Client to make requests to the Twilio REST API


				// Your Account SID and Auth Token from twilio.com/console
				$sid = '';
				$token = '';
				$client = new Client($sid, $token);

				// Use the client to do fun stuff like send text messages!
				$client->messages->create(
					// the number you'd like to send the message to
					'+919866042196',
					[
						// A Twilio phone number you purchased at twilio.com/console
						'from' => '', //+14843809078
						// the body of the text message you'd like to send
						'body' => 'Welcome. This is a test message.'
					]
				);
			}
		} catch (Exception $e) {
			//..
		}

		return false;
	}

	protected function clearSession()
	{
		$this->Session->delete('loginOtp');
		$this->Session->delete('loginUser');
		$this->Session->delete('enrollOtp');
		$this->Session->delete('enrollUser');
		$this->Session->delete('User');
		$this->Session->delete('Site');

		$this->Session->destroy();
	}

	protected function productsLimitExceeded()
	{
		App::uses('Product', 'Model');
		$productModel = new Product();

		$productsLimitForThisSite = (int)$this->Session->read('Site.products_limit');

		$productsCount = $this->getSiteProductsCount();

		if ($productsCount <= $productsLimitForThisSite) {
			return false;
		}

		return true;
	}

	protected function getSiteProductsCount()
	{
		App::uses('Product', 'Model');
		$productModel = new Product();

		return $productModel->find('count', ['conditions' => ['Product.site_id' => $this->Session->read('Site.id')]]);
	}

	protected function getBccEmails()
	{
		$adminEmail = Configure::read('AdminEmail');
		$bccEmails = [];
		$bccEmails[] = $adminEmail;

		$storeNotificationsEmails = $this->Session->read('Site.seller_notification_email');
		if (!empty(trim($storeNotificationsEmails))) {
			$storeNotificationsEmails = explode(',', $storeNotificationsEmails);
			if ($storeNotificationsEmails) {
				foreach ($storeNotificationsEmails as $storeNotificationsEmail) {
					$bccEmails[] = $storeNotificationsEmail;
				}
			}
		}

		return $bccEmails;
	}

	protected function createCustomer($mobile, $email)
	{
		App::uses('User', 'Model');
		$userModel = new User();

		$data['User']['id'] = null;
		$data['User']['mobile'] = $mobile;
		$data['User']['password'] = md5($mobile);
		$data['User']['email'] = $email;
		$data['User']['type'] = 'buyer';
		$data['User']['site_id'] = $this->Session->read('Site.id');

		$conditions = [
			'User.mobile' => $mobile,
			'User.site_id' => $this->Session->read('Site.id'),
		];

		$userInfo = $userModel->find('first', ['conditions' => $conditions]);

		if ($userInfo) {
			return $userInfo;
		} elseif ($userModel->save($data)) {
			return $userModel->read();
		}

		return false;
	}

	protected function sendSuccessfulEnrollmentMessage($mobile, $userEmail)
	{
		$subject = 'Registration Successful - ' . $mobile;
		$mailContent = 'Your account has been created with us. You can use your mobile no. ' . $mobile . ' to login.';
		$email = new CakeEmail('smtpNoReply');
		$email->to([$userEmail => $userEmail]);
		$email->subject($subject);
		$email->send($mailContent);
	}

	protected function getNewOrderStatusLog($orderId, $newOrderStatus, $message = null)
	{
		App::uses('Order', 'Model');
		$orderModel = new Order();
		$orderDetails = $orderModel->findById($orderId);

		$message = htmlentities(trim($message));

		if (empty($orderId) || empty($orderDetails)) {
			$log[] = [
				'orderStatus' => Order::ORDER_STATUS_DRAFT,
				'date' => time()
			];
		} else {
			$log = json_decode($orderDetails['Order']['log'], true);
			$orderStatusAlreadyExists = false;

			if ($log) {
				foreach ($log as $row) {
					if ($row['orderStatus'] === $newOrderStatus) {
						$orderStatusAlreadyExists = true;
						break;
					}
				}
			}

			$updatedByUserId = $this->Session->check('User.id') ? $this->Session->read('User.id') : '';
			if ($orderStatusAlreadyExists === false) {
				$log[] = [
					'orderStatus' => $newOrderStatus,
					'date' => time(),
					'message' => $message,
					'updated_by_user_id' => $updatedByUserId,
				];
			}
		}

		return json_encode($log);
	}

	protected function setTheme($theme = null)
	{
		if ($this->Session->check('Theme')) {
			return true;
		}

		App::uses('Site', 'Model');

		if (empty($theme)) {
			$theme = $this->Session->read('Site.theme');
		}

		$theme = !in_array($theme, Site::THEME_OPTIONS) ? Site::THEME_DARK : $theme;

		$navbarTheme = ' navbar-dark bg-dark bg-gradient ';
		$secondaryMenuBg = ' border-warning bg-ivory-light border-2 ';
		$linkColor = ' link-primary ';
		$cartBadgeBg = ' bg-orange ';
		$hightlightLink = ' text-warning ';

		switch ($theme) {
			case Site::THEME_WHITE:
				$navbarTheme = ' navbar-light bg-white ';

				// secondary menu
				$secondaryMenuBg = ' bg-light border-grey border-3 border-bottom border-top-1 border-start-0 border-end-0';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				$hightlightLink = ' text-orange ';
				break;
			case Site::THEME_WHITE_AND_RED:
				$navbarTheme = ' navbar-light bg-white ';

				// secondary menu
				$secondaryMenuBg = ' bg-white ';
				$linkColor = ' link-danger ';
				$cartBadgeBg = ' bg-orange ';
				$hightlightLink = ' text-orange ';
				break;
			case Site::THEME_LIGHT:
				$navbarTheme = ' navbar-light bg-light bg-gradient ';

				// secondary menu
				$secondaryMenuBg = ' bg-white border-grey border-3 border-bottom border-top-1 border-start-0 border-end-0';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				break;
			case Site::THEME_BLUE:
				$navbarTheme = ' navbar-dark bg-primary bg-gradient ';

				// secondary menu bg-primary-light-50
				$secondaryMenuBg = ' bg-primary-light-50 border-primary-light border-3 border-top-0 border-start-0 border-end-0 ';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				break;
			case Site::THEME_GREEN:
				$navbarTheme = ' navbar-dark bg-success bg-gradient ';

				// secondary menu
				$secondaryMenuBg = ' bg-success-light-50 border-success-light border-3 border-top-0 border-start-0 border-end-0 ';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				break;
			case Site::THEME_YELLOW:
				$navbarTheme = ' navbar-light bg-warning bg-gradient ';

				// secondary menu
				$secondaryMenuBg = ' bg-ivory-light border-warning-light border-3 border-top-0 border-start-0 border-end-0';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				$hightlightLink = ' text-dark ';
				break;
			case Site::THEME_DARK_GREY:
				$navbarTheme = ' navbar-dark bg-secondary bg-gradient ';

				// secondary menu
				$secondaryMenuBg = ' bg-light border-grey border-3 border-bottom border-top-0 border-start-0 border-end-0';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				break;
			case Site::THEME_DARK:
				$navbarTheme = ' navbar-dark bg-dark bg-gradient ';

				// secondary menu
				//$secondaryMenuBg = ' border-bottom border-warning-light border-2 bg-ivory-light ';
				$secondaryMenuBg = ' bg-ivory-light border-warning-light border-3 border-top-0 border-start-0 border-end-0';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				break;
			case Site::THEME_PURPLE:
				$navbarTheme = ' navbar-dark bg-purple bg-gradient ';

				// secondary menu
				$secondaryMenuBg = 'border-purple-light border-3 bg-purple-light-50 border-top-0 border-start-0 border-end-0';
				$linkColor = ' link-primary ';
				$cartBadgeBg = ' bg-orange ';
				break;
			case Site::THEME_RED:
				$navbarTheme = ' navbar-dark bg-danger bg-gradient ';

				// secondary menu
				$secondaryMenuBg = 'border-danger-light border-3 bg-danger-light-50 border-top-0 border-start-0 border-end-0';
				$linkColor = ' link-danger ';
				$cartBadgeBg = ' bg-orange ';
				break;
			default:
				break;
		}

		$themeInfo = [
			'name' => $theme,
			'navbarTheme' => $navbarTheme,
			'secondaryMenuBg' => $secondaryMenuBg,
			'linkColor' => $linkColor,
			'cartBadgeBg' => $cartBadgeBg,
			'hightlightLink' => $hightlightLink,
		];

		$this->Session->write('Theme', $themeInfo);

		return true;
	}

	protected function getCacheKey($keyOf)
	{
		$domain = $this->request->subdomains()[0];

		return $domain . '.' . $keyOf;
	}

	protected function getSiteInfoFromCache()
	{
		$cacheKey = $this->getCacheKey('siteInfo');

		return Cache::read($cacheKey, 'verylong');
	}

	protected function writeSiteInfoToCache($value)
	{
		$key = $this->getCacheKey('siteInfo');

		Cache::write($key, $value, 'verylong');
	}

	protected function deleteSiteInfoFromCache()
	{
		$cacheKey = $this->getCacheKey('siteInfo');

		return Cache::delete($cacheKey, 'verylong');
	}

	protected function getCategoryListFromCache()
	{
		$cacheKey = $this->getCacheKey('catList');

		return Cache::read($cacheKey, 'verylong');
	}

	protected function writeCategoryListToCache($value)
	{
		$key = $this->getCacheKey('catList');

		Cache::write($key, $value, 'verylong');
	}

	protected function deleteCategoryListFromCache()
	{
		$cacheKey = $this->getCacheKey('catList');

		// also delete products cache
		// $this->deleteFeaturedProductsFromCache();

		return Cache::delete($cacheKey, 'verylong');
	}

	protected function generateCategoryList()
	{
		if ($catList = $this->getCategoryListFromCache()) {
			return $catList;
		}

		$siteId = $this->Session->read('Site.id');
		App::uses('Category', 'Model');
		$categoryModel = new Category;
		$categories = $categoryModel->getCategories($siteId);

		App::uses('CategoryProduct', 'Model');
		$categoryProductModel = new CategoryProduct;
		$categoryProductsCount = $categoryProductModel->getCategoryProductsCount($siteId);

		if ($categories) {
			foreach ($categories as $index => &$row) {
				$row['Category']['products_count'] = $categoryProductsCount[$row['Category']['id']] ?? 0;
			}
		}
		unset($categoryProductsCount);

		$this->writeCategoryListToCache($categories);

		return $this->getCategoryListFromCache();
	}

	protected function getFeaturedProductsFromCache()
	{
		$cacheKey = $this->Session->read('CacheKeys.featuredProducts');

		return Cache::read($cacheKey, 'verylong');
	}

	protected function writeFeaturedProductsToCache($value)
	{
		$cacheKey = $this->Session->read('CacheKeys.featuredProducts');

		Cache::write($cacheKey, $value, 'verylong');
	}

	protected function deleteFeaturedProductsFromCache()
	{
		$cacheKey = $this->Session->read('CacheKeys.featuredProducts');

		return Cache::delete($cacheKey, 'verylong');
	}

	protected function generateFeaturedProductsList()
	{
		if ($featuredProductsList = $this->getFeaturedProductsFromCache()) {
			return $featuredProductsList;
		}

		App::uses('Product', 'Model');
		$productModel = new Product();
		$allCategoryProducts = $productModel->getAllProducts($this->Session->read('Site.id'), true);
		$this->writeFeaturedProductsToCache($allCategoryProducts);
	}

	protected function sendVerifyOtp($mobile, $toEmail, $otp = null)
	{
		try {
			if (empty($otp)) {
				$otp = random_int(1000, 9999);
			}

			$this->Session->write('verifyOtp', $otp);
			$this->Sms->sendOtp($mobile, $otp);

			$subject = 'OTP Verification for ' . $mobile;
			$bccEmail = Configure::read('AdminEmail');

			$mailContent = '<p>Below is your verfication OTP.</p><p><b>' . $otp . '</b></p><p><br>*Note: The above OTP is valid only for 15mins.</p><br><br>-<br>' . $this->Session->read('Site.title');
			$email = new CakeEmail('smtpNoReply');
			$email->emailFormat('html');
			$email->to([$toEmail => $toEmail]);
			$email->bcc($bccEmail, $bccEmail);
			$email->subject($subject);
			$email->send($mailContent);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}

	protected function verifyOtp($userOtp)
	{
		if ($this->Session->check('verifyOtp') && !empty(trim($userOtp))) {
			$otp = $this->Session->read('verifyOtp');

			if ($otp == $userOtp) {
				$this->Session->delete('verifyOtp');

				return true;
			}
		}

		return false;
	}

	protected function sendOrderEmailAndSms($orderId, $orderStatus, $message = null)
	{
		App::uses('Order', 'Model');
		$orderModel = new Order();

		$emailTemplate = null;
		$subject = null;
		$error = null;
		$order = $orderModel->findById($orderId);
		$message = htmlentities(trim($message));
		$smsNotificationEnabled = (bool) $this->Session->read('Site.sms_notifications') === true;

		switch ($orderStatus) {
			case Order::ORDER_STATUS_NEW:
				$emailTemplate = 'order_new';
				$subject = 'New Order #' . $orderId;
				break;
			case Order::ORDER_STATUS_CONFIRMED:
				$emailTemplate = 'order_confirmed';
				$subject = 'Confirmed - Order #' . $orderId;
				break;
			case Order::ORDER_STATUS_SHIPPED:
				$emailTemplate = 'order_shipped';
				$subject = 'Shipped - Order #' . $orderId;
				break;
			case Order::ORDER_STATUS_DELIVERED:
				$emailTemplate = 'order_delivered';
				$subject = 'Delivered - Order #' . $orderId;
				break;
			case Order::ORDER_STATUS_CANCELLED:
				$emailTemplate = 'order_cancelled';
				$subject = 'Cancelled - Order #' . $orderId;
				break;
				//			case Order::ORDER_STATUS_RETURNED:
				//				$emailTemplate = 'order_returned';
				//				$subject = 'Returned - Order #'.$orderId;
				//				break;
			case Order::ORDER_STATUS_CLOSED:
				$emailTemplate = 'order_closed';
				$subject = 'Closed - Order #' . $orderId;
				break;
			default:
				break;
		}

		if (!$emailTemplate) {
			$error = 'No template found';
		} else {
			$toName = $order['Order']['customer_name'];
			$toEmail = $order['Order']['customer_email'];
			$toPhone = $order['Order']['customer_phone'];
			$toDeliveryUserId = $order['Order']['delivery_user_id'];
			$deliveryPersonPhone = null;

			if ($smsNotificationEnabled && $toDeliveryUserId) {
				App::uses('User', 'Model');
				$userModel = new User();

				$deliveryPersonInfo = $userModel->findById($toDeliveryUserId);
				if ($deliveryPersonInfo) {
					$deliveryPersonPhone = $deliveryPersonInfo['User']['mobile'];
				}
			}

			$bccEmails = $this->getBccEmails();

			// send sms
			if (!in_array($orderStatus, [Order::ORDER_STATUS_DRAFT, Order::ORDER_STATUS_NEW, Order::ORDER_STATUS_CLOSED])) {
				$this->Sms->sendOrderUpdateSms($toPhone, '#' . $orderId, $orderStatus, $message, $this->Session->read('Site.title'));

				if ($deliveryPersonPhone && $orderStatus === Order::ORDER_STATUS_CONFIRMED) {
					// send confirmed order sms to delivery person
					$this->Sms->sendOrderUpdateSms($deliveryPersonPhone, '#' . $orderId, $orderStatus, $message, $this->Session->read('Site.title'));
				}
			}

			if (!empty(trim($toEmail))) {
				$Email = new CakeEmail('smtpNoReply');
				$Email->viewVars(array('order' => $order, 'message' => $message));
				$Email->template($emailTemplate, 'default')
					->emailFormat('html')
					->to([$toEmail => $toName])
					->bcc($bccEmails)
					->subject($subject)
					->send();
			}
		}

		return $error;
	}

	private function loadSiteConfiguration()
	{
		if ($this->Session->check('siteConfiguration')) {
			return true;
		}

		$siteSettingsFile = 'siteinfo.json';

		if (file_exists($siteSettingsFile)) {
			$subdomain = $this->request->subdomains()[0];
			$domain = $this->request->domain();
			$sitesInfo = json_decode(file_get_contents('siteinfo.json'), true);
			$siteInfo = $sitesInfo[$domain][$subdomain] ?? null;
			$this->Session->write('siteConfiguration', $siteInfo);
		} else {
			$this->Session->write('siteConfiguration', null);
		}

		return true;
	}

	private function loadSplashConfiguration()
	{
		$siteInfo = null;
		$siteSplashFile = 'splash.json';

		if (file_exists($siteSplashFile)) {
			$subdomain = $this->request->subdomains()[0];
			$domain = $this->request->domain();
			$sitesInfo = json_decode(file_get_contents($siteSplashFile), true);
			$siteInfo = $sitesInfo[$domain][$subdomain] ?? ($sitesInfo[$domain]['default'] ?? null);
		}

		return $siteInfo;
	}

	/** Function to get Transaction Category info **/
	public function getTransactionCategoryInfo($transactionCategoryId = null)
	{
		App::uses('TransactionCategory', 'Model');
		$this->TransactionCategory = new TransactionCategory();

		if (!$transactionCategoryId) {
			return [];
		} else {
			$conditions = ['TransactionCategory.id' => $transactionCategoryId, 'TransactionCategory.site_id' => $this->Session->read('Site.id')];
			if ($categoryInfo = $this->TransactionCategory->find('first', ['conditions' => $conditions])) {
				return $categoryInfo;
			}
		}
		return [];
	}

	public function getGroupInfo($groupId = null)
	{
		App::uses('Group', 'Model');
		$this->Group = new Group();

		if (!$groupId) {
			return [];
		} else {
			$conditions = ['Group.id' => $groupId, 'Group.site_id' => $this->Session->read('Site.id')];
			if ($groupInfo = $this->Group->find('first', ['conditions' => $conditions])) {
				return $groupInfo;
			}
		}

		return [];
	}
}
