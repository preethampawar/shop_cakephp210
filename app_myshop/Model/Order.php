<?php
App::uses('AppModel', 'Model');

class Order extends AppModel
{
	const ORDER_STATUS_DRAFT = 'DRAFT';
	const ORDER_STATUS_NEW = 'NEW';
	const ORDER_STATUS_CONFIRMED = 'CONFIRMED';
	const ORDER_STATUS_SHIPPED = 'SHIPPED';
	const ORDER_STATUS_DELIVERED = 'DELIVERED';
	const ORDER_STATUS_CLOSED = 'CLOSED';
	const ORDER_STATUS_RETURNED = 'RETURNED';
	const ORDER_STATUS_CANCELLED = 'CANCELLED';
	const ORDER_ARCHIVE = 'ARCHIVE';

	const PAYMENT_METHOD_COD = 'COD';
	const PAYMENT_METHOD_GPAY = 'GPAY';
	const PAYMENT_METHOD_PHONE_PE = 'PHONE_PE';
	const PAYMENT_METHOD_PAYTM = 'PAYTM';
	const PAYMENT_METHOD_DEBIT_CARD = 'DEBIT_CARD';
	const PAYMENT_METHOD_CREDIT_CARD = 'CREDIT_CARD';
	const PAYMENT_METHOD_PARTIAL_CASH = 'PARTIAL_CASH';

	const PAYMENT_METHOD_LABEL_COD = 'COD (Cash on delivery)';
	const PAYMENT_METHOD_LABEL_GPAY = 'Google Pay';
	const PAYMENT_METHOD_LABEL_PHONE_PE = 'Phone Pe';
	const PAYMENT_METHOD_LABEL_PAYTM = 'Paytm';
	const PAYMENT_METHOD_LABEL_DEBIT_CARD = 'Debit Card';
	const PAYMENT_METHOD_LABEL_CREDIT_CARD = 'Credit Card';
	const PAYMENT_METHOD_LABEL_PARTIAL_CASH = 'Partial Cash';

	const ORDER_STATUS_OPTIONS = [
		Order::ORDER_STATUS_DRAFT => Order::ORDER_STATUS_DRAFT,
		Order::ORDER_STATUS_NEW => Order::ORDER_STATUS_NEW,
		Order::ORDER_STATUS_CONFIRMED => Order::ORDER_STATUS_CONFIRMED,
		Order::ORDER_STATUS_SHIPPED => Order::ORDER_STATUS_SHIPPED,
		Order::ORDER_STATUS_DELIVERED => Order::ORDER_STATUS_DELIVERED,
		Order::ORDER_STATUS_CLOSED => Order::ORDER_STATUS_CLOSED,
		// Order::ORDER_STATUS_RETURNED => Order::ORDER_STATUS_RETURNED,
		Order::ORDER_STATUS_CANCELLED => Order::ORDER_STATUS_CANCELLED,
	];

	const ORDER_PAYMENT_OPTIONS = [
		self::PAYMENT_METHOD_COD => self::PAYMENT_METHOD_LABEL_COD,
		self::PAYMENT_METHOD_GPAY => self::PAYMENT_METHOD_LABEL_GPAY,
		self::PAYMENT_METHOD_PHONE_PE => self::PAYMENT_METHOD_LABEL_PHONE_PE,
		self::PAYMENT_METHOD_PAYTM => self::PAYMENT_METHOD_LABEL_PAYTM,
		self::PAYMENT_METHOD_DEBIT_CARD => self::PAYMENT_METHOD_LABEL_DEBIT_CARD,
		self::PAYMENT_METHOD_CREDIT_CARD => self::PAYMENT_METHOD_LABEL_CREDIT_CARD,
		self::PAYMENT_METHOD_PARTIAL_CASH => self::PAYMENT_METHOD_LABEL_PARTIAL_CASH,
	];

	public $name = 'Order';

	var $hasMany = ['OrderProduct'];
}
