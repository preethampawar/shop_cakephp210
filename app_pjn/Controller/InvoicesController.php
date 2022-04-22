<?php

class InvoicesController extends AppController
{
	public $invoiceTypes = ['purchase' => 'Purchase', 'sale' => 'Sale'];

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	// Get logged in user's selected invoice information

	public function index($type = null)
	{
		$this->Session->delete('Invoice');
		$conditions = ['Invoice.store_id' => $this->Session->read('Store.id')];
		if ($type) {
			$conditions['Invoice.invoice_type'] = ($type == 'sale') ? 'sale' : 'purchase';
		}
		$invoices = $this->Invoice->find('all', ['conditions' => $conditions, 'order' => ['Invoice.invoice_date DESC', 'Invoice.created DESC']]);

		$this->set('invoices', $invoices);
		$this->set('type', $type);
	}

	public function add()
	{
		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier();
		$suppliersList = $this->Supplier->find('list', ['conditions' => ['Supplier.store_id' => $this->Session->read('Store.id')]]);

		App::uses('Franchise', 'Model');
		$this->Franchise = new Franchise();
		$franchiseList = $this->Franchise->getKeyValuePair($this->Session->read('Store.id'));

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Invoice']['name'])) {
				if (!Validation::blank($data['Invoice']['static_invoice_value'])) {
					if ((!Validation::decimal($data['Invoice']['static_invoice_value'])) OR ($data['Invoice']['static_invoice_value'] < 0)) {
						$errorMsg = 'Enter Valid Invoice Amount';
					}
				}

				if (!$errorMsg) {
					if (!empty($data['Invoice']['invoice_date'])) {
						$invoiceDate = $data['Invoice']['invoice_date']['year'] . '-' . $data['Invoice']['invoice_date']['month'] . '-' . $data['Invoice']['invoice_date']['day'];
						$data['Invoice']['invoice_date'] = $invoiceDate;

						if ($data['Invoice']['name'] = trim($data['Invoice']['name'])) {
							$conditions = ['Invoice.name' => $data['Invoice']['name'], 'Invoice.store_id' => $this->Session->read('Store.id')];
							if ($this->Invoice->find('first', ['conditions' => $conditions])) {
								$errorMsg = "'" . $data['Invoice']['name'] . "'" . ' already exists';
							} else {
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = (isset($data['Invoice']['supplier_id']) and !empty($data['Invoice']['supplier_id'])) ? $suppliersList[$data['Invoice']['supplier_id']] : '';

								// get franchise details
								$franchiseName = '';
								$hasFranchise = $this->Session->read('Store.has_franchise');
								if ($hasFranchise && !empty($data['Invoice']['franchise_id'])) {
									$franchiseInfo = $this->Franchise->findById($data['Invoice']['franchise_id']);
									$franchiseName = isset($franchiseInfo['Franchise']['name']) ? $franchiseInfo['Franchise']['name'] : '';
								}
								$data['Invoice']['franchise_name'] = $franchiseName;

								if ($this->Invoice->save($data)) {
									$invoiceInfo = $this->Invoice->read();
									$msg = 'Invoice created successfully';
									$this->Session->setFlash($msg, 'default', ['class' => 'success']);
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
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		$invoiceTypes = $this->invoiceTypes;
		$this->set(compact('suppliersList', 'invoiceTypes', 'franchiseList'));
	}

	public function edit($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
			$this->redirect('/invoices/');
		}

		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier();
		$suppliersList = $this->Supplier->find('list', ['Supplier.store_id' => $this->Session->read('Store.id')]);

		App::uses('Franchise', 'Model');
		$this->Franchise = new Franchise();
		$franchiseList = $this->Franchise->getKeyValuePair($this->Session->read('Store.id'));

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Invoice']['name'])) {
				if ($data['Invoice']['name'] = trim($data['Invoice']['name'])) {
					if (!empty($data['Invoice']['dd_no'])) {
						if (Validation::blank($data['Invoice']['dd_no'])) {
							$errorMsg = 'Enter DD No';
						} else {
							if ((!Validation::decimal($data['Invoice']['static_invoice_value'])) OR ($data['Invoice']['static_invoice_value'] <= 0)) {
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
							$invoiceDate = $data['Invoice']['invoice_date']['year'] . '-' . $data['Invoice']['invoice_date']['month'] . '-' . $data['Invoice']['invoice_date']['day'];
							$data['Invoice']['invoice_date'] = $invoiceDate;
							$conditions = ['Invoice.name' => $data['Invoice']['name'], 'Invoice.store_id' => $this->Session->read('Store.id'), 'Invoice.id <>' => $invoiceID];
							if ($this->Invoice->find('first', ['conditions' => $conditions])) {
								$errorMsg = "'" . $data['Invoice']['name'] . "'" . ' already exists';
							} else {
								$data['Invoice']['id'] = $invoiceID;
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = isset($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';

								// get franchise details
								$franchiseId = null;
								$franchiseName = null;
								$hasFranchise = $this->Session->read('Store.has_franchise');
								if ($hasFranchise && !empty($data['Invoice']['franchise_id'])) {
									$franchiseId = $data['Invoice']['franchise_id'];
									$franchiseInfo = $this->Franchise->findById($franchiseId);
									$franchiseName = isset($franchiseInfo['Franchise']['name']) ? "'" . $franchiseInfo['Franchise']['name'] . "'" : null;
								}
								$data['Invoice']['franchise_name'] = $franchiseName;

								if ($this->Invoice->save($data)) {
									if ($invoiceInfo['Invoice']['invoice_type'] == 'sale') {
										// update sale products date with this invoice date.
										App::uses('Sale', 'Model');
										$this->Sale = new Sale();
										$fields = ['Sale.sale_date' => "'" . $invoiceDate . "'", 'Sale.invoice_name' => "'" . $data['Invoice']['name'] . "'"];
										$conditions = ['Sale.invoice_id' => $invoiceID];
										$this->Sale->recursive = '-1';
										$this->Sale->updateAll($fields, $conditions);
										// update franchise details
										if ($hasFranchise) {
											$fields = ['Sale.franchise_id' => $franchiseId, 'Sale.franchise_name' => $franchiseName];
											$conditions = ['Sale.invoice_id' => $invoiceID];
											$this->Sale->recursive = '-1';
											$this->Sale->updateAll($fields, $conditions);
										}
									} else {
										$this->updateInvoice($invoiceID);
										// update purchase products date with this invoice date.
										App::uses('Purchase', 'Model');
										$this->Purchase = new Purchase();
										$fields = ['Purchase.purchase_date' => "'" . $invoiceDate . "'"];
										$conditions = ['Purchase.invoice_id' => $invoiceID];
										$this->Purchase->recursive = '-1';
										$this->Purchase->updateAll($fields, $conditions);
									}

									$msg = 'Invoice updated successfully';
									$this->Session->setFlash($msg, 'default', ['class' => 'success']);
									$this->redirect('/invoices/');
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
		$invoiceTypes = $this->invoiceTypes;
		$this->set(compact('suppliersList', 'franchiseList', 'invoiceInfo', 'invoiceTypes'));

		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
	}

	public function getInvoiceInfo($invoiceID = null, $type = null)
	{
		if (!$invoiceID) {
			return [];
		}
		$conditions = ['Invoice.id' => $invoiceID, 'Invoice.store_id' => $this->Session->read('Store.id')];
		if ($type) {
			$conditions['Invoice.invoice_type'] = ($type == 'purchase') ? 'purchase' : 'sale';
		}
		return $this->Invoice->find('first', ['conditions' => $conditions, 'recursive' => '-1']);
	}

	public function delete($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
		} else {
			if ($invoiceInfo['Invoice']['invoice_type'] == 'sale') {
				// delete sale data of the selected Invoice.
				App::uses('Sale', 'Model');
				$this->Sale = new Sale();
				$conditions = ['Sale.invoice_id' => $invoiceID];
				$this->Sale->deleteAll($conditions);
			} else {
				// delete purchase data of the selected Invoice.
				App::uses('Purchase', 'Model');
				$this->Purchase = new Purchase();
				$conditions = ['Purchase.invoice_id' => $invoiceID];
				$this->Purchase->deleteAll($conditions);
			}

			// delete Invoice information
			$this->Invoice->delete($invoiceID);
			$this->Session->setFlash('Invoice "' . $invoiceInfo['Invoice']['name'] . '" has been removed', 'default', ['class' => 'success']);

		}
		$this->redirect($this->request->referer());
	}

	public function selectInvoice($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {

			$this->Session->setFlash('Invoice not found');
			$this->redirect('/invoices/');
		}

		$this->Session->write('Invoice', $invoiceInfo['Invoice']);
		if ($invoiceInfo['Invoice']['invoice_type'] == 'purchase') {
			$this->redirect('/purchases/addProduct/');
		} else {
			$this->redirect('/sales/addProduct/');
		}
	}

	public function details($invoiceID = null)
	{
		if (!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
			$this->redirect('/invoices/');
		}

		// find invoice products
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase();
		$conditions = ['Purchase.invoice_id' => $invoiceID];
		$invoiceProducts = $this->Purchase->find('all', ['conditions' => $conditions, 'recursive' => 2]);
		$this->set(compact('invoiceInfo', 'invoiceProducts'));
	}

}
