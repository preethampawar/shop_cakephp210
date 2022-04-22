<?php
class AccessController extends AppController {
	var $name = 'Access';	
	
	function checkUserAccess($controller, $action) {
		$business_type = $this->Session->read('Company.business_type');		
		$allowed = false;
		switch($business_type) {
			case 'personal':
				$allowed = $this->checkPersonalAccountAccess($controller, $action);
				break;
			case 'general';	
				$allowed = $this->checkGeneralAccountAccess($controller, $action);
				break;
			case 'inventory';	
				$allowed = $this->checkInventoryAccountAccess($controller, $action);
				break;
			case 'finance';	
				$allowed = $this->checkFinanceAccountAccess($controller, $action);
				break;
			case 'wineshop';	
				$allowed = $this->checkWineshopAccountAccess($controller, $action);
				break;
			default:
				$allowed = false;
		}
		return $allowed;
	}
	
	function checkPersonalAccountAccess($controller, $action) {
		$userLevel = $this->Session->read('UserCompany.user_level');
		$allowed = array();						
		switch($userLevel) {
			case '1': 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Companies' => array('switchCompany', 'selectCompany', 'requestNewAccount', 'add'),
					'Reports' => array('today', 'income_expense_report')
				);
				break;
				
			case '2': 				 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),							
					'Transactions' => array('index', 'add', 'edit', 'delete'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Reports' => array('today', 'income_expense_report')
				);
				break;
				
			case '3': 					
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Categories' => array('index', 'add', 'edit', 'delete', 'reorder'),										
					'Transactions' => array('index', 'add', 'edit', 'delete'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Groups' => array('index', 'add', 'edit', 'delete'),
					'Reports' => array('today', 'income_expense_report', 'income_expense_yearly_visual_report', 'income_expense_daterange_visual_report')
				);
				break;				
				
			default:
				return false;
				break;
		}						
		$access = 0;
		if(isset($allowed[$controller])) {
			foreach($allowed[$controller] as $id=>$allowedAction) {
				if($action == $allowedAction) {
					$access = 1;
					break;
				}
			}		
		}			
		if($access) {
			return true;
		}				
		return false;	
	}
	
	function checkGeneralAccountAccess($controller, $action) {		
		$userLevel = $this->Session->read('UserCompany.user_level');
		$allowed = array();						
		switch($userLevel) {
			case '1': 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Companies' => array('switchCompany', 'selectCompany', 'requestNewAccount', 'add'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today')
				);
				break;
				
			case '2': 				 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					// 'Categories' => array('index', 'add', 'edit'),
					'Purchases' => array('index', 'add', 'edit', 'delete'),
					'Sales' => array('index', 'add', 'edit', 'delete'),
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Ledger' => array('index', 'today'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Quotations' => array('index', 'create', 'details', 'download', 'selectTemplate', 'delete'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today')
				);
				break;
				
			case '3': 					
				$allowed = array(
					'Users' => array('index', 'login', 'forgotpassword', 'changepassword', 'logout', 'remove', 'changeUserAccess', 'resetpassword', 'inviteUser'),
					'Categories' => array('index', 'add', 'edit', 'delete', 'reorder'),										
					'Purchases' => array('index', 'add', 'edit', 'delete'),
					'Sales' => array('index', 'add', 'edit', 'delete'),
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Ledger' => array('index', 'today'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Groups' => array('index', 'add', 'edit', 'delete'),
					'Quotations' => array('index', 'create', 'details', 'download', 'selectTemplate', 'delete'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today')
				);
				break;				
				
			default:
				return false;
				break;
		}						
		$access = 0;
		if(isset($allowed[$controller])) {
			foreach($allowed[$controller] as $id=>$allowedAction) {
				if($action == $allowedAction) {
					$access = 1;
					break;
				}
			}		
		}			
		if($access) {
			return true;
		}				
		return false;			
	}
	
	function checkInventoryAccountAccess($controller, $action) {
		$userLevel = $this->Session->read('UserCompany.user_level');
		$allowed = array();						
		switch($userLevel) {
			case '1': 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Companies' => array('switchCompany', 'selectCompany', 'requestNewAccount', 'add'),					
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today', 'showStockReport','viewStockReport', 'viewDailyStockReport')
				);
				break;
				
			case '2': 				 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Purchases' => array('index', 'add', 'edit', 'delete'),
					'Sales' => array('index', 'add', 'edit', 'delete'),
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Ledger' => array('index', 'today'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Quotations' => array('index', 'create', 'details', 'download', 'selectTemplate', 'delete'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today', 'showStockReport', 'viewStockReport', 'viewDailyStockReport')
				);
				break;
				
			case '3': 					
				$allowed = array(
					'Users' => array('index', 'login', 'forgotpassword', 'changepassword', 'logout', 'remove', 'changeUserAccess', 'resetpassword', 'inviteUser'),
					'Categories' => array('index', 'add', 'edit', 'delete', 'reorder'),						
					'Inventory' => array('index', 'add', 'edit', 'delete', 'showDamagedStock', 'addDamagedStock', 'editDamagedStock', 'deleteDamagedStock'),					
					'Purchases' => array('index', 'add', 'edit', 'delete'),
					'Sales' => array('index', 'add', 'edit', 'delete'),
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Ledger' => array('index', 'today'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Groups' => array('index', 'add', 'edit', 'delete'),
					'Quotations' => array('index', 'create', 'details', 'download', 'selectTemplate', 'delete'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today', 'showStockReport', 'viewStockReport', 'viewDailyStockReport')
				);
				break;				
				
			default:
				return false;
				break;
		}
						
		$access = 0;
		if(isset($allowed[$controller])) {
			foreach($allowed[$controller] as $id=>$allowedAction) {
				if($action == $allowedAction) {
					$access = 1;
					break;
				}
			}		
		}			
		if($access) {
			return true;
		}			
		
		return false;
	}
	
	function checkWineshopAccountAccess($controller, $action) {
		$userLevel = $this->Session->read('UserCompany.user_level');
		$allowed = array();						
		switch($userLevel) {
			case '1': 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Companies' => array('switchCompany', 'selectCompany', 'requestNewAccount', 'add'),
					'Quotations' => array('index', 'details', 'download'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today', 'viewStockReport', 'viewDailyStockReport', 'viewDayStockMovementReport', 'viewMonthlyStockMovementReport'),
					'Invoices' => array('index', 'details')
				);
				break;
				
			case '2': 				 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Purchases' => array('index', 'add', 'edit', 'delete'),
					'Sales' => array('index', 'add', 'edit', 'delete'),
					'AvailableStock' => array('index', 'add', 'delete'),
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Ledger' => array('index', 'today'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Quotations' => array('index', 'create', 'details', 'download', 'selectTemplate', 'delete'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today', 'viewStockReport', 'viewDailyStockReport', 'viewDayStockMovementReport', 'viewMonthlyStockMovementReport'),
					'Invoices' => array('index', 'details', 'add', 'edit', 'delete', 'deleteProduct')
				);
				break;
				
			case '3': 					
				$allowed = array(
					'Users' => array('index', 'login', 'forgotpassword', 'changepassword', 'logout', 'remove', 'changeUserAccess', 'resetpassword', 'inviteUser'),
					'Categories' => array('index', 'add', 'edit', 'delete', 'reorder', 'recover'),						
					'Inventory' => array('index', 'add', 'edit', 'delete', 'showDamagedStock', 'addDamagedStock', 'editDamagedStock', 'deleteDamagedStock', 'moveStockToShop', 'showMovedStock', 'deleteMovedStock'),						
					'AvailableStock' => array('index', 'add', 'delete'),						
					'Purchases' => array('index', 'add', 'edit', 'delete'),
					'Sales' => array('index', 'add', 'edit', 'delete'),
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Ledger' => array('index', 'today'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Groups' => array('index', 'add', 'edit', 'delete'),
					'Quotations' => array('index', 'create', 'details', 'download', 'selectTemplate', 'delete'),
					'Reports' => array('index', 'generateCategoryReport', 'generateVisualizationDailyReport', 'generateVisualizationMonthlyReport', 'today', 'viewStockReport', 'viewDailyStockReport', 'viewDayStockMovementReport', 'viewMonthlyStockMovementReport'),
					'Invoices' => array('index', 'details', 'add', 'edit', 'delete', 'deleteProduct')
				);
				break;				
				
			default:
				return false;
				break;
		}
						
		$access = 0;
		if(isset($allowed[$controller])) {
			foreach($allowed[$controller] as $id=>$allowedAction) {
				if($action == $allowedAction) {
					$access = 1;
					break;
				}
			}		
		}			
		if($access) {
			return true;
		}			
		
		return false;
	}
	
	function checkFinanceAccountAccess($controller, $action) {		
		$userLevel = $this->Session->read('UserCompany.user_level');
		$allowed = array();						
		switch($userLevel) {
			case '1': 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Companies' => array('switchCompany', 'selectCompany', 'requestNewAccount', 'add'),
					'Reports' => array('today')
				);
				break;
				
			case '2': 				 
				$allowed = array(
					'Users' => array('login', 'forgotpassword', 'changepassword', 'logout', 'resetpassword'),
					'Cash' => array('index', 'add', 'edit'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Reports' => array('today')
				);
				break;
				
			case '3': 					
				$allowed = array(
					'Users' => array('index', 'login', 'forgotpassword', 'changepassword', 'logout', 'remove', 'changeUserAccess', 'resetpassword', 'inviteUser'),
					'Categories' => array('index', 'add', 'edit', 'delete'),															
					'Cash' => array('index', 'add', 'edit', 'delete'),
					'Pages' => array('display', 'registration_success'),
					'Companies' => array('switchCompany', 'selectCompany', 'add', 'requestNewAccount'),
					'Groups' => array('index', 'add', 'edit', 'delete'),
					'Reports' => array('today')
				);
				break;				
				
			default:
				return false;
				break;
		}						
		$access = 0;
		if(isset($allowed[$controller])) {
			foreach($allowed[$controller] as $id=>$allowedAction) {
				if($action == $allowedAction) {
					$access = 1;
					break;
				}
			}		
		}			
		if($access) {
			return true;
		}				
		return false;	
		
	}
}
?>
