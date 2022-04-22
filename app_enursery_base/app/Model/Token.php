<?php
App::uses('AppModel', 'Model');

class Token extends AppModel {
	var $name = 'Token';
	var $belongsTo = array('User');	
	
	public function generateToken($userId) {
		$plusOneMonth = strtotime("+1 month", strtotime(date('Y-m-d h:i:s')));
		$expirtyDate = date('Y-m-d h:i:s', $plusOneMonth);
		$salt = microtime();
		// token key format: userId+#+expirtyDate+#+salt
		$tokenKey = $userId.'#'.$expirtyDate.'#'.$salt;
		$token = md5($tokenKey);
		$data['Token']['user_id'] = $userId;
		$data['Token']['token'] = $token;
		$data['Token']['expiry_date'] = $expirtyDate;
		$data['Token']['salt'] = $salt;
		$tokenInfo = $this->save($data);
		return $tokenInfo;
	}
	
}
?>
