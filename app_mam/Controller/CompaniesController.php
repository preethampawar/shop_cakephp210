<?php
App::uses('CakeEmail', 'Network/Email');
class CompaniesController extends AppController {

	var $name = 'Companies';
	
	public function beforeFilter() {
		parent::beforeFilter();	
	}
	
	function index() {
		//$companies = $this->Company->find('all');
		
		$this->paginate = array(
				'limit' => 25,
				'order' => array('Company.created' => 'desc')
			);
		$companies = $this->paginate();
		$this->set('companies', $companies);
	}
	
	function add() {
		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '256M');
						
		$errorMsg = null;
		$subscription_end_date = null;					
		$hasPersonalAccount = false;
		$hasSharedAccounts = false;
		$hasOwnedAccounts = false;
		$showPersonalAccount = false;
		
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany;
		
		$userID = $this->Session->read('User.id');
		
		if($this->Company->findByUserId($userID)) {
			$hasOwnedAccounts = true;
			if($this->Company->find('first', array('conditions'=>array('Company.user_id'=>$userID, 'Company.business_type'=>'personal')))) {
				$hasPersonalAccount = true;
			}
			else {
				$showPersonalAccount = true;
			}
		}
		else {
			$showPersonalAccount = true;
			if($this->UserCompany->findByUserId($userID)) {
				$hasSharedAccounts = true;
			}			
		}
		$this->set('showPersonalAccount', $showPersonalAccount);
			
		if($this->request->is('post')) {
			$data = $this->request->data;			
			if(Validation::blank($data['Company']['title'])) {
				$errorMsg = 'Enter Company Name';
			}
			elseif(!(Validation::between($data['Company']['title'], 3, 55))) {
				$errorMsg = 'Company name should be 3 to 55 characters long';
			}
			if(Validation::blank($data['Company']['business_type'])) {
				$errorMsg = 'Select Account Type'; 
			}
			elseif($data['Company']['business_type'] != 'personal') {
				// +1 year from now.
				$active = '1';
				// $nxtDate = strtotime('+1 years');				
				$nxtDate = strtotime('+1 months');				
				$subscription_end_date = date('Y-m-d', $nxtDate);
			}
			else {
				if($hasPersonalAccount) {
					$errorMsg = 'You are already registered for a Personal Account. You cannot have more than one personal accounts';
				}
			}
			
			// Check if a company is already added with the same name
			$conditions = array('Company.title'=>$data['Company']['title'], 'Company.user_id'=>$this->Session->read('User.id'));
			if($this->Company->find('first', array('conditions'=>$conditions))) {
				$errorMsg = 'Company name already exists';
			}
			
			if(!$errorMsg) {
				$data['Company']['id'] = null;		
				$data['Company']['subscription_end_date'] = $subscription_end_date;	
				$data['Company']['user_id'] = $this->Session->read('User.id');				
				
				if($this->Company->save($data)) {
					$companyInfo = $this->Company->read();
					if($companyInfo['Company']['business_type'] == 'wineshop') {
						
						$baseCompanyID = '84';
						$newCompanyID = $companyInfo['Company']['id'];
						
						App::uses('Category', 'Model');
						$this->Category = new Category;
						$this->Category->recursive = -1;
						$baseCategories = $this->Category->findAllByCompanyId($baseCompanyID);
						if(!empty($baseCategories)) {
							foreach($baseCategories as $index=>$row) {
								$tmpCategory[$index]['old_id'] = $row['Category']['id'];
								$tmpCategory[$index]['new_id'] = null;
								
								$data = $row;
								unset($data['Category']['id']);
								unset($data['Category']['lft']);
								unset($data['Category']['rght']);
								unset($data['Category']['created']);
								unset($data['Category']['modified']);
								unset($data['Category']['company_id']);
								
								$data['Category']['id'] = null;
								$data['Category']['company_id'] = $newCompanyID;
								
								if($this->Category->save($data)) {
									$this->Category->recursive = -1;
									$newCategoryInfo = $this->Category->read();
									$tmpCategory[$index]['new_id'] = $newCategoryInfo['Category']['id'];									
								}							
							}
							
							foreach($tmpCategory as $row) {
								$conditions = array('Category.company_id'=>$newCompanyID, 'Category.parent_id'=>$row['old_id']);
								if($categories = $this->Category->find('all', array('conditions'=>$conditions))) {
									if(!empty($categories)) {
										foreach($categories as $tmp) {
											$data = array();
											$data['Category']['id'] = $tmp['Category']['id'];
											$data['Category']['parent_id'] = $row['new_id'];
											$this->Category->save($data);
										}
									}
								}
							}
						}
						
					}
					
					$tmp['UserCompany']['company_id'] = $companyInfo['Company']['id'];
					$tmp['UserCompany']['user_id'] = $this->Session->read('User.id');	
					$tmp['UserCompany']['user_level'] = '3';						
					
					$tmp['UserCompany']['id'] = null;
					
					if($this->UserCompany->save($tmp)) {
					
						if($data['Company']['business_type'] == 'personal') {
							$this->Session->setFlash('Your Personal Account has been created succesfully.', 'default', array('class'=>'success'));								
						}
						else {
							$this->Session->setFlash('Your business account has been created and is in trail period. Please contact the administrator to renew your subscription.', 'default', array('class'=>'success'));
						}						
						
						// send email to admin						
						try {
							$fromEmail = 'noreply@myaccountmanager.in';
							$fromName = 'MyAccountManager.in';
							$toEmail = Configure::read('Admin.email');
							$subject = 'Business/Personal Account Request';
							$message = '
Dear Admin,

'.$this->Session->read('User.name').'('.$this->Session->read('User.email').'), has registered for a business/personal account('.$companyInfo['Company']['title'].') with MyAccountManager.in.

Account has been registered successfully.

-
MyAccountManager.in


';
							
							$email = new CakeEmail();
							$email->from(array($fromEmail => $fromName));
							$email->to($toEmail);								
							$email->subject($subject);
							$email->send($message);
						}
						catch(Exception $ex) {
							
						}
						

						$userCompany = $this->UserCompany->read();						
						$this->Session->write('UserCompany', $userCompany['UserCompany']);
						$this->Session->write('Company', $companyInfo['Company']);
						
						$this->redirect(array('action'=>'selectCompany'));						
						
					}
					else {
						$this->Company->delete();
						$errorMsg = 'An error occured while communicating with the server';
					}
					
				}
				else {
					$errorMsg = 'An error occured while communicating with the server';
				}			
			}
		}
		$this->set('errorMsg', $errorMsg);
	}
	
	function edit($companyID) {
		$errorMsg = null;
		$companyInfo = $this->Company->find('first', array('conditions'=>array('Company.id'=>$companyID)));
	
		if(empty($companyInfo)) {
			$this->Session->setFlash('Company not found', 'default', array('class'=>'message'));
			$this->redirect('/companies/');
		}
		
		if($this->request->is('put')) {
			$data = $this->request->data;
			if(empty($data['Company']['title'])) {
				$errorMsg = 'Enter Company Name';
			}
			elseif($this->Company->find('first', array('conditions'=>array('Company.title'=>$data['Company']['title'], 'Company.id NOT'=>$companyID)))) {
				$errorMsg = 'Company name already exists';
			}
			
			if(!$errorMsg) {
				$data['Company']['id'] = $companyID;
				if($this->Company->save($data)) {
					$this->Session->setFlash('Data Modified Successfully', 'default', array('class'=>'success'));
					$this->redirect(array('action'=>'index'));
				}
				else {
					$errorMsg = 'An error occured while communicating with the server';
				}			
			}
		}
		else {
			$this->data = $companyInfo;
		}
		$this->set('errorMsg', $errorMsg);
		$this->set('companyInfo', $companyInfo);
	}
	
	function delete($companyID) {		
		try {
			// Delete company data and datalogs
			App::uses('Data', 'Model');
			$this->Data = new Data;
			$conditions = null;
			$conditions = array('Data.company_id'=>$companyID);		
			$dataIDs = $this->Data->find('list', array('conditions'=>$conditions));
			
			if(!empty($dataIDs)) {			
				App::uses('Datalog', 'Model');
				$this->Datalog = new Datalog;
				$conditions = null;
				$conditions = array('Datalog.data_id'=>$dataIDs);
				$this->Datalog->deleteAll($conditions);
				
				$conditions = null;
				$conditions = array('Data.company_id'=>$companyID);		
				$this->Data->deleteAll($conditions);				
			}
			
			// Delete Categories
			App::uses('Category', 'Model');
			$this->Category = new Category;
			$conditions = null;
			$conditions = array('Category.company_id'=>$companyID);
			$this->Category->deleteAll($conditions);
			
			// Delete Company Users
			App::uses('UserCompany', 'Model');
			$this->UserCompany = new UserCompany;
			$conditions = null;
			$conditions = array('UserCompany.company_id'=>$companyID);
			$this->UserCompany->deleteAll($conditions);			
			
			// Delete Company Inventory
			App::uses('Inventory', 'Model');
			$this->Inventory = new Inventory;
			$conditions = null;
			$conditions = array('Inventory.company_id'=>$companyID);
			$this->Inventory->deleteAll($conditions);	
		
			// Delete Company AvailableStock
			App::uses('AvailableStock', 'Model');
			$this->AvailableStock = new AvailableStock;
			$conditions = null;
			$conditions = array('AvailableStock.company_id'=>$companyID);
			$this->AvailableStock->deleteAll($conditions);	
			
			// Delete Company DataGroups
			App::uses('DataGroup', 'Model');
			$this->DataGroup = new DataGroup;
			$conditions = null;
			$conditions = array('DataGroup.company_id'=>$companyID);
			$this->DataGroup->deleteAll($conditions);			
			
			// Delete Company Groups
			App::uses('Group', 'Model');
			$this->Group = new Group;
			$conditions = null;
			$conditions = array('Group.company_id'=>$companyID);
			$this->Group->deleteAll($conditions);			
			
			// Delete Quotations
			App::uses('Quotation', 'Model');
			$this->Quotation = new Quotation;
			$conditions = null;
			$conditions = array('Quotation.company_id'=>$companyID);
			$this->Quotation->deleteAll($conditions);			
			
			// Delete Entity Items
			App::uses('EntityItem', 'Model');
			$this->EntityItem = new EntityItem;
			$conditions = null;
			$conditions = array('EntityItem.company_id'=>$companyID);
			$this->EntityItem->deleteAll($conditions);			
			
			
			
			// Delete company information
			$this->Company->delete($companyID);		
		}
		catch(Exception $ex) {
		
		}
		
		return true;
	}
	
	/*
	function deleteCompany($companyID) {	
		$this->delete($companyID);
		$this->Session->setFlash('Company Deleted Successfully', 'default', array('class'=>'success'));
	
		if($this->Session->read('Company.id') == $companyID) {
			$this->redirect('/companies/selectCompany');
		}
		else {
			$this->redirect('/companies/');
		}		
	}
	*/
	
	function selectCompany() {		
		$hideTopNavMenu = false;
		
		$this->Session->delete('Company');
		$this->Session->delete('UserCompany');
			
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany();
		$this->UserCompany->recursive = 2;
		$userCompanies = $this->UserCompany->findAllByUserId($this->Session->read('User.id'));
		if(count($userCompanies) == 0) {
			$this->redirect(array('action'=>'add'));
		}
		
		App::uses('Company', 'Model');
		$this->Company = new Company();
		$userCompany = $this->Company->findByUserId($this->Session->read('User.id'));
			
		$this->set(compact('userCompanies', 'hideTopNavMenu', 'userCompany'));
	}
	
	function switchCompany($encodedCompanyID) {
		$companyID = base64_decode($encodedCompanyID);
		// check if user is subscribed to the selected company
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany();
		if($companyInfo = $this->UserCompany->find('first', array('conditions'=>array('UserCompany.user_id'=>$this->Session->read('User.id'), 'UserCompany.company_id'=>$companyID)))) {
			if(!$companyInfo['Company']['active']) {
				$this->Session->setFlash('Your account is inactive. Please contact administrator to activate your account', 'default', array('class'=>'notice'));
				$this->redirect($this->referer());
			}
			
			$this->Session->setFlash('Your account switched to - '.$companyInfo['Company']['title'], 'default', array('class'=>'notice'));
			$this->Session->write('Company', $companyInfo['Company']);
			$this->Session->write('UserCompany', $companyInfo['UserCompany']);
			$this->Session->write('UserCompany.user_level', $companyInfo['UserCompany']['user_level']);
			$this->redirect('/');
		}
		else {
			$this->Session->setFlash('You are not an authorized user', 'default', array('class'=>'error'));
		}
		$this->redirect(array('action'=>'selectCompany'));	
	}
	
	function requestNewAccount() {
		$this->redirect('/companies/add');
	}
	
	function changeStatus($companyID, $type='active') {
		$data['Company']['id'] = $companyID;
		$data['Company']['active'] = ($type == 'active') ? '1' : '0';
		if($this->Company->save($data)) {
			$this->Session->setFlash('Company Status Successfully Changes', 'default', array('class'=>'success'));
		}
		else {
			$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'error'));
		}
		$this->redirect('/users/showUserCompanies');
	}
	
	/**
	 * Show all companies
	 */
	function admin_index() {
		$this->paginate = array(
				'limit' => 25,
				'order' => array('Company.created' => 'desc'),
				'recursive' => 2
			);
		$companies = $this->paginate();
		$this->set('companies', $companies);		
	}
	
	/**
	 * Function to edit company information
	 */
	function admin_edit($companyID) {
		$errorMsg = null;
		$companyInfo = $this->Company->find('first', array('conditions'=>array('Company.id'=>$companyID), 'recursive'=>2));
	
		if(empty($companyInfo)) {
			$this->Session->setFlash('Company not found', 'default', array('class'=>'message'));
			$this->redirect('/admin/companies/');
		}
		
		if($this->request->is('put')) {
			$data = $this->request->data;
			$conditions = array('Company.title'=>$data['Company']['title'], 'Company.id NOT'=>$companyID, 'Company.user_id'=>$companyInfo['Company']['user_id']);
			if(empty($data['Company']['title'])) {
				$errorMsg = 'Enter Company Name';
			}
			elseif($this->Company->find('first', array('conditions'=>$conditions))) {
				$errorMsg = 'Company name already exists';
			}
			
			if(!$errorMsg) {
				$data['Company']['id'] = $companyID;
				if($this->Company->save($data)) {
					$this->Session->setFlash('Data Modified Successfully', 'default', array('class'=>'success'));
					$this->redirect('/admin/companies/');
				}
				else {
					$errorMsg = 'An error occured while communicating with the server';
				}			
			}
		}
		else {
			$this->data = $companyInfo;
		}
		$this->set('errorMsg', $errorMsg);
		$this->set('companyInfo', $companyInfo);
	} 
	
	/**
	 * Function to delete a company
	 */
	function admin_delete($companyID) {
		$this->delete($companyID);
		$this->Session->setFlash('Company deleted successfully', 'default', array('class'=>'success'));
		$this->redirect('/admin/companies/');
	} 
}
?>
