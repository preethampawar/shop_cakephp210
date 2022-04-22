<?php
class CategoriesController extends AppController {

	var $name = 'Categories';
		
	/**
	 * Function to show list of categories
	 */
	 public function index() {		
	 
		// $conditions = array('Category.company_id'=>$this->Session->read('Company.id'));		
		// $this->paginate = array(
				// 'limit' => 25,
				// 'order' => array('Category.created' => 'desc'),
				// 'conditions' => $conditions,
				// 'recursive' => '0'
 			// );
		// $categories = $this->paginate();		
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, array(), '####');		
		
		// Find all categories
		$allCategories = $this->Category->findAllByCompanyId($this->Session->read('Company.id'));
		
		$this->set(compact('allCategories', 'categories'));
    }	 
	 
	function index1() {	
		// $this->redirect('/categories/');
		// exit;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, '####');
		$this->set('categories', $categories);
		
		App::uses('Data', 'Model');
		$this->Data = new Data;		
		$posts = $this->Data->find('all', array('order'=>'Data.created DESC'));
	
		$this->set('posts', $posts);
	}
	
	function add($parent_id=null) {
		// $this->layout = 'admin';
		$this->set('parent_id', $parent_id);
			
		$error = null;
		if(isset($this->request->data) and !empty($this->request->data) )
		{	
			$data['Category'] = $this->request->data['Category'];
			$data['Category']['name'] = trim($data['Category']['name']);			
			if(!isset($data['Category']['parent_id'])) {
				$data['Category']['parent_id'] = null;
			}
			
			// validate category
			if(Validation::blank($data['Category']['name'])) {
				$error = 'Enter Category Name';
			}
			elseif(isset($data['Category']['is_product']) and !empty($data['Category']['is_product'])) {				
				$data['Category']['cost_price'] = trim($data['Category']['cost_price']);
				$data['Category']['selling_price'] = trim($data['Category']['selling_price']);
				
				// if(Validation::blank($data['Category']['selling_price'])) {
					// $error = "Enter SP. Value";
				// }	
				if(!Validation::blank($data['Category']['selling_price'])) {
					if(!(Validation::decimal($data['Category']['selling_price'])) and (!Validation::numeric($data['Category']['selling_price']))) {
						$error = "Invalid SP vaue. It should contain numerical or decimal values only. eg: 1200 or 1200.50";
					}
					
					if(Validation::blank($data['Category']['cost_price'])) {
						
					}	
					elseif(!(Validation::decimal($data['Category']['cost_price'])) and (!Validation::numeric($data['Category']['cost_price']))) {
						$error = "Invalid Cost Price value. It should contain numerical or decimal values only. eg: 1200 or 1200.50";
					}
				}
			}
			$this->data = $data;
			
			if(!$error) {
				if(empty($data['Category']['parent_id'])) {
					$conditions = array('Category.name'=>htmlentities($data['Category']['name'], ENT_QUOTES), 'Category.company_id'=>$this->Session->read('Company.id'));
				}
				else {
					$conditions = array('Category.name'=>htmlentities($data['Category']['name'], ENT_QUOTES), 'Category.parent_id'=>$data['Category']['parent_id'], 'Category.company_id'=>$this->Session->read('Company.id'));
				}
				if($this->Category->find('first', array('conditions'=>$conditions))) {
					$error = 'Category with the same name already exist\'s';
				}
			
				if(!empty($data['Category']['name'])) {
					$data['Category']['name'] = htmlentities($data['Category']['name'], ENT_QUOTES);				
				}
				
				$this->set('parent_id', $data['Category']['parent_id']);
				
				$data['Category']['id'] = null;	
				$data['Category']['company_id'] = $this->Session->read('Company.id');	
				if(!$error) {
					if($this->Category->save($data))
					{						
						$this->Session->setFlash('Category Created Successfully', 'default', array('class'=>'success'));
						$this->redirect('/categories/');
					}
					else
					{
						$this->set('errorMsg', 'An error occured while creating a new category');
					}
				}			
			}
		}	
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product NOT'=>'1'), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');		
		$this->set('categories', $categories);					
		$this->set('errorMsg', $error);
	}
	
	
	function edit($categoryID=null) {
		if(!$categoryID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			$this->redirect('/categories/');
		}
		else {
			if(!($categoryInfo = $this->Category->find('first', array('conditions'=>array('Category.id'=>$categoryID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
				$this->redirect('/categories/');
			}
		}		
		$error = null;
					
		if(isset($this->request->data) and !empty($this->request->data) )
		{		
			$data['Category'] = $this->request->data['Category'];
			
			$data['Category']['name'] = trim($data['Category']['name']);	
			
			// validate category
			if(Validation::blank($data['Category']['name'])) {
				$error = 'Enter Category Name';
			}
			elseif(isset($data['Category']['is_product']) and !empty($data['Category']['is_product'])) {				
				$data['Category']['cost_price'] = trim($data['Category']['cost_price']);
				$data['Category']['selling_price'] = trim($data['Category']['selling_price']);
				
				// if(Validation::blank($data['Category']['selling_price'])) {
					// $error = "Enter SP. Value";
				// }	
				if(!Validation::blank($data['Category']['selling_price'])) {
					if(!(Validation::decimal($data['Category']['selling_price'])) and (!Validation::numeric($data['Category']['selling_price']))) {
						$error = "Invalid SP vaue. It should contain numerical or decimal values only. eg: 1200 or 1200.50";
					}
					
					if(Validation::blank($data['Category']['cost_price'])) {
						
					}	
					elseif(!(Validation::decimal($data['Category']['cost_price'])) and (!Validation::numeric($data['Category']['cost_price']))) {
						$error = "Invalid Cost Price value. It should contain numerical or decimal values only. eg: 1200 or 1200.50";
					}
				}
			}
			$this->data = $data;
			
			if(!$error) {
				if(empty($data['Category']['parent_id'])) {
					$conditions = array('Category.name'=>htmlentities($data['Category']['name'], ENT_QUOTES), 'Category.id NOT'=>$categoryID, 'Category.parent_id'=>'', 'Category.company_id'=>$this->Session->read('Company.id'));
				}
				else {
					$conditions = array('Category.name'=>htmlentities($data['Category']['name'], ENT_QUOTES), 'Category.parent_id'=>$data['Category']['parent_id'], 'Category.id NOT'=>$categoryID, 'Category.company_id'=>$this->Session->read('Company.id'));
				}
				if($this->Category->find('first', array('conditions'=>$conditions))) {
					$error = 'Category with the same name already exist\'s';
				}
				//debug($categoryID);
				$children = $this->Category->children($categoryID);
				//debug($children);
				if($children) {
					if($data['Category']['is_product']) {
						$error = 'Inventory cannot be managed as the category selected has subcategories. ';
					}
				}
						
				if(!empty($data['Category']['name'])) {
					$data['Category']['name'] = htmlentities($data['Category']['name'], ENT_QUOTES);				
				}			
				$data['Category']['id'] = $categoryID;	
				if(!$error) {
					if($this->Category->save($data))
					{					
						$this->Session->setFlash('Category Modified Successfully', 'default', array('class'=>'success'));
						$this->redirect('/categories/');
					}
					else
					{
						$this->set('errorMsg', 'An error occured while communicating with the server');
					}
				}				
			}			
		}
		else {
			$this->data = $categoryInfo;
		}
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product NOT'=>'1'), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');		
		$this->set('categories', $categories);					
		$this->set('categoryInfo', $categoryInfo);					
		$this->set('errorMsg', $error);
	}
	
	function delete($categoryID = null) {
		if(!$categoryID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
			$this->redirect('/categories/');
		}
		else {
			if(!($categoryInfo = $this->Category->find('first', array('conditions'=>array('Category.id'=>$categoryID))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'error'));
				$this->redirect('/categories/');
			}
		}
		$categories = $this->Category->children($categoryID);	
		App::uses('Data', 'Model');
		$this->Data = new Data;
		App::uses('Inventory', 'Model');
		$this->Inventory = new Inventory;
		
		if(!empty($categories)) {
			foreach($categories as $row) {							
				$postConditions = array('Data.category_id'=>$row['Category']['id']);
				$this->Data->deleteAll($postConditions);
				
				$categoryConditions = array('Category.id'=>$row['Category']['id']);
				$this->Category->deleteAll($categoryConditions);
				
				// Delete information from inventory
				$inventoryConditions = array();
				$inventoryConditions = array('Inventory.category_id'=>$id);
				$this->Inventory->deleteAll($inventoryConditions);	
			}
		}
		$this->Category->delete($categoryID);
		// Delete information from inventory
		$inventoryConditions = array();
		$inventoryConditions = array('Inventory.category_id'=>$categoryID);
		$this->Inventory->deleteAll($inventoryConditions);	

		$postConditions = array('Data.category_id'=>$categoryID);
		$this->Data->deleteAll($postConditions);
		
		$this->Session->setFlash('Category deleted successfully', 'default', array('class'=>'success'));
		$this->redirect('/categories/');
		
	}
	
	
	function activateCategory($categoryId, $type)
	{
		$this->layout = 'ajax';
		if(!empty($categoryId))
		{
			$this->data['Category']['id'] = $categoryId;
			$this->data['Category']['active'] = ($type == 'true') ? '1' : '0';
			
			App::import('Model', 'Category');
			$this->Category = new Category;
			
			$this->Category->save($this->data);
		}
		$this->redirect('/administrator/refreshCategoryBox/'.$categoryId);
	}
	
	/* Function to create a category. Used in category suggestions */
	function addNewCategory() {
		$this->layout = 'ajax';
			
		if(isset($this->request->data) and !empty($this->request->data) )
		{
			$error = null;
			$data['Category'] = $this->request->data['NewCategory'];
			if(empty($data['Category']['name'])) {
				$error = 'Account name is required';
			}			
			else {
				if(empty($data['Category']['parent_id'])) {
					$conditions = array('Category.name'=>htmlentities($data['Category']['name'], ENT_QUOTES));
				}
				else {
					$conditions = array('Category.name'=>htmlentities($data['Category']['name'], ENT_QUOTES), 'Category.parent_id'=>$data['Category']['parent_id']);
				}
				
				if($this->Category->find('first', array('conditions'=>$conditions))) {
					$error = 'Account with the same name already exist\'s';
				}			
				if(!empty($data['Category']['name'])) {
					$data['Category']['name'] = htmlentities($data['Category']['name'], ENT_QUOTES);				
				}
			}
			$this->set('parent_id', $data['Category']['parent_id']);
			
			$data['Category']['id'] = null;	
			if(!$error) {
				if($this->Category->save($data))
				{
					$categoryInfo = $this->Category->read();
					$this->set('categoryInfo', $categoryInfo);
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
	
	function reorder($categoryID=null) {
		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '256M');
		if($categoryID) {
			$this->Category->reorder(array('id'=>$categoryID));
		}
		else {
			$this->Category->reorder();
		}
		$this->redirect('/categories/');
	}
	
	function recover() {
		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '256M');
		$this->Category->recover();
		$this->redirect('/categories/');
	}
	
}
?>
