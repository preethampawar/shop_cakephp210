<?php
App::uses('CakeEmail', 'Network/Email');

class ShoppingCartsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();

		// Allow only if shopping(request price quote) is enabled on site
//		if (!$this->Session->read('Site.request_price_quote')) {
//			$this->Session->setFlash('Shopping on this site has been disabled.', 'default', array('class' => 'notice'));
//			$this->redirect($this->request->referer());
//		}

		//$this->Auth->allow('index', 'add', 'edit', 'delete', 'getCartProducts', 'deleteShoppingCartProduct', 'requestQuoteForProduct');
	}

	public function loadTopNavCartHeader()
	{
		$this->layout = false;
	}

	public function loadTopNavCart()
	{
		$this->layout = false;
	}

	public function add($categoryID, $productID)
	{
		$errorMsg = null;

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->errorMsg('Category Not Found');
		} elseif (!$productInfo = $this->isSiteProduct($productID)) {
			$this->errorMsg('Product Not Found');
		} elseif ($this->request->isPost()) {
			$data = $this->request->data;
			$shoppingCartID = $this->getShoppingCartID();

			if ($shoppingCartID) {
				$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartID;
				$tmp['ShoppingCartProduct']['site_id'] = $this->Session->read('Site.id');
				$tmp['ShoppingCartProduct']['product_id'] = $productID;
				$tmp['ShoppingCartProduct']['category_id'] = $categoryID;
				$tmp['ShoppingCartProduct']['category_name'] = $categoryInfo['Category']['name'];
				$tmp['ShoppingCartProduct']['product_name'] = $productInfo['Product']['name'];
				$tmp['ShoppingCartProduct']['mrp'] = $productInfo['Product']['mrp'];
				$tmp['ShoppingCartProduct']['discount'] = $productInfo['Product']['discount'];

				// get shopping cart product details
				$shoppingCartProductInfo = $this->getShoppingCartProductDetails($tmp);
				if (!empty($shoppingCartProductInfo)) {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'] + $shoppingCartProductInfo['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = $shoppingCartProductInfo['ShoppingCartProduct']['id'];
				} else {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = null;
				}

				App::uses('ShoppingCartProduct', 'Model');
				$shoppingCartProductModel = new ShoppingCartProduct;
				if ($shoppingCartProductModel->save($tmp)) {
					$this->successMsg('Product successfully added to shopping list');
				} else {
					$this->successMsg('An error occurred while communicating with the server');
				}
			} else {
				$this->successMsg('An error occurred while communicating with the server');
			}
		}

		$this->redirect($this->request->referer());
	}

	public function addToCart()
	{
		$errorMsg = null;
		$this->layout = false;

		$data = $this->request->input('json_decode', true);

		$productID = $data['ShoppingCartProduct']['productId'] ?? 0;
		$categoryID = $data['ShoppingCartProduct']['categoryId'] ?? 0;
		$quantity = $data['ShoppingCartProduct']['quantity'] ?? 0;
		$updateQty = $data['ShoppingCartProduct']['shoppingCartId'] ?? null;

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$errorMsg = 'Category Not Found.';
		} elseif (!$productInfo = $this->isSiteProduct($productID)) {
			$errorMsg = 'Product Not Found.';
		} elseif ($this->request->isPost()) {
			$shoppingCartID = $this->getShoppingCartID();

			if ($shoppingCartID) {
				$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartID;
				$tmp['ShoppingCartProduct']['site_id'] = $this->Session->read('Site.id');
				$tmp['ShoppingCartProduct']['product_id'] = $productID;
				$tmp['ShoppingCartProduct']['category_id'] = $categoryID;
				$tmp['ShoppingCartProduct']['category_name'] = $categoryInfo['Category']['name'];
				$tmp['ShoppingCartProduct']['product_name'] = $productInfo['Product']['name'];
				$tmp['ShoppingCartProduct']['mrp'] = $productInfo['Product']['mrp'];
				$tmp['ShoppingCartProduct']['discount'] = $productInfo['Product']['discount'];

				// get shopping cart product details
				$shoppingCartProductInfo = $this->getShoppingCartProductDetails($tmp);

				if (!empty($shoppingCartProductInfo)) {
					if (!$updateQty) {
						// new qty + old qty
						$tmp['ShoppingCartProduct']['quantity'] = $quantity + $shoppingCartProductInfo['ShoppingCartProduct']['quantity'];
					} else {
						// update new qty only
						$tmp['ShoppingCartProduct']['quantity'] = $quantity;
					}
					$tmp['ShoppingCartProduct']['id'] = $shoppingCartProductInfo['ShoppingCartProduct']['id'];
				} else {
					$tmp['ShoppingCartProduct']['quantity'] = $quantity;
					$tmp['ShoppingCartProduct']['id'] = null;
				}

				App::uses('ShoppingCartProduct', 'Model');
				$shoppingCartProductModel = new ShoppingCartProduct;

				if (!$shoppingCartProductModel->save($tmp)) {
					$errorMsg = 'An error occurred while communicating with the server.';
				}
			} else {
				$errorMsg = 'An error occurred while communicating with the server.';
			}
		}

		$success = empty($errorMsg);

		$this->response->body('{"success": "' . $success . '", "errorMessage": "' . $errorMsg . '"}');
		$this->response->type('application/json');
		$this->response->send();
	}

	/**
	 * Get shopping cart product details based on data
	 */
	public function getShoppingCartProductDetails($data)
	{
		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;

		$conditions = [
			'ShoppingCartProduct.shopping_cart_id' => $data['ShoppingCartProduct']['shopping_cart_id'],
			'ShoppingCartProduct.product_id' => $data['ShoppingCartProduct']['product_id'],
			'ShoppingCartProduct.category_id' => $data['ShoppingCartProduct']['category_id'],
		];
		$productInfo = $shoppingCartProductModel->find('first', ['conditions' => $conditions]);

		return $productInfo;
	}

	/**
	 * Function to get shopping cart products
	 */
	public function getCartProducts()
	{
		$shoppingCart = null;
		if ($this->Session->check('ShoppingCart.id')) {
			$shoppingCart = $this->getShoppingCartProducts();
		}
		return $shoppingCart;
	}

	/**
	 * Function to delete shopping cart product
	 */
	public function deleteShoppingCartProduct($shoppingCartProductID, $isAjax = false)
	{
		if ((int)$isAjax === 1) {
			$this->layout = false;
		}

		App::uses('ShoppingCartProduct', 'Model');
		$shoppingCartProductModel = new ShoppingCartProduct;

		$conditions = [
			'ShoppingCartProduct.id' => $shoppingCartProductID,
			'ShoppingCartProduct.shopping_cart_id' => $this->Session->read('ShoppingCart.id'),
		];

		if ($productInfo = $shoppingCartProductModel->find('first', ['conditions' => $conditions])) {
			$shoppingCartProductModel->delete($shoppingCartProductID);
			if ((int)$isAjax !== 1) {
				$this->successMsg('Product successfully deleted from shopping list');
			}
		} else {
			if ((int)$isAjax !== 1) {
				$this->successMsg('Product not found in shopping list');
			}
		}

		if ((int)$isAjax !== 1) {
			return $this->redirect($this->request->referer());
		}
	}

	/**
	 * Function to add product to shopping list by request quote form
	 */
	public function requestQuoteForProduct($categoryID, $productID)
	{
		$errorMsg = null;

		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'error']);
		} else if (!$productInfo = $this->isSiteProduct($productID)) {
			$this->Session->setFlash('Product Not Found', 'default', ['class' => 'error']);
		} else if ($this->request->isPost()) {
			$data = $this->request->data;
			$shoppingCartID = $this->getShoppingCartID();

			if ($shoppingCartID) {

				$tmp['ShoppingCartProduct']['shopping_cart_id'] = $shoppingCartID;
				$tmp['ShoppingCartProduct']['site_id'] = $this->Session->read('Site.id');
				$tmp['ShoppingCartProduct']['product_id'] = $productID;
				$tmp['ShoppingCartProduct']['category_id'] = $categoryID;
				$tmp['ShoppingCartProduct']['product_name'] = $productInfo['Product']['name'];
				$tmp['ShoppingCartProduct']['category_name'] = $categoryInfo['Category']['name'];

				// get shopping cart product details
				$shoppingCartProductInfo = $this->getShoppingCartProductDetails($tmp);
				if (!empty($shoppingCartProductInfo)) {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'] + $shoppingCartProductInfo['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = $shoppingCartProductInfo['ShoppingCartProduct']['id'];
				} else {
					$tmp['ShoppingCartProduct']['quantity'] = $data['ShoppingCartProduct']['quantity'];
					$tmp['ShoppingCartProduct']['id'] = null;
				}

				App::uses('ShoppingCartProduct', 'Model');
				$shoppingCartProductModel = new ShoppingCartProduct;
				if ($shoppingCartProductModel->save($tmp)) {
					$this->Session->setFlash('Product successfully added to shopping list', 'default', ['class' => 'success']);
				} else {
					$this->Session->setFlash('An error occured while communicating with the server.', 'default', ['class' => 'error']);
					$this->redirect($this->request->referer());
				}
			} else {
				$this->Session->setFlash('An error occured while communicating with the server.', 'default', ['class' => 'error']);
				$this->redirect($this->request->referer());
			}
		}
		$this->redirect('/RequestPriceQuote');
	}

	public function loadOrderSummary()
	{
		$this->layout = 'ajax';
		$shoppingCartProducts = $this->ShoppingCart->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

		App::uses('Order', 'Model');
		$orderModel = new Order();

		$orderDetails = $orderModel->findById($this->getOrderId());

		$this->set('shoppingCartProducts', $shoppingCartProducts);
		$this->set('orderDetails', $orderDetails);
	}

	public function loadOrderDeliveryDetails()
	{
		$this->layout = 'ajax';
		$shoppingCartProducts = $this->ShoppingCart->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

		App::uses('Order', 'Model');
		$orderModel = new Order();

		$orderDetails = $orderModel->findById($this->getOrderId());

		$prefilledDeliveryDetails = false;

		// get prev order details
		if ($this->Session->check('User.id')) {
			$user_id = $this->Session->read('User.id');
			$conditions = [
				'Order.user_id' => $user_id,
				'Order.site_id' => $this->Session->read('Site.id'),
				'Order.id NOT' => $orderDetails['Order']['id'],
				'Order.status NOT' => Order::ORDER_STATUS_DRAFT,
			];

			$prevOrderDetails = $orderModel->find('first', ['conditions'=> $conditions, 'order' => 'Order.created DESC']);

			if (!empty($prevOrderDetails) && empty($orderDetails['Order']['customer_name'])) {
				$orderDetails = $prevOrderDetails;
				$prefilledDeliveryDetails = true;
			}
		}

		$this->set('prefilledDeliveryDetails', $prefilledDeliveryDetails);
		$this->set('shoppingCartProducts', $shoppingCartProducts);
		$this->set('orderDetails', $orderDetails);
	}
}

?>
