<?php
App::uses('AppModel', 'Model');

class User extends AppModel
{
	const USER_TYPE_BUYER = 'buyer';
	const USER_TYPE_DELIVERY = 'delivery';
	const USER_TYPE_SELLER = 'seller';
	const USER_TYPE_BUYER_LABEL = 'Buyer';
	const USER_TYPE_DELIVERY_LABEL = 'Delivery';
	const USER_TYPE_SELLER_LABEL = 'Seller';
	const USER_TYPE_OPTIONS = [
		self::USER_TYPE_BUYER => self::USER_TYPE_BUYER_LABEL,
		self::USER_TYPE_DELIVERY => self::USER_TYPE_DELIVERY_LABEL,
		self::USER_TYPE_SELLER => self::USER_TYPE_SELLER_LABEL,
	];

	public $belongsTo = ['Site'];
	public $name = 'User';

	public function beforeSave($params = [])
	{

		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}

?>
