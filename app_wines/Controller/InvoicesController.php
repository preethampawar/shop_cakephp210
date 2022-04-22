<?php
class InvoicesController extends AppController {
	function beforeFilter() {
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	// Get logged in user's selected invoice information
	public function getInvoiceInfo($invoiceID=null) {
		if(!$invoiceID) {
			return array();
		}
		return $this->Invoice->find('first', array('conditions'=>array('Invoice.id'=>$invoiceID, 'Invoice.store_id'=>$this->Session->read('Store.id'))));
	}

	public function add() {
		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier;
		$suppliersList = $this->Supplier->find('list', array('conditions'=>array('Supplier.store_id'=>$this->Session->read('Store.id'))));

		if($this->request->data) {
			$data = $this->request->data;
			if(isset($data['Invoice']['name'])) {
				if(Validation::blank($data['Invoice']['dd_amount'])) {
					$errorMsg = 'Enter DD Amount';
				}
				else {
					if((!Validation::decimal($data['Invoice']['dd_amount'])) OR ($data['Invoice']['dd_amount']<=0)) {
						$errorMsg = 'Enter Valid DD Amount';
					}
				}

				if(!empty($data['Invoice']['tcs_value'])) {
					if((!Validation::decimal($data['Invoice']['tcs_value'])) OR ($data['Invoice']['tcs_value']<0)) {
						$errorMsg = 'Enter Valid TCS Value';
					}
				}
				if(!empty($data['Invoice']['prev_credit'])) {
					if((!Validation::decimal($data['Invoice']['prev_credit'])) OR ($data['Invoice']['prev_credit']<0)) {
						$errorMsg = 'Enter Valid Previous Credit Value';
					}
				}

				if(!$errorMsg) {
					if(!empty($data['Invoice']['invoice_date'])) {
						$invoiceDate = $data['Invoice']['invoice_date']['year'].'-'.$data['Invoice']['invoice_date']['month'].'-'.$data['Invoice']['invoice_date']['day'];
						$data['Invoice']['invoice_date'] = $invoiceDate;

						if($data['Invoice']['name']=trim($data['Invoice']['name'])) {
							$conditions = array('Invoice.name'=>$data['Invoice']['name'], 'Invoice.store_id'=>$this->Session->read('Store.id'));
							if($this->Invoice->find('first', array('conditions'=>$conditions))) {
								$errorMsg = "'".$data['Invoice']['name']."'". ' already exists';
							}
							else {
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = ($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';
								if($this->Invoice->save($data)) {
									$invoiceInfo = $this->Invoice->read();
									$msg = 'Invoice created successfully';
									$this->Session->setFlash($msg, 'default', array('class'=>'success'));
									$this->redirect(array('controller'=>'invoices', 'action'=>'selectInvoice', $invoiceInfo['Invoice']['id']));
								}
								else {
									$errorMsg = 'An error occurred while communicating with the server';
								}
							}
						}
						else {
							$errorMsg = 'Enter Invoice Name';
						}
					}
					else {
						$errorMsg = 'Select Invoice Date';
					}
				}
			}
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		$this->set(compact('suppliersList'));
	}

	public function index() {
		$this->Session->delete('Invoice');
		$invoices = $this->Invoice->find('all', array('conditions'=>array('Invoice.store_id'=>$this->Session->read('Store.id')), 'order'=>array('Invoice.invoice_date DESC', 'Invoice.created DESC')));

		$this->set('invoices', $invoices);
		// $this->set('invoiceAmount', $invoiceAmount);
	}

	public function edit($invoiceID=null) {
		$errorMsg = null;
		App::uses('Supplier', 'Model');
		$this->Supplier = new Supplier;
		$suppliersList = $this->Supplier->find('list', array('Supplier.store_id'=>$this->Session->read('Store.id')));

		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
			$this->redirect('/invoices/');
		}

		if($this->request->data) {
			$data = $this->request->data;
			if(isset($data['Invoice']['name'])) {
				if($data['Invoice']['name']=trim($data['Invoice']['name'])) {
					if(!empty($data['Invoice']['dd_no'])) {
						if(Validation::blank($data['Invoice']['dd_no'])) {
							$errorMsg = 'Enter DD No';
						}
						else {
							if((!Validation::decimal($data['Invoice']['dd_amount'])) OR ($data['Invoice']['dd_amount']<=0)) {
								$errorMsg = 'Enter Valid DD Amount';
							}
							elseif((!Validation::decimal($data['Invoice']['dd_purchase'])) OR ($data['Invoice']['dd_purchase']<=0)) {
								$errorMsg = 'Enter valid DD Purchase Amount';
							}
						}
					}
					if(!empty($data['Invoice']['tcs_value'])) {
						if((!Validation::decimal($data['Invoice']['tcs_value'])) OR ($data['Invoice']['tcs_value']<0)) {
							$errorMsg = 'Enter Valid TCS Value';
						}
					}
					if(!empty($data['Invoice']['prev_credit'])) {
						if((!Validation::decimal($data['Invoice']['prev_credit'])) OR ($data['Invoice']['prev_credit']<0)) {
							$errorMsg = 'Enter Valid Previous Credit Value';
						}
					}

					if(!$errorMsg) {
						if(!empty($data['Invoice']['invoice_date'])) {
							$invoiceDate = $data['Invoice']['invoice_date']['year'].'-'.$data['Invoice']['invoice_date']['month'].'-'.$data['Invoice']['invoice_date']['day'];
							$data['Invoice']['invoice_date'] = $invoiceDate;
							$conditions = array('Invoice.name'=>$data['Invoice']['name'], 'Invoice.store_id'=>$this->Session->read('Store.id'), 'Invoice.id <>'=>$invoiceID);
							if($this->Invoice->find('first', array('conditions'=>$conditions))) {
								$errorMsg = "'".$data['Invoice']['name']."'". ' already exists';
							}
							else {
								$data['Invoice']['id'] = $invoiceID;
								$data['Invoice']['store_id'] = $this->Session->read('Store.id');
								$data['Invoice']['supplier_name'] = ($data['Invoice']['supplier_id']) ? $suppliersList[$data['Invoice']['supplier_id']] : '';
								if($this->Invoice->save($data)) {
									// update purchase products date with this invoice date.
									App::uses('Purchase', 'Model');
									$this->Purchase = new Purchase;
									$fields = array('Purchase.purchase_date'=>"'".$invoiceDate."'");
									$conditions = array('Purchase.invoice_id'=>$invoiceID);
									$this->Purchase->recursive = '-1';
									$this->Purchase->updateAll($fields, $conditions);

									$this->updateInvoice($invoiceID);

									$msg = 'Invoice updated successfully';

									$this->Session->setFlash($msg, 'default', array('class'=>'success'));
									$this->redirect('/invoices/');
								}
								else {
									$errorMsg = 'An error occurred while communicating with the server';
								}
							}
						}
						else {
							$errorMsg = 'Enter Invoice Date';
						}
					}
				}
				else {
					$errorMsg = 'Enter Invoice Name';
				}
			}
		}
		else {
			$this->data = $invoiceInfo;
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		$this->set(compact('suppliersList'));
	}

	public function delete($invoiceID=null) {
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
		}
		else {
			// delete purchase data of the selected Invoice.
			App::uses('Purchase', 'Model');
			$this->Purchase = new Purchase;
			$conditions = array('Purchase.invoice_id'=>$invoiceID);
			$this->Purchase->deleteAll($conditions);

			// delete Invoice information
			$this->Invoice->delete($invoiceID);

			$this->Session->setFlash('Invoice "'.$invoiceInfo['Invoice']['name'].'" has been removed', 'default', array('class'=>'success'));

		}
		$this->redirect($this->request->referer());
	}

	public function selectInvoice($invoiceID=null) {
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
			$this->redirect('/invoices/');
		}

		$this->Session->write('Invoice', $invoiceInfo['Invoice']);
		$this->redirect('/purchases/addProduct/');
	}

	public function details($invoiceID=null) {
		if(!($invoiceInfo = $this->getInvoiceInfo($invoiceID))) {
			$this->Session->setFlash('Invoice not found');
			$this->redirect('/invoices/');
		}

		// find invoice products
		App::uses('Purchase', 'Model');
		$this->Purchase = new Purchase;
		$conditions = array('Purchase.invoice_id'=>$invoiceID);
		$invoiceProducts = $this->Purchase->find('all', array('conditions'=>$conditions, 'recursive'=>2));
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
