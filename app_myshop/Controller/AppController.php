<?php
App::uses('Controller', 'Controller');
App::uses('Validation', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('AuthComponent', 'Controller/Component');

class AppController extends Controller
{
	public $helpers = ['Html', 'Form', 'Session', 'Number', 'Text', 'Img', 'Js' => ['Jquery'], 'App'];

	public $noReplyEmail = [
		'fromName' => 'ApnaStores',
		'fromEmail' => 'no-reply@letsgreenify.com',

	];

	public $components = [
		'Session',
	];

	public function beforeFilter()
	{
		parent::beforeFilter();

		if (isset($this->request->params['admin']) && $this->request->params['admin'] === true) {
			if (!$this->Session->check('userLoggedIn') || $this->Session->read('userLoggedIn') === false) {
				$this->redirect('/users/logout');
			}

			if (!$this->isSellerForThisSite()) {
				$this->errorMsg('You don\'t have permissions to access this location');
				$this->redirect('/users/setView/buyer');
			}
		}

		// set layout
		$this->layout = 'buyer';
		if ($this->Session->read('inSellerView')) {
			$this->layout = 'seller';
		}

		// Parse http request and load the appropriate domain
		$this->parseURL();

		/* Domain Information */
		Configure::write('Domain', $this->request->domain());
		Configure::write('DomainName', $this->request->host());
		Configure::write('DomainUrl', 'http://' . $this->request->host());

		/* Site email configuration */
		$supportEmail = $this->Session->read('Site.contact_email');
		Configure::write('SupportEmail', $supportEmail);
		Configure::write('NoReply', ['name' => $this->request->domain(), 'email' => 'noreply@' . $this->request->domain()]);
		Configure::write('Security.salt', '');
	}

	public function setBuyerCategories()
	{
		App::uses('Category', 'Model');
		$categoryModel = new Category;
		$categories = $categoryModel->getCategories($this->Session->read('Site.id'));
		$this->Session->write('SiteCategories', $categories);
	}

	private function getDefaultDomainInfo()
	{
		$siteInfo = [];
		$dName = $this->request->host();

		App::import('Model', 'Domain');
		$domainModel = new Domain;
		$domainModel->unbindModel(['belongsTo'=>['Site']]);
		$sitedomain = $domainModel->findByName($dName, ['Domain.id', 'Domain.site_id']);

		if (!empty($sitedomain)) {

			// find all domains related to the selected site and get the default domain
			$sitedomains = $domainModel->findAllBySiteId($sitedomain['Domain']['site_id']);

			App::uses('User', 'Model');
			$userModel = new User;
			$userModel->recursive = '-1';
			$userModel->unbindModel(['hasOne'=>['Site']]);

			foreach ($sitedomains as $row) {
				if ($row['Domain']['default']) {
					$siteUser = $userModel->findById($row['Site']['user_id']);

					$siteInfo['Domain'] = $row['Domain'];
					$siteInfo['Site'] = $row['Site'];
					$siteInfo['Site']['Account'] = $siteUser['User'] ?? null;

					break;
				}
			}
		}

		return $siteInfo;
	}

	/**
	 * Function to parse request URL
	 */
	public function parseURL()
	{
		$baseSite = Configure::read('BaseDomainUrl');
		$siteNotFoundUri = '/sites/notFound';
		$redirectUrl = $baseSite . $siteNotFoundUri;

		$dName = $this->request->host();
		$uri = $this->request->here();
		$params = ($uri == '/') ? null : $uri;

		$defaultDomainInfo = $this->getDefaultDomainInfo();

		// check if default domain
		if ($defaultDomainInfo['Domain']['name'] !== $dName) {
			$redirectLink = 'http://' . $defaultDomainInfo['Domain']['name'] . $params;
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
			];

			if (!in_array($this->request->params['action'], $allowedActionsInMaintenance, true)) {
				$this->redirect('/sites/under_maintenance');
			}
		}

		// if all is right, then write domain info to session so next time there's no need for site checks
		$this->Session->write('Site', $defaultDomainInfo['Site']);
		$this->Session->write('Domain', $defaultDomainInfo['Domain']);
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function updateSiteVisits()
	{
		App::uses('Site', 'Model');
		$siteModel = new Site;
		$siteModel->recursive = -1;
		$siteInfo = $siteModel->findById($this->Session->read('Site.id'));
		$visitCount = $siteInfo['Site']['views'];
		$tmp['Site']['id'] = $this->Session->read('Site.id');
		$tmp['Site']['views'] = $visitCount + 1;
		$siteModel->save($tmp);
		$this->Session->write('SiteVisits', $visitCount);
		return $visitCount;
	}

	public function errorMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/error');
		}
		return true;
	}

	public function noticeMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/notice');
		}
		return true;
	}

	public function successMsg($msg)
	{
		if ($msg) {
			$this->Session->setFlash($msg, 'Flash/success');
		}

		return true;
	}

	function checkSuperAdmin()
	{
		if (!$this->Session->read('SuperAdmin')) {
			$this->Session->setFlash('You are not authorized to view this page');
			$this->redirect('/');
		} else {
			return true;
		}
	}

	function checkAdmin()
	{
		if (!$this->Session->read('User.admin')) {
			$this->Session->setFlash('You are not authorized to view this page');
			$this->redirect('/');
		} else {
			return true;
		}
	}

	public function checkSeller()
	{
		if (!$this->Session->read('User.seller')) {
			$this->Session->setFlash('You are not authorized to view this page');
			$this->redirect('/');
		} else {
			return true;
		}
	}

	public function isSeller()
	{
		if ($this->Session->read('User.type') == 'seller') {
			return true;
		}
		return false;
	}

	public function isSellerForThisSite()
	{
		if ($this->Session->read('User.superadmin') == 1) {
			return true;
		}

		if (!$this->isSeller()) {
			return false;
		}

		if ($this->Session->read('User.id') == $this->Session->read('Site.user_id')) {
			return true;
		}

		return false;
	}

	function checkLandingPage()
	{
		$contentInfo = $this->getLandingPageInfo();
		if (isset($contentInfo['Content']['landing_page']) and !empty($contentInfo['Content']['landing_page'])) {
			return true;
		}
		return false;
	}

	/**
	 * Function to get landing page information.
	 */
	function getLandingPageInfo()
	{
		App::uses('Content', 'Model');
		$contentModel = new Content;

		$conditions = ['Content.site_id' => $this->Session->read('Site.id'), 'Content.landing_page' => '1'];
		$contentInfo = $contentModel->find('first', ['conditions' => $conditions]);

		if (empty($contentInfo)) {
			$data = [];
			$data['Content']['id'] = null;
			$data['Content']['landing_page'] = '1';
			$data['Content']['site_id'] = $this->Session->read('Site.id');
			$data['Content']['title'] = 'Landing Page';
			if ($contentModel->save($data)) {
				$contentInfo = $contentModel->read();
			}
		}

		return $contentInfo;
	}

	/**
	 * Function to check if product is from selected site.
	 */
	function isSiteProduct($productID)
	{
		App::uses('Product', 'Model');
		$productModel = new Product;
		$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.id' => $productID];
		return $productModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	public function getRearrangedImages($data)
	{
		if (!is_array($data) and !empty($data)) {
			$data = json_decode($data);
		}

		$images = [];
		if($data) {
			foreach ($data as $row) {
				$images[$row->commonId][$row->type] = $row;
			}
		}

		return $images;
	}

	public function getHighlightImage($data)
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

	/**
	 * Function to check if category is from selected site.
	 */
	function isSiteImage($imageID)
	{
		App::uses('Image', 'Model');
		$imageModel = new Image;
		$conditions = ['Image.site_id' => $this->Session->read('Site.id'), 'Image.id' => $imageID];
		return $imageModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	/**
	 * Function to check if category is from selected site.
	 */
	function isSiteCategory($categoryID)
	{
		App::uses('Category', 'Model');
		$categoryModel = new Category;
		$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.id' => $categoryID];
		return $categoryModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	/**
	 * Function to check if content is from selected site.
	 */
	function isSiteContent($contentID)
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
	function isSiteBlog($blogID)
	{
		App::uses('Blog', 'Model');
		$blogModel = new Blog;
		$conditions = ['Blog.site_id' => $this->Session->read('Site.id'), 'Blog.id' => $blogID];
		$content = $blogModel->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
		return $content;
	}

	/**
	 * Function to check valid image size
	 */
	function isValidImageSize($imgSize)
	{
		if ($imgSize > 0) {
			$maxSize = Configure::read('MaxImageSize');
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
	function isValidImage($image)
	{
		if (isset($image['tmp_name'])) {
			$info = getimagesize($image['tmp_name']);
			$validImage = false;
			if ($info) {
				switch ($info[2]) {
					case IMAGETYPE_PNG:
						$validImage = true;
						break;
					case IMAGETYPE_JPEG:
						$validImage = true;
						break;
					case IMAGETYPE_GIF:
						$validImage = true;
						break;
					default:
						$validImage = false;
						break;
				}
			}
			return ($validImage) ? true : false;
		}
		return false;
	}

	/**
	 * Function to delete a category by id
	 */
	function deleteCategory($categoryID)
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
				App::uses('CategoryProduct', 'Model');
				$categoryProductModel = new CategoryProduct;
				$categoryProductModel->recursive = '-1';
				$categoryProducts = $categoryProductModel->findAllByCategoryId($catId);
				if (!empty($categoryProducts)) {
					foreach ($categoryProducts as $row2) {
						$productID = $row2['CategoryProduct']['product_id'];
						try {
							$this->deleteProduct($productID);
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
	function deleteImage($imageID)
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
	function deleteProduct($productID, $categoryID = null)
	{
		App::uses('CategoryProduct', 'Model');
		$categoryProductModel = new CategoryProduct;
		$categoryProductModel->recursive = -1;

		App::uses('ProductVisit', 'Model');
		$productVisitModel = new ProductVisit;

		// remove this product from categories
		if (!empty($categoryID)) {
			// remove this product from the selected category
			$conditions = ['CategoryProduct.product_id' => $productID, 'CategoryProduct.category_id' => $categoryID];
			$categoryProductModel->deleteAll($conditions);

			// remove this product from product_visits table
			$conditions = ['ProductVisit.product_id' => $productID, 'ProductVisit.category_id' => $categoryID];
			$productVisitModel->deleteAll($conditions);
		} else {
			// remove this product from all categories
			$conditions = ['CategoryProduct.product_id' => $productID];
			$categoryProductModel->deleteAll($conditions);

			// remove this product from product_visits table
			$conditions = ['ProductVisit.product_id' => $productID];
			$productVisitModel->deleteAll($conditions);

			// delete product images
			App::uses('Image', 'Model');
			$imageModel = new Image;
			$productImages = $imageModel->findAllByProductId($productID);
			if (!empty($productImages)) {
				foreach ($productImages as $row) {
					$this->deleteImage($row['Image']['id']);
				}
			}

			// delete product from database
			App::uses('Product', 'Model');
			$productModel = new Product;
			$productModel->delete($productID);
		}
		return true;
	}

	/**
	 * Function to get shopping cart id for the current session
	 */
	function getShoppingCartID()
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
	function getShoppingCartProducts()
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
	function addProductVisit($categoryID, $productID, $categoryName, $productName)
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
	function getProductVisits($categoryID, $productID)
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
	function getRecentProductViewsByUser()
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
	function getMostViewedProductsList()
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
	function getSiteCategoriesProductsImages($options = [])
	{
		App::uses('Product', 'Model');
		$productModel = new Product;

		$data = $productModel->getSiteCategoriesProducts($options = [], $this->Session->read('Site.id'));
		return $data;
	}

	function sendSMS($to, $message = null)
	{
		try {
			$message = trim($message);
			if (!empty($message)) {
				$params = [
					'apikey=zxIG5YlUzkafKUwd6X82dw',
					'senderid=ENURSE',
					'channel=trans',
					'dcs=0',
					'flashsms=0',
					'number=' . $to,
					'text=' . urlencode($message),
					'route=8',
				];
				$params_string = implode('&', $params);

				$api_url = 'http://apsms.s2mark.in/api/mt/SendSMS?' . $params_string;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$contents = curl_exec($ch);
				if (curl_errno($ch)) {
					$contents = curl_error($ch);
				} else {
					curl_close($ch);

					//return true;
				}

				if (!is_string($contents) || !strlen($contents)) {
					$contents = '';
				}
			}
		} catch (Exception $e) {
			//..
		}

		return false;
	}

	public function clearSession()
	{
		$this->Session->delete('loginOtp');
		$this->Session->delete('loginUser');
		$this->Session->delete('userLoggedIn');
		$this->Session->delete('enrollOtp');
		$this->Session->delete('enrollUser');
		$this->Session->delete('User');
		$this->Session->delete('Site');

		$this->Session->destroy();
	}

	public function productsLimitExceeded()
	{
		App::uses('Product', 'Model');
		$productModel = new Product();

		$productsLimitForThisSite = (int)$this->Session->read('Site.products_limit');

		$productsCount = $this->getSiteProductsCount();

		if($productsCount <= $productsLimitForThisSite) {
			return false;
		}

		return true;
	}

	public function getSiteProductsCount() {
		App::uses('Product', 'Model');
		$productModel = new Product();

		return $productModel->find('count', ['conditions' => ['Product.site_id' => $this->Session->read('Site.id')]]);
	}

}
?>
