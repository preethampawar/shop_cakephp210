<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Order', 'Model');

class OrdersController extends AppController
{
	public $components = array('Sms');

	private $divider = '#####';

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index()
	{
		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.user_id' => $this->Session->read('User.id'),
			'Order.status !=' => Order::ORDER_STATUS_DRAFT,
		];

		$this->Order->unbindModel(['hasMany' => ['OrderProduct']]);

		$this->paginate = [
			'limit' => 10,
			'order' => ['Order.created' => 'DESC'],
			'conditions' => $conditions,
		];
		$orders = $this->paginate();

		$this->set('orders', $orders);
	}

	public function admin_index($orderType = null)
	{
		$this->checkSeller();

		$siteId = $this->Session->read('Site.id');

		$sql = 'select count(*) count, status from orders where site_id = '.$siteId.' and archived = 0 group by status';
		$ordersCountByStatus = $this->Order->query($sql);

		$sql = 'select count(*) count from orders where site_id = '.$siteId.' and archived = 1';
		$archivedOrdersCount = $this->Order->query($sql);

		$conditions = [
			'Order.site_id' => $siteId,
			'Order.archived' => 0,
		];

		switch ($orderType) {
			case Order::ORDER_STATUS_DRAFT:
			case Order::ORDER_STATUS_NEW:
			case Order::ORDER_STATUS_CONFIRMED:
			case Order::ORDER_STATUS_SHIPPED:
			case Order::ORDER_STATUS_DELIVERED:
			case Order::ORDER_STATUS_CANCELLED:
			case Order::ORDER_STATUS_CLOSED:
			// case Order::ORDER_STATUS_RETURNED:
				break;
			default:
				$orderType = Order::ORDER_STATUS_NEW;
				break;
		}

		$conditions['Order.status'] = $orderType;

		$this->Order->bindModel(['belongsTo' => ['User']]);
		$this->Order->unbindModel(['hasMany' => ['OrderProduct']]);

		$this->paginate = [
			'limit' => 100,
			'order' => ['Order.created' => 'ASC'],
			'conditions' => $conditions,
		];
		$orders = $this->paginate();

		App::import('Model', 'User');
		$userModel = new User();
		$conditions = [
			'User.site_id' => $this->Session->read('Site.id'),
			'User.type' => User::USER_TYPE_DELIVERY,
		];
		$usersList = $userModel->find('list', ['conditions' => $conditions]);

		$this->set('usersList', $usersList);
		$this->set('orderType', $orderType);
		$this->set('orders', $orders);
		$this->set('ordersCountByStatus', $ordersCountByStatus);
		$this->set('archivedOrdersCount', $archivedOrdersCount);
	}

	public function details($encodedOrderId)
	{
		$orderId = base64_decode($encodedOrderId);
		$order = $this->Order->findById($orderId);

		$this->set('order', $order);
	}

	public function admin_assignDeliveryBoy($encodedOrderId)
	{
		$orderId = base64_decode($encodedOrderId);
		$order = $this->Order->findById($orderId);

		if ($order) {
			if ($this->request->isPost() || $this->request->isPut()) {
				$data = $this->request->data;
				$tmp['Order']['id'] = $orderId;
				$tmp['Order']['delivery_user_id'] = $data['Order']['delivery_user_id'];

				$this->Order->save($tmp);
				$this->successMsg('Delivery boy assigned to this order.');
			} else {
				$this->errorMsg('Invalid request.');
			}
		} else {
			$this->errorMsg('Order not found.');
		}

		$this->redirect($this->referer());
	}

	public function admin_details($encodedOrderId)
	{
		$orderId = base64_decode($encodedOrderId);
		$order = $this->Order->findById($orderId);

		App::import('Model', 'User');
		$userModel = new User();
		$conditions = [
			'User.site_id' => $this->Session->read('Site.id'),
			'User.active' => 1,
			'User.type' => User::USER_TYPE_DELIVERY,
		];
		$usersList = $userModel->find('list', ['conditions' => $conditions]);

		$this->set('order', $order);
		$this->set('usersList', $usersList);
	}

	private function registerGuestUser($mobile, $email)
	{
		$user = $this->createCustomer($mobile, $email);

		if ($user) {
			try {
				$this->sendSuccessfulEnrollmentMessage($mobile, $email);
			} catch (Exception $e) {
			}

			return $user;
		}

		return false;
	}

	public function create($autoRegister = 0)
	{
		$autoRegister = (int)$autoRegister;

		App::uses('ShoppingCart', 'Model');
		$shoppingCartModel = new ShoppingCart;

		App::uses('User', 'Model');
		$userModel = new User;

		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;
		$error = null;

		$this->layout = false;
		$orderId = $this->getOrderId();
		$orderDetails = $this->Order->findById($orderId);

		$userId = null;

		// if the user is not logged in then auto register user based on $autoRegister flag
		if (!$this->Session->check('User.id')) {
			$userEmail = $orderDetails['Order']['customer_email'];
			$userMobile = $orderDetails['Order']['customer_phone'];

			if ($autoRegister === 1) {
				// check if guest is already registered
				$conditions = [
					'User.mobile' => (int)$userMobile,
					'User.site_id' => $this->Session->read('Site.id'),
				];
				$existingUser = $userModel->find('first', ['fields' => ['User.id', 'User.email'], 'conditions' => $conditions, 'recursive' => -1]);

				if ($existingUser) {
					$userId = $existingUser['User']['id'];
				} else {
					$newUser = $this->registerGuestUser($userMobile, $userEmail);

					if ($newUser) {
						$userId = $newUser['User']['id'];
					} else {
						$error = 'Could not auto register user. Please try again.';
					}
				}
			} else {
				if((bool)$this->Session->read('Site.sms_notifications') === true) {
					$this->sendVerifyOtp($userMobile, $userEmail);
				}
				$error = 'Please login to place an Order';
			}
		} else {
			$userId = $this->Session->read('User.id');
		}

		$orderEmailUrl = '/orders/sendOrderEmail/' . base64_encode($orderId) . '/NEW';
		$data = $this->request->input('json_decode', true);
		$msg = '';
		$siteId = $this->Session->read('Site.id');
		$shoppingCartId = $this->getShoppingCartID();
		$cartValue = 0;
		$totalItems = 0;
		$totalDiscount = 0;
		$totalTax = 0;
		$payableAmount = 0;
		$shippingAmount = (float)$this->Session->read('Site.shipping_charges');
		$minOrderForFreeShipping = (float)$this->Session->read('Site.free_shipping_min_amount');

		$log = json_decode($orderDetails['Order']['log'], true);
		$orderStatus = Order::ORDER_STATUS_NEW;
		$newLog = [
			'orderStatus' => $orderStatus,
			'date' => time(),
			'message' => '',
			'updated_by_user_id' => $userId,
		];
		$log[] = $newLog;
		$log = json_encode($log);

		if (!$error) {
			$error = isset($data['confirmed']) && $data['confirmed'] == 1 ? null : 'Invalid request (OR) Your session has timed out.';
		}

		if (!$error) {
			$shoppingCartProducts = $shoppingCartModel->getShoppingCartProducts($shoppingCartId);

			if (empty($shoppingCartProducts['ShoppingCartProduct'])) {
				$error = 'There are no items in your cart (OR) Your session has timed out. Please try again.';
			}
		}

		if (!$error) {
			foreach ($shoppingCartProducts['ShoppingCartProduct'] as $row) {
				$qty = $row['quantity'] ?: 0;
				$mrp = $row['mrp'];
				$discount = $row['discount'];
				$salePrice = $mrp - $discount;
				$totalProductPurchaseValue = $salePrice * $qty;
				$cartValue += $totalProductPurchaseValue;
				$totalItems += $qty;
				$totalDiscount += $discount * $qty;
			}


			// if minimum order for free shipping is specified then make shipping charges as 0
			if ($minOrderForFreeShipping > 0 && $cartValue >= $minOrderForFreeShipping) {
				$shippingAmount = 0;
			}

			$payableAmount = $cartValue + $shippingAmount;

			$applyPromoDiscount = false;
			$promoDiscountValue = 0;
			$purchaseThisMuchToAvailPromoCode = 0;
			$promoCodeInfo = $this->Session->check('PromoCode') ? $this->Session->read('PromoCode') : null;
			$promoCode = null;
			$promoCodeId = null;
			$promoCodeDetails = null;

			if ($promoCodeInfo) {
				$promoCodeId = $promoCodeInfo['id'];
				$promoCode = $promoCodeInfo['name'];
				$promoCodeDetails = json_encode($promoCodeInfo);
				$minPurchaseValue = (float)$promoCodeInfo['min_purchase_value'];
				$promoDiscountValue = (float)$promoCodeInfo['discount_value'];

				if ($cartValue >= $minPurchaseValue) {
					$applyPromoDiscount = true;
				} else {
					$purchaseThisMuchToAvailPromoCode = $minPurchaseValue - $cartValue;
				}
			}

			if ($applyPromoDiscount) {
				$totalDiscount = $totalDiscount + $promoDiscountValue;
				$payableAmount = $payableAmount - $promoDiscountValue;
			}


			$orderData = [
				'Order' => [
					'id' => $orderId,
					'total_cart_value' => $cartValue,
					'total_items' => $totalItems,
					'total_discount' => $totalDiscount,
					'shipping_amount' => $shippingAmount,
					'total_tax' => $totalTax,
					'total_order_amount' => $payableAmount,
					'status' => $orderStatus,
					'log' => $log,
					'notes' => null,
					'user_id' => $userId,
					'promo_code' => $promoCode,
					'promo_code_discount' => $promoDiscountValue,
					'promo_code_id' => $promoCodeId,
					'promo_code_details' => $promoCodeDetails,
				]
			];

			if ($this->Order->save($orderData)) {
				$orderDetails = $this->Order->read();

				$this->Session->delete('PromoCode');

				if ($this->saveOrderProducts($shoppingCartProducts['ShoppingCartProduct'], $orderId)) {
					// delete shopping cart details
					$customerPhone = $orderDetails['Order']['customer_phone'];
					$this->Session->delete('ShoppingCart');
					$this->Session->delete('Order');
					$shoppingCartModel->delete($shoppingCartId);
					$shoppingCartProductModel->deleteAll(['ShoppingCartProduct.shopping_cart_id' => $shoppingCartId]);
					$msg = 'Your order has been placed successfully. You will be notified once the order is confirmed.';

					if((bool)$this->Session->read('Site.sms_notifications') === true) {
						$this->Sms->sendNewOrderSms($customerPhone, '#'.$orderId, $this->Session->read('Site.title'));

						// send new order sms to manager of the site
						$adminPhone = $this->Session->read('Site.notifications_mobile_no');

						if (!empty($adminPhone)) {
							$this->Sms->sendNewOrderSms($adminPhone, '#'.$orderId, $this->Session->read('Site.title'));
						}
					}

				} else {
					// delete Order as OrderProducts could not be saved
					$this->Order->delete($orderId);
					$error = 'Order details could not be saved. Please try again.';
				}
			} else {
				$error = 'Order could not be saved. Please try again.';
			}
		}

		$this->set('error', $error);
		$this->set('msg', $msg);
		$this->set('orderEmailUrl', $orderEmailUrl);
	}

	public function admin_updateStatus($encodedOrderId, $orderStatus, $sendEmailToCustomer = null, $base64_encoded_message = null, $paymentMethod = null)
	{
		if (!in_array($orderStatus, Order::ORDER_STATUS_OPTIONS)) {
			$this->errorMsg('Invalid request');
			$this->redirect('/admin/orders/details/'.$encodedOrderId);
			return;
		}

		if ($paymentMethod && !isset(Order::ORDER_PAYMENT_OPTIONS[$paymentMethod])) {
			$paymentMethod = null;
		}

		$message = $base64_encoded_message ? base64_decode($base64_encoded_message) : '';
		$message = $message ? htmlentities($message) : '';

		$orderId = base64_decode($encodedOrderId);
		$this->layout = false;
		$error = null;

		$siteId = $this->Session->read('Site.id');
		$conditions = ['Order.site_id' => $siteId, 'Order.id' => $orderId];
		$orderDetails = $this->Order->find('first', ['conditions'=>$conditions]);

		if ($orderDetails['Order']['archived']) {
			$this->errorMsg('This action cannot be performed on archived orders');
			$this->redirect('/admin/orders/details/'.$encodedOrderId);
		}

		$log = $this->getNewOrderStatusLog($orderId, $orderStatus, $message);

		$orderData = [
			'Order' => [
				'id' => $orderId,
				'status' => $orderStatus,
				'log' => $log,
			]
		];

		if ($paymentMethod) {
			$orderData['Order']['payment_method'] = $paymentMethod;
		}

		if ($this->Order->save($orderData)) {
			$this->successMsg('Order status updated successfully');

			if ($sendEmailToCustomer) {
				$message = html_entity_decode($message);
				$this->sendOrderEmail($encodedOrderId, $orderStatus, true, $message);
			}
		} else {
			$this->errorMsg('Failed to update order status');
		}

		$this->redirect('/admin/orders/details/'.$encodedOrderId);
		exit;
	}

	public function admin_archive($encodedOrderId, $archiveText)
	{
		$orderId = base64_decode($encodedOrderId);
		$archiveText = base64_decode($archiveText);

		if ($archiveText !== Order::ORDER_ARCHIVE) {
			$this->errorMsg('Invalid request. Please try again.');
			$this->redirect($this->request->referer());
		}

		$siteId = $this->Session->read('Site.id');
		$conditions = ['Order.site_id' => $siteId, 'Order.id' => $orderId];
		$orderDetails = $this->Order->find('first', ['conditions'=>$conditions]);

		if (empty($orderDetails)) {
			$this->errorMsg('You are not authorized to perform this action.');
			$this->redirect($this->request->referer());
		}

		$this->layout = false;
		$orderData = [
			'Order' => [
				'id' => $orderId,
				'archived' => true,
			]
		];

		if ($this->Order->save($orderData)) {
			$this->successMsg('Order no. #' . $orderId . ' has been archived.');
		} else {
			$this->errorMsg('Failed to update order status');
		}

		$this->redirect($this->request->referer());
		exit;
	}

	private function saveOrderProducts($shoppingCartProducts, $orderId)
	{
		App::uses('OrderProduct', 'Model');
		$orderProductModel = new OrderProduct();
		$error = false;
		$siteId = $this->Session->read('Site.id');

		foreach ($shoppingCartProducts as $row) {
			$qty = $row['quantity'] ?: 0;
			$mrp = $row['mrp'];
			$discount = $row['discount'];
			$salePrice = $mrp - $discount;
			$productName = $row['Product']['name'];
			$categoryName = $row['Category']['name'];

			$orderProductData = null;
			$orderProductData = [
				'OrderProduct' => [
					'id' => null,
					'order_id' => $orderId,
					'site_id' => $siteId,
					'product_name' => $productName,
					'category_name' => $categoryName,
					'quantity' => $qty,
					'mrp' => $mrp,
					'discount' => $discount,
					'sale_price' => $salePrice,
				]
			];

			if (!$orderProductModel->save($orderProductData)) {
				$error = true;
				break;
			}
		}

		if ($error) {
			// delete previously saved order products
			$conditions = ['OrderProduct.order_id' => $orderId];
			$orderProductModel->deleteAll($conditions, false);

			return false;
		}

		return true;
	}

	public function sendOrderEmail($encodedOrderId, $orderStatus, $return = false, $message = null)
	{
		$this->layout = false;

		$orderId = base64_decode($encodedOrderId);
		$error = $this->sendOrderEmailAndSms($orderId, $orderStatus, $message);

		$this->set('error', $error);

		if ($return) {
			return $error;
		}
	}

	private function validatePaymentDetails($data)
	{
		$paymentMethods = [Order::PAYMENT_METHOD_COD, Order::PAYMENT_METHOD_GPAY, Order::PAYMENT_METHOD_PHONE_PE, Order::PAYMENT_METHOD_PAYTM];

		if (empty($data['paymentMethod'])) {
			return 'Please select payment method';
		}

		if (!empty($data['paymentMethod']) && !in_array($data['paymentMethod'], $paymentMethods)) {
			return 'Invalid Payment Method';
		}

		if (!empty($data['paymentMethod'])
			&& $data['paymentMethod'] != Order::PAYMENT_METHOD_COD
			&& empty($data['paymentReferenceNo'])) {
			return 'Payment reference no. is required';
		}

		return null;
	}

	private function validateDeliveryDetails($data)
	{
		if (empty($data['customerName'])) {
			return 'Contact Name is required';
		}
		if (empty($data['customerPhone'])) {
			return 'Contact Phone no. is required';
		}
		if (empty($data['customerAddress'])) {
			return 'Delivery Address is required';
		}

		return null;
	}

	public function saveOrderDeliveryDetails()
	{
		$this->layout = false;

		$data = $this->request->input('json_decode', true);

		if($this->Session->check('User')) {
			$data['customerPhone'] = $this->Session->read('User.mobile');
			$data['customerEmail'] = $this->Session->read('User.email');
		}

		if ((bool)$this->Session->read('Site.sms_notifications') === true && empty($data['customerEmail'])) {
			$data['customerEmail'] = $this->Session->read('Site.default_customer_notification_email');
		}

		$error = $this->validateDeliveryDetails($data);

		if (!$error) {
			$orderId = $this->getOrderId();

			//remove staring char 0 from phone number(ex. 09494102030)
			$data['customerPhone'] = ltrim($data['customerPhone'], '0');

			$orderData = [
				'Order' => [
					'id' => $orderId,
					'customer_name' => $data['customerName'],
					'customer_phone' => $data['customerPhone'],
					'customer_email' => $data['customerEmail'],
					'customer_address' => $data['customerAddress'],
					'customer_message' => $data['customerMessage'],
				]
			];

			if (! $this->Order->save($orderData)) {
				$error = 'Delivery details could not be saved. Please try again.';
			}
		}

		$this->set('error', $error);
	}

	public function loadOrderPaymentDetails()
	{
		$this->layout = false;

		App::uses('ShoppingCart', 'Model');
		$shoppingCartModel = new ShoppingCart();

		$shoppingCartProducts = $shoppingCartModel->getShoppingCartProducts($this->getShoppingCartID());

		$orderDetails = $this->Order->findById($this->getOrderId());

		$this->set('shoppingCartProducts', $shoppingCartProducts);
		$this->set('orderDetails', $orderDetails);
	}


	public function saveOrderPaymentDetails()
	{
		$this->layout = false;

		$data = $this->request->input('json_decode', true);
		$error = $this->validatePaymentDetails($data);

		if (!$error) {
			$orderId = $this->getOrderId();
			$orderData = [
				'Order' => [
					'id' => $orderId,
					'payment_method' => $data['paymentMethod'],
					'payment_reference_no' => $data['paymentReferenceNo'],
				]
			];

			if (! $this->Order->save($orderData)) {
				$error = 'Payment details could not be saved. Please try again.';
			}
		}

		$this->set('error', $error);
	}

	public function admin_createOrder()
	{
		$orderStatus = Order::ORDER_STATUS_DRAFT;
		$tmp['Order']['id'] = null;
		$tmp['Order']['user_id'] = null;
		$tmp['Order']['status'] = $orderStatus;
		$tmp['Order']['site_id'] = $this->Session->read('Site.id');
		$tmp['Order']['is_offline_order'] = 1;

		$log = $this->getNewOrderStatusLog(null, $orderStatus);
		$tmp['Order']['log'] = $log;

		if ($this->Order->save($tmp)) {
			$orderInfo = $this->Order->read();
			$orderId = $orderInfo['Order']['id'];
			$encodedOrderId = base64_encode($orderId);

			$this->successMsg('Offline order created.');
			$this->redirect('/admin/orders/saveOrder/'.$encodedOrderId);
		}

		$this->errorMsg('Failed to create offline order.');
		$this->redirect('/admin/orders/');
	}

	public function admin_saveOrder($encodedOrderId)
	{
		$orderId = (int)base64_decode($encodedOrderId);

		$error = null;

		if ($orderId <= 0) {
			$error = 'Invalid request.';
		}

		if (!$error) {
			$orderInfo = $this->Order->findById($orderId);

			if (empty($orderInfo)) {
				$error = 'Order #' . $orderId . ' could not be found.';
			}
		}

		if ($error) {
			$this->errorMsg($error);
			$this->redirect('/admin/orders/');
		}

		if ($this->request->isPost() || $this->request->isPut()) {
			$data = $this->request->data;

			$totalCartValue = 0;
			$totalItems = 0;
			$totalDiscount = 0;
			$totalTax = 0;
			$totalOrderAmount = 0;
			$shippingAmount = (float)$data['shipping_amount'];

			foreach($orderInfo['OrderProduct'] as $offlineOrderProduct) {
				$qty = (int)$offlineOrderProduct['quantity'];
				$mrp = (float)$offlineOrderProduct['mrp'];
				$discount = (float)$offlineOrderProduct['discount'];
				$salePrice = $mrp - $discount;
				$totalProductPurchaseValue = $salePrice * $qty;

				$totalCartValue += $totalProductPurchaseValue;
				$totalItems += $qty;
				$totalDiscount += $discount * $qty;
			}
			$totalOrderAmount = $totalCartValue + $shippingAmount + $totalTax;

			$orderStatus = Order::ORDER_STATUS_NEW;

			$log = $this->getNewOrderStatusLog($orderId, $orderStatus);

			$tmpData['Order'] = [
				'id' => $orderId,
				'customer_name' => $data['customer_name'],
				'customer_phone' => $data['customer_phone'],
				'customer_email' => $data['customer_email'],
				'customer_address' => $data['customer_address'],
				'customer_message' => $data['customer_message'],
				'payment_method' => $data['payment_method'],
				'shipping_amount' => $shippingAmount,
				'total_cart_value' => $totalCartValue,
				'total_items' => $totalItems,
				'total_discount' => $totalDiscount,
				'total_tax' => $totalTax,
				'total_order_amount' => $totalOrderAmount,
				'status' => $orderStatus,
				'log' => $log,
			];

			if($this->Order->save($tmpData)) {
				$this->successMsg('Order Saved.');
				$this->redirect('/admin/orders/');
			} else {
				$this->errorMsg('Something went wrong. Could not save order information.');
			}

			$orderInfo['Order'] = $tmpData['Order'];
		}


		// get category products
		App::uses('CategoryProduct', 'Model');
		$categoryProductModel = new CategoryProduct;

		$categoryProductModel->bindModel([
			'belongsTo' => [
				'Product' => [
					'order' => 'Product.name',
					'fields' => ['Product.id', 'Product.name', 'Product.mrp', 'Product.discount'],
					'conditions' => ['Product.active' => 1, 'Product.deleted' => 0],
				],
				'Category' => [
					'fields' => ['Category.id', 'Category.name'],
					'conditions' => ['Category.active' => 1, 'Category.deleted' => 0],
				]
			]
		]);

		$products = $categoryProductModel->find('all', ['conditions' => ['CategoryProduct.site_id' => $this->Session->read('Site.id')]]);

		$categoryProducts = [];
		if ($products) {
			foreach($products as $row) {
				if (!empty($row['Category']['id']) && !empty($row['Product']['id'])) {
					$categoryIdProductIdHash = $row['Category']['id'] . $this->divider . $row['Product']['id'];

					$categoryProducts[$categoryIdProductIdHash] = $row['Product']['name']
						. ' [ '.$row['Category']['name'].' ]'
						. '[ mrp='.$row['Product']['mrp'].' ]'
						. '[ discount='.$row['Product']['discount'].' ]';

				}
			}
		}

		$this->set('categoryProducts', $categoryProducts);
		$this->set('orderInfo', $orderInfo);
	}

	public function admin_addOfflineProduct($encodedOrderId) {
		$orderId = (int)base64_decode($encodedOrderId);

		if ($this->request->isPost() || $this->request->isPut()) {
			list($categoryId, $productId) = explode($this->divider, $this->request->data['categoryIdProductId']);

			// get category product
			App::uses('CategoryProduct', 'Model');
			$categoryProductModel = new CategoryProduct;

			App::uses('Category', 'Model');
			$categoryModel = new Category;
			$category = $categoryModel->findById($categoryId, ['Category.id', 'Category.name'], [], -1);

			App::uses('Product', 'Model');
			$productModel = new Product;
			$product = $productModel->findById($productId, ['Product.id', 'Product.name', 'Product.mrp', 'Product.discount'], [], -1);

			$qty = (int)($this->request->data['quantity'] ?? 1);
			$qty = $qty < 1 ? 1 : $qty;

			$data['OrderProduct'] = [
				'id' => null,
				'order_id' => $orderId,
				'site_id' => $this->Session->read('Site.id'),
				'product_name' => $product['Product']['name'],
				'category_name' => $category['Category']['name'],
				'quantity' => $qty,
				'mrp' => $product['Product']['mrp'],
				'discount' => $product['Product']['discount'],
				'sale_price' => (float)$product['Product']['mrp'] - (float)$product['Product']['discount'],
			];

			App::uses('OrderProduct', 'Model');
			$orderProductModel = new OrderProduct;

			if($orderProductModel->save($data)) {
				$this->successMsg('Product added successfully');
				$this->redirect('/admin/orders/saveOrder/'.$encodedOrderId);
			}
		}

		$this->errorMsg('Something went wrong. Please try again.');
		$this->redirect('/admin/orders/saveOrder/'.$encodedOrderId);
	}

	public function admin_updateOfflineProducts($encodedOrderId)
	{
		if ($this->request->isPost() || $this->request->isPut()) {
			$data = $this->request->data;
			debug($data);

			foreach($data as $orderProductId => $row) {

				$qty = (int)$row['quantity'];
				$mrp = (float)$row['mrp'];
				$discount = (float)$row['discount'];
				$salePrice = $mrp - $discount;

				$tmpData['OrderProduct'] = [
					'id' => $orderProductId,
					'quantity' => $qty,
					'mrp' => $mrp,
					'discount' => $discount,
					'sale_price' => $salePrice,
				];

				App::uses('OrderProduct', 'Model');
				$orderProductModel = new OrderProduct;

				$orderProductModel->save($tmpData);
			}

			$this->successMsg('Products updated successfully');
			$this->redirect('/admin/orders/saveOrder/'.$encodedOrderId);
		}

		$this->errorMsg('Something went wrong. Please try again.');
		$this->redirect('/admin/orders/saveOrder/'.$encodedOrderId);

		exit;
	}

	public function admin_deleteOrderProduct($encodedOrderProductId)
	{
		$orderProductId = (int)base64_decode($encodedOrderProductId);

		App::uses('OrderProduct', 'Model');
		$orderProductModel = new OrderProduct;

		if($orderProductModel->delete($orderProductId)) {
			$this->successMsg('Product deleted successfully from this order');
		} else {
			$this->errorMsg('Failed to delete the product');
		}

		$this->redirect($this->request->referer());
	}

}
?>
