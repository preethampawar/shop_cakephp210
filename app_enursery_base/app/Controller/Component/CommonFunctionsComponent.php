<?php
App::uses('Component', 'Controller');
class CommonFunctionsComponent extends Component {
    var $components = array('Session', 'Auth');	
	
	public function authenticate($data) {
		App::uses('User', 'Model');
		$this->User = new User;
		
		$conditions = [
			'User.email' => $data['email'],
			'User.password' => md5($data['password']),
			'User.active' => 1
		];
		$fields = [
			'User.id', 'User.firstname', 'User.middlename', 'User.lastname', 'User.phone', 'User.email'
		];
		
		return $this->User->find('first', array('conditions'=>$conditions, 'recursive'=>'-1', 'fields' => $fields));		
	}
	
	public function generateToken($userId) {
		App::uses('Token', 'Model');
		$this->Token = new Token;
		
		$newToken = $this->Token->generateToken($userId);
		return $newToken;
	}
	
	public function authenticateByToken($token) {
		App::uses('User', 'Model');
		$this->User = new User;
		
		$conditions = [
			'User.id' => $token,
			'User.active' => 1
		];
		$fields = [
			'User.id', 'User.firstname', 'User.middlename', 'User.lastname', 'User.phone', 'User.email'
		];
		
		return $this->User->find('first', array('conditions'=>$conditions, 'recursive'=>'-1', 'fields' => $fields));
	}
	
	public function sendEmail($fromName, $fromEmail, $toName, $toEmail, $subject, $content, $replyToEmail = null, $replyToName = null) {
		if(!$replyToEmail) {
			$replyToEmail = $fromEmail;
		}
		if(!$replyToName) {
			$replyToName = $fromName;
		}
		$baseSupportEmail = 'preetham.pawar+base@gmail.com';
		$email = new CakeEmail('smtp');
		$email->from(array($fromEmail => $fromName));
		$email->replyTo(array($replyToEmail => $replyToName));
		$email->to($toEmail);
		$email->bcc($baseSupportEmail); // send email to letsgreenify support team
		$email->subject($subject);
		$email->emailFormat('both');
		return $email->send($content);
	}
}