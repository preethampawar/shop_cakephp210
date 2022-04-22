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

	function admin_products($supplierId = null, $groupId = null)
	{
		$errorMsg = null;
		
		App::uses('SupplierProduct', 'Model');
		$supplierProductModel = new SupplierProduct();		

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (!empty($data['SupplierProduct'])) {				

				foreach($data['SupplierProduct'] as $row) {
					$deleteConditions = [
						'SupplierProduct.supplier_id' => $supplierId,
						'SupplierProduct.product_id' => $row['product_id'],  
						'SupplierProduct.site_id' => $this->Session->read('Site.id')
					];
					$supplierProductModel->deleteAll($deleteConditions);

					$tmp = [];
					$tmp['SupplierProduct']['id'] = null;
					$tmp['SupplierProduct']['product_id'] = $row['product_id'];
					$tmp['SupplierProduct']['price_relation'] = $row['price_relation'];
					$tmp['SupplierProduct']['relative_base_price'] = (float)$row['relative_base_price'];
					$tmp['SupplierProduct']['price_relation2'] = $row['price_relation2'];
					$tmp['SupplierProduct']['relative_base_price2'] = (float)$row['relative_base_price2'];
					$tmp['SupplierProduct']['active'] = (bool)$row['active'];
					$tmp['SupplierProduct']['supplier_id'] = $supplierId;
					$tmp['SupplierProduct']['site_id'] = $this->Session->read('Site.id');

					$supplierProductModel->save($tmp);				
				}

				$this->successMsg('Supplier Products updated.');
				$this->redirect($this->request->referer());
			}
		}

		$suppliers = $this->Supplier->find('list', ['conditions' => ['Supplier.site_id' => $this->Session->read('Site.id'), 'Supplier.deleted' => 0]]);
		$supplierProductModel->recursive = -1;
		$supplierProducts = $supplierProductModel->find('all', ['conditions' => ['SupplierProduct.supplier_id' => $supplierId]]);
		
		if ($supplierProducts) {
			$tmp = [];
			foreach($supplierProducts as $row) {
				$tmp[$row['SupplierProduct']['product_id']] = $row;
			}
			$supplierProducts = $tmp;
			unset($tmp);
		}

		App::uses('Group', 'Model');
		$this->Group = new Group();
		$groupRates = $this->Group->find('list', ['fields' => ['Group.id', 'Group.rate'], 'conditions' => ['Group.site_id' => $this->Session->read('Site.id'), 'Group.deleted' => 0]]);

		$groups = $this->Group->find('all', ['conditions' => ['Group.site_id' => $this->Session->read('Site.id'), 'Group.deleted' => 0], 'order' => ['Group.name']]);

		$groupsList = [];
		foreach($groups as $index => $row) {
			$groupName = $row['Group']['name'] . ' [' . $row['Group']['rate'] . ']';
			$groupsList[$row['Group']['id']] = $groupName;
		}
		unset($groups);

		$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.deleted' => 0, 'Product.group_id >' => 0];

		if ($groupId) {
			$conditions['Product.group_id'] = $groupId;
		}

		App::uses('Product', 'Model');
		$productModel = new Product();
		$productModel->unbindModel(['hasMany' => 'CategoryProduct']);
		$products = $productModel->find('all',
			[
				'conditions' => $conditions,
				'fields' => [
					'Product.id',
					'Product.name',
					'Product.mrp',
					'Product.allow_relative_price_update',
					'Product.relative_base_price',
					'Product.group_id',
					'Product.relative_price_relation',
					'Product.discount',
				],
				'order' => ['Product.name ASC', 'Product.group_id ASC']
			]
		);
		// debug($products);

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg', 'products', 'supplierProducts', 'groupsList', 'groupId', 'groupRates', 'suppliers', 'supplierId'));
	}
	
}
