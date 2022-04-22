<?php
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();		
		$this->Auth->allow('login','add','forgotpassword', 'resetpassword', 'register', 'confirm'); // Letting users register themselves
	}	
	
	public function login() {
		$this->set('title_for_layout', 'Log In');
		
		if ($this->request->is('post')) {		
			if ($this->Auth->login()) {				
				
				$userInfo = $this->Auth->user();
				if(!$userInfo['registered']) {
					$encodedUserID = base64_encode($userInfo['id']);
					$this->sendConfirmationLink($encodedUserID);	
					$this->Session->setFlash('Your account is not confirmed yet. A confirmation link has been sent to your email address.');
					$this->redirect('/users/login');
				}				
				
				$this->Session->write('User', $userInfo);
				
				// App::uses('Company', 'Model');
				// $this->Company = new Company;
				// $companyInfo = $this->Company->findById($userInfo['company_id']);
				// $this->Session->write('Company', $companyInfo['Company']);
								
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
	public function edit($userID) {
		App::uses('Company', 'Model');
		$this->Company = new Company;
		if($this->Session->read('UserCompany.user_level') == '4') {
			$this->set('companies', $this->Company->find('list'));
		}
		else{
			$this->set('companies', $this->Company->find('list', array('conditions'=>array('Company.id'=>$this->Session->read('Company.id')))));
		}
	
		$errorMsg = null;
		$userInfo = $this->User->find('first', array('conditions'=>array('User.id'=>$userID)));
	
		if(empty($userInfo)) {
			$this->Session->setFlash('User not found', 'default', array('class'=>'message'));
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
					$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
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
MyAccountManager.in


*This is a system generated message. Please do not reply.
						';
						
						// send verification code in email					
						$email = new CakeEmail();
						$email->from(array('noreply@enursery.in' => 'MyAccountManager.in'));
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
			$this->Session->setFlash('Your session has expired. Please try again.');
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
MyAccountManager.in


*This is a system generated message. Please do not reply.
								';
								
								// send login credentials in email					
								$email = new CakeEmail();
								$email->from(array('noreply@enursery.in' => 'MyAccountManager.in'));
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
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$errorMsg = null;	
			
			// Validations			
			// validate user name
			if(Validation::blank($data['User']['name'])) {
				$errorMsg = 'Enter Name';
			}
			elseif(!(Validation::between($data['User']['name'], 4, 55))) {
				$errorMsg = 'Name should be 4 to 55 characters long';
			}
			
			// validate user email
			elseif(Validation::blank($data['User']['email'])) {
				$errorMsg = 'Enter Email Address';
			}
			elseif(!(Validation::email($data['User']['email']))) {
				$errorMsg = 'Invalid Email Address';
			}
			elseif($this->User->findByEmail($this->request->data['User']['email'])) {
				$errorMsg = 'User with this email address is already registered with us';
			} 	
			
			
			// validate password
			elseif(Validation::blank($data['User']['password'])) {
				$errorMsg = 'Enter Password';
			}
			elseif(Validation::blank($data['User']['confirm_password'])) {
				$errorMsg = 'Confirm Password field is empty';
			}
			elseif($data['User']['confirm_password'] != $data['User']['password']) {
				$errorMsg = 'Passwords do not match';
			}						
			
			// Sanitize data
			$data['User']['name'] = Sanitize::paranoid($data['User']['name'], array(' \s'));
			$data['User']['name'] = Sanitize::stripWhitespace($data['User']['name']);
			
			if(!$errorMsg) {
				unset($data['User']['confirm_password']);
				$data['User']['id'] = null;
				if($this->User->save($data)) {
					$userInfo = $this->User->read();					
					$encodedUserID = base64_encode($userInfo['User']['id']);
					$this->sendConfirmationLink($encodedUserID);	
					$this->redirect('/pages/registration_success');
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));
				}
			
				
			}
			
			$this->set(compact('errorMsg'));
			
		}		
	}	
	
	/**
	 * Function to send a account confirmation link to the user being registered
	 */
	function sendConfirmationLink($encodedUserID) {
		try {
			$userID = base64_decode($encodedUserID);
			$userInfo = $this->User->findById($userID);
			if(!empty($userInfo)) {
				$link = 'www.myaccountmanager.in/users/confirm/'.$encodedUserID;
								
				$mailContent = '
Dear '.$userInfo['User']['name'].',

Your account has been successfully created. Before you start using your account, you need to confirm it. 

Click the below link to confirm your account.
'.$link.'

If the above link doesnt work for you, then copy paste the same in the address bar.

Below are your login details
	Email: '.$userInfo['User']['email'].'
	Password: ***** (not shown for security reasons)

Thank you for showing interest in us.


-
MyAccountManager.in


*This is a system generated message. Please do not reply.

';
				$email = new CakeEmail();
				$email->from(array('noreply@enursery.in' => 'MyAccountManager.in'));
				$email->to($userInfo['User']['email']);
				$adminEmail = Configure::read('Admin.email');
				$email->bcc($adminEmail);
				$email->subject('Registration');
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
			if($userInfo['User']['registered']) {
				$this->Session->setFlash('Your account has been already confirmed.', 'default', array('class'=>'notice'));
				$this->redirect('/');
			}
		
			$data['User']['id'] = $userID;
			$data['User']['registered'] = '1';
			if($this->User->save($data)) {
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
	
	/**
	 * Function to invite a user to the selected company
	 */
	function inviteUser() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$errorMsg = null;	
			$registeredUser = false;
			
			// Validations			
			// validate user name
			if(Validation::blank($data['User']['name'])) {
				$errorMsg = 'Enter Name';
			}
			elseif(!(Validation::between($data['User']['name'], 4, 55))) {
				$errorMsg = 'Name should be 4 to 55 characters long';
			}
			
			// validate user email
			elseif(Validation::blank($data['User']['email'])) {
				$errorMsg = 'Enter Email Address';
			}
			elseif(!(Validation::email($data['User']['email']))) {
				$errorMsg = 'Invalid Email Address';
			}
			
			if($userDetails = $this->User->findByEmail($data['User']['email'])) {
				$registeredUser = true;
			} 				
			else {
				$password = $this->generatePassword();
				$data['User']['password'] = $password;
				// $data['User']['password'] = 'preetham';
				$data['User']['registered'] = '1';
				
			}
			
			if(!$registeredUser) {
				// Sanitize data
				$data['User']['name'] = Sanitize::paranoid($data['User']['name'], array(' \s'));
				$data['User']['name'] = Sanitize::stripWhitespace($data['User']['name']);
				
				if(!$errorMsg) {
					
					$data['User']['id'] = null;
					if($this->User->save($data)) {
						$userInfo = $this->User->read();					
						$encodedUserID = base64_encode($userInfo['User']['id']);
						
						$tmp['UserCompany']['id'] = null;
						$tmp['UserCompany']['company_id'] = $this->Session->read('Company.id');
						$tmp['UserCompany']['user_id'] = $userInfo['User']['id'];
						$tmp['UserCompany']['user_level'] = $data['UserCompany']['user_level'];
						
						App::uses('UserCompany', 'Model');
						$this->UserCompany = new UserCompany;
						if($this->UserCompany->save($tmp)) {
							// Send Email To User
							try {							
								$mailContent = '
Dear '.$userInfo['User']['name'].',

You have been invited by "'.$this->Session->read('User.name').'" to access "'.$this->Session->read('Company.title').'" account on www.myaccountmanager.in 						

Below are your login details
Email: '.$userInfo['User']['email'].'
Password: '.$password.'										

Login with your Email Address and Password to access this account.

http://www.myaccountmanager.in/users/login


-
MyAccountManager.in


*This is a system generated message. Please do not reply.

';
							
								$email = new CakeEmail();
								$email->from(array('noreply@enursery.in' => 'MyAccountManager.in'));
								$email->to($data['User']['email']);
								$email->subject('Invite to access an account on MyAccountManager.in');
								$email->send($mailContent);									
							}
							catch(Exception $ex) {
								
							}
							$this->Session->setFlash($userInfo['User']['email'].' has been invited to access this account', 'default', array('class'=>'success'));
							$this->redirect('/users/');							
						}
						else {
							$this->User->delete($userInfo['User']['id']);
							$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));
						}	
					}
					else {
						$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));
					}				
					
				}
			}
			else {
				$userInfo = $userDetails;
				App::uses('UserCompany', 'Model');
				$this->UserCompany = new UserCompany;
				
				$conditions = array('UserCompany.user_id'=>$userInfo['User']['id'], 'UserCompany.company_id'=>$this->Session->read('Company.id'));
				if($this->UserCompany->find('first', array('conditions'=>$conditions))) {
					$this->Session->setFlash($userInfo['User']['email'].' is already registed with this account.', 'default', array('class'=>'notice'));
				}
				else {
					$tmp['UserCompany']['id'] = null;
					$tmp['UserCompany']['company_id'] = $this->Session->read('Company.id');
					$tmp['UserCompany']['user_id'] = $userInfo['User']['id'];
					$tmp['UserCompany']['user_level'] = $data['UserCompany']['user_level'];					
					
					if($this->UserCompany->save($tmp)) {
						// Send Email To User
						try {							
							$mailContent = '
Dear '.$userInfo['User']['name'].',

You have been invited by "'.$this->Session->read('User.name').'" to access "'.$this->Session->read('Company.title').'" account on www.myaccountmanager.in 						

Below are your login details
Email: '.$userInfo['User']['email'].'
Password: ****** (not shown for security reasons)										

Login with your Email Address and Password to access this account.

http://www.myaccountmanager.in/users/login


-
MyAccountManager.in


*This is a system generated message. Please do not reply.
							
							';
						
							$email = new CakeEmail();
							$email->from(array('noreply@enursery.in' => 'MyAccountManager.in'));
							$email->to($data['User']['email']);
							$email->subject('Invite to access an account on MyAccountManager.in');
							$email->send($mailContent);									
						}
						catch(Exception $ex) {
							
						}	
						$this->Session->setFlash($userInfo['User']['email'].' has been invited to access this account', 'default', array('class'=>'success'));
						$this->redirect('/users/');			
					}
					else {
						$this->User->delete($userInfo['User']['id']);
						$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));
					}
				}
			}
			$this->set(compact('errorMsg'));
			
		}
	}
		
	/**
	 * Function to change user access level
	 */
	public function changeUserAccess($userID=null) {
		if($this->request->isPost() and (!empty($userID))) {
			App::uses('UserCompany', 'Model');
			$this->UserCompany = new UserCompany;
			$data = $this->request->data;
						
			$companyID = $this->Session->read('Company.id');
			
			$conditions = array('UserCompany.user_id'=>$userID, 'UserCompany.company_id'=>$companyID);
			if($usercompanyInfo = $this->UserCompany->find('first', array('conditions'=>$conditions))) {
				$data['UserCompany']['id'] = $usercompanyInfo['UserCompany']['id'];
				
				$data['UserCompany']['user_level'] = ($data['UserCompany']['user_level'] > 3) ? '3' : $data['UserCompany']['user_level'];
				$data['UserCompany']['user_level'] = $this->data['UserCompany']['user_level'];
				if($this->UserCompany->save($data)) {
					$this->Session->setFlash('User Access Changed Successfully', 'default', array('class'=>'success'));
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'error'));
				}				
			}		
			else {
				$this->Session->setFlash('User Not Found', 'default', array('class'=>'error'));
			}
		}
		else {
			$this->Session->setFlash('You are not authorized to perform this action', 'default', array('class'=>'error'));
		}
		$this->redirect('/users/');
	}
	
	/**
	 * Show all registered users
	 */
	function admin_index() {
		$this->paginate = array(
				'limit' => 25,
				'order' => array('User.created' => 'desc'),
				'recursive' => 2
			);
		$users = $this->paginate();
		$this->set('users', $users);		
	}
	
	/**
	 * Function to add a user
	 */
	public function admin_add() {	
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$errorMsg = null;	
			
			// Validations			
			// validate user name
			if(Validation::blank($data['User']['name'])) {
				$errorMsg = 'Enter Name';
			}
			elseif(!(Validation::between($data['User']['name'], 4, 55))) {
				$errorMsg = 'Name should be 4 to 55 characters long';
			}
			
			// validate user email
			elseif(Validation::blank($data['User']['email'])) {
				$errorMsg = 'Enter Email Address';
			}
			elseif(!(Validation::email($data['User']['email']))) {
				$errorMsg = 'Invalid Email Address';
			}
			elseif($this->User->findByEmail($this->request->data['User']['email'])) {
				$errorMsg = 'User with this email address is already registered';
			} 			
			
			// validate password
			elseif(Validation::blank($data['User']['password'])) {
				$errorMsg = 'Enter Password';
			}
			elseif(Validation::blank($data['User']['confirm_password'])) {
				$errorMsg = 'Enter Confirm Password';
			}
			elseif($data['User']['confirm_password'] != $data['User']['password']) {
				$errorMsg = 'Passwords do not match';
			}			
			
			// Sanitize data
			$data['User']['name'] = Sanitize::paranoid($data['User']['name'], array(' \s'));
			$data['User']['city'] = Sanitize::paranoid($data['User']['city'], array(' \s'));
			$data['User']['state'] = Sanitize::paranoid($data['User']['state'], array(' \s'));
			$data['User']['country'] = Sanitize::paranoid($data['User']['country'], array(' \s'));
			$data['User']['zip'] = Sanitize::paranoid($data['User']['zip']);
			
			if(!$errorMsg) {				
				if ($userInfo = $this->User->save($data)) {					
					$this->Session->setFlash('Account Registered Successfully', 'default', array('class'=>'success'));
					$this->redirect('/admin/users/');							
				}	
				else {
					$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
				}
			}
			else {
				$this->set('errorMsg', $errorMsg);
			}
	    }
	} 
	 
	 
	
	/**
	 * Function to edit a user
	 */
	public function admin_edit($userID) {	
		$this->set('userID', $userID);
		$errorMsg = null;
		$userInfo = $this->User->find('first', array('conditions'=>array('User.id'=>$userID), 'recursive'=>2));		
	
		if(empty($userInfo)) {
			$this->Session->setFlash('User not found', 'default', array('class'=>'message'));
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
					$this->redirect('/admin/users/');
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
	
	function admin_edit_company_info($userID, $companyID) {
		if($this->request->is('put')) {			
			$data = $this->request->data;
			$data['Company']['id'] = $companyID;			
			App::uses('Company', 'Model');
			$this->Company = new Company;
			if($this->Company->save($data)) {
				$this->Session->setFlash('Changes saved successfully', 'default', array('class'=>'success'));
				$this->redirect('/admin/users/edit/'.$userID);
			}
		}
		$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'error'));
		$this->redirect('/admin/users/edit/'.$userID);
	}
	
	function admin_add_user_company($userID) {
		if($this->request->is('put')) {			
			$data = $this->request->data;
			$data['Company']['user_id'] = $userID;
			
			
			App::uses('Company', 'Model');
			$this->Company = new Company;
			if($this->Company->save($data)) {
				$companyInfo = $this->Company->read();
				$tmp['UserCompany']['id'] = null;
				$tmp['UserCompany']['company_id'] = $companyInfo['Company']['id'];
				$tmp['UserCompany']['user_id'] = $userID;
				$tmp['UserCompany']['user_level'] = '3';
				
				App::uses('UserCompany', 'Model');
				$this->UserCompany = new UserCompany;
				if($this->UserCompany->save($tmp)) {
					$this->Session->setFlash('Businesss/Personal account successfully created', 'default', array('class'=>'success'));
					$this->redirect('/admin/users/edit/'.$userID);
				}
				else {
					$this->Company->delete($companyInfo['Company']['id']);
				}			
			}		
		}
		$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'error'));
		$this->redirect('/admin/users/edit/'.$userID);
	}
	
	function admin_delete($userID) {
		App::uses('CompaniesController', 'Controller');
		$CompaniesController = new CompaniesController;
		$CompaniesController->constructClasses();
				
		if($userID) {
			App::uses('UserCompany', 'Model');
			$this->UserCompany= new UserCompany;
			$this->UserCompany->recursive = -1;
			$userCompanies = $this->UserCompany->findAllByUserId($userID);
			if(!empty($userCompanies)) {
				foreach($userCompanies as $userCompany) {
					$companyID = $userCompany['UserCompany']['company_id'];
					$CompaniesController->delete($companyID);
				}
			}
			$this->User->delete($userID);
			$this->Session->setFlash('User Account Deleted Successfully', 'default', array('class'=>'success'));
		}
		else {
			$this->Session->setFlash('Record Not Found', 'default', array('class'=>'error'));
		}
		$this->redirect('/admin/users/');
	}
	
	function admin_delete_user_company($userID, $companyID) {
		App::uses('CompaniesController', 'Controller');
		$CompaniesController = new CompaniesController;
		$CompaniesController->constructClasses();
		$CompaniesController->delete($companyID);
		$this->Session->setFlash('Company Deleted Successfully', 'default', array('class'=>'success'));
		$this->redirect('/admin/users/edit/'.$userID);
	}
	
}
?>