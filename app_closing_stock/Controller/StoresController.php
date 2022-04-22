<?php

class StoresController extends AppController
{
	public function beforeFilter()
	{
		parent::beforeFilter();

		$this->set('hideSideBar', true);
		if (in_array($this->request->params['action'], ['add', 'edit', 'delete'])) {
			if ($this->Session->read('manager') != '1') {
				$this->errorMsg('Access denied. Contact this software provider.');
				$this->redirect($this->Auth->redirectUrl());
			}
		}

	}

	// Get logged in user's selected store information

	public function add()
	{
		$this->Session->delete('Store');
		$errorMsg = null;
		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Store']['name'])) {
				if ($data['Store']['name'] = trim($data['Store']['name'])) {
					$conditions = ['Store.name' => $data['Store']['name'], 'Store.user_id' => $this->Auth->user('id')];
					if ($this->Store->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Store']['name'] . "'" . ' already exists';
					} else {
						if ($this->Session->read('manager') != '1') {
							$data['Store']['user_id'] = $this->Auth->user('id');
						}

						if ($this->Store->save($data)) {
							$msg = 'Store created successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'alert alert-success']);
							$this->redirect('/stores/');
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				} else {
					$errorMsg = 'Enter Store Name';
				}
			}
		}

		App::uses('User', 'Model');
		$this->User = new User();
		$userInfo = $this->User->find('list');

		($errorMsg) ? $this->Session->setFlash($errorMsg, 'default', ['class' => 'alert alert-danger']) : null;

		$this->set(compact('userInfo', 'storeInfo'));
	}

	public function access()
	{
		$this->Session->write('storeAccess.allowed', false);
		$this->Session->write('storeAccess.isAdmin', false);
		if ($this->request->data) {
			$userType = $this->request->data['Store']['user_type'];
			$storePassword = $this->request->data['Store']['access_password'];
			$userId = $this->Auth->user('id');
			$storeMasterPassword = $this->Auth->user('store_password');
			$storeAccess = false;
			$stores = [];

			if ($userType == 'admin') {
				if ($storeMasterPassword == $storePassword) {
					$storeAccess = true;
					$this->Session->write('storeAccess.isAdmin', true);
				}
			} else if ($userType == 'user') {
				$query = "select s.id
                    from users u
                        left join stores s on s.user_id = u.id
                        left join store_passwords sp on sp.store_id = s.id
                    where u.id = $userId and sp.password = '$storePassword' ";
				$result = $this->Store->query($query);

				if (!empty($result)) {
					$storeAccess = true;
					$this->Session->write('storeAccess.isAdmin', false);
					foreach ($result as $row) {
						$stores[$row['s']['id']] = $row['s']['id'];
					}
				}
			}

			if ($storeAccess) {
				$this->Session->write('storeAccess.allowed', true);
				$this->Session->write('storeAccess.list', $stores);
				$this->redirect('/stores/');
			} else {
				$this->errorMsg('You are not authorized to access any store(s).');
			}
		}
	}

	public function index()
	{
		// check if users have store access
		$featureStoreAccessPasswordsEnabled = $this->isFeatureStoreAccessPasswordsEnabled();
		$storeAccess = false;
		$isStoreAdmin = false; // the logged in user is store admin/owner
		$storeAccessList = []; // list of accessible store for a user
		if ($featureStoreAccessPasswordsEnabled) {
			if ($this->Session->check('storeAccess')) {
				if ($this->Session->read('storeAccess.allowed')) {
					$storeAccess = true;
				}
				$isStoreAdmin = $this->Session->read('storeAccess.isAdmin');
				$storeAccessList = $this->Session->read('storeAccess.list');
			}

			if (!$storeAccess) {
				$this->redirect('/stores/access');
			}
		}

		$this->Session->delete('Store');

		$conditions = null;

		if ($this->Session->read('manager') != '1') {
			$conditions = ['Store.user_id' => $this->Auth->user('id')];
		}

		$storesTmp = $this->Store->find('all', ['conditions' => $conditions, 'order' => ['Store.expiry_date desc']]);
		$stores = $storesTmp;
		if ($featureStoreAccessPasswordsEnabled && !$isStoreAdmin) {
			foreach ($storesTmp as $index => $row) {
				if (!in_array($row['Store']['id'], $storeAccessList)) {
					unset($stores[$index]);
				}
			}
		}

		App::uses('User', 'Model');
		$this->User = new User();
		$userInfo = $this->User->find('list');

		$this->set('stores', $stores);
		$this->set('userInfo', $userInfo);
	}

	public function isFeatureStoreAccessPasswordsEnabled()
	{
		// check if users have store access
		$featureStoreAccessPasswordsEnabled = false;

		if ($this->Session->read('manager') != '1') {
			$featureStoreAccessPasswordsEnabled = (int)$this->Auth->user('feature_store_access_passwords');
		}

		return $featureStoreAccessPasswordsEnabled;
	}

	public function edit($storeID = null)
	{
		$errorMsg = null;

		if (!($storeInfo = $this->getStoreInfo($storeID))) {
			$this->Session->setFlash('Store not found', 'default', ['class' => 'alert alert-danger']);
			$this->redirect('/stores/');
		}

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Store']['name'])) {
				if ($data['Store']['name'] = trim($data['Store']['name'])) {

					$conditions = ['Store.name' => $data['Store']['name'], 'Store.user_id' => $storeInfo['Store']['user_id'], 'Store.id <>' => $storeID];
					if ($this->Store->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Store']['name'] . "'" . ' already exists';
					} else {
						$data['Store']['id'] = $storeID;

						if ($this->Session->read('manager') != '1') {
							$data['Store']['user_id'] = $this->Auth->user('id');
						}

						if ($this->Store->save($data)) {
							$msg = 'Store updated successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'alert alert-success']);
							$this->redirect('/stores/');
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				} else {
					$errorMsg = 'Enter Store Name';
				}
			}
		} else {
			$this->data = $storeInfo;
		}

		App::uses('User', 'Model');
		$this->User = new User();
		$userInfo = $this->User->find('list');

		($errorMsg) ? $this->Session->setFlash($errorMsg, 'default', ['class' => 'alert alert-danger']) : null;

		$this->set(compact('userInfo', 'storeInfo'));
	}

	public function getStoreInfo($storeID = null)
	{
		if (!$storeID) {
			return [];
		}
		$conditions = null;
		if ($this->Session->read('manager') != '1') {
			$conditions = ['Store.id' => $storeID, 'Store.user_id' => $this->Auth->user('id')];
		} else {
			$conditions = ['Store.id' => $storeID];
		}
		return $this->Store->find('first', ['conditions' => $conditions]);
	}

	public function delete($storeID = null)
	{
		if ($this->request->isPost()) {
			if (!($storeInfo = $this->getStoreInfo($storeID))) {
				$this->Session->setFlash('Store not found', 'default', ['class' => 'alert alert-danger']);
			} else {
				$this->Store->query("delete from cashbook where store_id='$storeID'");    // remove records from cashbook table
				$this->Store->query("delete from categories where store_id='$storeID'");    // remove records from categories table
				$this->Store->query("delete from employees where store_id='$storeID'");    // remove records from employees table
				$this->Store->query("delete from invoices where store_id='$storeID'");    // remove records from invoices table
				$this->Store->query("delete from product_categories where store_id='$storeID'");    // remove records from product_categories table
				$this->Store->query("delete from products where store_id='$storeID'");    // remove records from products table
				$this->Store->query("delete from purchases where store_id='$storeID'");    // remove records from purchases table
				$this->Store->query("delete from breakages where store_id='$storeID'");    // remove records from breakages table
				$this->Store->query("delete from salaries where store_id='$storeID'");    // remove records from salaries table
				$this->Store->query("delete from sales where store_id='$storeID'");    // remove records from sales table
				$this->Store->query("delete from suppliers where store_id='$storeID'");    // remove records from suppliers table
				$this->Store->query("delete from counter_balance_sheets where store_id='$storeID'");    // remove records from counter_balance_sheets table
				$this->Store->query("delete from tags where store_id='$storeID'");    // remove records from tags  table
				$this->Store->query("delete from transaction_logs where store_id='$storeID'");    // remove records from transaction_logs table
				$this->Store->query("delete from dealers where store_id='$storeID'");    // remove records from dealers table
				$this->Store->query("delete from brands where store_id='$storeID'");    // remove records from brands table
				$this->Store->query("delete from counter_balance_sheets where store_id='$storeID'");    // remove records from counter_balance_sheets table

				$this->Store->query("delete from stores where id='$storeID'");    // remove records from stores table

				$this->Session->setFlash('Store "' . $storeInfo['Store']['name'] . '" has been removed', 'default', ['class' => 'alert alert-success']);
			}
		} else {
			$this->Session->setFlash('Unauthorized access', 'default', ['class' => 'alert alert-danger']);
		}
		$this->redirect(['action' => 'index']);
	}

	public function selectStore($storeID = null)
	{
		if (!($storeInfo = $this->getStoreInfo($storeID))) {
			$this->Session->setFlash('Store not found', 'default', ['class' => 'alert alert-danger']);
			$this->redirect('/stores/');
		}

		$this->Session->write('Store', $storeInfo['Store']);
		$store_name = strtolower($storeInfo['Store']['name']);
		// reset store data when user is logged out and logged into test store again.
		if ($store_name == 'test') {
			if (!$this->Session->check('test_store_in_progress')) {
				// delete store data
				$this->Store->query("delete from cashbook where store_id='$storeID'");    // remove records from cashbook table
				$this->Store->query("delete from categories where store_id='$storeID'");    // remove records from categories table
				$this->Store->query("delete from employees where store_id='$storeID'");    // remove records from employees table
				$this->Store->query("delete from invoices where store_id='$storeID'");    // remove records from invoices table
				$this->Store->query("delete from product_categories where store_id='$storeID'");    // remove records from product_categories table
				$this->Store->query("delete from products where store_id='$storeID'");    // remove records from products table
				$this->Store->query("delete from purchases where store_id='$storeID'");    // remove records from purchases table
				$this->Store->query("delete from salaries where store_id='$storeID'");    // remove records from salaries table
				$this->Store->query("delete from sales where store_id='$storeID'");    // remove records from sales table
				$this->Store->query("delete from suppliers where store_id='$storeID'");    // remove records from suppliers table
				$this->Store->query("delete from counter_balance_sheets where store_id='$storeID'");    // remove records from counter_balance_sheets table
				$this->Store->query("delete from tags where store_id='$storeID'");    // remove records from tags  table
				$this->Store->query("delete from transaction_logs where store_id='$storeID'");    // remove records from transaction_logs table
				$this->Store->query("delete from dealers where store_id='$storeID'");    // remove records from dealers table
				$this->Store->query("delete from brands where store_id='$storeID'");    // remove records from brands table
//				$this->Store->query("delete from brand_products where store_id='$storeID'");	// remove records from brand_products table
				$this->Store->query("delete from counter_balance_sheets where store_id='$storeID'");    // remove records from counter_balance_sheets table

				$this->Session->setFlash('Test data has been removed', 'default', ['class' => 'alert alert-success']);

				$this->Session->write('test_store_in_progress', true);
			}


		}

		$this->redirect(['action' => 'home']);
		// $this->redirect('/product_categories/');
	}

	public function home()
	{
        if (!$this->Session->check('Store.id')) {
            $this->redirect('/stores');
        }
	}

	public function createbackup()
	{
		App::uses('ConnectionManager', 'Model');
		$dataSource = ConnectionManager::enumConnectionObjects();

		$dbhost = 'localhost:3036';
		$dbuser = $dataSource['default']['login'];
		$dbpass = $dataSource['default']['password'];
		$dbname = $dataSource['default']['database'];

		$backup_file_path = Configure::read('Access.db_backup_file_path');
		$backup_filename = $dbname . '_' . date("d-m-Y_H-i-s") . '.sql';
		$file = $backup_file_path . $backup_filename;

		$mysqldump_path = Configure::read('Access.mysqldump_path');

		$command = $mysqldump_path . DS . "mysqldump -h localhost -u $dbuser --database $dbname > $file";
		system($command);
		$this->successMsg('Backup successfully created. Backup file path: ' . $file);
		$this->redirect(['action' => 'dbbackuplist']);
		exit;
	}

	public function dbbackuplist()
	{
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');

		$backup_file_path = Configure::read('Access.db_backup_file_path');
		$dir = new Folder($backup_file_path);
		$files = $dir->find('.*\.sql');
		$this->set('files', $files);
	}

	public function downloadfile($file)
	{
		$this->viewClass = 'Media';

		$filenameArray = explode('.', $file);
		$filename = $filenameArray[0];

		$backup_file_path = Configure::read('Access.db_backup_file_path');
		$params = [
			'id' => $file,
			'name' => $filename,
			'download' => true,
			'extension' => 'sql',
			'path' => $backup_file_path,
		];
		$this->set($params);
	}

	public function downloadProductListTemplate()
	{
		Configure::write('debug', 0);
		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '1024M');

		$fileName = 'ProductListTemplate-' . time() . '.csv';
		$this->layout = 'csv';

		$this->response->compress();
		$this->response->type('csv');
		$this->response->download($fileName);

		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();

		$conditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
		$this->ProductCategory->bindModel(['hasMany' => ['Product' => ['order' => 'Product.name']]]);
		$storeProducts = $this->ProductCategory->find('all', ['conditions' => $conditions, 'order' => 'ProductCategory.name']);
		$this->set(compact('storeProducts'));
	}

	public function downloadClosingStockTemplate()
	{
		Configure::write('debug', 0);
		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '1024M');

		$fileName = 'ClosingStockTemplate-' . time() . '.csv';
		$this->layout = 'csv';

		$this->response->compress();
		$this->response->type('csv');
		$this->response->download($fileName);

		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();

		$conditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
		$this->ProductCategory->bindModel(['hasMany' => ['Product' => ['order' => 'Product.name']]]);
		$storeProducts = $this->ProductCategory->find('all', ['conditions' => $conditions, 'order' => 'ProductCategory.name']);
		$this->set(compact('storeProducts'));
	}

	public function showbrandsinproducts($storeID = 0)
	{
		if ($storeID) {
			$this->updateFields($storeID, 'show_brands_in_products', 1);
		}
		$this->redirect('/stores/');
	}

	private function updateFields($storeID, $field, $value)
	{
		$data['Store']['id'] = $storeID;
		$data['Store'][$field] = $value;

		if ($this->Store->save($data)) {
			$msg = 'Store updated successfully';
			$this->Session->setFlash($msg, 'default', ['class' => 'alert alert-success']);

		} else {
			$msg = 'An error occured while communicating with the server';
			$this->Session->setFlash($msg, 'default', ['class' => 'alert alert-danger']);
		}
		$this->redirect('/stores/');
	}

	public function hidebrandsinproducts($storeID = 0)
	{
		if ($storeID) {
			$this->updateFields($storeID, 'show_brands_in_products', 0);
		}
		$this->redirect('/stores/');
	}

	public function showbrandsinreports($storeID = 0)
	{
		if ($storeID) {
			$this->updateFields($storeID, 'show_brands_in_reports', 1);
		}
		$this->redirect('/stores/');
	}

	public function hidebrandsinreports($storeID = 0)
	{
		if ($storeID) {
			$this->updateFields($storeID, 'show_brands_in_reports', 0);
		}
		$this->redirect('/stores/');
	}

	public function storeAccess()
	{
		if (!$this->isFeatureStoreAccessPasswordsEnabled()) {
			$this->redirect('/stores/');
		}

		if ($this->request->data) {

			$storeId = $this->request->data['StorePassword']['store_id'];
			$storePassword = $this->request->data['StorePassword']['pin'];
			$storePasswordId = $this->request->data['StorePassword']['id'];

			if ($storeId) {
				$data['StorePassword']['id'] = $storePasswordId;
				$data['StorePassword']['store_id'] = $storeId;
				$data['StorePassword']['password'] = $storePassword;
				App::uses('StorePassword', 'Model');
				$this->StorePassword = new StorePassword();
				if ($this->StorePassword->save($data)) {
					$this->successMsg('Store access password saved successfully');
				} else {
					$this->errorMsg('An error occurred. Please try again.');
				}
			}
			$this->redirect('/stores/storeAccess');
		}

		if ($this->Session->read('storeAccess.isAdmin')) {
			$userId = $this->Auth->user('id');
			$whereCondition = " where u.id = $userId ";

			if ($this->Session->read('manager') == '1') {
				$whereCondition = "";
			}

			$query = "select s.name, s.id as store_id, sp.id, sp.password, u.id, u.email, u.name, u.store_password
                    from stores s
                        left join users u on u.id = s.user_id
                        left join store_passwords sp on sp.store_id = s.id
                    $whereCondition
                    order by u.email, s.name";

			$result = $this->Store->query($query);

			// get stores list
			$conditions = ['Store.user_id' => $userId];
			if ($this->Session->read('manager') == '1') {
				$conditions = [];
			}
			$storeKeyValuePair = $this->Store->find('list', ['conditions' => $conditions]);


			$this->set('result', $result);
			$this->set('storeKeyValuePair', $storeKeyValuePair);

		} else {
			$this->errorMsg('You are not authorized to view this page');
			$this->redirect('/stores/');
		}
	}

	public function deleteAcccess()
	{
		if (!$this->isFeatureStoreAccessPasswordsEnabled()) {
			$this->redirect('/stores/');
		}

		if ($this->request->data) {
			$storePasswordId = $this->request->data['StorePassword']['id'];

			if ($storePasswordId) {
				App::uses('StorePassword', 'Model');
				$this->StorePassword = new StorePassword();
				if ($this->StorePassword->delete($storePasswordId)) {
					$this->successMsg('Store access password deleted successfully');
				} else {
					$this->errorMsg('An error occurred. Please try again.');
				}
			} else {
				$this->errorMsg('An error occurred. Please try again.');
			}
		} else {
			$this->errorMsg('Unauthorized access.');
		}

		$this->redirect('/stores/storeAccess');
	}

}
