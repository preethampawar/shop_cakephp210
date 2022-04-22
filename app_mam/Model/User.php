<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
    public $name = 'User';
	
	var $hasMany = array('UserCompany');
	
	public $validate = array(				
		'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Invalid Email Address'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This E-mail used by another user'
            )
        )
		
	);

	function confirmPassword($data) {
		$valid = false;
		if ($data['password'] == $this->data['User']['confirm_password']) {
			$valid = true;
		}
		return $valid;
	}
		
	public function beforeSave($options = []) {
		if (isset($this->data[$this->alias]['password'])) {
		//echo $this->alias['password'];die;
		
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
    
}
?>