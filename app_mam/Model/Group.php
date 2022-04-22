<?php
App::uses('AppModel', 'Model');
class Group extends AppModel {
    public $name = 'Group';
	var $hadMany = array('DataGroup');
	
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => false,
				'message' => 'Group name is a required field'
			),
			'between' => array(
				'rule' => array('between', 2, 55),
				'message' => 'Group name should be minimum of 2 characters and maximum of 55 characters'
			)
		)
	);
}