<?php
App::uses('Validation', 'Utility');
class SalariesController extends AppController {

	var $name = 'Salaries';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->checkStoreInfo();
	}
		
	/**
	 * Function to show list of category products
	 */
	 public function index() {		
		$conditions = array('Salary.store_id'=>$this->Session->read('Store.id'));					
		$this->paginate = array(
							'conditions' => $conditions,
							'order' => array('Salary.payment_date' => 'DESC', 'Salary.created' => 'DESC'),
							'limit' => 100,
							'recursive' => '-1'
							);
		$salaries = $this->paginate();
		
		$this->set(compact('salaries'));
    } 
	
	function addSalaryFormValidation($data=null) {
		$error = null;
		
		if($data) {
			if(!isset($data['Salary']['employee_id'])) {
				$error = 'employee not found';
			}			
			if((!isset($data['Salary']['payment_amount'])) OR (!Validation::decimal($data['Salary']['payment_amount'])) OR ($data['Salary']['payment_amount']<=0)) {
				$error = 'Payment amount should be greater than 0';
			}
		}
		else {
			$error = 'Empty salary details';					
		}
		return $error;
	}
	
	function add() {
		$error = null;
		
		App::uses('Employee', 'Model');
		$this->Employee = new Employee;
		$conditions = array('Employee.store_id'=>$this->Session->read('Store.id'));				
		$employeesList = $this->Employee->find('list', array('conditions'=>$conditions, 'order'=>'Employee.name', 'recursive'=>'-1'));
				
		if($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			$employeeID = $data['Salary']['employee_id'];
			$paymentDate = $data['Salary']['payment_date']['year'].'-'.$data['Salary']['payment_date']['month'].'-'.$data['Salary']['payment_date']['day'];
			$data['Salary']['payment_date'] = $paymentDate;
			
			$error = $this->addSalaryFormValidation($data);
			// check if employee is available
			if(!$error) {
				if(!$employeeInfo = $this->CommonFunctions->getEmployeeInfo($employeeID)) {
					$error = 'Employee not found.';
				}
				else {
					// check if duplicate record is entered.
					$conditions = array('Salary.employee_id'=>$employeeID, 'Salary.payment_date'=>$paymentDate, 'Salary.payment_amount'=>$data['Salary']['payment_amount']);
					if($this->Salary->find('first', array('conditions'=>$conditions))) {
						$error = 'Duplicate entry. A similar record already exists.';
					}
				}
			}			
			
			if(!$error) {				
				$data['Salary']['id'] = null;
				$data['Salary']['store_id'] = $this->Session->read('Store.id');
				$data['Salary']['payment_date'] = $paymentDate;
				$data['Salary']['employee_name'] = $employeeInfo['Employee']['name'];
								
				if($this->Salary->save($data)) {					
					$this->Session->write('paymentDate', $paymentDate);
					$msg = '"'.$employeeInfo['Employee']['name'].'" Salary information updated';
					$this->Session->setFlash($msg, 'default', array('class'=>'success'));
					$this->redirect(array('controller'=>'salaries', 'action'=>'add'));
				}
			}
		}
		else {
			if($this->Session->check('paymentDate')) {
				$data['Salary']['payment_date'] = $this->Session->read('paymentDate');
				$this->data = $data;
			}
		}
		
		// find recent sale products
		$conditions = array('Salary.store_id'=>$this->Session->read('Store.id'));
		$salaries = $this->Salary->find('all', array('conditions'=>$conditions, 'order'=>'Salary.created DESC', 'recursive'=>'-1', 'limit'=>'10'));
		
		if($error) {$this->Session->setFlash($error);}
		$this->set(compact('employeesList', 'salaries'));
	}
	
	
	public function remove($salaryID=null) {
		if($this->request->isPost()) {
			if($salaryInfo = $this->Salary->findById($salaryID)) {
				$this->Salary->delete($salaryID);
				$this->Session->setFlash('"'.$salaryInfo['Salary']['employee_name'].'" Salary information, dated "'.date('d M Y',strtotime($salaryInfo['Salary']['payment_date'])).'" has been removed from the list', 'default', array('class'=>'success'));
			}
			else {
				$this->Session->setFlash('Record not found');
			}
		}
		else {
				$this->Session->setFlash('Invalid request');		
		}		
		
		$this->redirect($this->request->referer());
	}
	

}
?>
