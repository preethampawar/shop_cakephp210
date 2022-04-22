<?php
App::uses('Validation', 'Utility');

class SuppliersController extends AppController
{
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	// Get logged in user's selected invoice information

	public function add()
	{
		$errorMsg = null;
		if ($this->request->isPost()) {
			$data = $this->request->data;

			if ($data['Supplier']['name'] = trim($data['Supplier']['name'])) {
				if ((!$data['Supplier']['name']) OR !Validation::custom($data['Supplier']['name'], "/^[a-zA-Z0-9][a-zA-Z0-9\s\-\._#]{1,55}$/i")) {
					$errorMsg = 'Invalid Name (Or) Supplier name should be between 2 and 55 chars';
				}
				if (!$errorMsg) {
					$conditions = ['Supplier.name' => $data['Supplier']['name'], 'Supplier.store_id' => $this->Session->read('Store.id')];
					if ($this->Supplier->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Supplier']['name'] . "'" . ' already exists';
					} else {
						$data['Supplier']['store_id'] = $this->Session->read('Store.id');
						if ($this->Supplier->save($data)) {
							$supplierInfo = $this->Supplier->read();
							$msg = 'Supplier created successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'success']);
							$this->redirect(['controller' => 'suppliers', 'action' => 'index']);
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				}
			} else {
				$errorMsg = 'Enter Supplier Name';
			}
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;

	}

	public function index()
	{
		$suppliers = $this->Supplier->find('all', ['conditions' => ['Supplier.store_id' => $this->Session->read('Store.id')], 'order' => ['Supplier.created DESC']]);
		$this->set('suppliers', $suppliers);
	}

	public function edit($supplierID = null)
	{
		$errorMsg = null;

		if (!($supplierInfo = $this->getSupplierInfo($supplierID))) {
			$this->Session->setFlash('Supplier not found');
			$this->redirect('/suppliers/');
		}
		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			if (isset($data['Supplier']['name'])) {
				if ($data['Supplier']['name'] = trim($data['Supplier']['name'])) {
					if ((!$data['Supplier']['name']) OR !Validation::custom($data['Supplier']['name'], "/^[a-zA-Z0-9][a-zA-Z0-9\s\-\._#]{1,55}$/i")) {
						$errorMsg = 'Invalid Name (Or) Supplier name should be between 2 and 55 chars';
					}

					if (!$errorMsg) {
						$conditions = ['Supplier.name' => $data['Supplier']['name'], 'Supplier.store_id' => $this->Session->read('Store.id'), 'Supplier.id <>' => $supplierID];
						if ($this->Supplier->find('first', ['conditions' => $conditions])) {
							$errorMsg = "'" . $data['Supplier']['name'] . "'" . ' already exists';
						} else {
							$data['Supplier']['id'] = $supplierID;
							$data['Supplier']['store_id'] = $this->Session->read('Store.id');
							if ($this->Supplier->save($data)) {
								$msg = 'Supplier updated successfully';
								$this->Session->setFlash($msg, 'default', ['class' => 'success']);
								$this->redirect('/suppliers/');
							} else {
								$errorMsg = 'An error occured while communicating with the server';
							}
						}
					}
				} else {
					$errorMsg = 'Enter Supplier Name';
				}
			}
		} else {
			$this->data = $supplierInfo;
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
	}

	public function getSupplierInfo($supplierID = null)
	{
		if (!$supplierID) {
			return [];
		}
		return $this->Supplier->find('first', ['conditions' => ['Supplier.id' => $supplierID, 'Supplier.store_id' => $this->Session->read('Store.id')]]);
	}

	public function remove($supplierID = null)
	{
		if (!($supplierInfo = $this->getSupplierInfo($supplierID))) {
			$this->Session->setFlash('Supplier not found');
		} else {
			// delete Supplier information
			$this->Supplier->delete($supplierID);

			$this->Session->setFlash('Supplier "' . $supplierInfo['Supplier']['name'] . '" has been removed', 'default', ['class' => 'success']);

		}
		$this->redirect($this->request->referer());
	}

	public function details($supplierID = null)
	{
		if (!($supplierInfo = $this->getSupplierInfo($supplierID))) {
			$this->Session->setFlash('Supplier not found');
			$this->redirect('/suppliers/');
		}

		// find supplier invoices
		App::uses('Invoice', 'Model');
		$this->Invoice = new Invoice();
		$conditions = ['Invoice.supplier_id' => $supplierID];
		$supplierInvoices = $this->Invoice->find('all', ['conditions' => $conditions]);
		$this->set(compact('supplierInfo', 'supplierInvoices'));
	}

}
