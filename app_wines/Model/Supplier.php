<?php
App::uses('AppModel', 'Model');
class Supplier extends AppModel {
	var $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => false,
				'message' => 'Supplier name is a required field'
			),
			'between' => array(
				'rule' => array('between', 2, 55),
				'message' => 'Supplier name should be minimum of 2 characters and maximum of 55 characters'
			)
		)
	);
}
