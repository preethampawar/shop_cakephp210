<?php
App::uses('Validation', 'Utility');

class PurchasesController extends AppController
{

	public $name = 'Purchases';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	/**
	 * Function to show list of category products
	 */
	public function index()
	{
		$conditions = ['Purchase.store_id' => $this->Session->read('Store.id')];
		// $purchases = $this->Purchase->find('all', array(
		// 'order' => array('Purchase.purchase_date' => 'DESC', 'Purchase.created' => 'DESC'),
		// 'conditions' => $conditions,
		// 'limit' => '100',
		// 'recursive' => '-1'
		// ));

		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['Purchase.purchase_date' => 'DESC', 'Purchase.created' => 'DESC'],
			'limit' => 10,
			'recursive' => '-1',
		];
		$purchases = $this->paginate();

		$this->set(compact('purchases'));
	}

	public function addProduct()
	{
		$error = null;
		if (!($invoice_info = $this->Session->read('Invoice'))) {
			$this->errorMsg('Invoice not found');
			$this->redirect('/invoices/');
		}

		App::uses('Invoice', 'Model');
		$this->Invoice = new Invoice();
		$this->Invoice->clear();
		$invoiceInfo = $this->Invoice->findById($invoice_info['id']);
		$this->Session->delete('Invoice');
		$this->Session->write('Invoice', $invoiceInfo['Invoice']);

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		$productsInfo = $this->Product->find('all', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '-1']);
		$productsList = $this->Product->find('list', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '-1']);

		$productsList = [];
		if ($productsInfo) {
			foreach ($productsInfo as $row) {

				$productsList[$row['Product']['id']] = ($this->Session->read('Store.show_brands_in_products')) ? (($row['Brand']['name']) ? $row['Brand']['name'] . ' - ' : '') . $row['Product']['name'] : $row['Product']['name'];
			}
		}
		//debug($productsList);
		//debug($productsInfo);

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			$error = $this->addInvoiceProductsFormValidation($data);
			if (!$error) {
				$this->Product->bindModel(['belongsTo' => ['ProductCategory']]);

				if (!$productInfo = $this->Product->findById($data['Purchase']['product_id'])) {
					$error = 'Product not found.';
				}
			}

			if (!$error) {
				$this->Session->write('selectedProductID', $productInfo['Product']['id']);

				$data['Purchase']['id'] = null;
				$data['Purchase']['product_code'] = $productInfo['Product']['product_code'];
				$data['Purchase']['product_category_id'] = $productInfo['ProductCategory']['id'];
				$data['Purchase']['store_id'] = $this->Session->read('Store.id');
				$data['Purchase']['invoice_id'] = $invoiceInfo['Invoice']['id'];
				$data['Purchase']['purchase_date'] = $invoiceInfo['Invoice']['invoice_date'];
				$data['Purchase']['product_name'] = $productInfo['Product']['name'];
				$data['Purchase']['category_name'] = $productInfo['ProductCategory']['name'];
				$data['Purchase']['store_name'] = $this->Session->read('Store.name');
				$data['Purchase']['invoice_name'] = $invoiceInfo['Invoice']['name'];

				if ($this->request->subdomains()[0] == 'closingstock') {
					$data['Purchase']['from_mobile_app'] = 1;
				}

				if ($this->Purchase->save($data)) {
					$this->updateInvoice($invoiceInfo['Invoice']['id']);

					$msg = $productInfo['Product']['name'] . ' successfully added to Invoice - ' . $invoiceInfo['Invoice']['name'];
					$this->successMsg($msg);
					$this->redirect(['controller' => 'purchases', 'action' => 'addProduct']);
				}
			}
		} else {
			if ($this->Session->check('selectedProductID')) {
				$data['Purchase']['product_id'] = $this->Session->read('selectedProductID');
				$this->data = $data;
			}
		}

		// find invoice products
		$conditions = ['Purchase.invoice_id' => $invoiceInfo['Invoice']['id']];
		$invoiceProducts = $this->Purchase->find('all', ['conditions' => $conditions, 'order' => 'Purchase.created ASC', 'recursive' => '2']);

		if ($error) {
			$this->errorMsg($error);
		}
		$this->set(compact('productsInfo', 'productsList', 'invoiceProducts', 'invoiceInfo'));
	}

	public function addInvoiceProductsFormValidation($data = null)
	{
		$error = null;
		if ($data) {
			if (!isset($data['Purchase']['product_id'])) {
				$error = 'Product not found';
			}
			/*if((!isset($data['Purchase']['box_qty'])) OR (!Validation::naturalNumber($data['Purchase']['box_qty']))) {
				$error = 'No. of Boxes should be greater than 0';
			}*/
			if ((!isset($data['Purchase']['units_in_box'])) or (!Validation::naturalNumber($data['Purchase']['units_in_box']))) {
				$error = 'Units in Box should be greater than 0';
			}
			if ((!isset($data['Purchase']['box_buying_price'])) or (!Validation::decimal($data['Purchase']['box_buying_price'])) or ($data['Purchase']['box_buying_price'] <= 0)) {
				$error = 'Box price should be greater than 0';
			}
			if ((!isset($data['Purchase']['total_units'])) or (!Validation::naturalNumber($data['Purchase']['total_units']))) {
				$error = 'No. of Units should be greater than 0';
			}
			if ((!isset($data['Purchase']['total_amount'])) or (!Validation::decimal($data['Purchase']['total_amount'])) or ($data['Purchase']['total_amount'] <= 0)) {
				$error = 'Total amount should be greater than 0';
			}
		} else {
			$error = 'Empty product details';
		}
		return $error;
	}

	public function add()
	{
		$error = null;

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		$productsInfo = $this->Product->find('all', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '-1']);
		$productsList = $this->Product->find('list', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '-1']);

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			$purchaseDate = $data['Purchase']['purchase_date']['year'] . '-' . $data['Purchase']['purchase_date']['month'] . '-' . $data['Purchase']['purchase_date']['day'];
			$data['Purchase']['purchase_date'] = $purchaseDate;
			$this->Session->delete('selectedProductID');

			$error = $this->addPurchaseFormValidation($data);
			if (!$error) {
				$this->Product->bindModel(['belongsTo' => ['ProductCategory']]);

				if (!$productInfo = $this->Product->findById($data['Purchase']['product_id'])) {
					$error = 'Product not found.';
				}
			}

			if (!$error) {

				$data['Purchase']['id'] = null;
				$data['Purchase']['product_code'] = $productInfo['Product']['product_code'];
				$data['Purchase']['product_category_id'] = $productInfo['ProductCategory']['id'];
				$data['Purchase']['store_id'] = $this->Session->read('Store.id');
				$data['Purchase']['invoice_id'] = null;
				$data['Purchase']['purchase_date'] = $purchaseDate;
				$data['Purchase']['product_name'] = $productInfo['Product']['name'];
				$data['Purchase']['category_name'] = $productInfo['ProductCategory']['name'];
				$data['Purchase']['store_name'] = $this->Session->read('Store.name');
				$data['Purchase']['invoice_name'] = null;

				if ($this->request->subdomains()[0] == 'closingstock') {
					$data['Purchase']['from_mobile_app'] = 1;
				}

				if ($this->Purchase->save($data)) {
					$this->Session->write('selectedProductID', $productInfo['Product']['id']);
					$this->Session->write('purchaseDate', $purchaseDate);
					$msg = $productInfo['Product']['name'] . ' successfully added to Purchase list';
					$this->successMsg($msg);
					$this->redirect(['controller' => 'purchases', 'action' => 'add']);
				}
			}
		} else {
			if ($this->Session->check('selectedProductID')) {
				$data['Purchase']['product_id'] = $this->Session->read('selectedProductID');
				$data['Purchase']['purchase_date'] = $this->Session->read('purchaseDate');
				$this->data = $data;
			}
		}

		// find recent purchase products
		$conditions = ['Purchase.store_id' => $this->Session->read('Store.id')];
		$purchaseProducts = $this->Purchase->find('all', ['conditions' => $conditions, 'order' => 'Purchase.created DESC', 'recursive' => '-1', 'limit' => '10']);

		if ($error) {
			$this->errorMsg($error);
		}
		$this->set(compact('productsInfo', 'productsList', 'purchaseProducts'));
	}

	public function addPurchaseFormValidation($data = null)
	{
		$error = null;
		if ($data) {
			if (!isset($data['Purchase']['product_id'])) {
				$error = 'Product not found';
			}
			if ((!isset($data['Purchase']['total_units'])) or (!Validation::naturalNumber($data['Purchase']['total_units']))) {
				$error = 'No. of Units should be greater than 0';
			}
			if ((!isset($data['Purchase']['total_amount'])) or (!Validation::decimal($data['Purchase']['total_amount'])) or ($data['Purchase']['total_amount'] < 0)) {
				$error = 'Total amount cannot be less than 0';
			}
		} else {
			$error = 'Empty product details';
		}
		return $error;
	}

	public function removeProduct($purchaseID = null)
	{
		if ($this->request->isPost()) {
			if ($purchaseInfo = $this->CommonFunctions->getPurchaseInfo($purchaseID)) {

				$this->Purchase->delete($purchaseID);

				if ($purchaseInfo['Purchase']['invoice_id']) {
					$this->updateInvoice($purchaseInfo['Purchase']['invoice_id']);
				}
				$this->successMsg('"' . $purchaseInfo['Purchase']['product_name'] . '" removed from the list');
			} else {
				$this->errorMsg('Product not found');
			}
		} else {
			$this->errorMsg('Invalid request');
		}

		$this->redirect($this->request->referer());
	}


	public function uploadCsv()
	{
		$hideSideBar = true;
		$updateResponse = [];

		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '256M');

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (isset($data['Purchase']['csv']['error']) and (!$data['Purchase']['csv']['error'])) {
				$mimes = ['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'application/octet-stream'];
				if (in_array($data['Purchase']['csv']['type'], $mimes)) {
					$fileSize = $data['Purchase']['csv']['size'];
					if ($fileSize > 0) {
						$maxSize = 4;
						if (ceil($fileSize / (1024 * 1024)) > $maxSize) {
							$this->errorMsg('File size exceeds 4Mb limit');
						} else {
							// valid file
							$response = $this->checkValidCsvData($data);

							if ($response['error']) {
								$this->errorMsg($response['msg']);
							} else {
								$updateResponse = $this->updateCsvData($response['fileData']);

								if ($updateResponse['error']) {
									$this->errorMsg($updateResponse['msg']);
								} else {
									$this->Session->write('updateResponse', $updateResponse);
									$this->successMsg('File uploaded successfully');
									$this->redirect('/purchases/uploadCsv/');
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

		unset($updateResponse);
		if ($this->Session->check('updateResponse')) {
			$updateResponse = $this->Session->read('updateResponse');
			$this->Session->delete('updateResponse');
		}

		$this->set(compact('hideSideBar', 'response', 'updateResponse'));
	}

	private function checkValidCsvData($fileInfo)
	{
		App::uses('Validation', 'Utility');
		$response = ['success' => false, 'error' => false, 'msg' => '', 'fileData' => []];

		$file = '"' . $fileInfo['Purchase']['csv']['name'] . '"';
		$handle = fopen($fileInfo['Purchase']['csv']['tmp_name'], 'r');
		$fileData = [];

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$this->Product->bindModel(['belongsTo' => ['ProductCategory']]);
		$storeProducts = $this->Product->find('all', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id')], 'fields' => ['Product.id', 'Product.name', 'Product.box_qty', 'Product.box_buying_price', 'ProductCategory.name', 'ProductCategory.id']]);

		//debug($storeProducts); exit;

		if ($storeProducts) {
			$i = 1;
			$updatePurchasesContent = [];
			while (($data = fgetcsv($handle)) !== false) {
				//process
				if (!empty($data)) {
					// validate number of columns
					if (count($data) != 4) {
						$response['error'] = true;
						$response['msg'] = $file . '. File should have 4 columns. File Format: (CategoryName, ProductName, ClosingStock, ClosingDate)';
					} else {
						$dataHeader = ['CategoryName', 'ProductName', 'Quantity', 'Date'];
						if ($dataHeader != $data) {
							// validate column data type

							// category name
							if (!Validation::notBlank($data[0])) {
								$response['error'] = true;
								$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Category name cannot be empty';
							}

							// product name
							if (!Validation::notBlank($data[1])) {
								$response['error'] = true;
								$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Product name cannot be empty';
							}

							// closing quantity
							if (!Validation::naturalNumber($data[2])) {
								$response['error'] = true;
								$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Invalid Quantity (or) Quantity should be greater than 0.';
							}

							// date
							if (Validation::notBlank($data[3])) {
								if (date('d-m-Y', strtotime($data[3])) != $data[3]) {
									$response['error'] = true;
									$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Invalid Date';
								}
							} else {
								$response['error'] = true;
								$response['msg'] = 'File ' . $file . ', Line No ' . $i . ': Date column cannot be empty';
							}

							// check if category & product exists for the selected store.
							$tmpCategoryName = htmlentities($data[0], ENT_QUOTES);
							$tmpProductName = htmlentities($data[1], ENT_QUOTES);
							$tmpQty = $data[2];
							$tmpDate = date('Y-m-d', strtotime($data[3]));

							$errorMsg = [];
							$productFound = false;
							foreach ($storeProducts as $row) {
								$pName = $row['Product']['name'];
								$pID = $row['Product']['id'];
								$pBoxPrice = $row['Product']['box_buying_price'];
								$pBoxQty = $row['Product']['box_qty'];

								$cName = $row['ProductCategory']['name'];
								$cID = $row['ProductCategory']['id'];

								if (($pName == $tmpProductName) and ($cName == $tmpCategoryName)) {
									$productFound = true;
									if (($pBoxPrice > 0) and ($pBoxQty > 0)) {
										$unitPrice = round($pBoxPrice / $pBoxQty, 3);

										$updatePurchasesContent[$i]['Purchase']['id'] = null;
										$updatePurchasesContent[$i]['Purchase']['product_id'] = $pID;
										$updatePurchasesContent[$i]['Purchase']['product_category_id'] = $cID;
										$updatePurchasesContent[$i]['Purchase']['store_id'] = $this->Session->read('Store.id');
										$updatePurchasesContent[$i]['Purchase']['unit_price'] = $unitPrice;
										$updatePurchasesContent[$i]['Purchase']['total_units'] = $tmpQty;
										$updatePurchasesContent[$i]['Purchase']['total_amount'] = $tmpQty * $unitPrice;
										$updatePurchasesContent[$i]['Purchase']['purchase_date'] = $tmpDate;
										$updatePurchasesContent[$i]['Purchase']['product_name'] = $pName;
										$updatePurchasesContent[$i]['Purchase']['category_name'] = $cName;
										$updatePurchasesContent[$i]['Purchase']['store_name'] = $this->Session->read('Store.name');
									} else {
										$response['error'] = true;
										$errorMsg[] = 'Line No ' . $i . ': ' . $tmpCategoryName . '->' . $tmpProductName . '. Check Product Box price and quanity in Box';
									}
									break;
								}
							}

							if (!$productFound) {
								$response['error'] = true;
								$errorMsg[] = 'Line No ' . $i . ': Category/Product not found. Category - "' . $tmpCategoryName . '", Product - "' . $tmpProductName . '"';
							}
						}
					}
				}

				if ($response['error'] == true) {
					break;
				}

				$i++;
			}

			if ($errorMsg) {
				$response['error'] = true;
				$response['msg'] = implode('<br>', $errorMsg);
			}
			$response['fileData'] = $updatePurchasesContent;
		} else {
			$response['error'] = true;
			$response['msg'] = 'No products found in this Store. You need to add product(s) before updating stock.';
		}
		return $response;
	}

	private function updateCsvData($fileData)
	{

		$response = ['success' => false, 'error' => false, 'msg' => '', 'info' => []];
		$errorMsg = [];
		$totalRecords = 0;
		$savedRecords = 0;
		$failedRecords = 0;
		if (!empty($fileData)) {
			$totalRecords = count($fileData);
			foreach ($fileData as $row) {
				$tmp = [];
				$tmp = $row;
				if ($this->request->subdomains()[0] == 'closingstock') {
					$tmp['Purchase']['from_mobile_app'] = 1;
				}
				if ($this->Purchase->save($tmp)) {
					$savedRecords++;
				} else {
					$errorMsg[] = 'Failed to add record: Category: "' . $row['Purchase']['category_name'] . '", Product: "' . $row['Purchase']['product_name'] . '"';
					$failedRecords++;
				}
			}
		}

		if ($errorMsg) {
			$response['error'] = true;
			$response['msg'] = implode($errorMsg, '<br>');
		}
		$response['info']['totalRecords'] = $totalRecords;
		$response['info']['savedRecords'] = $savedRecords;
		$response['info']['failedRecords'] = $failedRecords;

		return $response;
	}


}

?>
