<?php
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();		
		$this->Auth->allow('login','add','forgotpassword', 'resetpassword', 'register', 'confirm', 'contactus'); // Letting users register themselves
	}	
	
	public function login() {
		$this->set('title_for_layout', 'Log In');
		$this->set('loginLinkActive', true);
		
		if ($this->request->is('post')) {		
			if ($this->Auth->login()) {				
				
				$userInfo = $this->Auth->user();
				if(!$userInfo['confirmed']) {
					$encodedUserID = base64_encode($userInfo['id']);
					$this->sendConfirmationLink($encodedUserID);	
					$this->Session->setFlash('Your account is not confirmed yet. A confirmation link has been sent to your email address.', 'default', array('class'=>'notice'));
					$this->redirect('/users/login');
				}		
								
				$this->Session->write('User', $userInfo);										
				$this->Session->write('User.login', '1');
				$this->redirect($this->Auth->redirect());
			} else {
				$this->set('errorMsg', 'Invalid email address or password. Please try again');
			}
		}
	}
	
	public function logout() {
		$this->Session->delete('User');
		$this->Session->delete('Company');
		$this->Session->delete('UserCompany');
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
		
	}
	
    public function index() {
		//$users = $this->User->find('all');
		
		
		$conditions = array('UserCompany.company_id'=>$this->Session->read('Company.id'));
		$users = $this->User->UserCompany->find('all', array('conditions'=>$conditions, 'order'=>array('UserCompany.created')));
				
		$this->set('users', $users);
    }	
	
	/**
	 * Function to edit user profile
	 */
	public function edit($userID=null) {
		App::uses('Company', 'Model');
		$this->Company = new Company;
		
		if(!$userID) {
			$userID = $this->Session->read('User.id');
		}
	
		$errorMsg = null;
		$userInfo = $this->User->find('first', array('conditions'=>array('User.id'=>$userID)));
	
		if(empty($userInfo)) {
			$this->Session->setFlash('User not found', 'default', array('class'=>'error'));
			$this->redirect('/users/');
		}
		
		if($this->request->is('put')) {
			$data = $this->request->data;
			// validations
			$errorMsg = null;	
			if(Validation::blank($data['User']['name'])) {
				$errorMsg = 'Enter Name';
			}
			elseif(!(Validation::between($data['User']['name'], 3, 55))) {
				$errorMsg = 'Name should be 3 to 55 characters long';
			}			
			elseif($this->User->find('first', array('conditions'=>array('User.email'=>$data['User']['email'], 'User.id NOT'=>$userID)))) {
				$errorMsg = 'User with this email address is already registered with us';
			}
			if(!$errorMsg) {		
				$data['User']['id'] = $userID;
				if ($this->User->save($data)) {
					$this->Session->setFlash('Account Updated Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action' => 'index'));
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'error'));
				}
			}			
		}
		else {
			$this->data = $userInfo;
		}
		$this->set('errorMsg', $errorMsg);
		$this->set('userInfo', $userInfo);
	}
	
	/**
	 * Function to request code for password reset
	 */	
	public function forgotpassword() { 
		$this->set('title_for_layout', 'Forgot your password?');
		
		if ($this->request->is('post')) {
			
			$data = $this->request->data;
			
			$errorMsg = null;
			$err = false;
			
			if(empty($data['User']['email'])){
				$errorMsg = 'Enter Email Address';
				$err = true;
			}
			if($err){
				$this->set('errorMsg',$errorMsg);
			}else{
				$email  = $data['User']['email'];
				$user = $this->User->findByEmail($email); 
				
				if(!$user){
					$this->Session->setFlash('Account not found.', 'default', array('class'=>'error'));
				}
				else{					
					$randomPass = $this->generatePassword(4);
					$this->Session->write('verification_code', $randomPass);
					$this->Session->write('verification_email', $email);
					
					try {						
						$mailContent = '
Dear '.$user['User']['name'].',

You have requested to reset your password. 

Below is the verification code, which is needed to reset your password.

Verification Code: '.$randomPass.'


-
'.Configure::read('Domain').'


*This is a system generated message. Please do not reply.
						';
						
						// send verification code in email					
						$email = new CakeEmail('smtpNoReply');
						$email->from(array('no-reply@letsgreenify.com' => 'letsgreenify.com'));
						$email->to($user['User']['email']);
						$email->subject('Password Reset');
						$email->send($mailContent);						
					}
					catch(Exception $ex) {
					}					
					
					$this->Session->setFlash('Verification Code has been sent to your Email Address.', 'default', array('class'=>'success'));
					$this->redirect('/users/resetpassword');
				}
			}
		}
		
	}
	
	public function resetpassword() { 
		$this->set('title_for_layout', 'Reset your password');	
		if(!$this->Session->check('verification_code')) {
			$this->Session->setFlash('Your session has expired. Please try again.', 'default', array('class'=>'error'));
			$this->redirect('/users/forgotpassword');
		}
		
		$errorMsg = null;
		if ($this->request->is('post')) {
			$data = $this->request->data;			
			if(empty($data['User']['verification_code'])) {
				$errorMsg = 'Enter Verification Code';
			}
			else {
				if($this->data['User']['verification_code'] == $this->Session->read('verification_code')) {
					$email = $this->Session->read('verification_email');
					$user = $this->User->findByEmail($email); 
					if(!empty($user)) {					
						$randomPass = $this->generatePassword();						
						
						$tmp['User']['id'] = $user['User']['id'];
						$tmp['User']['password'] = $randomPass;
						if($this->User->save($tmp)) {					
							try {						
								$mailContent = '
Dear '.$user['User']['name'].',

Your password has been reset. Below are your login credentials. 

Email: '.$email.'
Password: '.$randomPass.'


-
'.Configure::read('Domain').'


*This is a system generated message. Please do not reply.
								';
								
								// send login credentials in email					
								$email = new CakeEmail('smtpNoReply');
								$email->from(array('no-reply@letsgreenify.com' => 'letsgreenify.com'));
								$email->to($user['User']['email']);
								$email->subject('Your New Password');
								$email->send($mailContent);									
							}
							catch(Exception $ex) {
							}
							
							$this->Session->delete('verification_code');
							$this->Session->delete('verification_email');							
							
							$this->Session->setFlash('Your password has been reset. Login details have been sent to your email address. Please check your Email.', 'default', array('class'=>'success'));
							$this->redirect('/users/login');
						}
					}
					else {
						$errorMsg = 'Account Not Found';
					}
				}
				else {
					$errorMsg = 'Invalid Verification Code';
				}
			}
			
		}
		$this->set('errorMsg', $errorMsg);
	}
	
	/**
	 * Function to genereate random password
	 */
	function generatePassword ($length = 8){
        // inicializa variables
        $password = "";
        $i = 0;
        $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
        
        // agrega random
        while ($i < $length){
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            
            if (!strstr($password, $char)) { 
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }
	
	/**
	 * Function to change password
	 */
	function changepassword() {
		$this->set('title_for_layout', 'Change your password');
		$errorMsg = '';
		if($this->request->ispost()) {
			$oldPwd = $this->request->data['User']['password'];
			$oldPwd = AuthComponent::password($oldPwd);
			$conditions = array('User.id'=>$this->Session->read('User.id'), 'User.password'=>$oldPwd);
			$userInfo = $this->User->find('first', array('conditions'=>$conditions, 'recursive'=>'-1'));
			
			if(!empty($userInfo)) {
				$newPwd = $this->request->data['User']['new_password'];
				$confirmPwd = $this->request->data['User']['confirm_password'];				
				
				if(!(Validation::equalTo($newPwd, $confirmPwd))) {
					$errorMsg = 'New Password and Confirm Password do not match';
				}
				else {
					$this->User->id = $userInfo['User']['id'];
					$this->User->set('password', $newPwd);
					$this->User->save();
					$this->Session->setFlash('Password has been changed successfully', 'default', array('class'=>'success'));
					$this->redirect('/users/changepassword');
				}
			}
			else{
				$errorMsg = 'Incorrect Old Password';
			}
		}
		
		
		$this->set('errorMsg', $errorMsg);
	}
		
	/**
	 * Function to register a user
	 */
	function register() {		
		$this->set('title_for_layout', 'Register Your Account');
		$this->set('registerLinkActive', true);
		
		$this->setCaptchaColor(); 
				
		$errorMsg = array();	
		if($this->request->is('post')) {
			$data = $this->request->data;			
			
			// Validations						
			// validate name
			if(Validation::blank($data['User']['name'])) {
				$errorMsg[] = 'Enter Name';
			}
			elseif(!Validation::between($data['User']['name'], 3, 50)) {
				$errorMsg[] = 'Name should be 3 to 50 chars long';
			}
			
			if(Validation::blank($data['User']['phone'])) {
				$errorMsg[] = 'Enter Phone No.';
			}			
			elseif(!Validation::between($data['User']['phone'], 10, 55)) {
				$errorMsg[] = 'Phone No. should contain min 10 & max 55 digits';
			}
			
			// validate user email
			if(Validation::blank($data['User']['email'])) {
				$errorMsg[] = 'Enter Email Address';
			}
			elseif(!(Validation::email($data['User']['email']))) {
				$errorMsg[] = 'Invalid Email Address';
			}
			elseif($this->User->findByEmail($this->request->data['User']['email'])) {
				$errorMsg[] = 'User with this email address already exists';
			} 			
			
			// validate password
			elseif(Validation::blank($data['User']['password'])) {
				$errorMsg[] = 'Enter Password';
			}
			elseif(Validation::blank($data['User']['confirm_password'])) {
				$errorMsg[] = 'Confirm Password field is empty';
			}
			elseif($data['User']['confirm_password'] != $data['User']['password']) {
				$errorMsg[] = 'Passwords do not match';
			}
			elseif(!Validation::between($data['User']['password'], 5, 50)) {
				$errorMsg[] = 'Password should be 5 to 50 chars long';
			}
			
			// validate Site Title			
			if(Validation::blank($data['Site']['title'])) {
				$errorMsg[] = 'Enter Site Title';
			}
			elseif(!Validation::between($data['Site']['title'], 3, 50)) {
				$errorMsg[] = 'Site title should be 3 to 50 chars long';
			}
			if(!Validation::blank($data['Site']['caption'])) {
				if(!Validation::between($data['Site']['caption'], 3, 50)) {
					$errorMsg[] = 'Site caption should be 3 to 50 chars long';
				}
			}
			if(Validation::blank($data['Site']['name'])) {
				$errorMsg[] = 'Enter Subdomain Name';
			}
			elseif(!Validation::between($data['Site']['name'], 3, 25)) {
				$errorMsg[] = 'Subdomain name should be 3 to 25 chars long';
			}
				
			// Sanitize data
			$data['User']['name'] = Sanitize::clean($data['User']['name']);
			$data['Site']['title'] = Sanitize::clean($data['Site']['title']);
			$data['Site']['caption'] = Sanitize::clean($data['Site']['caption']);
			$data['Site']['name'] = Sanitize::stripAll($data['Site']['name']);
			$data['Site']['name'] = Sanitize::clean($data['Site']['name']);
			$data['Site']['name'] = strtolower($data['Site']['name']);
			
			// Check if subdomain name is already taken
			App::uses('Site', 'Model');
			$this->Site = new Site;
			if($this->Site->findByName($data['Site']['name'])) {
				$errorMsg[] = 'Site with this subdomain name already exists';
			} 	
			
			$response = $this->verifyReCaptcha($data);
			if($response['error']) {
				$errorMsg[] = 'User could not be verified as human. {'.$response['error'].'}';
			}
			
			// if(isset($data['Color']['code'])) {
				// if(!empty($data['Color']['code'])) {
					// if(!$this->validCaptchaColor($data['Color']['code'])) {
						// $errorMsg[] = 'Select Proper Color';
					// }
				// }
				// else {
					// $errorMsg[] = 'Select Color';
				// }
			// }
			// else {
				// $errorMsg[] = 'Select Color.';
			// }			
			
			if(!$errorMsg) {
				unset($data['User']['confirm_password']);
				$data['User']['id'] = null;
				$password = $data['User']['password'];
				if($this->User->save($data)) {
					$userInfo = $this->User->read();
					
					$sData['Site'] = $data['Site'];					
					$sData['Site']['domain_name'] = $data['Site']['name'].'.'.Configure::read('Domain');					
					$sData['Site']['id'] = null;
					$sData['Site']['user_id'] = $userInfo['User']['id'];					
					$sData['Site']['contact_email'] = $userInfo['User']['email'];					
					$sData['Site']['contact_phone'] = $userInfo['User']['phone'];	
					
					if($this->Site->save($sData)) {
						$siteInfo = $this->Site->read();
						$domainData['Domain']['name'] = $siteInfo['Site']['domain_name'];
						$domainData['Domain']['site_id'] = $siteInfo['Site']['id'];
						$domainData['Domain']['user_id'] = $siteInfo['Site']['user_id'];
						$domainData['Domain']['default'] = true;
						
						App::uses('Domain', 'Model');
						$this->Domain = new Domain;
						if($this->Domain->save($domainData)) {
							$domainInfo = $this->Domain->read();
							$this->Session->write('DomainInfo', $domainInfo);
							
							$encodedUserID = base64_encode($userInfo['User']['id']);
							$this->sendConfirmationLink($encodedUserID, $password);	
							$this->Session->setFlash('You have successfully registered with '.Configure::read('Domain'), 'default', array('class'=>'success'));
							$this->redirect('/pages/registration_success');	
						}
						else {
							$errorMsg[] = 'An error occured while communicating with the server';
							$this->Site->delete($siteInfo['Site']['id']);
						}
					}
					else {
						$errorMsg[] = 'An error occured while communicating with the server';	
						$this->User->delete($userInfo['User']['id']);
					}
				}
				else {
					$errorMsg[] = 'An error occured while communicating with the server';	
				}			
			}
			$errorMsg = implode('<br/>', $errorMsg);			
		}		
		
		$this->set(compact('errorMsg'));
	}	
	
	/**
	 * Function to send a account confirmation link to the user being registered
	 */
	function sendConfirmationLink($encodedUserID, $password=null) {
		try {
			$userID = base64_decode($encodedUserID);
			$userInfo = $this->User->findById($userID);
			$linkPath = Configure::read('DomainUrl').'/users/confirm/'.$encodedUserID;
			//$hyperLink = '<a href="'.$linkPath.'">'.$linkPath.'</a>';
			$pwd = ($password) ? $password : '***** (not shown for security reasons)';
			if(!empty($userInfo)) {
								
				$mailContent = '
Dear User,

Your account has been successfully created. Before you start using your account, you need to confirm it. 

Click the below link to confirm your account.
'.$linkPath.'

If the above link doesnt work for you, then copy paste the same in the address bar.

Below are your login details
	Email: '.$userInfo['User']['email'].'
	Password: '.$pwd.'

	
Thank you!.

-
'.Configure::read('Domain').'


*This is a system generated message. Please do not reply.

';
				$fromName = Configure::read('NoReply.name');
				$fromEmail = Configure::read('NoReply.email');				
				$toName = $userInfo['User']['name'];
				$toEmail = $userInfo['User']['email'];				
				$bcc = Configure::read('SupportEmail');
				$subject = 'Registration';
				
				$email = new CakeEmail('smtpNoReply');
				$email->from(array($fromEmail=>$fromName));
				$email->to(array($toEmail=>$toName));				
				$email->subject($subject);
				$email->send($mailContent);		
				
				// send message to support team
				$mailContent = '
Dear Admin,

'.				
$toName.'('.$toEmail.') has registered on '.Configure::read('Domain').'

This message is for notification purpose only. 

-
'.Configure::read('Domain').'

*This is a system generated message. Please do not reply.	
			
';
				$supportEmail = Configure::read('SupportEmail');				
				$email = new CakeEmail('smtpNoReply');
				$email->from(array($fromEmail=>$fromName));
				$email->to($supportEmail);				
				$email->subject('New Registration');
				$email->send($mailContent);				
			}			
		}
		catch(Exception $ex) {
			
		}
	}
	
	/**
	 * Function to confirm a user's account
	 */	
	function confirm($encodedUserID) {
		$userID = base64_decode($encodedUserID);
		if($userInfo = $this->User->findById($userID)) {
			if($userInfo['User']['confirmed']) {
				$this->Session->setFlash('Your account has been already confirmed.', 'default', array('class'=>'notice'));
				$this->redirect('/');
			}
		
			$data['User']['id'] = $userID;
			$data['User']['confirmed'] = '1';
			if($this->User->save($data)) {
				$userInfo = $this->User->read();
				$this->Session->destroy();				
				$this->Session->setFlash('Your account has been confirmed. Please login to continue', 'default', array('class'=>'success'));	
				$this->redirect('/users/login');				
			}
			else {
				$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));
			}
		}
		else {
			$this->Session->setFlash('Unknown user', 'default', array('class'=>'error'));
		}
		$this->set(compact('userInfo'));
		$this->redirect('/');
	}
	
	/**
	 * Function to remove a user from the selected company
	 */
	function remove($userID=0) {
		
		$conditions = array('UserCompany.user_id'=>$userID, 'UserCompany.company_id'=>$this->Session->read('Company.id'));
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany;
		
		$this->UserCompany = new UserCompany();
		if($companyInfo = $this->UserCompany->find('first', array('conditions'=>$conditions))) {
			$this->UserCompany->delete($companyInfo['UserCompany']['id']);
			$this->Session->setFlash('User has been successfully removed', 'default', array('class'=>'success'));
		}
		else {
			$this->Session->setFlash('You are not authorized to perform this action [Restricted Access]', 'default', array('class'=>'error'));			
		}
		$this->redirect('/users/');
	}	

	function verifyReCaptcha($data) {		
		$recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
		$params['secret'] = '6Ldj7vEUAAAAAC3dpUZubF3-xeahsV7HXbE2li7o';
		$params['response'] = $data['g-recaptcha-response'];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $recaptchaVerifyUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
		$result = curl_exec($ch);		
		$error = null;
		if (curl_errno($ch)) {
			$error = curl_error($ch);			
		} else {
			$result = (array)json_decode($result);
			if(isset($result['success']) and ($result['success'] == false)) {
				$error = $result['error-codes'][0];
			}
		}
		
		curl_close($ch);

		$response['error'] = $error;
		$response['data'] = $result;
		
		return $response;		
	}
	
	function contactus() {
		$errorMsg = array();
		$successMsg = null;
		if ($this->request->is('post')) {
			$data = $this->request->data;
			
			if(!$this->Session->check('User')) {			
				// Validate name
				if(Validation::blank($data['User']['name'])) {
					$errorMsg[] = 'Enter your name';
				}
				
				// validate user email
				if(Validation::blank($data['User']['email'])) {
					$errorMsg[] = 'Enter Email Address';
				}
				elseif(!(Validation::email($data['User']['email']))) {
					$errorMsg[] = 'Invalid Email Address';
				}
			}
			else {
				$data['User']['name'] = $this->Session->read('User.name');
				$data['User']['email'] = $this->Session->read('User.email');
			}
			// Validate message
			if(Validation::blank($data['User']['message'])) {
				$errorMsg[] = 'Message field cannot be empty';
			}
			
			$response = $this->verifyReCaptcha($data);
			if($response['error']) {
				$errorMsg[] = 'User could not be verified as human. {'.$response['error'].'}';
			}			
			
			if(empty($errorMsg)) {
				try {									
					$mailContent = '
Dear Admin,

A person has tried to contact you on '.Configure::read('Domain').'.

Contact Details:
----------------------------------------
Name: '.$data['User']['name'].'
Email: '.$data['User']['email'].'
Message: '.htmlentities($data['User']['message']).'


-
'.Configure::read('Domain').'

*This is a system generated message. Please do not reply.

';					
					$fromName = Configure::read('NoReply.name');
					$fromEmail = Configure::read('NoReply.email');	
					$supportEmail = Configure::read('SupportEmail');
					$email = new CakeEmail('smtpNoReply');
					$email->from(array($fromEmail => $fromName));
					$email->replyTo(array($data['User']['email'] => $data['User']['name']));
					$email->to($supportEmail);
					$email->subject('Contact Us');
					$email->send($mailContent);	
					
					// Send SMS
					$user_message = trim($data['User']['message']);
					$sms_text = array(
						$data['User']['name'],
						$data['User']['email'],
						'message: '.$user_message
					);
					$sms_text = implode(', ', $sms_text);
					$sms_text = substr($sms_text, 0, 159);
					$this->sendSMS('919494203060', $sms_text);
					// end of send SMS
					
					$this->Session->setFlash('Your message has been sent successfully.', 'default', array('class'=>'success'));
					$this->redirect('/pages/contactus_message_sent');		
				}
				catch(Exception $ex) {
					$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));					
				}
			}			
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set('errorMsg', $errorMsg);
		$this->set('successMsg', $successMsg);
		$this->set('title_for_layout', 'Contact Us');
	}

	function manageYourSite() {
		if($this->Session->read('User.superadmin')) {
			$this->redirect('/admin/sites/');
		}
		else {
			$this->redirect('/pages/manageYourSite');
		}
	}
	
	function admin_login() {
		$this->redirect('/users/login');
	}
	function admin_logout() {
		$this->redirect('/users/logout');
	}
	
	function admin_index() {
		$this->checkSuperAdmin();	
		$this->paginate = array(
				'limit' => 25,
				'order' => array('User.created' => 'desc')
			);
		$users = $this->paginate();
		$this->set(compact('users'));
	}
	
	function admin_userInfo($userID=null) {
		if(!$userID) {
			$userID = $this->Session->read('User.id');
		}
		// restrict admin users to view other user's profile
		if($userID != $this->Session->read('User.id')) {
			if(!$this->checkSuperAdmin()) {
				$this->Session->setFlash('You are not authorized to view this page', 'default', array('class'=>'error'));
				$this->redirect('/admin/users/userInfo');
			}
		}		
		
		if($userInfo = $this->User->findById($userID)) {
			$this->set(compact('userInfo'));
		}
		else {
			$this->Session->setFlash('Account not found', 'default', array('class'=>'error'));
			$this->redirect('/');
		}
		$this->set('userID', $userID);
	}
	
	function admin_edit($userID=null) {
		if(!$userID) {
			$userID = $this->Session->read('User.id');
		}
		// restrict admin users to view other user's profile
		if($userID != $this->Session->read('User.id')) {
			if(!$this->checkSuperAdmin()) {
				$this->Session->setFlash('You are not authorized to view this page', 'default', array('class'=>'error'));
				$this->redirect('/admin/users/userInfo');
			}
		}
		App::uses('Site', 'Model');
		$this->Site = new Site;
		$sites = $this->Site->find('list');
		$this->set('sites', $sites);
		
		$errorMsg = array();
		if($this->request->isPost() or $this->request->isPut()) {
			$data['User'] = $this->request->data['User'];
			$data['Site'] = $this->request->data['Site'];
			$data['User']['id'] = $userID;
			unset($data['User']['email']);
			
			$siteID = (isset($data['Site']['id']) and !empty($data['Site']['id'])) ? $data['Site']['id'] : null;
			
			// Validations						
			// validate name
			if(Validation::blank($data['User']['name'])) {
				$errorMsg[] = 'Enter Name';
			}
			elseif(!Validation::between($data['User']['name'], 3, 50)) {
				$errorMsg[] = 'Name should be 3 to 50 chars long';
			}			
			
			// Sanitize data
			$data['User']['name'] = Sanitize::clean($data['User']['name']);
			$data['User']['address'] = Sanitize::clean($data['User']['address']);
			$data['User']['phone'] = Sanitize::clean($data['User']['phone']);
			$data['User']['city'] = Sanitize::clean($data['User']['city']);
			$data['User']['state'] = Sanitize::clean($data['User']['state']);
			$data['User']['country'] = Sanitize::clean($data['User']['country']);
			$data['User']['postcode'] = Sanitize::clean($data['User']['postcode']);
			
			if(!$errorMsg) {
				if($this->User->save($data)) {
					if($siteID) {
						$tmp['Site']['id'] = $siteID;
						$tmp['Site']['user_id'] = $userID;
						$this->Site->save($tmp);
					}
				
					$userInfo = $this->User->read();
					if($this->Session->read('User.id')==$userID) {
						$this->Session->write('User', $userInfo['User']);
					}
					$this->Session->setFlash('Account information updated successfully', 'default', array('class'=>'success'));
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'error'));
				}
			}
		}
		else {
			if($userInfo = $this->User->findById($userID)) {
				$this->data = $userInfo;
			}
			else {
				$this->Session->setFlash('Account not found', 'default', array('class'=>'error'));
				$this->redirect('/');
			}
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('errorMsg', 'userID'));
	}
	
	function admin_add() {
		$this->checkSuperAdmin();
		
		App::uses('Site', 'Model');
		$this->Site = new Site;
		$sites = $this->Site->find('list');
		$this->set('sites', $sites);		
		
		$errorMsg = array();
		if($this->request->isPost()) {
			$data['User'] = $this->request->data['User'];
			$data['User']['id'] = null;
			
			// Validations						
			// validate name
			if(Validation::blank($data['User']['name'])) {
				$errorMsg[] = 'Enter Name';
			}
			elseif(!Validation::between($data['User']['name'], 3, 50)) {
				$errorMsg[] = 'Name should be 3 to 50 chars long';
			}	
			
			// validate user email
			if(Validation::blank($data['User']['email'])) {
				$errorMsg[] = 'Enter Email Address';
			}
			elseif(!(Validation::email($data['User']['email']))) {
				$errorMsg[] = 'Invalid Email Address';
			}
			elseif($this->User->findByEmail($data['User']['email'])) {
				$errorMsg[] = 'User with this email address already exists';
			} 			
			
			// validate password
			elseif(Validation::blank($data['User']['password'])) {
				$errorMsg[] = 'Enter Password';
			}
			elseif(Validation::blank($data['User']['confirm_password'])) {
				$errorMsg[] = 'Confirm Password field is empty';
			}
			elseif($data['User']['confirm_password'] != $data['User']['password']) {
				$errorMsg[] = 'Passwords do not match';
			}
			elseif(!Validation::between($data['User']['password'], 5, 50)) {
				$errorMsg[] = 'Password should be 5 to 50 chars long';
			}		
			// Sanitize data
			$data['User']['name'] = Sanitize::clean($data['User']['name']);
			$data['User']['address'] = Sanitize::clean($data['User']['address']);
			$data['User']['phone'] = Sanitize::clean($data['User']['phone']);
			$data['User']['city'] = Sanitize::clean($data['User']['city']);
			$data['User']['state'] = Sanitize::clean($data['User']['state']);
			$data['User']['country'] = Sanitize::clean($data['User']['country']);
			$data['User']['postcode'] = Sanitize::clean($data['User']['postcode']);			
			
			if(!$errorMsg) {
				if($this->User->save($data)) {
					$userInfo = $this->User->read();				
					$this->Session->setFlash('Account created successfully', 'default', array('class'=>'success'));
					$this->redirect('/admin/users/');
				}
				else {
					$errorMsg[] = 'An error occured while communicating with the server';
				}
			}
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('errorMsg'));
	}
}
?>