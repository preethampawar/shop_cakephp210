<?php
/**
	rpc call with positional parameters:

--> {"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}
<-- {"jsonrpc": "2.0", "result": 19, "id": 1}

--> {"jsonrpc": "2.0", "method": "subtract", "params": [23, 42], "id": 2}
<-- {"jsonrpc": "2.0", "result": -19, "id": 2}

rpc call with named parameters:

--> {"jsonrpc": "2.0", "method": "subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}
<-- {"jsonrpc": "2.0", "result": 19, "id": 3}

--> {"jsonrpc": "2.0", "method": "subtract", "params": {"minuend": 42, "subtrahend": 23}, "id": 4}
<-- {"jsonrpc": "2.0", "result": 19, "id": 4}

a Notification:

--> {"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}
--> {"jsonrpc": "2.0", "method": "foobar"}

rpc call of non-existent method:

--> {"jsonrpc": "2.0", "method": "foobar", "id": "1"}
<-- {"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "1"}

rpc call with invalid JSON:

--> {"jsonrpc": "2.0", "method": "foobar, "params": "bar", "baz]
<-- {"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}

rpc call with invalid Request object:

--> {"jsonrpc": "2.0", "method": 1, "params": "bar"}
<-- {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}

rpc call Batch, invalid JSON:

--> [
  {"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
  {"jsonrpc": "2.0", "method"
]
<-- {"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}

rpc call with an empty Array:

--> []
<-- {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}

rpc call with an invalid Batch (but not empty):

--> [1]
<-- [
  {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
]

rpc call with invalid Batch:

--> [1,2,3]
<-- [
  {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
  {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
  {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
]

rpc call Batch:

--> [
        {"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
        {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]},
        {"jsonrpc": "2.0", "method": "subtract", "params": [42,23], "id": "2"},
        {"foo": "boo"},
        {"jsonrpc": "2.0", "method": "foo.get", "params": {"name": "myself"}, "id": "5"},
        {"jsonrpc": "2.0", "method": "get_data", "id": "9"}
    ]
<-- [
        {"jsonrpc": "2.0", "result": 7, "id": "1"},
        {"jsonrpc": "2.0", "result": 19, "id": "2"},
        {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
        {"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "5"},
        {"jsonrpc": "2.0", "result": ["hello", 5], "id": "9"}
    ]

rpc call Batch (all notifications):

--> [
        {"jsonrpc": "2.0", "method": "notify_sum", "params": [1,2,4]},
        {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]}
    ]
<-- //Nothing is returned for all notification batches
*/

App::uses('CakeEmail', 'Network/Email');
class ApiController extends AppController{
	public $helpers = array('Html', 'Session');
	public $components = array('RequestHandler', 'CommonFunctions');
	public $uses = array();

	public $site_id;
	public $image_quality = 75;
	public $landingpage_image_quality = 85;
	public $image_thumb_height = 60;
	public $image_thumb_width = 60;
	public $image_small_height = 150;
	public $image_small_width = 150;
	public $image_medium_height = 400;
	public $image_medium_width = 400;
	public $image_large_height = 600;
	public $image_large_width = 600;
	public $image_xl_height = 600;
	public $image_xl_width = 960;
	public $image_landingpage_height = 500;
	public $image_landingpage_width = 960;
	private $allowedActions = ['authenticate', 'auth', 'getProducts', 'getCategoryProducts', 'getCategoryProduct', 'getNavLinks', 'getSiteInfo', 'getLandingPageInfo', 'getPageContent', 'addProductToCart', 'getShoppingCartDetails',
		'updateShoppingCart', 'confirmOrder'];

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');

		header('Access-Control-Request-Headers: X-Requested-With, accept, content-type');
		header('Access-Control-Allow-Methods: GET, POST');
		header('Access-Control-Allow-Origin: *');
		
		$this->RequestHandler->addInputType('json', array('json_decode', true));
		$this->response->type('json');

		$this->autoRender = false;
		$this->layout = false;
		if(!in_array($this->request->params['action'], $this->allowedActions)) {
			$this->authorize();
		}
		// if($this->request->params['action'] != 'authenticate') {
			// $this->authorize();
		// }
	}

	private function validateSecretKey() {
		if(isset($_GET['key']) and !empty($_GET['key'])) {
			list($site_id, $user_id) = explode('#', base64_decode($_GET['key']));
			$this->site_id = $site_id;
			$site_info = $this->getSiteInfo($site_id);
			if(!empty($site_info)) {
				if($site_info['Site']['user_id'] == $user_id) {
					return true;
				}
			}
		}
		return false;
	}

	private function authorize() {
		if($this->validateSecretKey()) {
			return true;
		} else {
			$this->sendResponse('error', 'Unauthorized access', null);
		}
	}

	public function sendResponse($type, $data=null, $id=null, $error=false) {
		switch($type) {
			case 'result':
				$response = array(
					"jsonrpc" => "2.0",
					"result" => $data,
					"id" => $id
				);
			break;

			case 'notification':
				$response = array(
					"jsonrpc" => "2.0",
					"method" => $data
				);
			break;

			case 'error':
				$response = array(
					"jsonrpc" => "2.0",
					"error" => $data,
					"id" => $id
				);
			break;

			case 'default':
				$response = array(
					"jsonrpc" => "2.0"
				);
			break;
		}
		
		// array holding allowed Origin domains
		$allowedOrigins = array(
		  '(http(s)://)?(www\.)?letsgreenify\.com',
		  '(http(s)://)?(www\.)?ec\.local',
		);
		 
		if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
		  foreach ($allowedOrigins as $allowedOrigin) {
			if (preg_match('#' . $allowedOrigin . '#', $_SERVER['HTTP_ORIGIN'])) {
			  header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
			  header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			  header('Access-Control-Max-Age: 1000');
			  header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
			  break;
			}
		  }
		}
		$response = json_encode($response);
		echo $response;
		exit;
	}

	public function authenticate($site_id) {
		if(empty($site_id)) {
			$this->sendResponse('error', 'Invalid Request');
		}

		$error = false;
		$error_msg = '';
		$data = array();
		$user_id = null;

		if($this->request->isPost()) {
			$params = $this->request->data;
			if(!isset($params['email']) or empty($params['email'])) {
				$error = true;
				$error_msg = 'Email Address is required';
			}
			if(!isset($params['password']) or empty($params['password'])) {
				$error = true;
				$error_msg = 'Password is required';
			}

			if(!$error) {
				App::uses('User', 'Model');
				$this->User = new User();
				$user_info = $this->User->findByEmail($params['email']);
				if(!empty($user_info)) {
					App::uses('Site', 'Model');
					$this->Site = new Site();
					$site_info = $this->Site->findById($site_id);

					$pwd = md5($params['password']);
					if($user_info['User']['password'] == $pwd) {
						if(!$user_info['User']['superadmin']) {
							$user_id = $user_info['User']['id'];
							if($user_info['User']['site_id'] == $site_id) {
								unset($user_info['Site']);
								unset($user_info['User']['password']);
								$data = $user_info;
								$data['superadmin'] = false;
							} else {
								$error = true;
								$error_msg = 'Unauthorized user';
							}
						} else {
							if($site_info) {
								$site_user_id = $site_info['Site']['user_id'];
								$site_user_info = $this->User->findById($site_user_id);
								$user_id = $site_user_info['User']['id'];
								unset($site_user_info['Site']);
								unset($site_user_info['User']['password']);

								$data = $site_user_info;
								$data['superadmin'] = true;
							}
						}
					} else {
						$error = true;
						$error_msg = 'Invalid Email or Password';
					}
				} else {
					$error = true;
					$error_msg = 'User not found';
				}
			}
		} else {
			$error = true;
			$error_msg = 'Invalid Request';
		}

		if($error) {
			$this->sendResponse('error', $error_msg);
		} else {
			$data['site'] = $site_info['Site'];
			$data['secret_key'] = urlencode(base64_encode($site_id.'#'.$user_id));
			$this->sendResponse('result', $data, $user_id);
		}
	}

	public function products($category_id=null, $return = false, $product_id = null) {
		$site_id = $this->site_id;
		$category_id = (int)$category_id;
		$product_id = (int)$product_id;
		$products = null;
		$categories = null;

		App::uses('Product', 'Model');
		$this->Product = new Product();

		$site_info = $this->getSiteInformation($site_id);
		$site_url = 'http://'.$site_info['Site']['default_domain']['name'].'/';
		$image_path = $site_url.'images/getImageContent/';



		$products_list = $this->Product->getActiveCategoryProducts($site_id, $category_id, $product_id);

	
		if(!empty($products_list)) {			
			foreach($products_list as $row) {
				$categories[$row['Category']['id']] = $row['Category']['name'];

				$products[$row['Category']['id']][$row['Product']['id']]['id'] = $row['Product']['id'];
				$products[$row['Category']['id']][$row['Product']['id']]['name'] = $row['Product']['name'];
				$products[$row['Category']['id']][$row['Product']['id']]['name_slug'] = Inflector::slug($row['Product']['name'], '-');
				$products[$row['Category']['id']][$row['Product']['id']]['description'] = $row['Product']['description'];
				$products[$row['Category']['id']][$row['Product']['id']]['featured'] = $row['Product']['featured'];
				$products[$row['Category']['id']][$row['Product']['id']]['request_price_quote'] = $row['Product']['request_price_quote'];
				$products[$row['Category']['id']][$row['Product']['id']]['category_id'] = $row['Category']['id'];
				$products[$row['Category']['id']][$row['Product']['id']]['category_name'] = $row['Category']['name'];
				$products[$row['Category']['id']][$row['Product']['id']]['category_name_slug'] = Inflector::slug($row['Category']['name'], '-');

				if(!empty($row['Product']['Image'])) {
					foreach($row['Product']['Image'] as $index=>$image) {
						$images[$index] = $image;
						$images[$index]['thumb_url'] = $image_path.$this->getImageUri($image['id'], 'thumb');
						$images[$index]['small_url'] = $image_path.$this->getImageUri($image['id'], 'small');
						$images[$index]['medium_url'] = $image_path.$this->getImageUri($image['id'], 'medium');
						$images[$index]['large_url'] = $image_path.$this->getImageUri($image['id'], 'large');
						$images[$index]['ori_url'] = $image_path.$this->getImageUri($image['id'], 'original');
					}
				}
				else {
					$image['id'] = 0;
					$image['caption'] = "No image";
					$images[0] = $image;
					$images[0]['thumb_url'] = $image_path.$this->getImageUri($image['id'], 'thumb');
					$images[0]['small_url'] = $image_path.$this->getImageUri($image['id'], 'small');
					$images[0]['medium_url'] = $image_path.$this->getImageUri($image['id'], 'medium');
					$images[0]['large_url'] = $image_path.$this->getImageUri($image['id'], 'large');
					$images[0]['ori_url'] = $image_path.$this->getImageUri($image['id'], 'original');
				}
				$products[$row['Category']['id']][$row['Product']['id']]['Image'] = $images;
			}
		}
		$data['Products'] = $products;
		$data['ImageSettings']['thumb']['width'] = $this->image_thumb_width;
		$data['ImageSettings']['thumb']['height'] = $this->image_thumb_height;
		$data['ImageSettings']['small']['width'] = $this->image_small_width;
		$data['ImageSettings']['small']['height'] = $this->image_small_height;
		$data['ImageSettings']['medium']['width'] = $this->image_medium_width;
		$data['ImageSettings']['medium']['height'] = $this->image_medium_height;
		$data['ImageSettings']['large']['width'] = $this->image_large_width;
		$data['ImageSettings']['large']['height'] = $this->image_large_height;

		$data['Category']['id'] = 0;
		$data['Category']['name'] = null;
		$data['Category']['active'] = 0;
		$data['Categories'] = $categories;
		if($category_id) {
			App::uses('Category', 'Model');
			$this->Category = new Category();
			$category = $this->Category->findById($category_id);
			$data['Category']['id'] = $category['Category']['id'];
			$data['Category']['name'] = $category['Category']['name'];
			$data['Category']['active'] = $category['Category']['active'];
		}
		if($return) {
			return $data;
		}
		$this->sendResponse('result', $data);
	}

	public function categories($active=1) {
		$site_id = $this->site_id;
		App::uses('Category', 'Model');
		$this->Category = new Category();
		if($active) {
			$categories = $this->Category->find('all', array('conditions'=>array('Category.site_id'=>$site_id, 'Category.active'=>1), 'fields'=>array('Category.id', 'Category.name'), 'order'=>array('Category.name')));
		} else {
			$categories = $this->Category->find('all', array('conditions'=>array('Category.site_id'=>$site_id), 'fields'=>array('Category.id', 'Category.name'), 'order'=>array('Category.name')));
		}
		$this->sendResponse('result', $categories);
	}

	public function getImageUri($image_id, $image_type='', $type = 'crop') {
		// image_type => thumb, small, medium, large, original

		// 'type' => original, exact, portrait, landscape, auto, crop
		//$type = 'crop';
		$image_uri = '';
		switch($image_type) {
			case 'original':
				$type = 'original';
				$image_uri = $image_id.'/'.$type;
				break;

			case 'thumb':
				$image_uri = $image_id.'/'.$type.'/'.$this->image_thumb_height.'/'.$this->image_thumb_width.'/'.$this->image_quality;
				break;

			case 'small':
				$image_uri = $image_id.'/'.$type.'/'.$this->image_small_height.'/'.$this->image_small_width.'/'.$this->image_quality;
				break;

			case 'medium':
				$image_uri = $image_id.'/'.$type.'/'.$this->image_medium_height.'/'.$this->image_medium_width.'/'.$this->image_quality;
				break;

			case 'large':
				$image_uri = $image_id.'/'.$type.'/'.$this->image_large_height.'/'.$this->image_large_width.'/'.$this->image_quality;
				break;

			case 'xl':
				$image_uri = $image_id.'/'.$type.'/'.$this->image_xl_height.'/'.$this->image_xl_width.'/'.$this->image_quality;
				break;
				
			case 'landingpage':
				$image_uri = $image_id.'/'.$type.'/'.$this->image_landingpage_height.'/'.$this->image_landingpage_width.'/'.$this->landingpage_image_quality;
				break;
				
			case 'default':
				$type = 'original';
				$image_uri = $image_id.'/'.$type;
				break;
		}

		return $image_uri;
	}

	public function getSiteInformation($site_id) {
		App::uses('Site', 'Model');
		$this->Site = new Site;
		$site_info = $this->Site->findById($site_id);
		$site_domains = $site_info['Domain'];
		$site_default_domain = null;
		if(!empty($site_domains)) {
			foreach($site_domains as $index=>$row) {
				if($row['default'] == true) {
					$site_default_domain = $row;
					break;
				}
			}
		}
		$site_info['Site']['default_domain'] = $site_default_domain;
		return $site_info;
	}

	public function getProducts($siteId, $type = null) {
		$this->site_id = $siteId;
		$data = $this->products(null, true);
		$allproducts = [];
		if(!empty($data['Products'])) {
			foreach($data['Products'] as $categoryId => $catProducts) {
				$allproducts = array_merge($allproducts, $catProducts);
			}

			// check for featured products			
			if($type == 'featured') {
				$featuredProducts = [];
				foreach($allproducts as $product) {
					if($product['featured']) {
						$featuredProducts[] = $product;
					}
				}
				$allproducts = $featuredProducts;
			}
		}
		$this->sendResponse('result', $allproducts);
	}

	public function getCategoryProducts($siteId, $categoryId=null) {
		$this->site_id = $siteId;
		$data = $this->products($categoryId, true);
		$this->sendResponse('result', $data);
	}
	
	public function getCategoryProduct($siteId, $categoryId, $productId) {
		$this->site_id = $siteId;
		$data = $this->products($categoryId, true, $productId);
		$this->sendResponse('result', $data);
	}
	
	public function getNavLinks($siteId) {
		$this->site_id = $siteId;
		
		App::uses('Content', 'Model');
		$contentModel = new Content();
		$pages = $contentModel->getTopNavContent($siteId);

		$links = [];
		if(!empty($pages)) {
			foreach($pages as $row) {
				$contentID = $row['Content']['id'];
				$contentTitle = $row['Content']['title'];
				$contentTitleSlug = Inflector::slug($row['Content']['title'], '-');
				// echo '<li id="content'.$contentID.'">';
				// echo $this->Html->link($contentTitle, '/contents/show/'.$contentID.'/'.$contentTitleSlug, array('title'=>$contentTitle));
				// echo '</li>';
				$links[] = [
					'contentId' => $contentID,
					'contentTitle' => $contentTitle,
					'contentTitleSlug' => $contentTitleSlug,
					'url' => '/contents/show/'.$contentID.'/'.$contentTitleSlug					
				];
			}
		}
		
		
		$this->sendResponse('result', $links);
	}
	
	public function getSiteInfo($site_id, $return = false) {
		$site_info = $this->getSiteInformation($site_id);
		$site_url = 'http://'.$site_info['Site']['default_domain']['name'].'/';
		$image_path = $site_url.'images/getImageContent/';
		
		$response = ['site_info' => $site_info['Site'], 'site_url' => $site_url, 'image_path' => $image_path];

		if($return) {
			return $response;
		}
		
		$this->sendResponse('result', $response);
	}

	public function getLandingPageInfo($site_id) {
		App::uses('Content', 'Model');
		$this->Content = new Content;	
		$showSiteInfo = false;
		$contentInfo = $this->Content->getLandingPageInfoWithImages($site_id, $showSiteInfo);
		$images = [];
		if($contentInfo and $contentInfo['Images']) {
			foreach($contentInfo['Images'] as $index => $image) {
				$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
				$imageCaption = (!empty($image['Image']['caption'])) ? trim($image['Image']['caption']) : '';
				$captionSlug = Inflector::slug($imageCaption, '-');
				
				$images[] = [
					'id' => $imageID,
					'caption' => $imageCaption,
					'captionSlug' => $captionSlug,					
					'url' => ($this->getImageUri($image['Image']['id'], 'landingpage', 'crop'))
				];				
			}
			$contentInfo['Images'] = $images;
		}
		
		$this->sendResponse('result', $contentInfo);
	}
	
	public function getPageContent($site_id, $contentId, $contentSlug) {		
		App::uses('Content', 'Model');
		$this->Content = new Content;
		$conditions = array('Content.site_id'=>$site_id, 'Content.id'=>$contentId);		
		$contentInfo = $this->Content->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
		
		if($contentInfo) {
			App::uses('Image', 'Model');
			$this->Image = new Image;
			$this->Image->recursive = -1;
			$contentInfo['Images'] = $this->Image->findAllByContentId($contentInfo['Content']['id']);		
			$title_for_layout = $contentInfo['Content']['title'];
			
			$site_info = $this->getSiteInformation($site_id);
			$site_url = 'http://'.$site_info['Site']['default_domain']['name'].'/';
			$image_path = $site_url.'images/getImageContent/';
			
			$images = [];
			foreach($contentInfo['Images'] as $index => $image) {
				$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
				$imageCaption = (!empty($image['Image']['caption'])) ? trim($image['Image']['caption']) : '';
				$captionSlug = Inflector::slug($imageCaption, '-');
				
				$images[$index] = [
					'id' => $imageID,
					'caption' => $imageCaption,
					'captionSlug' => $captionSlug,					
					'url' => ($this->getImageUri($image['Image']['id'], 'medium'))
				];				
				$images[$index]['thumb_url'] = $image_path.$this->getImageUri($image['Image']['id'], 'thumb');
				$images[$index]['small_url'] = $image_path.$this->getImageUri($image['Image']['id'], 'small');
				$images[$index]['medium_url'] = $image_path.$this->getImageUri($image['Image']['id'], 'medium');
				$images[$index]['large_url'] = $image_path.$this->getImageUri($image['Image']['id'], 'large');
				$images[$index]['ori_url'] = $image_path.$this->getImageUri($image['Image']['id'], 'original');
			}
			$contentInfo['Images'] = $images;
			
		} else {
			$contentInfo['Images'] = [];
		}
		
		
		$this->sendResponse('result', $contentInfo);
	}
	
	public function addProductToCart($siteId) {
		$result = [];
		if($this->request->isPost()) {						
			$data = $this->request->data;
			
			$shoppingCartId = $data['shoppingCartId'];
			$productId = $data['productId'];
			$quantity = $data['quantity'];
			$categoryId = $data['categoryId'];
			if(!$shoppingCartId) {
				$shoppingCartId = $this->getShoppingCartID($siteId);
			}
			
			$this->addToCart($siteId, $shoppingCartId, $categoryId, $productId, $quantity);
			
			
			$result['shoppingCartId'] = $shoppingCartId;
		}
		
		$this->sendResponse('result', $result);
	}
	
	/**
	 * Function to get shopping cart products
	 */
	function getShoppingCartDetails($siteId, $shoppingCartId) {
		$shoppingCart = $this->getShoppingCartProducts($siteId, $shoppingCartId);
		$this->sendResponse('result', $shoppingCart);
	} 
	
	function updateShoppingCart($siteId) {
		$result = [];
		if($this->request->isPut()) {						
			$data = $this->request->data;			
			$shoppingCartId = $data['shoppingCartId'];
			$cartInfo = $data['cart'];			
			$this->updateCart($siteId, $shoppingCartId, $cartInfo);
			
			$result['shoppingCartId'] = $shoppingCartId;
		}
		
		$this->sendResponse('result', $result);
	}
	
	function confirmOrder($siteId) {
		$result = [];
		$orderPlaced = false;
		if($this->request->isPut()) {						
			$data = $this->request->data;			
			$shoppingCartId = $data['shoppingCartId'];
			$cartInfo = $data['cartDetails'];
			$orderType = $data['orderType'];
			if($this->updateOrder($siteId, $shoppingCartId, $orderType, $cartInfo)) {
				$orderPlaced = true;
				if($this->sendEmailOrderConfirmation($siteId, $shoppingCartId)) {
					$orderPlaced = true;
				}
			}
		}		
		//$orderPlaced = false;
		$result['orderPlaced'] = $orderPlaced;
		
		$this->sendResponse('result', $result);
	}
	
	function auth($siteId) {
		$response = ['isAuthenticated' => false, 'token' => '', 'userInfo' => []];
		if($this->request->isPost()) {
			$data = $this->request->data;
			$userInfo = $this->CommonFunctions->authenticate($data);
			if($userInfo) {
				$response['isAuthenticated'] = true;				
				// generate token
				$tokenInfo = $this->CommonFunctions->generateToken($userInfo['User']['id']);				
				$response['token'] = $tokenInfo['Token']['token'];
				
				unset($userInfo['User']['id']);
				$response['userInfo'] = $userInfo['User'];
				
			}
		}
		
		$this->sendResponse('result', $response);
	}
}
?>