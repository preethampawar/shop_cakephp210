<?php

class ProductCategoriesController extends AppController
{

	var $name = 'ProductCategories';

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	public function getProductCategoryInfo($productCategoryID = null)
	{
		if (!$productCategoryID) {
			return array();
		} else {
			$conditions = array('ProductCategory.id' => $productCategoryID, 'ProductCategory.store_id' => $this->Session->read('Store.id'));
			if ($productCategoryInfo = $this->ProductCategory->find('first', array('conditions' => $conditions))) {
				return $productCategoryInfo;
			}
		}
		return array();
	}

	/**
	 * Function to show list of categories
	 */
	public function index($categoryID = null)
	{
		$hideSideBar = true;
		$conditions = array('ProductCategory.store_id' => $this->Session->read('Store.id'));
		if ($categoryID) {
			$conditions['ProductCategory.id'] = $categoryID;
		}

		$fields = array('ProductCategory.id', 'ProductCategory.name', 'ProductCategory.created');
		$this->ProductCategory->bindModel(array('hasMany' => array('Product' => array('class' => 'Product', 'fields' => array('Product.id', 'Product.name', 'Product.product_code', 'Product.box_qty', 'Product.box_buying_price', 'Product.unit_selling_price', 'Product.special_margin', 'Product.created', 'Product.brand_id')))));
		$products = $this->ProductCategory->find('all', array(
			'fields' => $fields,
			'order' => array('ProductCategory.name' => 'ASC'),
			'conditions' => $conditions,
			'recursive' => '1'
		));
		$categories = $this->ProductCategory->find('all', array(
			'fields' => $fields,
			'order' => array('ProductCategory.name' => 'ASC'),
			'conditions' => array('ProductCategory.store_id' => $this->Session->read('Store.id')),
			'recursive' => '0'
		));
		$category = $this->ProductCategory->findById($categoryID);
		App::uses('Brand', 'Model');
		$this->Brand = new Brand();
		$brands = $this->Brand->find('list', array('conditions' => array('Brand.store_id' => $this->Session->read('Store.id'))));

		$this->set(compact('products', 'categories', 'hideSideBar', 'brands', 'categoryID', 'category'));
	}

	function add()
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
				$conditions = array('ProductCategory.name' => $data['ProductCategory']['name'], 'ProductCategory.store_id' => $this->Session->read('Store.id'));
				if ($this->ProductCategory->find('first', array('conditions' => $conditions))) {
					$error = 'Category ' . $data['ProductCategory']['name'] . ' already exists';
				}
			} else {
				$error = 'Category name cannot be empty';
			}

			if (!$error) {
				$data['ProductCategory']['id'] = null;
				$data['ProductCategory']['store_id'] = $this->Session->read('Store.id');
				if ($this->ProductCategory->save($data)) {
					$this->Session->setFlash('Category Created Successfully', 'default', array('class' => 'success'));
				} else {
					$error = 'An error occurred while creating a new category';
				}
			}
		}
		if ($error) {
			$this->Session->setFlash($error, 'default', array('class' => 'error'));
		}
		$this->redirect('/product_categories/');
	}


	function edit($productCategoryID = null)
	{
		$hideSideBar = true;
		if (!$pCatInfo = $this->getProductCategoryInfo($productCategoryID)) {
			$this->Session->setFlash('Category not found.', 'default', array('class' => 'error'));
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
				$conditions = array('ProductCategory.name' => $data['ProductCategory']['name'], 'ProductCategory.store_id' => $this->Session->read('Store.id'), 'ProductCategory.id <>' => $productCategoryID);
				if ($this->ProductCategory->find('first', array('conditions' => $conditions))) {
					$error = 'Category ' . $data['ProductCategory']['name'] . ' already exists';
				}
			} else {
				$error = 'Category name cannot be empty';
			}

			if (!$error) {
				$data['ProductCategory']['id'] = $productCategoryID;
				$data['ProductCategory']['store_id'] = $this->Session->read('Store.id');
				if ($this->ProductCategory->save($data)) {
					$this->Session->setFlash('Category Updated Successfully', 'default', array('class' => 'success'));
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
			$this->Session->setFlash($error, 'default', array('class' => 'error'));
		}
	}

	function remove($categoryID = null)
	{
		if ($this->CommonFunctions->removeProductCategory($categoryID)) {
			$this->Session->setFlash('Category removed successfully', 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash('Category not found', 'default', array('class' => 'error'));
		}
		$this->redirect($this->request->referer());
	}

	function delete($categoryID = null)
	{
		if ($this->CommonFunctions->deleteProductCategory($categoryID)) {
			$this->Session->setFlash('Category deleted successfully', 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash('Category not found', 'default', array('class' => 'error'));
		}
		$this->redirect('/product_categories/');
	}

	function uploadCsv()
	{
		$hideSideBar = true;
		$productsCount = 0;
		$productsAdded = 0;
		$productsUpdated = 0;
		$categoriesCount = 0;
		$categoriesAdded = 0;
		$categoriesUpdated = 0;
		$brandsCount = 0;
		$brandsUpdated = 0;
		$brandsAdded = 0;
		$response = null;

		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '2048M');


		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (isset($data['ProductCategory']['csv']['error']) and (!$data['ProductCategory']['csv']['error'])) {
				$mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'application/octet-stream');
				if (in_array($data['ProductCategory']['csv']['type'], $mimes)) {
					$fileSize = $data['ProductCategory']['csv']['size'];
					if ($fileSize > 0) {
						$maxSize = 4;
						if (ceil($fileSize / (1024 * 1024)) > $maxSize) {
							$this->Session->setFlash('File size exceeds 4Mb limit', 'default', array('class' => 'error'));
						} else {
							// valid file
							$response = $this->checkValidCsvData($data);

							if ($response['error']) {
								$this->Session->setFlash($response['msg'], 'default', array('class' => 'error'));
							} else {
								$updateResponse = $this->updateCsvData($response['fileData']);

								if ($updateResponse['error']) {
									$this->Session->setFlash($updateResponse['msg'], 'default', array('class' => 'error'));
								}

								$productsCount = $updateResponse['info']['productCount'];
								$productsAdded = $updateResponse['info']['newProductCount'];
								$productsUpdated = $updateResponse['info']['oldProductCount'];
								$categoriesCount = $updateResponse['info']['categoryCount'];
								$categoriesAdded = $updateResponse['info']['newCategoryCount'];
								$categoriesUpdated = $updateResponse['info']['oldCategoryCount'];
								$brandsCount = $updateResponse['info']['brandsCount'];
								$brandsUpdated = $updateResponse['info']['oldBrandsCount'];
								$brandsAdded = $updateResponse['info']['newBrandsCount'];
							}
						}
					} else {
						$this->Session->setFlash('Invalid File Size', 'default', array('class' => 'error'));
					}
				} else {
					$this->Session->setFlash('Invalid CSV File', 'default', array('class' => 'error'));
				}
			} else {
				$this->Session->setFlash('Unknown File Type', 'default', array('class' => 'error'));
			}
		}

		$this->set(compact(
			'hideSideBar',
			'productsCount',
			'productsAdded',
			'productsUpdated',
			'categoriesCount',
			'categoriesAdded',
			'categoriesUpdated',
			'response',
			'brandsCount',
			'brandsUpdated',
			'brandsAdded'
		));
	}

	private function checkValidCsvData($fileInfo)
	{
		App::uses('Validation', 'Utility');
		$response = array('success' => false, 'error' => false, 'msg' => '', 'fileData' => array());

		$file = '"' . $fileInfo['ProductCategory']['csv']['name'] . '"';
		$handle = fopen($fileInfo['ProductCategory']['csv']['tmp_name'], 'r');
		$fileData = array();

		$i = 1;
		while (($data = fgetcsv($handle)) !== FALSE) {
			//process
			//debug($data);
			if (!empty($data)) {
				// validate number of columns
				if (count($data) != 7) {
					$response['error'] = true;
					$response['msg'] = $file . '. File should have 7 columns. (Order of columns: CategoryName, BrandName, ProductName, ProductCode, BoxPrice, BoxQuantity, UnitPrice)';
				} else {
					$dataHeader = array('CategoryName', 'BrandName', 'ProductName', 'ProductCode', 'BoxPrice', 'BoxQuantity', 'UnitPrice');
					if ($dataHeader != $data) {
						// validate column data type

						// category name validation
						if ((!Validation::notBlank($data[0])) or (!Validation::between($data[0], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Category name should be between 2 and 100 characters';
						}

						if (!empty($data[1]) and (!Validation::between($data[1], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Invalid Brand (Or) Brand name should be between 2 and 100 chars';
						}

						// product name validation
						//if((!Validation::notBlank($data[1])) OR !Validation::custom($data[1], "/^[a-zA-Z0-9][a-zA-Z0-9\s\-\._#()]{1,55}$/i")) {
						if (!Validation::notBlank($data[2]) or (!Validation::between($data[2], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Invalid Name (Or) Product name should be between 2 and 100 chars';
						}

						if (!empty($data[3]) and (!Validation::between($data[3], 2, 100))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Invalid Product Code (Or) Product Code should be between 2 and 100 chars';
						}

						// product box price validation
						if ((Validation::notBlank($data[4])) and (!Validation::decimal($data[4]) or ($data[4] <= 0))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Box buying price should be greater than 0';
						}

						// product quantity in box
						if ((Validation::notBlank($data[5])) and (!Validation::naturalNumber($data[5]))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Box quantity should be greater than 0. eg: 1,2,3,4,5,... etc';
						}

						// product unit price validation
						if ((Validation::notBlank($data[6])) and (!Validation::decimal($data[6]) or ($data[6] <= 0))) {
							$response['error'] = true;
							$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Unit price should be greater than 0';
						}

						// product special margin price validation
//						if((Validation::notBlank($data[5])) AND (!Validation::decimal($data[5]) OR ($data[5]<0))) {
//							$response['error'] = true;
//							$response['msg'] = 'File '.$file.', Line No '.$i.': Special margin should be greater than or equal to "0"';
//						}

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


	private function updateCsvData($fileData)
	{
		$response = array('success' => false, 'error' => false, 'msg' => '', 'info' => array());
		$errorMsg = array();
		$brandCount = 0;
		$newBrandCount = 0;
		$oldBrandCount = 0;

		if (!empty($fileData)) {
			// debug($fileData);
			$conditions = array('ProductCategory.store_id' => $this->Session->read('Store.id'));
			$this->ProductCategory->bindModel(array('hasMany' => array('Product')));
			$storeCategoryProducts = $this->ProductCategory->find('all', array('conditions' => $conditions));
			//debug($storeCategoryProducts);

			App::uses('Brand', 'Model');
			$brandModel = new Brand;
			$storeBrands = $brandModel->find(
				'list',
				[
					'conditions' => [
						'Brand.store_id' => $this->Session->read('Store.id')
					]
				]
			);

			$fileCategories = array();
			$fileProducts = array();

			foreach ($fileData as $row) {
				$fileCategoryProducts[$row[0]]['id'] = null;
				$fileCategoryProducts[$row[0]]['name'] = htmlentities($row[0], ENT_QUOTES);
				$fileCategoryProducts[$row[0]]['brandName'] = htmlentities($row[1], ENT_QUOTES);

				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['id'] = null;
				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['name'] = htmlentities($row[2], ENT_QUOTES);
				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['product_code'] = htmlentities($row[3], ENT_QUOTES);
				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['box_buying_price'] = $row[4];
				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['box_qty'] = $row[5];
				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['unit_selling_price'] = $row[6];
				//$fileCategoryProducts[$row[0]]['Products'][$row[1]]['special_margin'] = $row[5];
				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['store_id'] = $this->Session->read('Store.id');

				// set brand_id in products
				$brand_id = null;

				if (in_array($fileCategoryProducts[$row[0]]['brandName'], $storeBrands)) {
					$brand_id = array_search($fileCategoryProducts[$row[0]]['brandName'], $storeBrands);
					$oldBrandCount++;
				} elseif (!empty($fileCategoryProducts[$row[0]]['brandName'])) {
					// create new brand
					$tmp['Brand'] = [
						'id' => null,
						'name' => $fileCategoryProducts[$row[0]]['brandName'],
						'store_id' => $this->Session->read('Store.id'),
					];

					if ($brandModel->save($tmp)) {
						$brandDetails = $brandModel->read();
						$brand_id = $brandDetails['Brand']['id'];
						$newBrandCount++;
					}
				}

				$fileCategoryProducts[$row[0]]['Products'][$row[2]]['brand_id'] = $brand_id;
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
			$this->Product = new Product;

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
						$tmp = array();
						$tmp['Product'] = $product;
						$tmp['Product']['product_category_id'] = $row['id'];
						if ($this->Product->save($tmp)) {
							($product['id']) ? $oldProductCount++ : $newProductCount++;
						} else {
							$errorMsg[] = 'An error occurred while adding/updating - Category: ' . $row['name'] . ', Product: ' . $product['name'];
						}
					}
				} else {
					$tmp = array();
					$tmp['ProductCategory']['id'] = null;
					$tmp['ProductCategory']['name'] = $row['name'];
					$tmp['ProductCategory']['store_id'] = $this->Session->read('Store.id');

					if ($this->ProductCategory->save($tmp)) {
						$newCategoryCount++;

						$categoryInfo = $this->ProductCategory->read();
						$categoryID = $categoryInfo['ProductCategory']['id'];

						foreach ($row['Products'] as $product) {
							$productCount++;
							$tmp = array();
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
			$info['brandsCount'] = $oldBrandCount+$newBrandCount;
			$info['oldBrandsCount'] = $oldBrandCount;
			$info['newBrandsCount'] = $newBrandCount;

			$response['info'] = $info;
		}

		return $response;
	}

	function downloadCsv()
	{
		//Configure::write('debug', 0);

		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '1024M');

		$fileName = 'ProductList-' . time() . '.csv';
		$this->layout = 'csv';

		$this->response->compress();
		$this->response->type('csv');
		$this->response->download($fileName);

		$conditions = array('ProductCategory.store_id' => $this->Session->read('Store.id'));
		$this->ProductCategory->bindModel(array('hasMany' => array('Product' => array('order' => 'Product.name'))));
		$storeProducts = $this->ProductCategory->find('all', array('conditions' => $conditions, 'order' => 'ProductCategory.name'));

		App::uses('Brand', 'Model');
		$brandModel = new Brand;
		$storeBrands = $brandModel->find(
			'list',
			[
				'conditions' => [
					'Brand.store_id' => $this->Session->read('Store.id')
				]
			]
		);
		$this->set(compact('storeProducts', 'storeBrands'));
	}

}

?>
