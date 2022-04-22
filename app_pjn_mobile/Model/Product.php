<?php
App::uses('AppModel', 'Model');

class Product extends AppModel
{
	public $name = 'Product';

	public $belongsTo = ['ProductCategory', 'Brand'];

	public $validate = [
		'name' => [
			'notBlank' => [
				'rule' => 'notBlank',
				'required' => false,
				'message' => 'Product name is a required field',
			],
			'between' => [
				'rule' => ['between', 2, 100],
				'message' => 'Product name should be minimum of 2 characters and maximum of 100 characters',
			],
		],
	];
}
