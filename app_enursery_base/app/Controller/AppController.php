<?php
App::uses('Controller', 'Controller');
App::uses('Validation', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('AuthComponent', 'Controller/Component');
class AppController extends Controller {
	public $helpers = array('Html', 'Form', 'Session', 'Number', 'Js' => array('Jquery'));
	
	public $components = array(
        'Session',
		'CommonFunctions',
        'Auth' => array(
            'loginAction' => array(
				'controller' => 'users',
				'action' => 'login'
			),
			'logoutAction' => array(
				'controller' => 'users',
				'action' => 'logout'
			),
			'authError' => 'Did you really think you are allowed to see that?',
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
		
		$subdomain = $this->request->subdomains();
		if(empty($subdomain)) {
			$this->redirect(Configure::read('DomainUrl'));
		}	
			
		App::uses('Site', 'Model');
		$this->Site = new Site;
		$siteInfo = $this->Site->findByName('www');
		if(!empty($siteInfo['Site']['search_engine_code'])) {
			$this->Session->write('SearchEngineCode', $siteInfo['Site']['search_engine_code']);
		}
		else {
			$searchCode = <<<'EOT'
<script>
  (function() {
    var cx = 'partner-pub-1985514378863670:0294881708';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchbox-only></gcse:searchbox-only>
EOT;
			$this->Session->write('SearchEngineCode', $searchCode);
		}
	}
	
	function checkSuperAdmin() {
		if(!$this->Session->read('User.superadmin')) {
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
	
	function setCaptchaColor() {
		$colors = array('red', 'green', 'blue', 'pink', 'yellow', 'black', 'grey');
		$randomColor = $colors[rand('0', '6')];
		
		// if captcha color is not set in session, then set default values.
		if(!$this->Session->check('prevCaptchaColor')) {
			$this->Session->write('prevCaptchaColor', $randomColor);
			$this->Session->write('presentCaptchaColor', $randomColor);
		}		
					
		
		$this->Session->write('prevCaptchaColor', $this->Session->read('presentCaptchaColor')); // update prev captcha. 	
		$this->Session->write('presentCaptchaColor', $randomColor); // reset present captcha
	}
		
	function validCaptchaColor($colorCode=null) {
		$colorCodes = array('c_r'=>'red', 'c_g'=>'green', 'c_blue'=>'blue', 'c_b'=>'black', 'c_grey'=>'grey', 'c_p'=>'pink', 'c_y'=>'yellow');
		
		if(isset($colorCodes[$colorCode])) {
			if($this->Session->check('prevCaptchaColor')) {
				if($this->Session->read('prevCaptchaColor') == $colorCodes[$colorCode]) {
					return true;
				}
			}
		}
		return false;
	}
	
	function sendSMS($to, $message = null) {
		return true;
		try {
			$message = trim($message);			
			if(!empty($message)) {
				$params = array(
					'apikey=zxIG5YlUzkafKUwd6X82dw',
					'senderid=ENURSE',
					'channel=trans',
					'dcs=0',
					'flashsms=0',
					'number='.$to,
					'text='.urlencode($message),
					'route=8'
				);
				$params_string = implode('&', $params);
				
				$api_url = 'http://apsms.s2mark.in/api/mt/SendSMS?'.$params_string;
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $api_url);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
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
	
	public function getSiteInfo($site_id) {
		App::uses('Site', 'Model');
		$this->Site = new Site();
		return $this->Site->findById($site_id);
	}
	
	
	/** 
	 * Function to get shopping cart id for the current session
	 */
	function getShoppingCartID($siteId) {
		$shoppingCartID = null;
		if($this->Session->check('ShoppingCart.id')) {
			$shoppingCartID = $this->Session->read('ShoppingCart.id');
		}
		else {
			App::uses('ShoppingCart', 'Model');
			$this->ShoppingCart = new ShoppingCart;
			$tmp['ShoppingCart']['id'] = null;
			$tmp['ShoppingCart']['site_id'] = $siteId;
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
	function getShoppingCartProducts($siteId, $shoppingCartId) {
		$shoppingCart = null;
		if($shoppingCartId) {
			App::uses('ShoppingCart', 'Model');
			$this->ShoppingCart = new ShoppingCart;		
		
			$this->ShoppingCart->bindModel(array('hasMany'=>array('ShoppingCartProduct'=>array('order'=>'ShoppingCartProduct.product_name'))));
			$shoppingCart = $this->ShoppingCart->find('first', array('conditions'=>array('ShoppingCart.id'=>$shoppingCartId, 'ShoppingCart.site_id' => $siteId)));
		}
		
		return $shoppingCart;
	} 
	
	/**
	 * Function to add product to shopping cart
	 */
	function addToCart($siteID, $shoppingCartID, $categoryID, $productID, $quantity) {
		if($shoppingCartID) {			
			App::uses('Product', 'Model');
			$this->Product = new Product;
			$conditions = array('Product.site_id'=>$siteID, 'Product.id'=>$productID);		
			$productInfo = $this->Product->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
			
			App::uses('Category', 'Model');
			$this->Category = new Category;
			$conditions = array('Category.site_id'=>$siteID, 'Category.id'=>$categoryID);		
			$categoryInfo = $this->Category->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
			
			$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartID;
			$tmp['ShoppingCartProduct']['site_id'] = $siteID;
			$tmp['ShoppingCartProduct']['product_id'] = $productID;
			$tmp['ShoppingCartProduct']['category_id'] = $categoryID;			
			$tmp['ShoppingCartProduct']['size'] = '';
			$tmp['ShoppingCartProduct']['age'] = '';
			$tmp['ShoppingCartProduct']['product_name'] = $productInfo['Product']['name'];
			$tmp['ShoppingCartProduct']['category_name'] = $categoryInfo['Category']['name'];
			
			// get shopping cart product details
			$shoppingCartProductInfo = $this->getShoppingCartProductDetails($tmp);
			if(!empty($shoppingCartProductInfo)) {
				$tmp['ShoppingCartProduct']['quantity'] = $quantity+$shoppingCartProductInfo['ShoppingCartProduct']['quantity'];
				$tmp['ShoppingCartProduct']['id'] = $shoppingCartProductInfo['ShoppingCartProduct']['id'];
			}
			else {
				$tmp['ShoppingCartProduct']['quantity'] = $quantity; 
				$tmp['ShoppingCartProduct']['id'] = null; 
			}

			App::uses('ShoppingCartProduct', 'Model');
			$this->ShoppingCartProduct = new ShoppingCartProduct;
			if($this->ShoppingCartProduct->save($tmp)) {
				return true;
			}
		}
		return false;		
		
	}	
	
	/**
	 * Get shopping cart product details based on data
	 */
	public function getShoppingCartProductDetails($data) {
		App::uses('ShoppingCartProduct', 'Model');
		$this->ShoppingCartProduct = new ShoppingCartProduct;
		
		$conditions = array(
						'ShoppingCartProduct.shopping_cart_id'=>$data['ShoppingCartProduct']['shopping_cart_id'],		
						'ShoppingCartProduct.product_id'=>$data['ShoppingCartProduct']['product_id'],		
						'ShoppingCartProduct.category_id'=>$data['ShoppingCartProduct']['category_id'],		
						'ShoppingCartProduct.age'=>$data['ShoppingCartProduct']['age'],		
						'ShoppingCartProduct.size'=>$data['ShoppingCartProduct']['size'],		
					);
		$productInfo = $this->ShoppingCartProduct->find('first', array('conditions'=>$conditions));
		
		return $productInfo;
	}
	
	public function updateCart($siteId, $shoppingCartId, $cartInfo) {		
		if($shoppingCartId and $siteId) {
			App::uses('ShoppingCartProduct', 'Model');
			$this->ShoppingCartProduct = new ShoppingCartProduct;
			
			$conditions = array('ShoppingCartProduct.shopping_cart_id' => $shoppingCartId, 'ShoppingCartProduct.site_id' => $siteId);
			$this->ShoppingCartProduct->deleteAll($conditions, false);
			
			if($cartInfo) {
				foreach($cartInfo as $row) {
					$tmp['ShoppingCartProduct']['id'] = null;
					$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartId;
					$tmp['ShoppingCartProduct']['site_id'] = $siteId;
					$tmp['ShoppingCartProduct']['product_id'] = $row['product_id'];
					$tmp['ShoppingCartProduct']['category_id'] = $row['category_id'];
					$tmp['ShoppingCartProduct']['quantity'] = $row['quantity'];
					$tmp['ShoppingCartProduct']['size'] = '';
					$tmp['ShoppingCartProduct']['age'] = '';
					$tmp['ShoppingCartProduct']['product_name'] = $row['product_name'];
					$tmp['ShoppingCartProduct']['category_name'] = $row['category_name'];
					$this->ShoppingCartProduct->save($tmp);
				}
			}
		}
		return true;
	}
	
	public function updateOrder($siteId, $shoppingCartId, $orderType, $cartInfo) {
		App::uses('ShoppingCart', 'Model');
		$this->ShoppingCart = new ShoppingCart;		
		$shoppingCart = $this->ShoppingCart->find('first', array('conditions'=>array('ShoppingCart.id'=>$shoppingCartId, 'ShoppingCart.site_id' => $siteId)));
		
		if($shoppingCart) {
			$data = array(
				'id' => $shoppingCartId,
				'name' => $cartInfo['userName'],
				'email' => $cartInfo['userEmail'],
				'phone' => $cartInfo['userMobile'],
				'address' => $cartInfo['userAddress'],
				'message' => $cartInfo['userMessage'],
				'site_id' => $siteId,
				'request_price_quote' => ($orderType == 'quote' ? 1 : 0 ),
				'book_order' => 1
			);
			if($this->ShoppingCart->save($data)) {
				return true;
			}
		}
		
		return false;		
	}
	
	public function sendEmailOrderConfirmation($siteId, $shoppingCartId) {
		$siteInfo = $this->getSiteInfo($siteId, true);
		
		App::uses('ShoppingCart', 'Model');
		$this->ShoppingCart = new ShoppingCart;		
		$shoppingCart = $this->ShoppingCart->find('first', array('conditions'=>array('ShoppingCart.id'=>$shoppingCartId, 'ShoppingCart.site_id' => $siteId)));
		try {
			if($siteInfo and $shoppingCart) {
				// Sanitize data
				$shoppingCart['ShoppingCart']['name'] = Sanitize::paranoid($shoppingCart['ShoppingCart']['name'], array(' ','-', '.'));
				$shoppingCart['ShoppingCart']['phone'] = Sanitize::paranoid($shoppingCart['ShoppingCart']['phone'], array(' ', '+'));
				$shoppingCart['ShoppingCart']['address'] = Sanitize::clean($shoppingCart['ShoppingCart']['address']);
				$shoppingCart['ShoppingCart']['message'] = Sanitize::clean($shoppingCart['ShoppingCart']['message']);
				
				$isPriceQuote = $shoppingCart['ShoppingCart']['request_price_quote'];
				
				$subject = 'Order Booking Request - '.$siteInfo['site_info']['default_domain']['name'];
				if($isPriceQuote) {
					$subject = 'Request Price Quote - '.$siteInfo['site_info']['default_domain']['name'];
				}			
				
				// create items list
				$items = 'Products List:<br>';
				$sms_items = '';
				if(isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
					$i=0;
					foreach($shoppingCart['ShoppingCartProduct'] as $row) {
						$i++;
						$items.='<br>'.$i.') '.$row['product_name']. ', Quantity: '.$row['quantity'].'<br>';
						$sms_items.= $row['product_name'].'('.$row['quantity'].'), ';
		
					}
				}
				
				// Send email to admin -----------------------------------------------------
				$tmpText = 'A person has requested to book order on '.$siteInfo['site_url'].'.';
				if($isPriceQuote) {
					$tmpText = 'A person has requested for price quote on '.$siteInfo['site_url'].'.';
				}
				$mailContent = '
Dear Admin,
<br><br>
'.$tmpText.'
<br><br>
Contact Details:<br>
----------------------------------------<br>
Name: '.$shoppingCart['ShoppingCart']['name'].'<br>
Email: '.$shoppingCart['ShoppingCart']['email'].'<br>
Phone: '.$shoppingCart['ShoppingCart']['phone'].'<br>
Address: '.htmlentities($shoppingCart['ShoppingCart']['address']).'<br>
Message: '.htmlentities($shoppingCart['ShoppingCart']['message']).'
<br><br>
'.$items.'
<br>
-<br>
'.$siteInfo['site_info']['default_domain']['name'].'
<br><br>
*This is a system generated message. Please do not reply.
<br>
';					
				$fromName = "order@letsgreenify.com";
				$fromEmail = "no-reply@letsgreenify.com";
				$supportEmail = 'support@letsgreenify.com';
				
				$baseSupportEmail = 'preetham.pawar@gmail.com';
				$email = new CakeEmail('smtpNoReply');
				//$email->from(array($fromEmail => $fromName));
				$email->replyTo(array($shoppingCart['ShoppingCart']['email'] => $shoppingCart['ShoppingCart']['name']));
				$email->to($supportEmail);
				$email->bcc($baseSupportEmail); // send email to letsgreenify support team
				$email->subject($subject);
				$email->emailFormat('both');
				
				
				$email->send($mailContent);
				
				// Send email to user -----------------------------------------------------
				$mailContent = '
Dear '.$shoppingCart['ShoppingCart']['name'].',
<br><br>
We have received your request and it is under process. Our representative will contact you soon.
<br><br>
Below are the product details.
<br><br>
'.$items.'
<br>
-<br>
'.$siteInfo['site_info']['default_domain']['name'].'
<br><br>
*This is a system generated message. Please do not reply.
<br>
';					
				$fromName = "no-reply@letsgreenify.com";
				$fromEmail = "no-reply@letsgreenify.com";
				$userEmail = $shoppingCart['ShoppingCart']['email'];
				$email = new CakeEmail('smtpNoReply');
				//$email->from(array($fromEmail => $fromName));
				$email->to($userEmail);
				$email->subject($subject);	
				$email->replyTo(array('no-reply@letsgreenify.com' => 'Do not reply'));
				$email->emailFormat('both');					
				$email->send($mailContent);	
			}
			return true;
		} catch (Exception $e) {
			debug($e);			
		}
		return false;
	} 
}
?>
