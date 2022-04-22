<?php
App::uses('AppModel', 'Model');
class Category extends AppModel {
    public $name = 'Category';
    public $actsAs = array('Tree');
	var $hasMany = array('Data');	
	var $belongsTo = array('ParentCategory'=>array('className'=>'Category', 'foreignKey'=>'parent_id'));	
	
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
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