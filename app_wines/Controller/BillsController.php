<?php
App::uses('Validation', 'Utility');

class BillsController extends AppController
{
    var $name = 'Bills';
	var $storeId = null;

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->checkStoreInfo();

		$this->layout = 'new';
		$this->storeId = $this->Session->read('Store.id');
    }

	protected function getBillInfo($billId) {
		$conditions = [
			'Bill.id' => $billId,
			'Bill.store_id' => $this->storeId,
		];

		return $this->Bill->find('first', ['conditions' => $conditions]);
	}

    function index()
    {

    }

	public function add()
	{
		$data['Bill']['id'] = null;
		$data['Bill']['store_id'] = $this->storeId;
		$data['Bill']['bill_date'] = date('Y-m-d h:m:i');

		if ($this->Bill->save($data)) {
			$bill = $this->Bill->read();
			$this->redirect('/bills/edit/' . $bill['Bill']['id']);
		}
		$this->errorMsg('New bill could not be created. Please try again.');
		$this->redirect('/bills/');
	}

	public function edit($billId, $autoSubmit = null)
	{
		$productsList = [];
		$productsInfo = [];

		if ($autoSubmit !== null) {
			if ($autoSubmit) {
				$this->Session->write('Bills.autoSubmitEnabled', true);
			} else {
				$this->Session->write('Bills.autoSubmitEnabled', false);
			}

			$this->redirect('/bills/edit/'.$billId);
		}

		if ($bill = $this->getBillInfo($billId)) {
			if ($this->request->is('post') || $this->request->is('put')) {
				$data = $this->request->data;
				$data['Sale']['sale_date'] = $data['Bill']['date'];
				$saleDate = $data['Sale']['sale_date'];
				$productId = $data['Sale']['product_id'];
				$unitPrice = (float)$data['Sale']['unit_price'];
				$totalUnits = (int)$data['Sale']['total_units'];

				$billDataValidationResponse = $this->validateBillData($data);

				if($billDataValidationResponse['error'] === true) {
					$this->errorMsg($billDataValidationResponse['msg']);
					$this->redirect('/bills/edit/'.$billId);
				}

				$saleDataValidationResponse = $this->validateSaleData($data);

				if($saleDataValidationResponse['error'] === true) {
					$this->errorMsg($saleDataValidationResponse['msg']);
					$this->redirect('/bills/edit/'.$billId);
				}

				// check if product is of same store
				if(empty($productDetails = $this->CommonFunctions->getProductInfo($productId))) {
					$this->errorMsg('Invalid request.');
					$this->redirect('/bills/edit/'.$billId);
				}

				// check if product is in stock
				App::uses('ProductStockReport', 'Model');
				$productStockReportModel = new ProductStockReport;
				$conditions = array('ProductStockReport.product_id'=>$data['Sale']['product_id'], 'ProductStockReport.store_id' => $this->storeId);

				$error = null;
				if($tmp = $productStockReportModel->find('first', array('conditions'=>$conditions))) {
					$bal_qty = (int)$tmp['ProductStockReport']['balance_qty'];

					if ($bal_qty <= 0) {
						$error = '"'.$productDetails['Product']['name'].'" is out of stock';
					} elseif($totalUnits > $bal_qty) {
						$error = 'Quantity cannot be greater than '.$bal_qty;
					}
				}

				if ($error) {
					$this->errorMsg($error);
					$this->redirect('/bills/edit/'.$billId);
				}

				$tmp = [];
				$tmp['Sale']['id'] = null;
				$tmp['Sale']['product_code'] = $productDetails['Product']['product_code'];
				$tmp['Sale']['product_id'] = $productDetails['Product']['id'];
				$tmp['Sale']['product_category_id'] = $productDetails['Product']['product_category_id'];
				$tmp['Sale']['store_id'] = $this->storeId;
				$tmp['Sale']['unit_price'] = $unitPrice;
				$tmp['Sale']['total_units'] = $totalUnits;
				$tmp['Sale']['total_amount'] = $unitPrice*$totalUnits;
				$tmp['Sale']['sale_date'] = $saleDate;
				$tmp['Sale']['product_name'] = $productDetails['Product']['name'];
				$tmp['Sale']['category_name'] = $productDetails['ProductCategory']['name'];
				$tmp['Sale']['store_name'] = $this->Session->read('Store.name');
				$tmp['Sale']['closing_stock_qty'] = null;
				$tmp['Sale']['reference'] = '#Bill-' . $billId;
				$tmp['Sale']['bill_id'] = $billId;

				App::uses('Sale', 'Model');
				$salesModel = new Sale;

				if($salesModel->save($tmp)) {
					$this->successMsg('Product added successfully to sales list.');
					$this->redirect('/bills/edit/'.$billId);
				} else {
					$this->errorMsg('Error! Product could not be added to sales list.');
					$this->redirect('/bills/edit/'.$billId);
				}
			}

			App::uses('Product', 'Model');
			$productModel = new Product();

			// get products stock report
			$productsStockInfo = $productModel->getProductStockReport($this->storeId);
			$products = $productModel->find('all', [
				'conditions' => [
					'Product.store_id' => $this->storeId,
					'Product.active' => true,
				],
			]);

			if ($products) {
				foreach($products as $row) {
					$productAvailableQty = $productsStockInfo[$row['Product']['id']]['balance_qty'] ?? 0;
					$productLabel = $row['Product']['name'] . ' [Stock='.$productAvailableQty.', Unit Price='. (float)$row['Product']['unit_selling_price'] . (!empty($row['Product']['product_code']) ? ', ' . $row['Product']['product_code'] : '') . ']';

					$productsInfo[$row['Product']['id']] = [
						'id' => (int)$row['Product']['id'],
						'name' => (string)$row['Product']['name'],
						'product_code' => (string)$row['Product']['product_code'],
						'product_label' => $productLabel,
						'box_buying_price' => (float)$row['Product']['box_buying_price'],
						'box_selling_price' => (float)$row['Product']['box_selling_price'],
						'box_qty' => (int)$row['Product']['box_qty'],
						'unit_buying_price' => (float)$row['Product']['unit_buying_price'],
						'unit_selling_price' => (float)$row['Product']['unit_selling_price'],
						'category_name' => (string)$row['ProductCategory']['name'],
						'brand_name' => (string)$row['Brand']['name'],
						'available_qty' => (int)$productAvailableQty,
					];

					$productsList[$row['Product']['id']] = $productLabel;
				}
			}
		} else {
			$this->errorMsg('Invalid request.');
			$this->redirect('/bills/');
		}

		$this->set(compact('bill', 'productsList', 'productsInfo'));
	}

	protected function validateBillData($data)
	{
		$response = [
			'error' => false,
			'msg' => '',
		];

		$billDate = $data['Bill']['date'] ?? null;

		if (empty($billDate)) {
			$response['error'] = true;
			$response['msg'] = 'Please select bill date.';
		}

		return $response;
	}

	protected function validateSaleData($data)
	{
		$response = [
			'error' => false,
			'msg' => '',
		];

		$saleDate = $data['Sale']['sale_date'] ?? null;
		$product_id = $data['Sale']['product_id'] ?? null;
		$unit_price = (float)$data['Sale']['unit_price'] ?? null;
		$total_units = (int)$data['Sale']['total_units'] ?? null;

		if (empty($saleDate)) {
			$response['error'] = true;
			$response['msg'] = 'Please select sale date.';
		}

		if (empty($product_id)) {
			$response['error'] = true;
			$response['msg'] = 'Please select a product.';
		}

		if ($unit_price <= 0) {
			$response['error'] = true;
			$response['msg'] = 'Invalid unit price.';
		}

		if ($total_units <= 0) {
			$response['error'] = true;
			$response['msg'] = 'Invalid quantity.';
		}

		return $response;
	}

	public function printBill($billId)
	{
		$this->layout = 'print_bootstrap';

		if (!$bill = $this->getBillInfo($billId)) {
			$this->errorMsg('Invalid request.');
			$this->redirect('/bills/');
		}

		$this->set('bill', $bill);
	}

	public function delete()
	{

	}
}
