<?php
App::uses('AppModel', 'Model');
class Category extends AppModel {
    public $name = 'Category';
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => false,
				'message' => 'Category name is a required field'
			),
			'between' => array(
				'rule' => array('between', 2, 55),
				'message' => 'Category name should be minimum of 2 characters and maximum of 55 characters'
			)
		)
	);
}