<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');
App::uses('User', 'Model');

class DeliveriesController extends AppController
{
	public $components = array('Sms');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'delivery';

        if ($this->isSellerForThisSite()) {
			
        } elseif (!$this->Session->check('User') || $this->Session->read('User.type') !== User::USER_TYPE_DELIVERY) {
			$this->Session->destroy();
			$this->redirect('/users/login');
		}
	}

	public function home()
	{
		App::import('Model', 'Order');
		$orderModel = new Order();

		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.status' => Order::ORDER_STATUS_CONFIRMED,
			'Order.delivery_user_id' => $this->Session->read('User.id'),
		];
		$confirmedOrders = $orderModel->find('all', ['conditions' => $conditions, 'order' => 'Order.created desc']);

		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.status' => Order::ORDER_STATUS_SHIPPED,
			'Order.delivery_user_id' => $this->Session->read('User.id'),
		];
		$shippedOrders = $orderModel->find('all', ['conditions' => $conditions, 'order' => 'Order.created desc']);

		App::uses('Supplier', 'Model');
		$supplierModel = new Supplier();
		$conditions = [
			'Supplier.site_id' => $this->Session->read('Site.id'),
			'Supplier.active' => 1,
		];

		$suppliers = $supplierModel->find('list', ['conditions' => $conditions]);

		$this->set('confirmedOrders', $confirmedOrders);
		$this->set('shippedOrders', $shippedOrders);
		$this->set('suppliers', $suppliers);
	}

	public function dashboard()
	{
		App::import('Model', 'Order');
		$orderModel = new Order();

		$siteId = $this->Session->read('Site.id');
		$deliveryUserId = $this->Session->read('User.id');

		$sql = 'select count(*) count, status
				from orders
				where site_id = ' . $siteId . '
					and delivery_user_id = ' . $deliveryUserId . '
					and archived = 0
					and status not in (
						"' . Order::ORDER_STATUS_DRAFT . '",
						"' . Order::ORDER_STATUS_NEW . '",
						"' . Order::ORDER_STATUS_RETURNED . '",
						"' . Order::ORDER_STATUS_CANCELLED . '"
					)
				group by status';
		$ordersCountByStatus = $this->Delivery->query($sql);

		$sql = 'select count(*) count from orders where site_id = ' . $siteId . ' and archived = 1';
		$archivedOrdersCount = $this->Delivery->query($sql);

		$this->set('ordersCountByStatus', $ordersCountByStatus);
		$this->set('archivedOrdersCount', $archivedOrdersCount);
	}

	public function updateOrderStatusShipped($encodedOrderId)
	{
		$this->layout = false;

		App::import('Model', 'Order');
		$orderModel = new Order();

		$orderId = base64_decode($encodedOrderId);
		$orderStatus = Order::ORDER_STATUS_SHIPPED;

		$conditions = [
			'Order.id' => $orderId,
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.delivery_user_id' => $this->Session->read('User.id'),
		];
		$orderInfo = $orderModel->find('first', ['conditions' => $conditions]);

		if ($orderInfo) {
			$message = 'Delivery person has picked the order and it will be delivered shortly.';
			$log = $this->getNewOrderStatusLog($orderId, $orderStatus, $message);

			$orderData = [
				'Order' => [
					'id' => $orderId,
					'status' => $orderStatus,
					'log' => $log,
				]
			];

			if ($orderModel->save($orderData)) {
				$this->successMsg('Order status updated successfully');
				$this->sendOrderEmailAndSms($orderId, $orderStatus, $message);
			} else {
				$this->errorMsg('Failed to update order status');
			}
		} else {
			$this->errorMsg('Order not found');
		}

		$this->redirect($this->referer());
	}

	public function updateOrderStatusDelivered($encodedOrderId, $isAjax = 0, $paymentMethod = null, $partialCashValue = 0)
	{
		$this->layout = false;
		$error = null;
		App::import('Model', 'Order');
		$orderModel = new Order();

		$orderId = base64_decode($encodedOrderId);
		$orderStatus = Order::ORDER_STATUS_DELIVERED;

		if (!in_array($paymentMethod, array_keys(Order::ORDER_PAYMENT_OPTIONS))) {
			$paymentMethod = Order::PAYMENT_METHOD_COD;			
		}
		
		$partialCashValue = (int) $partialCashValue;

		$conditions = [
			'Order.id' => $orderId,
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.delivery_user_id' => $this->Session->read('User.id'),
		];
		$orderInfo = $orderModel->find('first', ['conditions' => $conditions]);

		if ($orderInfo) {
			$message = 'Please contact us if you have not received the order.';
			$log = $this->getNewOrderStatusLog($orderId, $orderStatus, $message);

			$orderData = [
				'Order' => [
					'id' => $orderId,
					'status' => $orderStatus,
					'log' => $log,
					'payment_method' => $paymentMethod,
					'partial_payment_amount' => $partialCashValue,
				]
			];

			if ($orderModel->save($orderData)) {
				$this->successMsg('Order status updated successfully');
				//$this->sendOrderEmailAndSms($orderId, $orderStatus, $message); // todo: uncomment in production
			} else {
				$error = 'Failed to update order status';
				$this->errorMsg($error);
			}
		} else {
			$error = 'Order not found';
			$this->errorMsg($error);
		}

		if ((int)$isAjax === 1) {
			$this->response->header('Content-type', 'application/json');
			$this->response->body(json_encode([
					'error' => !empty($error),
					'msg' => $error,
				], JSON_THROW_ON_ERROR)
			);
			$this->response->send();
			exit;
		}

		$this->redirect($this->referer());
	}

	public function updateOrderProductSupplier()
	{
		if ($this->request->isPost() || $this->request->isPut()) {
			$data = $this->request->data;

			$orderProductId = $data['OrderProduct']['id'] ?? null;
			$orderProductSupplierId = $data['OrderProduct']['supplier_id'] ?? null;

			if (empty($orderProductId)) {
				$this->errorMsg('Invalid request.');
				$this->redirect($this->request->referer());
			}

			$tmpData['OrderProduct'] = [
				'id' => $orderProductId,
				'supplier_id' => $orderProductSupplierId,
			];

			App::uses('OrderProduct', 'Model');
			$orderProductModel = new OrderProduct;
			$orderProductModel->save($tmpData);

			$this->successMsg('Supplier updated successfully.');
		} else {
			$this->errorMsg('Invalid request.');
		}

		$this->redirect($this->request->referer());
	}

	public function ordersDelivered()
	{
		App::import('Model', 'Order');
		$orderModel = new Order();

		$start_date = $this->request->query['start_date'] ?? date('Y-m').'-01';
		$end_date = $this->request->query['end_date'] ?? date('Y-m-d');

		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),
			'Order.status IN' => [Order::ORDER_STATUS_DELIVERED, Order::ORDER_STATUS_CLOSED],
			'Order.delivery_user_id' => $this->Session->read('User.id'),
			'Order.created >' => $start_date,
			'Order.created <=' => $end_date . ' 23:59:59',
			'Order.archived' => 0,
		];

		$deliveredOrders = $orderModel->find('all', ['conditions' => $conditions, 'order' => 'Order.created desc', 'recursive' => -1]);


		$this->set('deliveredOrders', $deliveredOrders);
		$this->set('start_date', $start_date);
		$this->set('end_date', $end_date);
	}

	function heartbeat()
	{
		$this->layout = null;

		$time = date('Y-m-d H:i:s');
		$prevConfirmedOrdersCount = 0;

		if ($this->Session->check('last_order_check')) {
			$prevCheckedTime = $this->Session->read('last_order_check');
			$this->Session->write('last_order_check', $time);
			$time = $prevCheckedTime;			
		} else {
			$this->Session->write('last_order_check', $time);
		}

		App::import('Model', 'Order');
		$orderModel = new Order();

		$conditions = [
			'Order.site_id' => $this->Session->read('Site.id'),			
			'Order.archived' => 0,			
		];

		if (!$this->isSellerForThisSite()) {
			$conditions['Order.status'] = Order::ORDER_STATUS_CONFIRMED;
			$conditions['Order.delivery_user_id'] = $this->Session->read('User.id');
		} else {
			$conditions['Order.status'] = Order::ORDER_STATUS_NEW;
		}

		//debug($conditions);

		$confirmedOrdersCount = (int)$orderModel->find('count', ['conditions' => $conditions]);


		if($this->Session->check('prevConfirmedOrdersCount')) {
			$prevConfirmedOrdersCount = $this->Session->read('prevConfirmedOrdersCount');
		} else {			
			$prevConfirmedOrdersCount = $confirmedOrdersCount;
		}
		$this->Session->write('prevConfirmedOrdersCount', $confirmedOrdersCount);

		$newOrdersCount = $confirmedOrdersCount - $prevConfirmedOrdersCount;

		$this->response->header('Content-type', 'application/json');
		$this->response->body(
			json_encode([
				'confirmedOrdersCount' => $confirmedOrdersCount,
				'newOrdersCount' => $newOrdersCount,
				'lastCheck' => $time,
			], JSON_THROW_ON_ERROR)
		);
		$this->response->send();
		exit;
	}
}
