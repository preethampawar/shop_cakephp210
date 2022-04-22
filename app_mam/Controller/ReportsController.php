<?php
App::uses('Sanitize', 'Utility');
class ReportsController extends AppController {
    public $name = 'Reports';
	
    public function index() { 
		$getParams = false;
		if(isset($this->request->params['named']) and !empty($this->request->params['named'])) {
			$getParams = true;
		}
		
		// $conditions = array('Report.company_id'=>$this->Session->read('Company.id'));
		// $fields = array('DISTINCT Report.category_name');		
		// $categories = $this->Report->find('all', array('conditions'=>$conditions, 'fields'=>$fields));
		
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany;
		$userCompanies = $this->UserCompany->find('all', array('conditions'=>array('UserCompany.company_id'=>$this->Session->read('Company.id'))));
		$users = array();
		if(!empty($userCompanies)) {
			foreach ($userCompanies as $row) {
				$users[$row['User']['id']] = $row['User']['name'];
			}
		}		
		
		App::uses('Group', 'Model');
		$this->Group = new Group;
		$groups = $this->Group->find('list', array('conditions'=>array('Group.company_id'=>$this->Session->read('Company.id'))));
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		//$results = array();
		if($this->request->isPost() or $getParams) {			
			$data = $this->request->data;
			if(!($this->request->isPost()) and ($getParams)) {
				$data['Report'] = $this->request->params['named'];
				$startDate = date('Y-01-01');
				$endDate = date('Y-m-d');
				
				$data['Report']['startdate'] = $startDate;
				$data['Report']['enddate'] = $endDate;
				
				$this->data = $data;
			}
			
			if($this->Session->read('UserCompany.user_level') == '2') {
				$conditions[] = array('Report.user_id'=>$this->Session->read('User.id'));
			}
			elseif(!empty($data['Report']['user_id'])) {
				$conditions[] = array('Report.user_id'=>$data['Report']['user_id']);
			}
			
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
			if(!empty($data['Report']['pending_payment'])) {
				$conditions[] = array('Report.pending_amount > '=>'0');
			}
			if(!empty($data['Report']['business_type'])) {
				$conditions[] = array('Report.business_type'=>$data['Report']['business_type']);
			}
			if(!empty($data['Report']['group_id'])) {
				App::uses('DataGroup', 'Model');
				$this->DataGroup = new DataGroup;
				
				$groupData = $this->DataGroup->find('list', array('conditions'=>array('DataGroup.group_id'=>$data['Report']['group_id'])));
				
				$conditions[] = array('Report.id'=>$groupData);					
			}
			if(!empty($data['Report']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Report']['category_id']);
				$categoriesList[] = $data['Report']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Report.category_id'=>$categoriesList);
			}
			if(!empty($data['Report']['payment_method'])) {
				$conditions[] = array('Report.payment_method'=>$data['Report']['payment_method']);
			}
			if(!empty($data['Report']['startdate'])) {
				$conditions[] = array('Report.date >='=>$data['Report']['startdate']);
			}
			if(!empty($data['Report']['enddate'])) {
				$conditions[] = array('Report.date <='=>$data['Report']['enddate']);
			}
			$this->Report->unbindModel(array('belongsTo'=>array('Company')));
			$results = $this->Report->find('all', array('conditions'=>$conditions, 'order'=>array('Report.date', 'Report.business_type', 'Report.category_name'), 'recursive'=>'2'));	
		}		
		
		$this->set(compact('categories', 'results', 'users', 'groups'));		
    }	

	public function generateCategoryReport($categoryID) {  
		App::uses('Category', 'Model');
		$this->Category = new Category;
		
		$categoryInfo = null;
		if(!$categoryID) {
			$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
			$this->redirect('/categories/');
		}
		else {
			if(!($categoryInfo = $this->Category->find('first', array('conditions'=>array('Category.id'=>$categoryID, 'Category.company_id'=>$this->Session->read('Company.id')))))) {
				$this->Session->setFlash('The page you are trying to access has been removed or moved to a new location', 'default', array('class'=>'message'));
				$this->redirect('/categories/');
			}
		}
		
		$categoryChildren = $this->Category->children($categoryID);
		$categoriesList[] = $categoryID;
		if(!empty($categoryChildren)) {
			foreach($categoryChildren as $row) {
				$categoriesList[] = $row['Category']['id'];
			}
		}
			
		$conditions = array('Report.company_id'=>$this->Session->read('Company.id'), 'Report.category_id'=>$categoriesList);
		$fields = array('DISTINCT Report.category_name');		
		$categories = $this->Report->find('all', array('conditions'=>$conditions, 'fields'=>$fields));
		
		if($this->request->isPost()) {			
			$data = $this->request->data;
			
			
			if($this->Session->read('UserCompany.user_level') == '2') {
				$conditions[] = array('Report.user_id'=>$this->Session->read('User.id'));
			}
			
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
			if(!empty($data['Report']['pending_payment'])) {
				$conditions[] = array('Report.pending_payment'=>'1');
			}
			if(!empty($data['Report']['business_type'])) {
				$conditions[] = array('Report.business_type'=>$data['Report']['business_type']);
			}
			if(!empty($data['Report']['category_name'])) {
				$conditions[] = array('Report.category_name'=>$data['Report']['category_name']);
			}
			if(!empty($data['Report']['payment_method'])) {
				$conditions[] = array('Report.payment_method'=>$data['Report']['payment_method']);
			}
			if(!empty($data['Report']['startdate'])) {
				$conditions[] = array('Report.date >='=>$data['Report']['startdate']);
			}
			if(!empty($data['Report']['enddate'])) {
				$conditions[] = array('Report.date <='=>$data['Report']['enddate']);
			}
			
			$results = $this->Report->find('all', array('conditions'=>$conditions, 'order'=>array('Report.date', 'Report.business_type', 'Report.category_name'), 'recursive'=>'-1'));			
		}
		else {
			
			$startDate = date('Y-m-01');
			$endDate = date('Y-m-d');
			
			$tmp['Report']['startdate'] = $startDate;
			$tmp['Report']['enddate'] = $endDate;
			$this->data = $tmp;
			$thisMonth = date('M - Y');
			$conditions[] = array('Report.date >='=>$startDate);
			$conditions[] = array('Report.date <='=>$endDate);
			
			
			if($this->Session->read('UserCompany.user_level') == '2') {
				$conditions[] = array('Report.user_id'=>$this->Session->read('User.id'));
			}
			
			$results = $this->Report->find('all', array('conditions'=>$conditions, 'order'=>array('Report.date', 'Report.business_type', 'Report.category_name'), 'recursive'=>'-1'));
		}
		
		
		$this->set(compact('categories', 'results', 'categoryInfo', 'thisMonth'));		
		
    }	
	
	public function generateVisualizationDailyReport() {		
	
		App::uses('Group', 'Model');
		$this->Group = new Group;
		$groups = $this->Group->find('list', array('conditions'=>array('Group.company_id'=>$this->Session->read('Company.id'))));
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoriesList = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '');
		
		if($this->request->isPost()) {
			$data = $this->request->data;			
			
			
			if($this->Session->read('UserCompany.user_level') == '2') {
				$conditions[] = array('Report.user_id'=>$this->Session->read('User.id'));
			}
			
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
			// if(!empty($data['Report']['pending_payment'])) {
				// $conditions[] = array('Report.pending_amount > '=>'0');
			// }
			if(!empty($data['Report']['business_type'])) {
				$conditions[] = array('Report.business_type'=>$data['Report']['business_type']);
			}
			if(!empty($data['Report']['group_id'])) {
				App::uses('DataGroup', 'Model');
				$this->DataGroup = new DataGroup;
				
				$groupData = $this->DataGroup->find('list', array('conditions'=>array('DataGroup.group_id'=>$data['Report']['group_id'])));
				
				$conditions[] = array('Report.id'=>$groupData);					
			}
			if(!empty($data['Report']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Report']['category_id']);
				$categoriesList[] = $data['Report']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Report.category_id'=>$categoriesList);
			}
			if(!empty($data['Report']['startdate'])) {
				$conditions[] = array('Report.date >='=>$data['Report']['startdate']);
			}
			if(!empty($data['Report']['enddate'])) {
				$conditions[] = array('Report.date <='=>$data['Report']['enddate']);
			}
			$results = array();
			
			// daily transactions report
			$order = array('Report.date');
			$group = array('Report.business_type', 'Report.transaction_type', 'Report.date');
			$fields = array('SUM(Report.total_amount) as Amount','SUM(Report.payment_amount) as PaymentAmount','SUM(Report.pending_amount) as PendingAmount', 'Report.date', 'Report.business_type', 'Report.transaction_type');
			$this->Report->unbindModel(array('belongsTo'=>array('Company')));
			$dailyTransactionsResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'2', 'group'=>$group));
			$this->set('dailyTransactionsResults', $dailyTransactionsResults);
			
			// transactions category wise report
			$order = array('Amount DESC');
			$group = array('Report.transaction_type', 'Report.category_id');
			$fields = array('SUM(Report.total_amount) as Amount','SUM(Report.payment_amount) as PaymentAmount','SUM(Report.pending_amount) as PendingAmount', 'Report.transaction_type', 'Report.category_name');
			$categoryResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'-1', 'group'=>$group));
			$this->set('categoryResults', $categoryResults);		
		}
		else {			
			$s = date('Y-m-d', strtotime('-1 weeks', strtotime(date('Y-m-d'))));
			$e = date('Y-m-d');
			$tmp['Report']['startdate'] = $s;
			$tmp['Report']['enddate'] = $e;
			$this->data = $tmp;
		}
		$this->set(compact('categories', 'results', 'groups', 'categoriesList'));	
	}
	
	public function generateVisualizationMonthlyReport() {	
	
		App::uses('Group', 'Model');
		$this->Group = new Group;
		$groups = $this->Group->find('list', array('conditions'=>array('Group.company_id'=>$this->Session->read('Company.id'))));	
	
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$allcategories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '');
		if($this->request->isPost()) {
			$data = $this->request->data;	
						
			if($this->Session->read('UserCompany.user_level') == '2') {
				$conditions[] = array('Report.user_id'=>$this->Session->read('User.id'));
			}
				
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
			// if(!empty($data['Report']['pending_payment'])) {
				// $conditions[] = array('Report.pending_payment'=>'1');
			// }
			if(!empty($data['Report']['business_type'])) {
				$conditions[] = array('Report.business_type'=>$data['Report']['business_type']);
			}
			if(!empty($data['Report']['group_id'])) {
				App::uses('DataGroup', 'Model');
				$this->DataGroup = new DataGroup;
				
				$groupData = $this->DataGroup->find('list', array('conditions'=>array('DataGroup.group_id'=>$data['Report']['group_id'])));
				
				$conditions[] = array('Report.id'=>$groupData);					
			}
			if(!empty($data['Report']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Report']['category_id']);
				$categoriesList[] = $data['Report']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Report.category_id'=>$categoriesList);
			}
			if(!empty($data['Report']['year'])) {
				$conditions[] = array('Report.date >='=>date('Y-m-d', strtotime($data['Report']['year'].'-01-01')));			
				$conditions[] = array('Report.date <='=>date('Y-m-d', strtotime('+1 years -1 days', strtotime($data['Report']['year'].'-01-01'))));
			}
			
			$results = array();
			
			// daily transactions report
			$order = array('Report.date');
			$group = array('Report.business_type', 'Report.transaction_type', 'MONTH(Report.date)');
			$fields = array('SUM(Report.total_amount) as Amount','SUM(Report.payment_amount) as PaymentAmount','SUM(Report.pending_amount) as PendingAmount', 'MONTH(Report.date) AS Month', 'Report.business_type', 'Report.transaction_type');
			$this->Report->unbindModel(array('belongsTo'=>array('Company')));
			$monthlyTransactionsResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'2', 'group'=>$group));
			$this->set('monthlyTransactionsResults', $monthlyTransactionsResults);
			
			
			// yearly transactions category report
			$order = array('Amount DESC');
			$group = array('Report.transaction_type', 'Report.category_id');
			$fields = array('SUM(Report.total_amount) as Amount', 'SUM(Report.payment_amount) as PaymentAmount','SUM(Report.pending_amount) as PendingAmount', 'Report.transaction_type', 'Report.category_name');
			$categoryResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'-1', 'group'=>$group));
			$this->set('categoryResults', $categoryResults);			
			
		}
		else {			
		}
		$categoriesList = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, null);
		$this->set(compact('categories', 'results', 'categoriesList', 'groups', 'allcategories'));	
	}
	
	/**
	 * Function to show today's transactions
	 */
	public function today() {      
		if(!$this->Session->check('Company')) {
			$this->redirect('/companies/selectCompany');	
		}		
		$startDate = date('Y-m-01');
		$endDate = date('Y-m-d');
		
		$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
		$conditions[] = array('Report.date >='=>$startDate);
		$conditions[] = array('Report.date <='=>$endDate);
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Report.user_id'=>$this->Session->read('User.id'));
		}		
		
		$results = $this->Report->find('all', array('conditions'=>$conditions, 'order'=>array('Report.date', 'Report.business_type', 'Report.category_name'), 'recursive'=>'-1'));			
		
		
		$this->set(compact('results', 'startDate', 'endDate'));		
		
    }	
	
	/**
	 * Function to show stock report
	 */
	public function showStockReport() {
	
		App::uses('Category', 'Model');
		$this->Category = new Category;		
				
		$categoryOptions = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');				
		
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany;
		$userCompanies = $this->UserCompany->find('all', array('conditions'=>array('UserCompany.company_id'=>$this->Session->read('Company.id'))));
		$users = array();
		if(!empty($userCompanies)) {
			foreach ($userCompanies as $row) {
				$users[$row['User']['id']] = $row['User']['name'];
			}
		}	
		
		
		// This month info
		$selected_category_id = null;
		if($this->request->isPost()) {			
			$selected_category_id = $this->request->data['Report']['category_id'];
			$month = $this->request->data['Report']['month']['month'];	
			$year = $this->request->data['Report']['year'];				
		}
		else {
			$month = date('m');
			$year = date('Y');		
		}
		
		if($selected_category_id) {			
			$this->Category->displayField = 'id';
			$categoryChildren = $this->Category->children($selected_category_id);
			$categoryIDs[] = $selected_category_id;
			if(!empty($categoryChildren)) {
				foreach($categoryChildren as $row) {
					$categoryIDs[] = $row['Category']['id'];
				}
			}
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1', 'Category.id'=>$categoryIDs), 'order'=>'Category.name'));		
		}
		else {
			$this->Category->displayField = 'id';
			$categoryIDs = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
			
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name'));		
		}
		
		$startDate = $year.'-'.$month.'-01';		
		$tmp = strtotime($startDate);
		$endDate = date('Y-m-d', strtotime($startDate.'+1 months -1 days'));
		$no_of_days = date('t', strtotime($startDate));				
		
		App::uses('Inventory', 'Model');
		$this->Inventory = new Inventory;	
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date >='=>$startDate);
		$conditions[] = array('Inventory.date <='=>$endDate);
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		
		// get day wise stock
		$group = array('Inventory.category_id', 'Inventory.type', 'Inventory.date');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type', 'Inventory.date');
		$results = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get month stock in&out
		$group = array('Inventory.category_id', 'Inventory.type');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type');
		$monthResults = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal in stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'in');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevInStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal out stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'out');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevOutStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal damaged stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'damaged');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevDamagedStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		// calculate prev stock in hand, stock out and damaged stock
		$prevStock = array();
		if(!empty($categories)) {
			foreach($categories as $catID=>$catName) {
				if(!empty($prevInStock)) {
					foreach($prevInStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['in_stock'] = $row[0]['Quantity'];							
						}
					}
				}
				if(!empty($prevOutStock)) {
					foreach($prevOutStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['out_stock'] = $row[0]['Quantity'];
						}
					}
				}
				if(!empty($prevDamagedStock)) {
					foreach($prevDamagedStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['damaged_stock'] = $row[0]['Quantity'];
						}
					}
				}
			}
		}	
		
		$tmp = array();
		if(!empty($results)) {
			foreach($results as $row) {
				$data = null;
				$data['quantity'] = $row[0]['Quantity'];
				$data['category_id'] = $row['Inventory']['category_id'];
				$data['type'] = $row['Inventory']['type'];
				$tmp[date('j', strtotime($row['Inventory']['date']))][] = $data;
			}
			
			$temp = array();
			foreach($tmp as $day=>$row) {
				foreach($row as $row2) {						
					$temp[$day][$row2['category_id']][] = $row2;
				}				
			}
			
			$tmp = array();
			foreach($temp as $day=>$row) {
				foreach($row as $category_id=>$row2) {
					$x['out_qty'] = 0;
					$x['in_qty'] = 0;
					$x['damaged_qty'] = 0;
					foreach($row2 as $row3) {
						if($row3['type']=='out') {
							$x['out_qty']+=$row3['quantity'];
						}
						elseif($row3['type']=='in') { 
							$x['in_qty']+=$row3['quantity'];
						}
						elseif($row3['type']=='damaged') {
							$x['damaged_qty']+=$row3['quantity'];
						}
					}
					$x['bal_qty'] = $x['in_qty']-$x['out_qty']-$x['damaged_qty'];							
					$tmp[$day][$category_id] = $x;	
				}
			}	
			$results = $tmp;
		}			
		
		
		$this->set(compact('results', 'categories', 'no_of_days', 'monthResults', 'month','year', 'prevStock', 'categoryOptions', 'users'));		
	}
	
	/**
	 * Function to show stock report for wine stores
	 */
	public function viewStockReport() {
	
		App::uses('Category', 'Model');
		$this->Category = new Category;		
				
		$categoryOptions = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');				
		
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany;
		$userCompanies = $this->UserCompany->find('all', array('conditions'=>array('UserCompany.company_id'=>$this->Session->read('Company.id'))));
		$users = array();
		if(!empty($userCompanies)) {
			foreach ($userCompanies as $row) {
				$users[$row['User']['id']] = $row['User']['name'];
			}
		}	
		
		
		// This month info
		$selected_category_id = null;
		if($this->request->isPost()) {			
			$selected_category_id = $this->request->data['Report']['category_id'];
			$month = $this->request->data['Report']['month']['month'];	
			$year = $this->request->data['Report']['year'];				
		}
		else {
			$month = date('m');
			$year = date('Y');		
		}
		
		if($selected_category_id) {			
			$this->Category->displayField = 'id';
			$categoryChildren = $this->Category->children($selected_category_id);
			$categoryIDs[] = $selected_category_id;
			if(!empty($categoryChildren)) {
				foreach($categoryChildren as $row) {
					$categoryIDs[] = $row['Category']['id'];
				}
			}
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1', 'Category.id'=>$categoryIDs), 'order'=>'Category.name'));		
		}
		else {
			$this->Category->displayField = 'id';
			$categoryIDs = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
			
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name'));		
		}
		
		$startDate = $year.'-'.$month.'-01';				
		$endDate = date('Y-m-d', strtotime($startDate.'+1 months -1 days'));
		$no_of_days = date('t', strtotime($startDate));				
		
		App::uses('Inventory', 'Model');
		$this->Inventory = new Inventory;	
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date >='=>$startDate);
		$conditions[] = array('Inventory.date <='=>$endDate);
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		
		// get day wise stock
		$group = array('Inventory.category_id', 'Inventory.type', 'Inventory.date');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type', 'Inventory.date');
		$results = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get month stock in&out
		$group = array('Inventory.category_id', 'Inventory.type');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type');
		$monthResults = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal in stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'in');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevInStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal out stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'out');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevOutStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal damaged stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'damaged');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevDamagedStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		// calculate prev stock in hand, stock out and damaged stock
		$prevStock = array();
		if(!empty($categories)) {
			foreach($categories as $catID=>$catName) {
				if(!empty($prevInStock)) {
					foreach($prevInStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['in_stock'] = $row[0]['Quantity'];							
						}
					}
				}
				if(!empty($prevOutStock)) {
					foreach($prevOutStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['out_stock'] = $row[0]['Quantity'];
						}
					}
				}
				if(!empty($prevDamagedStock)) {
					foreach($prevDamagedStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['damaged_stock'] = $row[0]['Quantity'];
						}
					}
				}
			}
		}	
		
		$tmp = array();
		if(!empty($results)) {
			foreach($results as $row) {
				$data = null;
				$data['quantity'] = $row[0]['Quantity'];
				$data['category_id'] = $row['Inventory']['category_id'];
				$data['type'] = $row['Inventory']['type'];
				$tmp[date('j', strtotime($row['Inventory']['date']))][] = $data;
			}
			
			$temp = array();
			foreach($tmp as $day=>$row) {
				foreach($row as $row2) {						
					$temp[$day][$row2['category_id']][] = $row2;
				}				
			}
			
			$tmp = array();
			foreach($temp as $day=>$row) {
				foreach($row as $category_id=>$row2) {
					$x['out_qty'] = 0;
					$x['in_qty'] = 0;
					$x['damaged_qty'] = 0;
					foreach($row2 as $row3) {
						if($row3['type']=='out') {
							$x['out_qty']+=$row3['quantity'];
						}
						elseif($row3['type']=='in') { 
							$x['in_qty']+=$row3['quantity'];
						}
						elseif($row3['type']=='damaged') {
							$x['damaged_qty']+=$row3['quantity'];
						}
					}
					$x['bal_qty'] = $x['in_qty']-$x['out_qty']-$x['damaged_qty'];							
					$tmp[$day][$category_id] = $x;	
				}
			}	
			$results = $tmp;
		}			
		
		$categoryDetails = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'recursive'=>'-1'));
		
		$this->set(compact('results', 'categories', 'no_of_days', 'monthResults', 'month','year', 'prevStock', 'categoryOptions', 'users', 'categoryDetails'));		
	}
	
	/**
	 * Function to show dialy stock report for wine stores
	 */
	public function viewDailyStockReport() {
	
		App::uses('Category', 'Model');
		$this->Category = new Category;		
				
		$categoryOptions = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');				
		
		App::uses('UserCompany', 'Model');
		$this->UserCompany = new UserCompany;
		$userCompanies = $this->UserCompany->find('all', array('conditions'=>array('UserCompany.company_id'=>$this->Session->read('Company.id'))));
		$users = array();
		if(!empty($userCompanies)) {
			foreach ($userCompanies as $row) {
				$users[$row['User']['id']] = $row['User']['name'];
			}
		}	
		
		
		// This month info
		$selected_category_id = null;

		$startDate = $endDate = date('Y-m-d');						
		if($this->request->isPost()) {			
			$selected_category_id = $this->request->data['Report']['category_id'];
			$startDate = $this->request->data['Report']['startdate'];		
			$endDate = $this->request->data['Report']['enddate'];	
			if(strtotime($startDate) > strtotime($endDate)) {
				$this->Session->setFlash('From date cannot be greater than To date', 'default', array('class'=>'error'));
				$this->redirect('/reports/viewDailyStockReport');
			}
			
		}
		
		
		if($selected_category_id) {			
			$this->Category->displayField = 'id';
			$categoryChildren = $this->Category->children($selected_category_id);
			$categoryIDs[] = $selected_category_id;
			if(!empty($categoryChildren)) {
				foreach($categoryChildren as $row) {
					$categoryIDs[] = $row['Category']['id'];
				}
			}
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1', 'Category.id'=>$categoryIDs), 'order'=>'Category.name'));		
		}
		else {
			$this->Category->displayField = 'id';
			$categoryIDs = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
			
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name'));		
		}		
		
		
		App::uses('Inventory', 'Model');
		$this->Inventory = new Inventory;	
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date >='=>$startDate);
		$conditions[] = array('Inventory.date <='=>$endDate);
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		
		// get day wise stock
		$group = array('Inventory.category_id', 'Inventory.type', 'Inventory.date');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type', 'Inventory.date');
		$results = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get month stock in&out
		$group = array('Inventory.category_id', 'Inventory.type');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id', 'Inventory.type');
		$monthResults = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal in stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'in');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevInStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal out stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'out');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevOutStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		
		// get prev bal damaged stock. excluding this months stock
		$conditions = array();
		$conditions[] = array('Inventory.category_id'=>$categoryIDs);
		$conditions[] = array('Inventory.date < '=>$startDate);
		$conditions[] = array('Inventory.type'=>'damaged');
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Inventory.user_id'=>$this->Session->read('User.id'));
		}		
		else {
			if($this->request->isPost()) {
				if(!empty($this->request->data['Report']['user_id'])) {
					$conditions[] = array('Inventory.user_id'=>$this->request->data['Report']['user_id']);
				}
			}
		}	
		$group = array('Inventory.category_id');
		$fields = array('SUM(Inventory.quantity) as Quantity', 'Inventory.category_id');
		$prevDamagedStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'group'=>$group, 'fields'=>$fields));	
		// calculate prev stock in hand, stock out and damaged stock
		$prevStock = array();
		if(!empty($categories)) {
			foreach($categories as $catID=>$catName) {
				if(!empty($prevInStock)) {
					foreach($prevInStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['in_stock'] = $row[0]['Quantity'];							
						}
					}
				}
				if(!empty($prevOutStock)) {
					foreach($prevOutStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['out_stock'] = $row[0]['Quantity'];
						}
					}
				}
				if(!empty($prevDamagedStock)) {
					foreach($prevDamagedStock as $row) {
						if($catID == $row['Inventory']['category_id']) {
							$prevStock[$catID]['damaged_stock'] = $row[0]['Quantity'];
						}
					}
				}
			}
		}

		// // get stock value (purchase, sale and damaged)
		// $group = array('Inventory.category_id', 'Inventory.type');
		// $fields = array('SUM(Inventory.unitrate) as Amount', 'Inventory.category_id', 'Inventory.type');
		// $stockValue = $this->Inventory->find('all', array(
									// 'conditions'=>array('Inventory.company_id'=>$this->Session->read('Company.id')),
									// 'fields'=>$fields,
									// 'group'=>$group
								// )
							// );	
		// debug($stockValue);
		// exit;

		
		
		$tmp = array();
		if(!empty($results)) {
			foreach($results as $row) {
				$data = null;
				$data['quantity'] = $row[0]['Quantity'];
				$data['category_id'] = $row['Inventory']['category_id'];
				$data['type'] = $row['Inventory']['type'];
				$tmp[date('j', strtotime($row['Inventory']['date']))][] = $data;
			}
			
			$temp = array();
			foreach($tmp as $day=>$row) {
				foreach($row as $row2) {						
					$temp[$day][$row2['category_id']][] = $row2;
				}				
			}
			
			$tmp = array();
			foreach($temp as $day=>$row) {
				foreach($row as $category_id=>$row2) {
					$x['out_qty'] = 0;
					$x['in_qty'] = 0;
					$x['damaged_qty'] = 0;
					foreach($row2 as $row3) {
						if($row3['type']=='out') {
							$x['out_qty']+=$row3['quantity'];
						}
						elseif($row3['type']=='in') { 
							$x['in_qty']+=$row3['quantity'];
						}
						elseif($row3['type']=='damaged') {
							$x['damaged_qty']+=$row3['quantity'];
						}
					}
					$x['bal_qty'] = $x['in_qty']-$x['out_qty']-$x['damaged_qty'];							
					$tmp[$day][$category_id] = $x;	
				}
			}	
			$results = $tmp;
		}			
		
		$categoryDetails = $this->Category->find('all', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'recursive'=>'-1'));
		
		$this->set(compact('results', 'categories', 'no_of_days', 'monthResults', 'startDate', 'endDate', 'prevStock', 'categoryOptions', 'users', 'categoryDetails'));		
	}
	
	/**
	 * Function to show income & expense transactions report for personal account
	 */
	function income_expense_report() {
		$getParams = false;
		if(isset($this->request->params['named']) and !empty($this->request->params['named'])) {
			$getParams = true;
		}		
		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$results = null;
		if($this->request->isPost() or $getParams) {			
			$data = $this->request->data;
			if(!($this->request->isPost()) and ($getParams)) {
				$data['Report'] = $this->request->params['named'];
				$startDate = date('Y-01-01');
				$endDate = date('Y-m-d');
				
				$data['Report']['startdate'] = $startDate;
				$data['Report']['enddate'] = $endDate;
				
				$this->data = $data;
			}
						
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));			
			$conditions[] = array('Report.business_type'=>'cash');
			
			if(!empty($data['Report']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Report']['category_id']);
				$categoriesList[] = $data['Report']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Report.category_id'=>$categoriesList);
			}
			
			if(!empty($data['Report']['transaction_type'])) {
				$conditions[] = array('Report.transaction_type'=>$data['Report']['transaction_type']);
			}
			
			if(!empty($data['Report']['startdate'])) {
				$conditions[] = array('Report.date >='=>$data['Report']['startdate']);
			}
			
			if(!empty($data['Report']['enddate'])) {
				$conditions[] = array('Report.date <='=>$data['Report']['enddate']);
			}
			$this->Report->unbindModel(array('belongsTo'=>array('Company')));
			$results = $this->Report->find('all', array('conditions'=>$conditions, 'order'=>array('Report.date', 'Report.category_name'), 'recursive'=>'2'));	
		}		
		
		$this->set(compact('categories', 'results'));	
	}
		
	/**
	 * Function to show income & expense yearly visual report for personal account
	 */
	function income_expense_yearly_visual_report() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		$categoryResults = array();
		
		$results = null;
		
		if($this->request->isPost()) {
			$data = $this->request->data;		
				
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
						
			if(!empty($data['Report']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Report']['category_id']);
				$categoriesList[] = $data['Report']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Report.category_id'=>$categoriesList);
			}
			if(!empty($data['Report']['year'])) {
				$conditions[] = array('Report.date >='=>date('Y-m-d', strtotime($data['Report']['year'].'-01-01')));			
				$conditions[] = array('Report.date <='=>date('Y-m-d', strtotime('+1 years -1 days', strtotime($data['Report']['year'].'-01-01'))));
			}
			
			$results = array();
			
			$conditions[] = array('Report.business_type'=>'cash');
			
			// daily transactions report
			$order = array('Report.date');
			$group = array('Report.transaction_type', 'MONTH(Report.date)');
			$fields = array('SUM(Report.payment_amount) as Amount', 'MONTH(Report.date) AS Month', 'Report.transaction_type');
			$this->Report->unbindModel(array('belongsTo'=>array('Company')));
			$monthlyTransactionsResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'2', 'group'=>$group));
			$this->set('monthlyTransactionsResults', $monthlyTransactionsResults);
			
			
			// yearly transactions category report
			$order = array('Amount DESC');
			$group = array('Report.transaction_type', 'Report.category_id');
			$fields = array('SUM(Report.payment_amount) as Amount', 'Report.transaction_type', 'Report.category_name');
			$categoryResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'-1', 'group'=>$group));
			$this->set('categoryResults', $categoryResults);
			
		}
		else {			
		}
		$categoriesList = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, null);
		
		$this->set(compact('categories', 'results', 'categoriesList', 'categoryResults'));	
	}
	
	
	/**
	 * Function to show income & expense date range visual report for personal account
	 */
	function income_expense_daterange_visual_report() {		
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		
		$results = array();

		if($this->request->isPost()) {
			$data = $this->request->data;			
				
			$conditions[] = array('Report.company_id'=>$this->Session->read('Company.id'));
			$conditions[] = array('Report.business_type'=>'cash');
			
			if(!empty($data['Report']['category_id'])) {
				$categoryChildren = $this->Category->children($data['Report']['category_id']);
				$categoriesList[] = $data['Report']['category_id'];
				if(!empty($categoryChildren)) {
					foreach($categoryChildren as $row) {
						$categoriesList[] = $row['Category']['id'];
					}
				}
			
				$conditions[] = array('Report.category_id'=>$categoriesList);
			}
			if(!empty($data['Report']['startdate'])) {
				$conditions[] = array('Report.date >='=>$data['Report']['startdate']);
			}
			if(!empty($data['Report']['enddate'])) {
				$conditions[] = array('Report.date <='=>$data['Report']['enddate']);
			}
			
			
			// daily transactions report
			$order = array('Report.date');
			$group = array('Report.transaction_type', 'Report.date');
			$fields = array('SUM(Report.payment_amount) as Amount', 'Report.date', 'Report.business_type', 'Report.transaction_type');
			$this->Report->unbindModel(array('belongsTo'=>array('Company')));
			$dailyTransactionsResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'2', 'group'=>$group));
			$this->set('dailyTransactionsResults', $dailyTransactionsResults);
			
			// transactions category wise report
			$order = array('Amount DESC');
			$group = array('Report.transaction_type', 'Report.category_id');
			$fields = array('SUM(Report.payment_amount) as Amount', 'Report.transaction_type', 'Report.category_name');
			$categoryResults = $this->Report->find('all', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>$order, 'recursive'=>'-1', 'group'=>$group));
			$this->set('categoryResults', $categoryResults);		
		}
		else {			
			$s = date('Y-m-d', strtotime('-1 weeks', strtotime(date('Y-m-d'))));
			$e = date('Y-m-d');
			$tmp['Report']['startdate'] = $s;
			$tmp['Report']['enddate'] = $e;
			$this->data = $tmp;
		}
		$this->set(compact('categories', 'results'));	
	}
	
	/**
	 * Funtion to display day stock movement report
	 */	 
	function viewDayStockMovementReport($place='godown') {
		
		$date = date('Y-m-d');
		$startDate = date('Y-m-d');
		$endDate = date('Y-m-d');
		
		App::uses('Category', 'Model');
		$this->Category = new Category;		
				
		$categoryOptions = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');		
		
		$categoryID = null;
		if($this->request->isPost()) {
			$data = $this->request->data;
			if(!empty($data['Report']['category_id'])) {
				$categoryID = $data['Report']['category_id'];
			}
			
			if(!empty($data['Report']['startdate'])) {
				$date = $startDate = $endDate = $data['Report']['startdate'];				
			}
		}		
		
		if($categoryID) {			
			$this->Category->displayField = 'id';
			$categoryChildren = $this->Category->children($categoryID);
			$categoryIDs[] = $categoryID;
			if(!empty($categoryChildren)) {
				foreach($categoryChildren as $row) {
					$categoryIDs[] = $row['Category']['id'];
				}
			}
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1', 'Category.id'=>$categoryIDs), 'order'=>'Category.name'));		
		}
		else {
			$this->Category->displayField = 'id';
			$categoryIDs = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
			
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name'));		
		}
		
		if($place == 'godown') {			
			$conditions = array();			
			$conditions[] = array('StockMovement.place'=>$place);
			if(!empty($startDate)) {
				$conditions[] = array('StockMovement.date >='=>$startDate);			
			}		
			
			if(!empty($endDate)) {
				$conditions[] = array('StockMovement.date <='=>$endDate);			
			}			
			
			
			$godownStockMovementInfo = array();
			foreach($categories as $categoryID=>$row) {			
				$stockInfo = $this->getStockInfoFromStockMovement($categoryID, $conditions, $place);		

				if(empty($startDate)) {
					$date = date('Y-m-d');
				}	
				$openingStock = $this->getOpeningStockInfoFromStockMovement($categoryID, $place, $date);
				$closingStock = $this->getClosingStockInfoFromStockMovement($categoryID, $place, $date);
				
				$stockInfo['openingStock'] = $openingStock;			
				$stockInfo['closingStock'] = $closingStock;			
				$godownStockMovementInfo[$categoryID] = $stockInfo;
			}	
		}
		
		if($place=='shop') {				
			$conditions = array();			
			$conditions[] = array('StockMovement.place'=>$place);
			if(!empty($startDate)) {
				$conditions[] = array('StockMovement.date >='=>$startDate);			
			}		
			if(!empty($endDate)) {
				$conditions[] = array('StockMovement.date <='=>$endDate);			
			}		
			$shopStockMovementInfo = array();
			foreach($categories as $categoryID=>$row) {			
				$stockInfo = $this->getStockInfoFromStockMovement($categoryID, $conditions, $place);		

				if(empty($startDate)) {
					$date = date('Y-m-d');
				}	
				$openingStock = $this->getOpeningStockInfoFromStockMovement($categoryID, $place, $date);
				$closingStock = $this->getClosingStockInfoFromStockMovement($categoryID, $place, $date);
				
				$stockInfo['openingStock'] = $openingStock;			
				$stockInfo['closingStock'] = $closingStock;			
				$shopStockMovementInfo[$categoryID] = $stockInfo;
			}		
		}
		$this->set(compact('categoryOptions', 'categories', 'godownStockMovementInfo', 'shopStockMovementInfo', 'date', 'place'));		
	}

		
	/**
	 * Funtion to show monthly stock movement from godown & shop
	 */
	function viewMonthlyStockMovementReport($place='godown') {
		
		$selected_category_id=null;
		$month = date('m');
		$year = date('Y');	
		$day = date('d');	
					
		App::uses('Category', 'Model');
		$this->Category = new Category;				
		
		if($this->request->isPost()) {			
			$selected_category_id = $this->request->data['Report']['category_id'];
			$month = $this->request->data['Report']['month']['month'];	
			$year = $this->request->data['Report']['year'];		
			$day = null;	
		}
		
		if($selected_category_id) {			
			$this->Category->displayField = 'id';
			$categoryChildren = $this->Category->children($selected_category_id);
			$categoryIDs[] = $selected_category_id;
			if(!empty($categoryChildren)) {
				foreach($categoryChildren as $row) {
					$categoryIDs[] = $row['Category']['id'];
				}
			}
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1', 'Category.id'=>$categoryIDs), 'order'=>'Category.name'));		
		}
		else {
			$this->Category->displayField = 'id';
			$categoryIDs = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), null, null, '');
			
			$this->Category->displayField = 'name';
			$categories = $this->Category->find('list', array('conditions'=>array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1'), 'order'=>'Category.name'));		
		}				
		$categoryOptions = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');	
		
		// Get Start date and End date
		$startDate = $year.'-'.$month.'-01';				
		$endDate = date('Y-m-d', strtotime($startDate.'+1 months -1 days'));
		$no_of_days = date('t', strtotime($startDate));
		
		$tmpstartDate = $year.'-'.$month.'-01';		

		$monthlyStockInfo = array();	
		for($i=1; $i<=$no_of_days; $i++) {
			if($i!=1) {
				$tmpstartDate = date('Y-m-d', strtotime($tmpstartDate.'+1 days'));			
			}
			$tmpendDate = date('Y-m-d', strtotime($tmpstartDate.'+1 days'));
			$monthlyStockInfo[$i] = array();
			
			$conditions = array();
			$conditions[] = array('StockMovement.date >='=>$tmpstartDate, 'StockMovement.date <'=>$tmpendDate);
			foreach($categoryIDs as $catID) {				
				$stockMovementConditions = $conditions;
				$stockMovementConditions[] = array('StockMovement.category_id'=>$catID);				
				
				$stockInfo = $this->getStockInfoFromStockMovement($catID, $stockMovementConditions, $place);
				$monthlyStockInfo[$i][$catID] = $stockInfo;
			}
			if($day) {
				if($day == $i) {
					break;
				}
			}			
		}	
		
		$categoryStockMovement = array();
		foreach($categoryIDs as $catID) {
			$categoryStockMovement[$catID]['openingStock'] = $this->getOpeningStockInfoFromStockMovement($catID, $place, $startDate);
			$categoryStockMovement[$catID]['closingStock'] = $this->getClosingStockInfoFromStockMovement($catID, $place, $endDate);
			
			$stockIn = 0;
			$stockOut = 0;
			$stockDamaged = 0;
			$tmpstartDate = $year.'-'.$month.'-01';		
			for($i=1; $i<=$no_of_days; $i++) {			
				if($i!=1) {
					$tmpstartDate = date('Y-m-d', strtotime($tmpstartDate.'+1 days'));			
				}
				$tmpendDate = date('Y-m-d', strtotime($tmpstartDate.'+1 days'));
			
				
				$stockMovementConditions = array();
				$stockMovementConditions[] = array('StockMovement.date >='=>$tmpstartDate, 'StockMovement.date <'=>$tmpendDate);
				$stockMovementConditions[] = array('StockMovement.category_id'=>$catID);								
				$stockInfo = $this->getStockInfoFromStockMovement($catID, $stockMovementConditions, $place);
				$stockIn = $stockIn+$stockInfo['stockIn'];
				$stockOut = $stockOut+$stockInfo['stockOut'];
				$stockDamaged = $stockDamaged+$stockInfo['stockDamaged'];
				
				if($day) {
					if($day == $i) {
						break;
					}
				}			
			}
			
			$categoryStockMovement[$catID]['stockIn'] = $stockIn;
			$categoryStockMovement[$catID]['stockOut'] = $stockOut;
			$categoryStockMovement[$catID]['stockDamaged'] = $stockDamaged;
			
		}
		// debug($categoryStockMovement);
		
		$this->set(compact('selected_category_id', 'month', 'year', 'categoryOptions', 'categories', 'startDate', 'endDate', 'no_of_days', 'monthlyStockInfo', 'day', 'categoryStockMovement', 'place'));	
		
	}
	
}
?>
