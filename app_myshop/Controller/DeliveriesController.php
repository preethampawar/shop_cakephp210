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

		if (!$this->Session->check('User') || $this->Session->read('User.type') !== User::USER_TYPE_DELIVERY) {
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

		$this->set('confirmedOrders', $confirmedOrders);
		$this->set('shippedOrders', $shippedOrders);
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

	public function updateOrderStatusDelivered($encodedOrderId)
	{
		$this->layout = false;

		App::import('Model', 'Order');
		$orderModel = new Order();

		$orderId = base64_decode($encodedOrderId);
		$orderStatus = Order::ORDER_STATUS_DELIVERED;

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

}
