<?php
App::uses('AppModel', 'Model');

class ProductCategory extends AppModel
{
	public $name = 'ProductCategory';
	// public $actsAs = array('Tree');
	// var $belongsTo = array('ParentCategory'=>array('className'=>'Category', 'foreignKey'=>'parent_id'));

	public $validate = [
		'name' => [
			'notBlank' => [
				'rule' => 'notBlank',
				'required' => false,
				'message' => 'Category name is a required field',
			],
			'between' => [
				'rule' => ['between', 2, 55],
				'message' => 'Category name should be minimum of 2 characters and maximum of 55 characters',
			],
		],
	];
}
