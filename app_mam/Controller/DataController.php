<?php
App::uses('Sanitize', 'Utility');
class DataController extends AppController {
    public $name = 'Data';
    public $helpers = array('Html', 'Form');

	function beforeFilter() {
		parent::beforeFilter();
		if($this->action != 'view') {
			// $this->isAdmin();
			// $this->layout = 'admin';
			
			App::uses('Category', 'Model');
			$this->Category = new Category;
			$categories = $this->Category->find('first');
			if(empty($categories)) {
				$this->Session->setFlash('No Account found. Create a book to add data in it', 'default', array('class'=>'notice'));
				$this->redirect('/categories/addCategory');
			}			
		}
	}
	
    public function index() {
         $this->set('data', $this->Data->find('all'));
    }

    function view($keyword=null) {
		if(!empty($keyword)) {
			$conditions = array('Data.active'=>'1', 'Data.keyword'=>$keyword);
			$data = $this->Data->find('first', array('conditions'=>$conditions));
			if(!empty($data)) {
				$this->set('title_for_layout', $data['Data']['title']);
				$this->set('data', $data);
				
			}
			else {
				$this->Session->setFlash('The page you are trying to acces has been removed or moved to a different location', 'default', array('class'=>'message'));
			}
		}
		else {
			$this->Session->setFlash('The page you are trying to acces has been removed or moved to a different location', 'default', array('class'=>'message'));
		}
	}
	
	function showAll($encodedCategoryID = null) {
		$categoryID = base64_decode($encodedCategoryID);
		
		$this->layout = 'admin';
		$this->checkAdmin();
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		if($categoryID) {
			$conditions = array('Category.id'=>$categoryID);
			if(!($categoryInfo = $this->Category->find('first', array('conditions'=>$conditions, 'recursive'=>'-1')))) {
				$this->Session->setFlash('The page you are trying to acces has been removed or moved to a different location', 'default', array('class'=>'message'));
				$this->redirect('/data/showAll');
			}
			else {
				$this->set('categoryInfo', $categoryInfo);
			}
		}
		
		// $categories = $this->Category->generateTreeList(array(), null, null, '####');
		// $this->set('categories', $categories);
		
		$conditions = ($categoryID) ? array('Data.category_id'=>$categoryID) : array();	
		$data = $this->Data->find('all', array('conditions'=>$conditions, 'order'=>'Data.created DESC'));
	
		$this->set('data', $data);
	}
	
	/**
	 * Function to change the status of a page from active to inactive and vice versa from admin panel
	 */
	function changeStatus($encodedDataID=null, $encodedStatus=null) {
		$postID = base64_decode($encodedDataID);
		$status = base64_decode($encodedStatus);
		$status = ($status == 'active') ? '1' : '0';
		
		if($this->Data->find('first', array('Data.id'=>$postID))) {
			$data['Data']['id'] = $postID;
			$data['Data']['active'] = $status;
			if($this->Data->save($data)) {
				($status) ? $this->Session->setFlash('Page Successfully Activated', 'default', array('class'=>'success')) : $this->Session->setFlash('Page Successfully DeActivated', 'default', array('class'=>'success'));
				
				$link = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '/data/showAll';
				$this->redirect($link);
			}
			else{
				$this->Session->setFlash('An error occured while communicating with the server', 'default', array('class'=>'message'));
			}			
		}
		$this->Session->setFlash('Page not found', 'default', array('class'=>'message'));		
	}
	
	/**
	 * Function to add a new Page
	 *
	 */
	function add($encodedCategoryID=null) {		
		$categoryID = null;
		App::uses('Category', 'Model');
		$this->Category = new Category;		
		$categories = $this->Category->generateTreeList(array(), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$this->set('categories', $categories);
		if($encodedCategoryID) {
			$categoryID = base64_decode($encodedCategoryID);
			if(!$encodedCategoryID) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
				$this->redirect('/data/showAll');
			}
			else {
				if(!($categoryInfo = $this->Category->find('first', array('conditions'=>array('Category.id'=>$categoryID))))) {
					$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
					$this->redirect('/data/showAll');
				}
			}
		}
		$this->set('parent_id', $categoryID);
		$this->set('encodedCategoryID', $encodedCategoryID);
		
		if($this->request->ispost()) {	
			$error = null;				
			$data['Data'] = $this->request->data['Data'];	
			if(empty($data['Data']['title'])) {
				$error = 'Title is a required field';
			}
			elseif(empty($data['Data']['body'])) {
				$error = 'Description is a required field';
			}
			
			if(!empty($data['Data']['keyword'])) {
				$data['Data']['keyword'] = $this->request->data['Data']['keyword'] = Sanitize::paranoid($data['Data']['keyword'], $allowed = array('-'));
			}
			
			if(!$error) {
				$conditionsTitle = array('Data.title'=>htmlentities($data['Data']['title'], ENT_QUOTES), 'Data.category_id'=>$data['Data']['category_id']);
				$conditionsKeyword = array('Data.keyword'=>$data['Data']['keyword']);
				if($this->Data->find('first', array('conditions'=>$conditionsTitle))) {
					$error = 'Page with same title already exist\'s';
				}				
				elseif($this->Data->find('first', array('conditions'=>$conditionsKeyword))) {
					$error = 'Page with same link keyword already exist\'s';
				}
				
				if(!empty($data['Data']['meta_keywords'])) {
					$data['Data']['meta_keywords'] = $this->request->data['Data']['meta_keywords'] = Sanitize::paranoid($data['Data']['meta_keywords'], $allowed = array(',','-',' \s','(',')','\\','/','@','#','$'));
				}
				if(!empty($data['Data']['meta_description'])) {
					$data['Data']['meta_description'] = $this->request->data['Data']['meta_description'] = Sanitize::paranoid($data['Data']['meta_description'], $allowed = array(',','-',' \s','(',')','\\','/','@','#','$'));
				}
				if(!empty($data['Data']['tags'])) {
					$data['Data']['tags'] = $this->request->data['Data']['tags'] = Sanitize::paranoid($data['Data']['tags'], $allowed = array(',','-',' \s'));
				}
			}			
			
			if(!$error) {
				$data['Data']['title'] = htmlentities($data['Data']['title'] , ENT_QUOTES);
				$data['Data']['active'] = ($data['Data']['active']) ? '1' : '0';
				
				if($this->Data->save($data)) {
					$this->Session->setFlash('Page Created Successfully', 'default', array('class'=>'success'));
					$this->redirect('/data/showAll');
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'message'));
				}
			}
			else {
				$this->set('errorMsg', $error);			
			}
		}	
	}
	
	/**
	 * Function to edit a Page
	 *
	 */
	function editPage($encodedDataID = null) {
		$this->checkAdmin();		
		$postID = base64_decode($encodedDataID);
		$this->set('encodedDataID', $encodedDataID);
		
		App::uses('Category', 'Model');
		$this->Category = new Category;		
		$categories = $this->Category->generateTreeList(array(), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$this->set('categories', $categories);
				
		$conditions = array('Data.id'=>$postID);
		if(!($postInfo = $this->Data->find('first', array('conditions'=>$conditions)))) {
			$this->Session->setFlash('Page not found', 'default', array('class'=>'message'));
			$this->redirect('/data/showAll');
		}	
				
		if($this->request->ispost()) {	
			$error = null;				
			$data['Data'] = $this->request->data['Data'];	
			if(empty($data['Data']['title'])) {
				$error = 'Title is a required field';
			}
			elseif(empty($data['Data']['body'])) {
				$error = 'Description is a required field';
			}
			
			if(!empty($data['Data']['keyword'])) {
				$data['Data']['keyword'] = $this->request->data['Data']['keyword'] = Sanitize::paranoid($data['Data']['keyword'], $allowed = array('-'));
			}
			
			if(!$error) {
				$conditionsTitle = array('Data.title'=>htmlentities($data['Data']['title'], ENT_QUOTES), 'Data.id NOT '=>$postID);
				$conditionsKeyword = array('Data.keyword'=>$data['Data']['keyword'], 'Data.id NOT '=>$postID);
				if($this->Data->find('first', array('conditions'=>$conditionsTitle))) {
					$error = 'Page with same title already exist\'s';
				}				
				elseif($this->Data->find('first', array('conditions'=>$conditionsKeyword))) {
					$error = 'Page with same link keyword already exist\'s';
				}
				
				if(!empty($data['Data']['meta_keywords'])) {
					$data['Data']['meta_keywords'] = $this->request->data['Data']['meta_keywords'] = Sanitize::paranoid($data['Data']['meta_keywords'], $allowed = array(',','-',' \s','(',')','\\','/','@','#','$'));
				}
				if(!empty($data['Data']['meta_description'])) {
					$data['Data']['meta_description'] = $this->request->data['Data']['meta_description'] = Sanitize::paranoid($data['Data']['meta_description'], $allowed = array(',','-',' \s','(',')','\\','/','@','#','$'));
				}
				if(!empty($data['Data']['tags'])) {
					$data['Data']['tags'] = $this->request->data['Data']['tags'] = Sanitize::paranoid($data['Data']['tags'], $allowed = array(',','-',' \s'));
				}
			}			
			
			if(!$error) {
				$data['Data']['title'] = htmlentities($data['Data']['title'] , ENT_QUOTES);
				$data['Data']['active'] = ($data['Data']['active']) ? '1' : '0';
				$data['Data']['id'] = $postID;
				
				if($this->Data->save($data)) {
					$this->Session->setFlash('Page Modified Successfully', 'default', array('class'=>'success'));
					$this->redirect('/data/showAll');
				}
				else {
					$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'message'));
				}
			}
			else {
				$this->set('errorMsg', $error);			
			}
		}		
		else {
			$this->data = $postInfo;
		}
	}
	
	/**
	 * Function to delete a Page
	 *
	 */
	function deletePage($encodedDataID = null) {
		$this->checkAdmin();		
		$postID = base64_decode($encodedDataID);
			
		$conditions = array('Data.id'=>$postID);
		$data = $this->Data->find('first', array('conditions'=>$conditions));	
		if($data) {
			$this->Data->Delete($postID);
			$this->Session->setFlash('Page Deleted Successfully', 'default', array('class'=>'success'));
		}
		else {
			$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', array('class'=>'message'));
		}
		$link = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '/data/showAll';
		$this->redirect($link);
	}
	
	function showFeaturedData() {		
		$this->layout = 'default';
        if ($this->request->is('requested')) {
			$conditions = array('Data.featured'=>1);	
			$data = $this->paginate($conditions);
            return $data;
        } else {
			$data = $this->paginate();
            $this->set('data', $data);
        }
	}	
	
}
?>
