<?php

/**
 * Class StoresController
 */
class StoresController extends AppController
{

    public const STORE_SETTINGS = [
        'hasFranchise' => [
            'type' => 'checkbox',
            'default' => false,
            'name' => 'Enable franchise',
            'description' => 'If enabled, franchise feature will be displayed',
        ],
        'showBrandsInProducts' => [
            'type' => 'checkbox',
            'default' => false,
            'name' => 'Show brands in products',
            'description' => 'If enabled, brand information will be shown along with product information',
        ],
        'showBrandsInReports' => [
            'type' => 'checkbox',
            'default' => false,
            'name' => 'Show brand in reports',
            'description' => 'If enabled, brand information will be shown along with product information in reports',
        ],
    ];

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
                            $this->successMsg($msg);
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

        ($errorMsg) ? $this->errorMsg($errorMsg) : null;

        $this->set(compact('userInfo', 'storeInfo'));
    }

    public function index()
    {
        $this->Session->delete('Store');
        $this->Session->delete('Message');

        $conditions = null;
        if ($this->Session->read('manager') != '1') {
            $conditions = ['Store.user_id' => $this->Auth->user('id')];
        }
        $stores = $this->Store->find('all', ['conditions' => $conditions, 'order' => ['Store.created ASC']]);

        App::uses('User', 'Model');
        $this->User = new User();
        $userInfo = $this->User->find('list');

        $this->set('stores', $stores);
        $this->set('userInfo', $userInfo);
    }

    public function edit($storeID = null)
    {
        $errorMsg = null;

        if (!($storeInfo = $this->getStoreInfo($storeID))) {
            $this->errorMsg('Store not found');
            $this->redirect('/stores/');
        }

        if ($this->request->data) {
            $data = $this->request->data;
            if (isset($data['Store']['name'])) {
                if ($data['Store']['name'] = trim($data['Store']['name'])) {
                    $conditions = [
                        'Store.name' => $data['Store']['name'],
                        'Store.user_id' => $storeInfo['Store']['user_id'],
                        'Store.id <>' => $storeID,
                    ];
                    if ($this->Store->find('first', ['conditions' => $conditions])) {
                        $errorMsg = "'" . $data['Store']['name'] . "'" . ' already exists';
                    } else {
                        $data['Store']['id'] = $storeID;

                        if ($this->Session->read('manager') != '1') {
                            $data['Store']['user_id'] = $this->Auth->user('id');
                        }

                        if ($this->Store->save($data)) {
                            $msg = 'Store updated successfully';
                            $this->successMsg($msg);
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

        ($errorMsg) ? $this->errorMsg($errorMsg) : null;

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

        $data = $this->Store->find('first', ['conditions' => $conditions, 'recursive' => 2]);

        App::uses('StoreSetting', 'Model');
        $this->StoreSetting = new StoreSetting();
        $settings = $this->StoreSetting->findAllByStoreId($storeID);
        $settingsList = [];

        if ($settings) {
            foreach ($settings as $row) {
                $settingsList[$row['StoreSetting']['key']] = $row['StoreSetting']['value'];
            }
        }
        $data['StoreSetting'] = $settingsList;

        return $data;
    }

    public function delete($storeID = null)
    {
        if ($this->request->isPost()) {
            if (!($storeInfo = $this->getStoreInfo($storeID))) {
                $this->errorMsg('Store not found');
            } else {
                $this->Store->query(
                    "delete from cashbook where store_id='$storeID'"
                );    // remove records from cashbook table
                $this->Store->query(
                    "delete from categories where store_id='$storeID'"
                );    // remove records from categories table
                $this->Store->query(
                    "delete from sales where store_id='$storeID'"
                );    // remove records from sales table
                $this->Store->query(
                    "delete from purchases where store_id='$storeID'"
                );    // remove records from purchases table
                $this->Store->query(
                    "delete from breakages where store_id='$storeID'"
                );    // remove records from breakages table

                $this->Store->query(
                    "delete from employees where store_id='$storeID'"
                );    // remove records from employees table
                $this->Store->query(
                    "delete from salaries where store_id='$storeID'"
                );    // remove records from salaries table
                $this->Store->query(
                    "delete from suppliers where store_id='$storeID'"
                );    // remove records from suppliers table
                $this->Store->query("delete from tags where store_id='$storeID'");    // remove records from tags  table
                $this->Store->query(
                    "delete from transaction_logs where store_id='$storeID'"
                );    // remove records from transaction_logs table
                $this->Store->query(
                    "delete from counter_balance_sheets where store_id='$storeID'"
                );    // remove records from counter_balance_sheets table


                $this->Store->query(
                    "delete from products where store_id='$storeID'"
                );    // remove records from products table
                $this->Store->query(
                    "delete from product_categories where store_id='$storeID'"
                );    // remove records from product_categories table
                $this->Store->query(
                    "delete from brands where store_id='$storeID'"
                );    // remove records from brands table
                $this->Store->query(
                    "delete from dealers where store_id='$storeID'"
                );    // remove records from dealers table
                $this->Store->query(
                    "delete from invoices where store_id='$storeID'"
                );    // remove records from invoices table

                $this->Store->query("delete from stores where id='$storeID'");    // remove records from stores table

                // todo: implement delete for the following tables
                // delete franchises
                // delete store_settings
                // quotations
                // entity_items
                // banks

                $this->successMsg('Store "' . $storeInfo['Store']['name'] . '" has been removed');
            }
        } else {
            $this->errorMsg('Unauthorized access');
        }
        $this->redirect(['action' => 'index']);
    }

    public function selectStore($storeID = null)
    {
        if (!($storeInfo = $this->getStoreInfo($storeID))) {
            $this->errorMsg('Store not found');
            $this->redirect('/stores/');
        }
        $this->Session->write('Store', $storeInfo['Store']);
        $this->Session->write('StoreSetting', $storeInfo['StoreSetting']);
//
//        $labels = [];
//        if (!empty($storeInfo['StoreLabel'])) {
//            foreach ($storeInfo['StoreLabel'] as $row) {
//                $labels[$row['key']] = $row['value'];
//            }
//        }
//        $this->Session->write('StoreLabel', $labels);

        $store_name = strtolower($storeInfo['Store']['name']);
        // reset store data when user is logged out and logged into test store again.
        if ($store_name == 'test') {
            if (!$this->Session->check('test_store_in_progress')) {
                // delete store data
                $this->Store->query(
                    "delete from cashbook where store_id='$storeID'"
                );    // remove records from cashbook table
                $this->Store->query(
                    "delete from categories where store_id='$storeID'"
                );    // remove records from categories table
                $this->Store->query(
                    "delete from employees where store_id='$storeID'"
                );    // remove records from employees table
                $this->Store->query(
                    "delete from invoices where store_id='$storeID'"
                );    // remove records from invoices table
                $this->Store->query(
                    "delete from product_categories where store_id='$storeID'"
                );    // remove records from product_categories table
                $this->Store->query(
                    "delete from products where store_id='$storeID'"
                );    // remove records from products table
                $this->Store->query(
                    "delete from purchases where store_id='$storeID'"
                );    // remove records from purchases table
                $this->Store->query(
                    "delete from salaries where store_id='$storeID'"
                );    // remove records from salaries table
                $this->Store->query(
                    "delete from sales where store_id='$storeID'"
                );    // remove records from sales table
                $this->Store->query(
                    "delete from suppliers where store_id='$storeID'"
                );    // remove records from suppliers table
                $this->Store->query(
                    "delete from counter_balance_sheets where store_id='$storeID'"
                );    // remove records from counter_balance_sheets table
                $this->Store->query("delete from tags where store_id='$storeID'");    // remove records from tags  table
                $this->Store->query(
                    "delete from transaction_logs where store_id='$storeID'"
                );    // remove records from transaction_logs table
                $this->Store->query(
                    "delete from dealers where store_id='$storeID'"
                );    // remove records from dealers table
                $this->Store->query(
                    "delete from brands where store_id='$storeID'"
                );    // remove records from brands table
//				$this->Store->query("delete from brand_products where store_id='$storeID'");	// remove records from brand_products table
                $this->Store->query(
                    "delete from counter_balance_sheets where store_id='$storeID'"
                );    // remove records from counter_balance_sheets table

                $this->successMsg('Test data has been removed');

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
        $storeProducts = $this->ProductCategory->find(
            'all',
            ['conditions' => $conditions, 'order' => 'ProductCategory.name']
        );
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
        $storeProducts = $this->ProductCategory->find(
            'all',
            ['conditions' => $conditions, 'order' => 'ProductCategory.name']
        );
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
            $this->successMsg($msg);
        } else {
            $msg = 'An error occured while communicating with the server';
            $this->errorMsg($msg);
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

    public function settings($storeId)
    {
        if (!($storeInfo = $this->getStoreInfo($storeId))) {
            $this->errorMsg('Store not found');
            $this->redirect('/stores/');
        }
        // $this->Session->write('Store', $storeInfo['Store']);

        App::uses('StoreSetting', 'Model');
        $this->StoreSetting = new StoreSetting();

        if ($this->request->is('post') or $this->request->is('put')) {
            $data = $this->request->data;

            if ($data) {
                foreach ($data as $row) {
                    $row['StoreSetting']['store_id'] = $storeId;
                    $tmpData = $row;
                    $this->StoreSetting->save($tmpData);
                }
                $this->successMsg('Settings updated successfully');
                $this->redirect('/stores/settings/' . $storeId);
            }
        }

        $storeSettings = $this->StoreSetting->findAllByStoreId($storeId);
        $this->set('storeFields', self::STORE_SETTINGS);
        $this->set(compact('storeSettings', 'storeInfo'));
    }

}
