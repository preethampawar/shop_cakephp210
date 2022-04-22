<?php

class InvoicesController extends AppController
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
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier();
		$suppliersList = $this->Supplier->find('list', ['conditions' => ['Supplier.store_id' => $this->Session->read('Store.id')]]);
		$invoiceDate = Date('Y-m-d');

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Invoice']['name'])) {
				if (Validation::blank($data['Invoice']['dd_amount'])) {
					$errorMsg = 'Enter DD Amount';
				} else {
					if ((!Validation::decimal($data['Invoice']['dd_amount'])) OR ($data['Invoice']['dd_amount'] <= 0)) {
						$errorMsg = 'Enter Valid DD Amount';
					}
				}

				if (!empty($data['Invoice']['tcs_value'])) {
					if ((!Validation::decimal($data['Invoice']['tcs_value'])) OR ($data['Invoice']['tcs_value'] < 0)) {
						$errorMsg = 'Enter Valid TCS Value';
					}
				}
				if (!empty($data['Invoice']['prev_credit'])) {
					if ((!Validation::decimal($data['Invoice']['prev_credit'])) OR ($data['Invoice']['prev_credit'] < 0)) {
						$errorMsg = 'Enter Valid Previous Credit Value';
					}
				}

				if (!$errorMsg) {
					if (!empty($data['Invoice']['invoice_date'])) {
//						$invoiceDate = $data['Invoice']['invoice_date']['year'].'-'.$data['Invoice']['invoice_date']['month'].'-'.$data['Invoice']['invoice_date']['day'];
//						$data['Invoice']['invoice_date'] = $invoiceDate;
						$invoiceDate = $data['Invoice']['invoice_date'];

						if ($data['Invoice']['name'] = trim($data['Invoice']['name'])) {
							$conditions = ['Invoice.name' => $data['Invoice']['name'], 'Invoice.store_id' => $this->Session->read('Store.id')];
							if ($this->Invoice->find('first', ['conditions' => $conditions])) {
								$errorMsg = "'" . $data['Invoice']['name'] . "'" . ' already exists';
							} else {
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = ($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';
								if ($this->Invoice->save($data)) {
									$invoiceInfo = $this->Invoice->read();
									$msg = 'Invoice created successfully';
									$this->successMsg($msg);
									$this->redirect(['controller' => 'invoices', 'action' => 'selectInvoice', $invoiceInfo['Invoice']['id']]);
								} else {
									$errorMsg = 'An error occurred while communicating with the server';
								}
							}
						} else {
							$errorMsg = 'Enter Invoice Name';
						}
					} else {
						$errorMsg = 'Select Invoice Date';
					}
				}
			}
		}
		($errorMsg) ? $this->errorMsg($errorMsg) : null;
		$this->set(compact('suppliersList', 'invoiceDate'));
	}

	public function index()
	{
		$this->Session->delete('Invoice');
		$invoices = $this->Invoice->find('all', ['conditions' => ['Invoice.store_id' => $this->Session->read('Store.id')], 'order' => ['Invoice.invoice_date DESC', 'Invoice.created DESC']]);

		$this->set('invoices', $invoices);
	}

	public function edit($invoiceID = null)
	{
		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier();
		$suppliersList = $this->Supplier->find('list', ['Supplier.store_id' => $this->Session->read('Store.id')]);

		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Invoice']['name'])) {
				if ($data['Invoice']['name'] = trim($data['Invoice']['name'])) {
					if (!empty($data['Invoice']['dd_no'])) {
						if (Validation::blank($data['Invoice']['dd_no'])) {
							$errorMsg = 'Enter DD No';
						} else {
							if ((!Validation::decimal($data['Invoice']['dd_amount'])) OR ($data['Invoice']['dd_amount'] <= 0)) {
								$errorMsg = 'Enter Valid DD Amount';
							} else if ((!Validation::decimal($data['Invoice']['dd_purchase'])) OR ($data['Invoice']['dd_purchase'] <= 0)) {
								$errorMsg = 'Enter valid DD Purchase Amount';
							}
						}
					}
					if (!empty($data['Invoice']['tcs_value'])) {
						if ((!Validation::decimal($data['Invoice']['tcs_value'])) OR ($data['Invoice']['tcs_value'] < 0)) {
							$errorMsg = 'Enter Valid TCS Value';
						}
					}
					if (!empty($data['Invoice']['prev_credit'])) {
						if ((!Validation::decimal($data['Invoice']['prev_credit'])) OR ($data['Invoice']['prev_credit'] < 0)) {
							$errorMsg = 'Enter Valid Previous Credit Value';
						}
					}

					if (!$errorMsg) {
						if (!empty($data['Invoice']['invoice_date'])) {
//							$invoiceDate = $data['Invoice']['invoice_date']['year'].'-'.$data['Invoice']['invoice_date']['month'].'-'.$data['Invoice']['invoice_date']['day'];
//							$data['Invoice']['invoice_date'] = $invoiceDate;
							$invoiceDate = $data['Invoice']['invoice_date'];
							$conditions = ['Invoice.name' => $data['Invoice']['name'], 'Invoice.store_id' => $this->Session->read('Store.id'), 'Invoice.id <>' => $invoiceID];
							if ($this->Invoice->find('first', ['conditions' => $conditions])) {
								$errorMsg = "'" . $data['Invoice']['name'] . "'" . ' already exists';
							} else {
								$data['Invoice']['id'] = $invoiceID;
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = ($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';
								if ($this->Invoice->save($data)) {
									// update purchase products date with this invoice date.
									App::uses('Purchase', 'Model');
									$this->Purchase = new Purchase();
									$fields = ['Purchase.purchase_date' => "'" . $invoiceDate . "'"];
									$conditions = ['Purchase.invoice_id' => $invoiceID];
									$this->Purchase->recursive = '-1';
									$this->Purchase->updateAll($fields, $conditions);

									$this->updateInvoice($invoiceID);

									$msg = 'Invoice details updated successfully';

									$this->successMsg($msg);
									$this->redirect('/invoices/selectInvoice/' . $invoiceID);
								} else {
									$errorMsg = 'An error occurred while communicating with the server';
								}
							}
						} else {
							$errorMsg = 'Enter Invoice Date';
						}
					}
				} else {
					$errorMsg = 'Enter Invoice Name';
				}
			}
		} else {
			$this->data = $invoiceInfo;
		}
		($errorMsg) ? $this->errorMsg($errorMsg) : null;
		$this->set(compact('suppliersList'));
	}

	public function getInvoiceInfo($invoiceID = null)
	{
		if (!$invoiceID) {
			return [];
		}
		return $this->Invoice->find('first', ['conditions' => ['Invoice.id' => $invoiceID, 'Invoice.store_id' => $this->Session->read('Store.id')]]);
	}

	public function delete($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
		} else {
			// delete purchase data of the selected Invoice.
			App::uses('Purchase', 'Model');
			$this->Purchase = new Purchase();
			$conditions = ['Purchase.invoice_id' => $invoiceID];
			$this->Purchase->deleteAll($conditions);

			// delete Invoice information
			$this->Invoice->delete($invoiceID);

			$this->successMsg('Invoice "' . $invoiceInfo['Invoice']['name'] . '" has been removed');

		}
		$this->redirect($this->request->referer());
	}

	public function selectInvoice($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}

		$this->Session->write('Invoice', $invoiceInfo['Invoice']);
		$this->redirect('/purchases/addProduct/');
	}

	public function details($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}

		// find invoice products
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase();
		$conditions = ['Purchase.invoice_id' => $invoiceID];
		$invoiceProducts = $this->Purchase->find('all', ['conditions' => $conditions, 'recursive' => 2]);
		$this->set(compact('invoiceInfo', 'invoiceProducts'));
	}

	public function refresh()
	{
		$storeId = $this->Session->read('Store.id');
		$invoices = $this->Invoice->find('list', ['conditions' => ['Invoice.store_id' => $storeId]]);

		foreach($invoices as $invoiceId => $invoiceName) {
			$this->updateInvoice($invoiceId);
		}

		$this->successMsg('Invoices updated successfully.');
		$this->redirect($this->request->referer());
	}

}
