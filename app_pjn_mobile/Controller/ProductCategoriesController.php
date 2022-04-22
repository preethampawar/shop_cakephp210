<?php

class ProductCategoriesController extends AppController
{

	public $name = 'ProductCategories';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	/**
	 * Function to show list of categories
	 */
	public function index($categoryID = null, $showAll = false)
	{
		$hideSideBar = true;
		$products = [];
		$conditions = [
			'ProductCategory.store_id' => $this->Session->read('Store.id'),
		];

		$fields = ['ProductCategory.id', 'ProductCategory.name', 'ProductCategory.created'];
		if ($categoryID) {
			$conditions['ProductCategory.id'] = $categoryID;
			$this->ProductCategory->bindModel([
				'hasMany' => [
					'Product' => [
						'class' => 'Product',
						'fields' => [
							'Product.id',
							'Product.name',
							'Product.box_qty',
							'Product.box_buying_price',
							'Product.unit_selling_price',
							'Product.special_margin',
							'Product.created',
							'Product.brand_id',
						],
					],
				],
			]);

			$products = $this->ProductCategory->find('all', [
				'fields' => $fields,
				'order' => ['ProductCategory.name' => 'ASC'],
				'conditions' => $conditions,
				'recursive' => '1',
			]);
		}


		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		if ($categoryID) {
			$conditions['Product.product_category_id'] = $categoryID;
		}

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$productsCount = $this->Product->find('count', [
			'conditions' => $conditions,
			'recursive' => '-1',
		]);


		$categories = $this->ProductCategory->find('all', [
			'fields' => $fields,
			'order' => ['ProductCategory.name' => 'ASC'],
			'conditions' => ['ProductCategory.store_id' => $this->Session->read('Store.id')],
			'recursive' => '0',
		]);

		$category = $this->ProductCategory->findById($categoryID);

		App::uses('Brand', 'Model');
		$this->Brand = new Brand();
		$brands = $this->Brand->find('list', ['conditions' => ['Brand.store_id' => $this->Session->read('Store.id')]]);

		$this->set(compact('products', 'categories', 'hideSideBar', 'brands', 'categoryID', 'category', 'productsCount'));
	}

	public function add()
	{
		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			App::uses('Validation', 'Utility');

			$data['ProductCategory'] = $this->request->data['ProductCategory'];
			$data['ProductCategory']['name'] = trim($data['ProductCategory']['name']);

			if (!empty($data['ProductCategory']['name'])) {
				if (!Validation::between($data['ProductCategory']['name'], 2, 100)) {
					$error = 'Category name should be between 2 and 100 characters';
				}
				$data['ProductCategory']['name'] = htmlentities($data['ProductCategory']['name'], ENT_QUOTES);

				//find if a similar category exists for the selected store
				$conditions = ['ProductCategory.name' => $data['ProductCategory']['name'], 'ProductCategory.store_id' => $this->Session->read('Store.id')];
				if ($this->ProductCategory->find('first', ['conditions' => $conditions])) {
					$error = 'Category ' . $data['ProductCategory']['name'] . ' already exists';
				}
			} else {
				$error = 'Category name cannot be empty';
			}

			if (!$error) {
				$data['ProductCategory']['id'] = null;
				$data['ProductCategory']['store_id'] = $this->Session->read('Store.id');
				if ($this->ProductCategory->save($data)) {
					$this->Session->setFlash('Category Created Successfully', 'default', ['class' => 'success']);
				} else {
					$error = 'An error occurred while creating a new category';
				}
			}
		}
		if ($error) {
			$this->Session->setFlash($error, 'default', ['class' => 'error']);
		}
		$this->redirect('/product_categories/');
	}

	public function edit($productCategoryID = null)
	{
		$hideSideBar = true;
		if (!$pCatInfo = $this->getProductCategoryInfo($productCategoryID)) {
			$this->Session->setFlash('Category not found.', 'default', ['class' => 'error']);
			$this->redirect('/product_categories/');
		}

		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			App::uses('Validation', 'Utility');

			$data['ProductCategory'] = $this->request->data['ProductCategory'];
			$data['ProductCategory']['name'] = trim($data['ProductCategory']['name']);

			if (!empty($data['ProductCategory']['name'])) {
				if (!Validation::between($data['ProductCategory']['name'], 2, 100)) {
					$error = 'Category name should be between 2 and 100 characters';
				}
				$data['ProductCategory']['name'] = htmlentities($data['ProductCategory']['name'], ENT_QUOTES);

				//find if a similar category exists for the selected store
				$conditions = ['ProductCategory.name' => $data['ProductCategory']['name'], 'ProductCategory.store_id' => $this->Session->read('Store.id'), 'ProductCategory.id <>' => $productCategoryID];
				if ($this->ProductCategory->find('first', ['conditions' => $conditions])) {
					$error = 'Category ' . $data['ProductCategory']['name'] . ' already exists';
				}
			} else {
				$error = 'Category name cannot be empty';
			}

			if (!$error) {
				$data['ProductCategory']['id'] = $productCategoryID;
				$data['ProductCategory']['store_id'] = $this->Session->read('Store.id');
				if ($this->ProductCategory->save($data)) {
					$this->Session->setFlash('Category Updated Successfully', 'default', ['class' => 'success']);
					$this->redirect('/product_categories/');
				} else {
					$error = 'An error occurred while creating a new category';
				}
			}
		} else {
			$this->data = $pCatInfo;
		}

		$this->set('pCatInfo', $pCatInfo);
		$this->set('hideSideBar', $hideSideBar);
		if ($error) {
			$this->Session->setFlash($error, 'default', ['class' => 'error']);
		}
	}

	public function getProductCategoryInfo($productCategoryID = null)
	{
		if (!$productCategoryID) {
			return [];
		} else {
			$conditions = ['ProductCategory.id' => $productCategoryID, 'ProductCategory.store_id' => $this->Session->read('Store.id')];
			if ($productCategoryInfo = $this->ProductCategory->find('first', ['conditions' => $conditions])) {
				return $productCategoryInfo;
			}
		}
		return [];
	}

	public function remove($categoryID = null)
	{
		if ($this->CommonFunctions->removeProductCategory($categoryID)) {
			$this->Session->setFlash('Category removed successfully', 'default', ['class' => 'success']);
		} else {
			$this->Session->setFlash('Category not found', 'default', ['class' => 'error']);
		}
		$this->redirect($this->request->referer());
	}

	public function delete($categoryID = null)
	{
		if ($this->CommonFunctions->deleteProductCategory($categoryID)) {
			$this->Session->setFlash('Category deleted successfully', 'default', ['class' => 'success']);
		} else {
			$this->Session->setFlash('Category not found', 'default', ['class' => 'error']);
		}
		$this->redirect('/product_categories/');
	}

	public function uploadCsv(): void
	{
		$hideSideBar = true;
		$brandsCount = 0;
		$brandsAdded = 0;
		$brandsUpdated = 0;
		$productsCount = 0;
		$productsAdded = 0;
		$productsUpdated = 0;
		$categoriesCount = 0;
		$categoriesAdded = 0;
		$categoriesUpdated = 0;
		$response = null;

		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '2048M');

		$this->set('beforeUpload', true);

		if ($this->request->isPost()) {

			$data = $this->request->data;

			if (isset($data['ProductCategory']['csv']['error']) and (!$data['ProductCategory']['csv']['error'])) {
				$this->set('beforeUpload', false);
				$mimes = ['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'application/octet-stream'];

				if (in_array($data['ProductCategory']['csv']['type'], $mimes, true)) {
					$fileSize = $data['ProductCategory']['csv']['size'];

					if ($fileSize > 0) {
						$maxSize = 5;

						if (ceil($fileSize / (1024 * 1024)) > $maxSize) {
							$this->errorMsg('File size exceeds ' . $maxSize . 'Mb limit');
						} else {
							// valid file
							$response = $this->checkValidCsvData($data);

							if ($response['error']) {
								$this->errorMsg($response['msg']);
							} else {
								$response = $this->updateCsvData1($response['fileData']);
								if ($response['error']) {
									$this->errorMsg($response['errorMsg']);
								} else {
									$this->successMsg('File processed successfully');
								}
							}
						}
					} else {
						$this->errorMsg('Invalid File Size');
					}
				} else {
					$this->errorMsg('Invalid CSV File');
				}
			} else {
				$this->errorMsg('Unknown File Type');
			}
		}

		$this->set(compact('hideSideBar', 'productsCount', 'productsAdded', 'productsUpdated', 'categoriesCount', 'categoriesAdded', 'categoriesUpdated', 'response'));
	}

	private function checkValidCsvData($fileInfo)
	{
		App::uses('Validation', 'Utility');
		$response = ['success' => false, 'error' => false, 'msg' => '', 'fileData' => []];

		$file = '"' . $fileInfo['ProductCategory']['csv']['name'] . '"';
		$handle = fopen($fileInfo['ProductCategory']['csv']['tmp_name'], 'r');
		$fileData = [];

		$i = 1;
		while (($data = fgetcsv($handle)) !== false) {
			//process
			// debug($data);
			if (!empty($data)) {
				// validate number of columns
				if (count($data) !== 6) {
					$response['error'] = true;
					$response['msg'] = $file . '. File should have 6 columns. (Order of columns: CategoryName, BrandName, ProductName, BoxPrice, BoxQuantity, UnitPrice)';
				} else {
					$dataHeader = ['CategoryName', 'BrandName', 'ProductName', 'BoxPrice', 'BoxQuantity', 'UnitPrice'];
					if ($dataHeader != $data) {
						// validate column data type

						// category name validation
						if ((!Validation::notBlank($data[0])) or (!Validation::between($data[0], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Category name should be between 2 and 100 characters';
						}

						// brand name validation
						if ((!Validation::notBlank($data[1])) or (!Validation::between($data[1], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Brand name should be between 2 and 100 characters';
						}

						// product name validation
						//if((!Validation::notBlank($data[1])) OR !Validation::custom($data[1], "/^[a-zA-Z0-9][a-zA-Z0-9\s\-\._#()]{1,55}$/i")) {
						if (!Validation::notBlank($data[2]) or (!Validation::between($data[2], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Invalid Name (Or) Product name should be between 2 and 100 chars';
						}

						// product box price validation
						if ((Validation::notBlank($data[3])) AND (!Validation::decimal($data[3]) OR ($data[3] <= 0))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Box buying price should be greater than 0';
						}

						// product quantity in box
						if ((Validation::notBlank($data[4])) AND (!Validation::naturalNumber($data[4]))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Box quantity should be greater than 0. eg: 1,2,3,4,5,... etc';
						}

						// product unit price validation
						if ((Validation::notBlank($data[5])) AND (!Validation::decimal($data[5]) OR ($data[5] <= 0))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Unit price should be greater than 0';
						}

						if (!$response['error']) {
							$fileData[] = $data;
						}
					}
				}
			}

			if ($response['error'] == true) {
				break;
			}

			$i++;
		}
		$response['fileData'] = $fileData;

		return $response;
	}


	private function updateCsvData1($fileData)
	{
		$updatedRecords = 0;
		$error = false;
		$errorMsg = null;

		// get store product categories
		$pcConditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
		$storeCategories = $this->ProductCategory->find('list', ['conditions' => $pcConditions, 'recursive' => -1]);

		// get store brands
		App::uses('Brand', 'Model');
		$this->Brand = new Brand();
		$bConditions = ['Brand.store_id' => $this->Session->read('Store.id')];
		$storeBrands = $this->Brand->find('list', ['conditions' => $bConditions, 'recursive' => -1]);

		// get store products
		App::uses('Product', 'Model');
		$this->Product = new Product();
		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		$storeProducts = $this->Product->find('all', ['conditions' => $conditions, 'recursive' => -1]);

		if ($fileData) {
			$csvCategories = [];
			$csvBrands = [];

			foreach ($fileData as $row) {
				// get categories from csv file
				$csvCategories[$row[0]] = $row[0];

				// get brands from csv file
				$csvBrands[$row[1]] = $row[1];
			}

			$categoriesToBeCreated = [];
			$brandsToBeCreated = [];

			// find new categories to be created
			foreach ($csvCategories as $catName) {
				if (!in_array($catName, $storeCategories, true)) {
					$categoriesToBeCreated[] = $catName;
				}
			}

			// find new brands to be created
			foreach ($csvBrands as $brandName) {
				if (!in_array($brandName, $storeBrands, true)) {
					$brandsToBeCreated[] = $brandName;
				}
			}

			// create new categories
			if ($categoriesToBeCreated) {
				foreach ($categoriesToBeCreated as $catName) {
					$tmp = [];
					$tmp['ProductCategory']['id'] = null;
					$tmp['ProductCategory']['active'] = 1;
					$tmp['ProductCategory']['store_id'] = $this->Session->read('Store.id');
					$tmp['ProductCategory']['name'] = $catName;

					$this->ProductCategory->save($tmp);
				}
			}

			// create new brands
			if ($brandsToBeCreated) {
				foreach ($brandsToBeCreated as $brandName) {
					$tmp = [];
					$tmp['Brand']['id'] = null;
					$tmp['Brand']['store_id'] = $this->Session->read('Store.id');
					$tmp['Brand']['name'] = $brandName;

					$this->Brand->save($tmp);
				}
			}

			// get all categories and brands after update
			$storeCategories = $this->ProductCategory->find('list', ['conditions' => $pcConditions, 'recursive' => -1]);
			$storeBrands = $this->Brand->find('list', ['conditions' => $bConditions, 'recursive' => -1]);

			foreach ($fileData as $index => $row) {
				$tmp = [];
				$error = false;

				$tmp['Product']['id'] = null;
				if (in_array($row[2], $storeProducts, true)) {
					$tmp['Product']['id'] = array_search($row[2], $storeProducts, true);
				}

				$tmp['Product']['product_category_id'] = null;

				if (!empty($row[0]) && in_array($row[0], $storeCategories, true)) {
					$tmp['Product']['product_category_id'] = array_search($row[0], $storeCategories, true);
					$tmp['Product']['brand_id'] = null;
					if (!empty($row[1]) && in_array($row[1], $storeBrands, true)) {
						$tmp['Product']['brand_id'] = array_search($row[1], $storeBrands, true);
					}

					$tmp['Product']['name'] = htmlentities($row[2], ENT_QUOTES);
					$tmp['Product']['box_buying_price'] = $row[3];
					$tmp['Product']['box_qty'] = $row[4];
					$tmp['Product']['unit_selling_price'] = $row[5];
					$tmp['Product']['store_id'] = $this->Session->read('Store.id');

					$this->Product->save($tmp);

					$updatedRecords++;
				} else {
					$error = true;
					$errorMsg .= 'Invalid category "' . $row[0] . '" at line no. ' . ($index + 1) . '<br>';
				}
			}
		}

		return ['error' => $error, 'errorMsg' => $errorMsg, 'updatedRecords' => $updatedRecords];
	}

	public function downloadCsv()
	{
		Configure::write('debug', 0);

		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '1024M');

		$fileName = 'ProductList-' . time() . '.csv';
		$this->layout = 'csv';

		$this->response->compress();
		$this->response->type('csv');
		$this->response->download($fileName);

		$conditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
		$this->ProductCategory->bindModel(['hasMany' => ['Product' => ['order' => 'Product.name']]]);
		$storeProducts = $this->ProductCategory->find('all', ['conditions' => $conditions, 'order' => 'ProductCategory.name', 'recursive' => 2]);

		$this->set(compact('storeProducts'));
	}

	private function updateCsvData($fileData)
	{
		$response = ['success' => false, 'error' => false, 'msg' => '', 'info' => []];
		$errorMsg = [];
		if (!empty($fileData)) {

			$conditions = ['ProductCategory.store_id' => $this->Session->read('Store.id')];
			$this->ProductCategory->bindModel(['hasMany' => ['Product']]);
			$storeCategoryProducts = $this->ProductCategory->find('all', ['conditions' => $conditions, 'recursive' => 2]);

			$fileCategories = [];
			$fileProducts = [];
			foreach ($fileData as $row) {
				$fileCategoryProducts[$row[0]]['id'] = null;
				$fileCategoryProducts[$row[0]]['name'] = htmlentities($row[0], ENT_QUOTES);

				$fileCategoryProducts[$row[0]]['Products'][$row[1]]['id'] = null;
				$fileCategoryProducts[$row[0]]['Products'][$row[1]]['name'] = htmlentities($row[2], ENT_QUOTES);
				$fileCategoryProducts[$row[0]]['Products'][$row[1]]['box_buying_price'] = $row[3];
				$fileCategoryProducts[$row[0]]['Products'][$row[1]]['box_qty'] = $row[4];
				$fileCategoryProducts[$row[0]]['Products'][$row[1]]['unit_selling_price'] = $row[5];
				$fileCategoryProducts[$row[0]]['Products'][$row[1]]['store_id'] = $this->Session->read('Store.id');

			}

			// if store has similar categories or products then get their respective id's.
			foreach ($fileCategoryProducts as $index => $row) {
				$fileCategoryName = $row['name'];

				if (!empty($storeCategoryProducts)) {
					foreach ($storeCategoryProducts as $row2) {
						if ($row2['ProductCategory']['name'] == $fileCategoryName) {
							$fileCategoryProducts[$index]['id'] = $row2['ProductCategory']['id'];

							foreach ($row['Products'] as $index2 => $fileProduct) {
								// if store has products.
								if (!empty($row2['Product'])) {
									foreach ($row2['Product'] as $storeProduct) {
										// if store product == file product
										if ($storeProduct['name'] == $fileProduct['name']) {
											$fileCategoryProducts[$index]['Products'][$index2]['id'] = $storeProduct['id'];
											break;
										}
									}
								}
								$fileCategoryProducts[$index]['Products'][$index2]['product_category_id'] = $row2['ProductCategory']['id'];
							}
						}
					}
				}
			}

			App::uses('Product', 'Model');
			$this->Product = new Product();

			$categoryCount = 0;
			$newCategoryCount = 0;
			$oldCategoryCount = 0;

			$productCount = 0;
			$newProductCount = 0;
			$oldProductCount = 0;
			foreach ($fileCategoryProducts as $row) {

				// if category has id then add/update its products in database, else create a new category and assign its ID to its products and add in database.
				if ($row['id']) {
					$oldCategoryCount++;

					foreach ($row['Products'] as $product) {
						$productCount++;
						$tmp = [];
						$tmp['Product'] = $product;
						$tmp['Product']['product_category_id'] = $row['id'];
						if ($this->Product->save($tmp)) {
							($product['id']) ? $oldProductCount++ : $newProductCount++;
						} else {
							$errorMsg[] = 'An error occurred while adding/updating - Category: ' . $row['name'] . ', Product: ' . $product['name'];
						}
					}
				} else {
					$tmp = [];
					$tmp['ProductCategory']['id'] = null;
					$tmp['ProductCategory']['name'] = $row['name'];
					$tmp['ProductCategory']['store_id'] = $this->Session->read('Store.id');

					if ($this->ProductCategory->save($tmp)) {
						$newCategoryCount++;

						$categoryInfo = $this->ProductCategory->read();
						$categoryID = $categoryInfo['ProductCategory']['id'];

						foreach ($row['Products'] as $product) {
							$productCount++;
							$tmp = [];
							$tmp['Product'] = $product;
							$tmp['Product']['product_category_id'] = $categoryID;
							if ($this->Product->save($tmp)) {
								$newProductCount++;
							} else {
								$errorMsg[] = 'An error occurred while adding/updating - Category: ' . $row['name'] . ', Product: ' . $product['name'];
							}
						}
					} else {
						$errorMsg[] = 'An error occurred while adding Category: ' . $row['name'] . ' and all its products.';
					}
				}

				$categoryCount++;
			}

			if ($errorMsg) {
				$response['error'] = true;
				$response['msg'] = implode($errorMsg, '<br>');
			}

			$info['categoryCount'] = $categoryCount;
			$info['newCategoryCount'] = $newCategoryCount;
			$info['oldCategoryCount'] = $oldCategoryCount;
			$info['productCount'] = $productCount;
			$info['newProductCount'] = $newProductCount;
			$info['oldProductCount'] = $oldProductCount;
			$response['info'] = $info;
		}

		return $response;
	}

}

?>
