<?php
App::uses('AppModel', 'Model');

class User extends AppModel
{
	var $name = 'User';
	var $hasOne = ['Site'];

	public function beforeSave($params = [])
	{

		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}

?>
