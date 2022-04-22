<?php
App::uses('AppModel', 'Model');

class Employee extends AppModel
{
	public $validate = [
		'name' => [
			'required' => [
				'rule' => ['notBlank'],
				'message' => 'Name is required',
			],
		],
	];

}
