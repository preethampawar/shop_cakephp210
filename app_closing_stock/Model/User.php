<?php
App::uses('AppModel', 'Model');

class User extends AppModel
{
	public $validate = [
		'name' => [
			'required' => [
				'rule' => ['notBlank'],
				'message' => 'Name is required',
			],
			'between' => [
				'rule' => ['between', 3, 55],
				'message' => 'Name should be between 3 and 55 characters',
			],
		],
		'email' => [
			'required' => [
				'rule' => ['notBlank'],
				'message' => 'Email is required',
			],
			'email-address-rule' => [
				'rule' => ['email'],
				'message' => 'Enter valid Email Address',
			],
			'uniqueness-rule' => [
				'rule' => ['isUnique'],
				'message' => 'User with this Email Address is already registered. Use a different Email Address',
			],
		],
		'password' => [
			'required' => [
				'rule' => ['notBlank'],
				'message' => 'A password is required',
			],
			'between' => [
				'rule' => ['between', 5, 55],
				'message' => 'Password should be between 5 and 55 characters',
			],
		],
	];

	public function beforeSave($options = [])
	{
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}
