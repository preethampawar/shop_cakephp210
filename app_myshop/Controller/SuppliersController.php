<?php
App::uses('CakeEmail', 'Network/Email');

class SuppliersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	function index()
	{
		$conditions = [
			'Supplier.site_id' => $this->Session->read('Site.id'),
			'Supplier.deleted' => 0,
		];
		$this->paginate = [
			'limit' => 50,
			'order' => ['Supplier.created' => 'DESC'],
			'conditions' => $conditions,
			'recursive' => -1
		];
		$suppliers = $this->paginate();
		$title_for_layout = 'Suppliers';
		$this->set(compact('suppliers', 'title_for_layout'));
	}

	function admin_index()
	{
		$sort = ['Supplier.created' => 'DESC'];

		$conditions = [
			'Supplier.site_id' => $this->Session->read('Site.id'),
			'Supplier.deleted' => 0,
		];
		$suppliers = $this->Supplier->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('suppliers'));
	}

	function admin_add()
	{
		$errorMsg = null;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (empty($data['Supplier']['name'])) {
				$errorMsg = 'Supplier name is required';
			} else {
				$conditions = [
					'Supplier.name' => $data['Supplier']['name'],
					'Supplier.site_id' => $this->Session->read('Site.id'),
					'Supplier.deleted' => 0
				];

				if ($this->Supplier->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Supplier with same name already exist.";
				}
			}

			if (!$errorMsg) {
				if (empty(trim($data['Supplier']['phone']))) {
					$errorMsg = 'Phone no. is required.';
				} else {
					$conditions = [
						'Supplier.phone' => trim($data['Supplier']['phone']),
						'Supplier.site_id' => $this->Session->read('Site.id'),
						'Supplier.deleted' => 0
					];

					if ($this->Supplier->find('first', ['conditions' => $conditions])) {
						$errorMsg = "Supplier with same phone no. already exist.";
					}
				}
			}

			if (!$errorMsg) {
				$data['Supplier']['name'] = $data['Supplier']['name'];
				$data['Supplier']['site_id'] = $this->Session->read('Site.id');

				if ($this->Supplier->save($data)) {
					$supplierInfo = $this->Supplier->read();

					$this->successMsg('Supplier created successfully.');
					$this->redirect('/admin/suppliers/');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		}

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg'));
	}

	function admin_edit($supplierId)
	{
		if (!$contentInfo = $this->isSiteSupplier($supplierId)) {
			$this->errorMsg('Supplier Not Found');
			$this->redirect('/admin/suppliers/');
		}

		$errorMsg = null;

		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['Supplier']['name'])) {
				$errorMsg = 'Supplier name is required';
			} else {
				$conditions = [
					'Supplier.name' => $data['Supplier']['name'],
					'Supplier.id NOT' => $supplierId,
					'Supplier.site_id' => $this->Session->read('Site.id'),
					'Supplier.deleted' => 0
				];

				if ($this->Supplier->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Supplier with same name already exist.";
				}


			}

			if (!$errorMsg) {
				if (empty(trim($data['Supplier']['phone']))) {
					$errorMsg = 'Phone no. is required.';
				} else {
					$conditions = [
						'Supplier.phone' => trim($data['Supplier']['phone']),
						'Supplier.id NOT' => $supplierId,
						'Supplier.site_id' => $this->Session->read('Site.id'),
						'Supplier.deleted' => 0
					];

					if ($this->Supplier->find('first', ['conditions' => $conditions])) {
						$errorMsg = "Supplier with same phone no. already exist.";
					}
				}
			}

			if (!$errorMsg) {
				$data['Supplier']['id'] = $supplierId;

				if ($this->Supplier->save($data)) {
					$this->successMsg('Supplier updated successfully');
					$this->redirect('/admin/suppliers');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}

		if ($errorMsg) {
			$this->errorMsg($errorMsg);
		}

		$this->set(compact('errorMsg', 'contentInfo'));
	}

	public function admin_activate($supplierId, $type)
	{
		if (!$contentInfo = $this->isSiteSupplier($supplierId)) {
			$this->errorMsg('Supplier Not Found');
			$this->redirect('/admin/suppliers/');
		}

		$data['Supplier']['id'] = $supplierId;
		$data['Supplier']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->Supplier->save($data)) {
			$this->successMsg('Supplier modified successfully');
		} else {
			$this->errorMsg('An error occurred while updating data');
		}
		$this->redirect('/admin/suppliers/');
	}

	public function admin_delete($supplierId)
	{
		if (!$supplierInfo = $this->isSiteSupplier($supplierId)) {
			$this->errorMsg('Supplier Not Found');
		} else {

			$data['Supplier']['id'] = $supplierId;
			$data['Supplier']['active'] = 0;
			$data['Supplier']['deleted'] = 1;

			if ($this->Supplier->save($data)) {
				$this->successMsg('Supplier deleted successfully.');
			} else {
				$this->errorMsg('An error occurred. Could not delete supplier.');
			}
			$this->redirect('/admin/suppliers/');
		}
		$this->redirect('/admin/suppliers/');
	}
}

?>
