<?php
App::uses('AppModel', 'Model');

class User extends AppModel {
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Name is required'
            ),
            'between' => array(
                'rule' => array('between', 4,55),
                'message' => 'Name should be between 4 and 55 characters'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Email is required'
            ),
			'email-address-rule'=>array(
				'rule' => array('email'),
				'message' => 'Enter valid Email Address'
			),
			'uniqueness-rule' => array(
				'rule' => array('isUnique'),
				'message' => 'User with this Email Address is already registered. Use a different Email Address'
			)
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A password is required'
            ),
			'between' => array(
                'rule' => array('between', 4,55),
                'message' => 'Password should be between 4 and 55 characters'
            )
        )
    );
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}