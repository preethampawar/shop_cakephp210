<?php
class GroupsController extends AppController {

	var $name = 'Groups';
		
	/**
	 * Function to show list of groups
	 */
	 public function index() {		
	 
		$conditions = array('Group.company_id'=>$this->Session->read('Company.id'));				
		$groups = $this->Group->find('all', array('conditions'=>$conditions, 'order'=>'Group.name', 'recursive'=>'-1'));		
		
		$this->set(compact('groups'));
    }	 
		
	function add() {			
		if(isset($this->request->data) and !empty($this->request->data) )
		{
			$error = null;
			$data['Group'] = $this->request->data['Group'];		
			if(!Validation::blank($data['Group']['name'])) {
				$data['Group']['name'] = htmlentities($data['Group']['name'], ENT_QUOTES);				
			}			
			else {
				$error = 'Enter Group Name';
			}
			if(!$error) {	
				$conditions = array('Group.name'=>$data['Group']['name'], 'Group.company_id'=>$this->Session->read('Company.id'));
				if($this->Group->find('first', array('conditions'=>$conditions))) {
					$error = "Group with the same name already exists";
				}
			}	
			$data['Group']['id'] = null;	
			$data['Group']['company_id'] = $this->Session->read('Company.id');	
			if(!$error) {
				if($this->Group->save($data))
				{
					$this->Session->setFlash('Group Created Successfully', 'default', array('class'=>'success'));
					$this->redirect('/groups/');
				}
				else
				{
					$this->set('errorMsg', 'An error occured while creating a new account');
				}
			}
			else{			
				$this->set('errorMsg', $error);
			}
		}				
	}
	
	
	function edit($groupID=null) {
		if(!$groupID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
			$this->redirect('/groups/');
		}
		else {
			if(!($groupInfo = $this->Group->find('first', array('conditions'=>array('Group.id'=>$groupID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
				$this->redirect('/groups/');
			}
		}		
		$this->set('groupInfo', $groupInfo);
		
		if(isset($this->request->data) and !empty($this->request->data))
		{
			$error = null;
			$data['Group'] = $this->request->data['Group'];
			if(!Validation::blank($data['Group']['name'])) {
				$data['Group']['name'] = htmlentities($data['Group']['name'], ENT_QUOTES);				
			}	
			else {
				$error = 'Enter Group Name';
			}
			
			if(!$error) {
				$conditions = array('Group.name'=>$data['Group']['name'], 'Group.id NOT'=>$groupID, 'Group.company_id'=>$this->Session->read('Company.id'));
			
				if($this->Group->find('first', array('conditions'=>$conditions))) {
					$error = 'Group with the same name already exists';
				}
			}		
					
			$data['Group']['id'] = $groupID;	
			if(!$error) {
				if($this->Group->save($data))
				{
					$this->Session->setFlash('Group Modified Successfully', 'default', array('class'=>'success'));
					$this->redirect('/groups/');
				}
				else
				{
					$this->set('errorMsg', 'An error occured while communicating with the server');
				}
			}
			else{			
				$this->set('errorMsg', $error);
			}
		}
		else {
			$this->data = $groupInfo;
		}				
	}
	
	function delete($groupID = null) {
		if(!$groupID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
			$this->redirect('/groups/');
		}
		else {
			if(!($groupInfo = $this->Group->find('first', array('conditions'=>array('Group.id'=>$groupID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
				$this->redirect('/groups/');
			}
		}
		
		App::uses('DataGroup', 'Model');
		$this->DataGroup = new DataGroup;	
				
		$groupConditions = array('DataGroup.group_id'=>$groupID);
		$this->DataGroup->deleteAll($groupConditions);
			
		$this->Group->delete($groupID);
		
		$this->Session->setFlash('Group deleted successfully', 'default', array('class'=>'success'));
		$this->redirect('/groups/');		
	}	
	
	/* Function to create a category. Used in category suggestions */
	function addNewGroup() {
		$this->layout = 'ajax';
			
		if(isset($this->request->data) and !empty($this->request->data) )
		{
			$error = null;
			$data['Group'] = $this->request->data['NewCategory'];
			if(empty($data['Group']['name'])) {
				$error = 'Account name is required';
			}			
			else {
				if(empty($data['Group']['parent_id'])) {
					$conditions = array('Group.name'=>htmlentities($data['Group']['name'], ENT_QUOTES));
				}
				else {
					$conditions = array('Group.name'=>htmlentities($data['Group']['name'], ENT_QUOTES), 'Group.parent_id'=>$data['Group']['parent_id']);
				}
				
				if($this->Group->find('first', array('conditions'=>$conditions))) {
					$error = 'Account with the same name already exist\'s';
				}			
				if(!empty($data['Group']['name'])) {
					$data['Group']['name'] = htmlentities($data['Group']['name'], ENT_QUOTES);				
				}
			}
			$this->set('parent_id', $data['Group']['parent_id']);
			
			$data['Group']['id'] = null;	
			if(!$error) {
				if($this->Group->save($data))
				{
					$groupInfo = $this->Group->read();
					$this->set('groupInfo', $groupInfo);
					$this->set('successMsg', 'Account list updated successfully');
				}
				else
				{
					$this->set('errorMsg', 'An error occured while creating a new account');
				}
			}
			else{			
				$this->set('errorMsg', $error);
			}
		}							
	}
	
	
}
?>
