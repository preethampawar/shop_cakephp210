<?php

class EmployeesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	public function index()
	{
		$this->Employee->recursive = 0;

		$conditions = ['Employee.store_id' => $this->Session->read('Store.id')];
		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['Employee.created' => 'DESC'],
			'limit' => 100,
			'recursive' => '-1',
		];
		$employees = $this->paginate();
		$this->set(compact('employees'));
	}

	public function view($employeeID = null)
	{
		$this->Employee->bindModel(['hasMany' => ['Salary' => [
			'className' => 'Salary',
			'order' => 'payment_date DESC',
		],
		]]);
		if (!($employeeInfo = $this->Employee->findById($employeeID))) {
			$this->Session->setFlash('Employee not found');
			$this->redirect(['controller' => 'employees', 'action' => 'index']);
		}
		$this->set(compact('employeeInfo'));
	}

	public function add()
	{
		$errorMsg = null;
		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Employee']['name'])) {

				if ($data['Employee']['name'] = trim($data['Employee']['name'])) {
					$conditions = ['Employee.name' => $data['Employee']['name'], 'Employee.store_id' => $this->Session->read('Store.id')];
					if ($this->Employee->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Employee']['name'] . "'" . ' already exists';
					} else {
						$data['Employee']['store_id'] = $this->Session->read('Store.id');
						if ($this->Employee->save($data)) {
							$employeeInfo = $this->Employee->read();
							$msg = 'Employee added successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'success']);
							$this->redirect(['controller' => 'employees', 'action' => 'index']);
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				} else {
					$errorMsg = 'Enter Employee Name';
				}

			}
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;

	}

	public function edit($employeeID = null)
	{
		$errorMsg = null;

		if (!($employeeInfo = $this->CommonFunctions->getEmployeeInfo($employeeID))) {
			$this->Session->setFlash('Employee not found');
			$this->redirect('/employees/');
		}

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Employee']['name'])) {
				if ($data['Employee']['name'] = trim($data['Employee']['name'])) {

					$conditions = ['Employee.name' => $data['Employee']['name'], 'Employee.store_id' => $this->Session->read('Store.id'), 'Employee.id <>' => $employeeID];
					if ($this->Employee->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Employee']['name'] . "'" . ' already exists';
					} else {
						$data['Employee']['id'] = $employeeID;
						$data['Employee']['store_id'] = $this->Session->read('Store.id');
						if ($this->Employee->save($data)) {
							$msg = 'Employee updated successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'success']);
							$this->redirect('/employees/');
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				} else {
					$errorMsg = 'Enter Employee Name';
				}
			}
		} else {
			$this->data = $employeeInfo;
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		$this->set(compact('employeeInfo'));
	}

	public function remove($employeeID = null)
	{
		if ($this->request->isPost()) {
			if (!($employeeInfo = $this->CommonFunctions->getEmployeeInfo($employeeID))) {
				$this->Session->setFlash('Employee not found');
			} else {
				// delete salary information
				App::uses('Salary', 'Model');
				$this->Salary = new Salary();
				$conditions = ['Salary.employee_id' => $employeeID, 'Salary.store_id' => $this->Session->read('Store.id')];
				$this->Salary->deleteAll($conditions);

				// delete employee information
				$this->Employee->delete($employeeID);
				$this->Session->setFlash('Employee "' . $employeeInfo['Employee']['name'] . '" has been removed', 'default', ['class' => 'success']);
			}
		} else {
			$this->Session->setFlash('Unauthorized access');
		}
		$this->redirect(['action' => 'index']);
	}

}
