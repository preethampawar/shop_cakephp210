<?php
App::uses('Validation', 'Utility');

class ReportsController extends AppController
{

	public $name = 'Reports';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();

		ini_set('max_execution_time', '10000');
		ini_set('memory_limit', '256M');

		$this->response->compress();
	}

	public function home()
	{

	}

	public function dayWiseStockReport()
	{
		$error = null;
		$result = null;

		$fromDate = date('Y-m-d');
		$toDate = date('Y-m-d');
		$showForm = true;
		$hideHeader = false;
		$hideSideBar = false;
		$viewType = '';

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		$productsList = $this->Product->find('list', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '-1']);

		if ($this->request->isPost()) {
			$hideSideBar = true;
			$data = $this->request->data;
			$viewType = $data['Report']['view_type'];
			$this->set('viewType', $viewType);
			if ($viewType == 'print') {
				$this->layout = 'print_view';
			}


			$showForm = false;
			$hideHeader = true;

			$productID = $data['Report']['product_id'];
			$fromDate = $data['Report']['from_date'];
			$toDate = $data['Report']['to_date'];
			$store_id = $this->Session->read('Store.id');

			if (strtotime($fromDate) > strtotime($toDate)) {
				$error = '"From Date" cannot be greater than "To Date"';
			}

			if (!$error) {
				if ($viewType == 'download') {
					$this->redirect('/reports/downloadDayWiseStockReport/' . $fromDate . '/' . $toDate . '/' . $productID);
				}
				if (!$productID) {
					$result = $this->getStoreStockReport($store_id, $fromDate, $toDate);
				} else {
					$result = $this->getProductStockReport($store_id, $fromDate, $toDate, $productID);
				}
			}
		}

		($error) ? $this->Session->setFlash($error) : null;
		$this->set(compact('error', 'result', 'fromDate', 'toDate', 'productsList', 'showForm', 'hideHeader', 'hideSideBar', 'viewType'));
	}

	public function getStoreStockReport($store_id, $fromDate, $toDate)
	{
		/** Query to get stock report */
		$query = "
#find (opening stock) (stock purchases,  stock sale in the date range) (closing stock)

#opening stock
SELECT p.id,p.name,p.product_category_id,p.unit_selling_price,p.box_buying_price,p.box_qty, c.name, (COALESCE(os_purchases.pu_qty,0)-COALESCE(os_sales.s_qty,0)-COALESCE(os_breakages.b_qty,0)) opening_stock,
	COALESCE(rs_purchases.pu_qty,0) stock_added, COALESCE(rs_sales.s_qty,0) stock_sale,
	COALESCE(rs_breakages.b_qty,0) stock_breakage,
	(COALESCE(cs_purchases.pu_qty,0)-COALESCE(cs_sales.s_qty,0)-COALESCE(cs_breakages.b_qty,0)) closing_stock,
	pu_total_amt as total_purchase_value, s_total_amt as total_sale_value, b_total_amt as total_breakage_value
FROM products p
  LEFT JOIN
	#get product category info
	product_categories c ON c.id=p.product_category_id
  LEFT JOIN
	#get opening sales
	(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date<@from_date GROUP BY s.product_id) os_sales ON os_sales.product_id=p.id
  LEFT JOIN
	#get opening purchases
	(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date<@from_date GROUP BY pu.product_id) os_purchases ON os_purchases.product_id=p.id
  LEFT JOIN
	#get opening breakages
	(SELECT b.product_id, SUM(total_units) b_qty FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date<@from_date GROUP BY b.product_id) os_breakages ON os_breakages.product_id=p.id

#closing stock
  LEFT JOIN
	#get closing sales
	(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date<=@to_date GROUP BY s.product_id) cs_sales ON cs_sales.product_id=p.id
  LEFT JOIN
	#get closing purchases
	(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date<=@to_date GROUP BY pu.product_id) cs_purchases ON cs_purchases.product_id=p.id
	#get closing breakages
  LEFT JOIN
	(SELECT b.product_id, SUM(total_units) b_qty FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date<=@to_date GROUP BY b.product_id) cs_breakages ON cs_breakages.product_id=p.id

#purchase and sales stock in date range
  LEFT JOIN
	#get purchases in date range
	(SELECT pu.product_id, SUM(total_units) pu_qty, SUM(pu.total_amount) pu_total_amt FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date BETWEEN @from_date AND @to_date GROUP BY pu.product_id) rs_purchases ON rs_purchases.product_id=p.id
  LEFT JOIN
	#get sales in date range
	(SELECT s.product_id, SUM(s.total_units) s_qty, SUM(s.total_amount) s_total_amt FROM sales s WHERE s.store_id=@store_id AND s.sale_date BETWEEN @from_date AND @to_date GROUP BY s.product_id) rs_sales ON rs_sales.product_id=p.id
  LEFT JOIN
	#get breakages in date range
	(SELECT b.product_id, SUM(total_units) b_qty, SUM(b.total_amount) b_total_amt FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date BETWEEN @from_date AND @to_date GROUP BY b.product_id) rs_breakages ON rs_breakages.product_id=p.id
WHERE p.store_id=@store_id
ORDER BY p.name";

		$this->Report->query("SET @store_id='$store_id'");
		$this->Report->query("SET @from_date='$fromDate'");
		$this->Report->query("SET @to_date='$toDate'");
		$result = $this->Report->query($query);

		return $result;
	}

	public function getProductStockReport($store_id, $fromDate, $toDate, $productID)
	{
		/** Query to get stock report */
		$query = "
#find (opening stock) (stock purchases,  stock sale in the date range) (closing stock)

#opening stock
SELECT p.id,p.name,p.product_category_id,p.unit_selling_price,p.box_buying_price,p.box_qty, c.name, (COALESCE(os_purchases.pu_qty,0)-COALESCE(os_sales.s_qty,0)-COALESCE(os_breakages.b_qty,0)) opening_stock,
	COALESCE(rs_purchases.pu_qty,0) stock_added, COALESCE(rs_sales.s_qty,0) stock_sale,
	COALESCE(rs_breakages.b_qty,0) stock_breakage,
	(COALESCE(cs_purchases.pu_qty,0)-COALESCE(cs_sales.s_qty,0)-COALESCE(cs_breakages.b_qty,0)) closing_stock,
	pu_total_amt as total_purchase_value, s_total_amt as total_sale_value, b_total_amt as total_breakage_value
FROM products p
  LEFT JOIN
	#get product category info
	product_categories c ON c.id=p.product_category_id
  LEFT JOIN
	#get opening sales
	(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date<@from_date GROUP BY s.product_id) os_sales ON os_sales.product_id=p.id
  LEFT JOIN
	#get opening purchases
	(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date<@from_date GROUP BY pu.product_id) os_purchases ON os_purchases.product_id=p.id
  LEFT JOIN
	#get opening breakages
	(SELECT b.product_id, SUM(total_units) b_qty FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date<@from_date GROUP BY b.product_id) os_breakages ON os_breakages.product_id=p.id

#closing stock
  LEFT JOIN
	#get closing sales
	(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date<=@to_date GROUP BY s.product_id) cs_sales ON cs_sales.product_id=p.id
  LEFT JOIN
	#get closing purchases
	(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date<=@to_date GROUP BY pu.product_id) cs_purchases ON cs_purchases.product_id=p.id
	#get closing breakages
  LEFT JOIN
	(SELECT b.product_id, SUM(total_units) b_qty FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date<=@to_date GROUP BY b.product_id) cs_breakages ON cs_breakages.product_id=p.id

#purchase and sales stock in date range
  LEFT JOIN
	#get purchases in date range
	(SELECT pu.product_id, SUM(total_units) pu_qty, SUM(pu.total_amount) pu_total_amt FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date BETWEEN @from_date AND @to_date GROUP BY pu.product_id) rs_purchases ON rs_purchases.product_id=p.id
  LEFT JOIN
	#get sales in date range
	(SELECT s.product_id, SUM(total_units) s_qty, SUM(s.total_amount) s_total_amt FROM sales s WHERE s.store_id=@store_id AND s.sale_date BETWEEN @from_date AND @to_date GROUP BY s.product_id) rs_sales ON rs_sales.product_id=p.id
  LEFT JOIN
	#get breakages in date range
	(SELECT b.product_id, SUM(total_units) b_qty, SUM(b.total_amount) b_total_amt FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date BETWEEN @from_date AND @to_date GROUP BY b.product_id) rs_breakages ON rs_breakages.product_id=p.id
WHERE p.id=@product_id AND p.store_id=@store_id
ORDER BY p.name";

		$this->Report->query("SET @store_id='$store_id'");
		$this->Report->query("SET @from_date='$fromDate'");
		$this->Report->query("SET @to_date='$toDate'");
		$this->Report->query("SET @product_id='$productID'");
		$result = $this->Report->query($query);
		return $result;
	}

	public function downloadDayWiseStockReport($fromDate, $toDate, $productId = null)
	{
		$this->layout = false;
		$store_id = $this->Session->read('Store.id');
		if (!$productId) {
			$result = $this->getStoreStockReport($store_id, $fromDate, $toDate);
		} else {
			$result = $this->getProductStockReport($store_id, $fromDate, $toDate, $productId);
		}

		Configure::write('debug', 0);
		$this->layout = 'csv';
		$this->response->type('csv');
		$fileName = 'StockReport-' . time() . '.csv';
		$this->response->download($fileName);
		$this->set(compact('result', 'fromDate', 'toDate', 'productId'));
	}

	public function monthWiseStockReport()
	{
		$error = null;
		$result = null;
		$month = date('m');
		$year = date('Y');
		$fromDate = null;
		$toDate = null;
		$selectedProductID = null;
		$showForm = true;
		$hideHeader = false;
		$hideSideBar = false;
		$viewType = '';

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		$productsList = $this->Product->find('list', ['conditions' => $conditions, 'order' => 'Product.name', 'recursive' => '-1']);

		if ($this->request->isPost()) {
			$hideSideBar = true;
			$data = $this->request->data;
			$viewType = $data['Report']['view_type'];
			if ($viewType == 'print') {
				$this->layout = 'print_view';
			}

			if ($viewType == 'download') {
				// Configure::write('debug',0);
				$this->layout = 'csv';
				$this->response->type('csv');
				$fileName = 'StockReport-' . $data['Report']['month']['month'] . '-' . $data['Report']['year'] . '-' . time() . '.csv';
				$this->response->download($fileName);
			}

			$showForm = false;
			$hideHeader = true;

			$store_id = $this->Session->read('Store.id');
			$selectedProductID = $data['Report']['product_id'];

			$month = $data['Report']['month']['month'];
			$year = $data['Report']['year'];

			$startDate = $year . '-' . $month . '-01';
			$no_of_days = date('t', strtotime($startDate));
			if (date('m') == $month) {
				$no_of_days = date('d');
			}

			if (empty($month) or empty($year)) {
				$error = 'Month and Year must be specified';
			}

			if (!$error) {
				for ($i = 1; $i <= $no_of_days; $i++) {
					$fromDate = $toDate = $startDate;

					if (!$selectedProductID) {
						$tmp = $this->getStoreStockReport($store_id, $fromDate, $toDate);
					} else {
						$tmp = $this->getProductStockReport($store_id, $fromDate, $toDate, $selectedProductID);
					}
					$result[$i] = $tmp;
					$startDate = date('Y-m-d', strtotime($fromDate . '+1 days'));
				}
			}
		}

		($error) ? $this->Session->setFlash($error) : null;
		$this->set(compact('error', 'result', 'fromDate', 'toDate', 'productsList', 'month', 'year', 'selectedProductID', 'showForm', 'hideHeader', 'hideSideBar', 'viewType'));
	}

	public function completeStockReport()
	{
		App::uses('ProductStockReport', 'Model');
		$this->ProductStockReport = new ProductStockReport();
		$this->ProductStockReport->bindModel(['belongsTo' => ['Product']]);
		$conditions = ['ProductStockReport.store_id' => $this->Session->read('Store.id')];
		$result = $this->ProductStockReport->find('all', ['conditions' => $conditions, 'order' => ['ProductStockReport.category_name', 'ProductStockReport.product_name']]);
		$this->set(compact('result'));
	}

	public function completeStockReportChart($type = 'store_performance')
	{
		if (!($this->request->is('post') || $this->request->is('put'))) {
			$data['Report']['showPurchases'] = 1;
			$data['Report']['showSales'] = 1;
			$data['Report']['showProfitOnSale'] = 1;
			$data['Report']['showPredictedSaleValue'] = 0;
			$data['Report']['showPredictedProfitOnSale'] = 0;

			$this->data = $data;
		}

		$order = [];
		if ($this->data['Report']['showSales']) {
			array_push($order, 'ProductStockReport.sale_amount DESC');
		}
		if ($this->data['Report']['showPurchases']) {
			array_push($order, 'ProductStockReport.purchase_amount DESC');
		}
		if ($this->data['Report']['showProfitOnSale']) {
			array_push($order, 'ProductStockReport.profit_amount DESC');
		}

		if ($order) {
			$order = implode(',', $order);
		} else {
			$order = ['ProductStockReport.purchase_amount DESC'];
		}

		App::uses('ProductStockReport', 'Model');
		$this->ProductStockReport = new ProductStockReport();
		$this->ProductStockReport->bindModel(['belongsTo' => ['Product']]);
		$conditions = ['ProductStockReport.store_id' => $this->Session->read('Store.id')];
		$result = $this->ProductStockReport->find('all', ['conditions' => $conditions, 'order' => $order]);

		$this->set(compact('result', 'type'));
	}

	public function incomeAndExpensesReport()
	{
		App::uses('Category', 'Model');
		$this->Category = new Category();

		$conditions = ['Category.store_id' => $this->Session->read('Store.id')];

		if ($this->request->isPost()) {
			$paymentType = $this->request->data['Report']['payment_type'];
			switch ($paymentType) {
				case 'income':
					$conditions = ['Category.store_id' => $this->Session->read('Store.id'), 'Category.income' => '1'];
					break;
				case 'expense':
					$conditions = ['Category.store_id' => $this->Session->read('Store.id'), 'Category.expense' => '1'];
					break;
				default:
					break;
			}
		}
		$categoriesList = $this->Category->find('list', ['conditions' => $conditions]);
		$this->set(compact('categoriesList'));
	}

	public function cashbookReport()
	{
		App::uses('Category', 'Model');
		$this->Category = new Category();

		$conditions = ['Category.store_id' => $this->Session->read('Store.id')];

		if ($this->request->isPost()) {
			$paymentType = $this->request->data['Report']['payment_type'];
			switch ($paymentType) {
				case 'income':
					$conditions = ['Category.store_id' => $this->Session->read('Store.id'), 'Category.income' => '1'];
					break;
				case 'expense':
					$conditions = ['Category.store_id' => $this->Session->read('Store.id'), 'Category.expense' => '1'];
					break;
				default:
					break;
			}
		}
		$categoriesList = $this->Category->find('list', ['conditions' => $conditions]);
		$this->set(compact('categoriesList'));
	}

	public function generateCashbookReport()
	{
		$result = null;
		$salaries = null;
		$hideHeader = true;
		$hideSideBar = true;
		$purchases = [];
		$sales = [];

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = false;
			$categoryID = $data['Report']['category_id'];
			$paymentType = ($data['Report']['payment_type']) ? $data['Report']['payment_type'] : null;
			$fromDate = $data['Report']['from_date'];
			$toDate = $data['Report']['to_date'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			App::uses('Cashbook', 'Model');
			$this->Cashbook = new Cashbook();
			$conditions = ['Cashbook.store_id' => $this->Session->read('Store.id'), 'Cashbook.payment_date BETWEEN ? AND ?' => [$fromDate, $toDate]];

			if ($paymentType) {
				$conditions[] = ['Cashbook.payment_type' => $paymentType];
			}
			if ($categoryID) {
				$conditions[] = ['Cashbook.category_id' => $categoryID];
			}
			$result = $this->Cashbook->find('all', ['conditions' => $conditions, 'order' => ['Cashbook.payment_date', 'Cashbook.created']]);
		} else {
			$this->Session->setFlash('Invalid Request');
		}

		$this->set(compact('result', 'hideHeader', 'fromDate', 'toDate', 'paymentType', 'hideSideBar'));
	}

	public function generateIncomeAndExpenseReport()
	{
		$result = null;
		$salaries = null;
		$hideHeader = true;
		$hideSideBar = true;
		$purchases = [];
		$sales = [];

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = ($data['Report']['view_type'] == 'print') ? true : false;
			$categoryID = $data['Report']['category_id'];
			$paymentType = ($data['Report']['payment_type']) ? $data['Report']['payment_type'] : null;
			$showSalaries = ($data['Report']['salary']) ? true : false;
			$showSalesPurchases = ($data['Report']['sales_purchases']) ? true : false;
			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			App::uses('Cashbook', 'Model');
			$this->Cashbook = new Cashbook();
			$conditions = ['Cashbook.store_id' => $this->Session->read('Store.id'), 'Cashbook.payment_date BETWEEN ? AND ?' => [$fromDate, $toDate]];

			if ($paymentType) {
				$conditions[] = ['Cashbook.payment_type' => $paymentType];
			}
			if ($categoryID) {
				$conditions[] = ['Cashbook.category_id' => $categoryID];
			}
			$result = $this->Cashbook->find('all', ['conditions' => $conditions, 'order' => 'Cashbook.payment_date']);

			if ($showSalaries) {
				App::uses('Salary', 'Model');
				$this->Salary = new Salary();
				$salaryConditions = ['Salary.store_id' => $this->Session->read('Store.id'), 'Salary.payment_date BETWEEN ? AND ?' => [$fromDate, $toDate]];
				$salaries = $this->Salary->find('all', ['conditions' => $salaryConditions, 'order' => 'Salary.payment_date']);
			}
			if ($showSalesPurchases) {
				if ((empty($paymentType)) or ($paymentType == 'income')) {
					// get all sale records
					App::uses('Sale', 'Model');
					$this->Sale = new Sale();

					$saleConditions = ['Sale.store_id' => $this->Session->read('Store.id'), 'Sale.sale_date BETWEEN ? AND ?' => [$fromDate, $toDate]];
					$sales = $this->Sale->find('all', ['conditions' => $saleConditions, 'order' => 'Sale.sale_date']);
				}
				if ((empty($paymentType)) or ($paymentType == 'expense')) {
					// get all purchase records
					App::uses('Purchase', 'Model');
					$this->Purchase = new Purchase();

					$purchaseConditions = ['Purchase.store_id' => $this->Session->read('Store.id'), 'Purchase.purchase_date BETWEEN ? AND ?' => [$fromDate, $toDate]];
					$purchases = $this->Purchase->find('all', ['conditions' => $purchaseConditions, 'order' => 'Purchase.purchase_date']);
				}
			}
		} else {
			$this->Session->setFlash('Invalid Request');
		}

		$this->set(compact('result', 'salaries', 'hideHeader', 'fromDate', 'toDate', 'paymentType', 'hideSideBar', 'purchases', 'sales'));
	}

	public function download()
	{
		$this->response->type('xls');
		$this->response->header('Content-disposition:attachment;filename=d.xls');
		$this->layout = 'ajax';

		$this->response->send();
	}

	public function purchaseReport()
	{
		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();
		$productCategoriesList = $this->ProductCategory->find('list', ['conditions' => ['ProductCategory.store_id' => $this->Session->read('Store.id')]]);

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$productsList = $this->Product->find('list', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id')]]);

		if ($this->request->isPost()) {
			$categoryID = $this->request->data['Report']['category_id'];
			if ($categoryID) {
				$productsList = $this->Product->find('list', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id'), 'Product.product_category_id' => $categoryID]]);
			}
		}
		$this->set(compact('productCategoriesList', 'productsList'));
	}

	public function generatePurchaseReport()
	{
		$result = null;
		$result_allrecords = null;
		$hideHeader = true;
		$hideSideBar = true;
		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = ($data['Report']['view_type'] == 'print') ? true : false;
			$categoryID = $data['Report']['category_id'];
			$productID = $data['Report']['product_id'];
			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			App::uses('Purchase', 'Model');
			$this->Purchase = new Purchase();
			$conditions = ['Purchase.store_id' => $this->Session->read('Store.id'), 'Purchase.purchase_date BETWEEN ? AND ?' => [$fromDate, $toDate]];

			if ($categoryID) {
				$conditions[] = ['Purchase.product_category_id' => $categoryID];
			}
			if ($productID) {
				$conditions[] = ['Purchase.product_id' => $productID];
			}

			if ($data['Report']['show_all_records'] == '1') {
				$result_allrecords = $this->Purchase->find('all', ['conditions' => $conditions, 'order' => 'Purchase.purchase_date']);
			} else {
				$groupBy = ['Purchase.store_id', 'Purchase.product_category_id', 'Purchase.product_id'];
				$fields = ['Purchase.product_category_id', 'Purchase.product_id', 'Purchase.category_name', 'Purchase.product_name', 'sum(Purchase.total_amount) total_amount', 'sum(Purchase.total_special_margin) total_special_margin', 'sum(Purchase.total_units) total_units'];
				$result = $this->Purchase->find('all', ['conditions' => $conditions, 'fields' => $fields, 'group' => $groupBy, 'order' => ['Purchase.category_name', 'Purchase.product_name']]);
			}
		} else {
			$this->Session->setFlash('Invalid Request');
		}
		$this->set(compact('result', 'result_allrecords', 'hideHeader', 'fromDate', 'toDate', 'hideSideBar'));
	}

	public function salesReport()
	{
		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();
		$productCategoriesList = $this->ProductCategory->find('list', ['conditions' => ['ProductCategory.store_id' => $this->Session->read('Store.id')]]);

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$productsList = $this->Product->find('list', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id')]]);

		if ($this->request->isPost()) {
			$categoryID = $this->request->data['Report']['category_id'];
			if ($categoryID) {
				$productsList = $this->Product->find('list', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id'), 'Product.product_category_id' => $categoryID]]);
			}
		}
		$this->set(compact('productCategoriesList', 'productsList'));
	}

	public function generateSalesReport()
	{
		$result = null;
		$result_allrecords = null;
		$hideHeader = true;
		$hideSideBar = true;
		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = ($data['Report']['view_type'] == 'print') ? true : false;
			$categoryID = $data['Report']['category_id'];
			$productID = $data['Report']['product_id'];
			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			App::uses('Sale', 'Model');
			$this->Sale = new Sale();
			$conditions = ['Sale.store_id' => $this->Session->read('Store.id'), 'Sale.sale_date BETWEEN ? AND ?' => [$fromDate, $toDate]];

			if ($categoryID) {
				$conditions[] = ['Sale.product_category_id' => $categoryID];
			}
			if ($productID) {
				$conditions[] = ['Sale.product_id' => $productID];
			}

			if ($data['Report']['show_all_records'] == '1') {
				$result_allrecords = $this->Sale->find('all', ['conditions' => $conditions, 'order' => 'Sale.sale_date']);
			} else {
				$groupBy = ['Sale.store_id', 'Sale.product_category_id', 'Sale.product_id'];
				$fields = ['Sale.product_category_id', 'Sale.product_id', 'Sale.category_name', 'Sale.product_name', 'sum(Sale.total_amount) total_amount', 'sum(Sale.total_units) total_units'];
				$result = $this->Sale->find('all', ['conditions' => $conditions, 'fields' => $fields, 'group' => $groupBy, 'order' => ['Sale.category_name', 'Sale.product_name']]);
			}
		} else {
			$this->Session->setFlash('Invalid Request');
		}
		$this->set(compact('result', 'result_allrecords', 'hideHeader', 'fromDate', 'toDate', 'hideSideBar'));
	}

	public function ddReport()
	{

	}

	public function generateDdReport()
	{
		$result = null;
		$hideHeader = true;
		$openingBalDD = 0;
		$closingBalDD = 0;

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = ($data['Report']['view_type'] == 'print') ? true : false;
			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			// App::uses('Dd', 'Model');
			// $this->Dd = new Dd;


			// $conditions = array('Dd.store_id'=>$this->Session->read('Store.id'), 'Dd.dd_date BETWEEN ? AND ?'=>array($fromDate, $toDate));
			// $result = $this->Dd->find('all', array('conditions'=>$conditions, 'order'=>'Dd.dd_date'));

			// $conditions = array('Dd.store_id'=>$this->Session->read('Store.id'), 'Dd.dd_date <'=>$fromDate);
			// $fields = array('SUM(Dd.dd_amount) dd_amount', 'SUM(Dd.dd_purchase) dd_purchase');
			// $group = array('Dd.store_id');
			// $openingBalDD = $this->Dd->find('all', array('conditions'=>$conditions, 'fields'=>$fields, 'group'=>$group));

			// $conditions = array('Dd.store_id'=>$this->Session->read('Store.id'), 'Dd.dd_date <='=>$toDate);
			// $closingBalDD = $this->Dd->find('all', array('conditions'=>$conditions, 'fields'=>$fields, 'group'=>$group));
		} else {
			$this->Session->setFlash('Invalid Request');
		}
		$this->set(compact('result', 'hideHeader', 'fromDate', 'toDate', 'openingBalDD', 'closingBalDD'));
	}

	public function invoiceReport()
	{

		if ($this->request->isPost()) {
			$data = $this->request->data;

			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			App::uses('Invoice', 'Model');
			$this->Invoice = new Invoice();

			$invoices = $this->Invoice->find('all', ['conditions' => ['Invoice.store_id' => $this->Session->read('Store.id'), 'Invoice.invoice_date BETWEEN ? AND ?' => [$fromDate, $toDate]], 'order' => ['Invoice.invoice_date DESC', 'Invoice.created DESC']]);

			// get invoice amount from purchases
			App::uses('Purchase', 'Model');
			$this->Purchase = new Purchase();
			$purchaseInfo = $this->Purchase->find('all', ['conditions' => ['Purchase.store_id' => $this->Session->read('Store.id'), 'Purchase.invoice_id NOT' => 'NULL', 'Purchase.purchase_date BETWEEN ? AND ?' => [$fromDate, $toDate]], 'fields' => ['SUM(Purchase.total_amount) as total_amount', 'SUM(Purchase.total_special_margin) as total_special_margin', 'Purchase.invoice_id'], 'group' => ['Purchase.invoice_id']]);
			$invoiceAmount = [];
			if ($purchaseInfo) {
				foreach ($purchaseInfo as $row) {
					$invoiceAmount[$row['Purchase']['invoice_id']] = $row[0]['total_amount'] + $row[0]['total_special_margin'];
				}
			}
		}

		$this->set(compact('invoices', 'invoiceAmount'));
	}

	public function generateInvoiceReport($invoiceID = null, $view = 'normal')
	{
		$hideHeader = true;
		$hideSideBar = true;

		if ($this->request->isGet()) {
			if ($invoiceID) {
				$data = $this->request->data;
				$printView = ($view == 'print') ? true : false;

				if ($printView) {
					$this->layout = 'print_view';
				}

				App::uses('Invoice', 'Model');
				$this->Invoice = new Invoice();
				$invoiceInfo = $this->Invoice->find('first', ['conditions' => ['Invoice.id' => $invoiceID, 'Invoice.store_id' => $this->Session->read('Store.id')]]);
				if ($invoiceInfo) {
					// find invoice products
					App::uses('Purchase', 'Model');
					$this->Purchase = new Purchase();
					$conditions = ['Purchase.invoice_id' => $invoiceID];
					$invoiceProducts = $this->Purchase->find('all', ['conditions' => $conditions]);
				} else {
					$this->Session->setFlash('Invoice Not Found');
				}
			} else {
				$this->Session->setFlash('Invalid Request');
			}
		} else {
			$this->Session->setFlash('Invalid Request');
		}

		$this->set(compact('invoiceInfo', 'invoiceProducts', 'hideHeader', 'hideSideBar'));
	}

	public function invoiceDdReport()
	{

		$invoices = null;
		$prevInvoices = null;
		$viewType = "";
		$showForm = true;
		$hideHeader = false;
		if ($this->request->isPost()) {
			$data = $this->request->data;
			$viewType = $data['Report']['view_type'];
			if ($viewType == 'print') {
				$showForm = false;
				$hideHeader = true;
				$this->layout = 'print_view';
			}

			if ($viewType == 'download') {
				$showForm = false;
				$hideHeader = true;
				Configure::write('debug', 0);
				$this->layout = 'csv';
				$this->response->type('csv');
				$fileName = 'invoice_dd_report-' . time() . '.csv';
				$this->response->download($fileName);

			}

			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			App::uses('Invoice', 'Model');
			$this->Invoice = new Invoice();

			$invoices = $this->Invoice->find('all', ['conditions' => ['Invoice.store_id' => $this->Session->read('Store.id'), 'Invoice.invoice_date BETWEEN ? AND ?' => [$fromDate, $toDate]], 'order' => ['Invoice.invoice_date ASC']]);

			if ($data['Report']['show_prev_balance']) {
				$prevInvoices = $this->Invoice->find('all', ['conditions' => ['Invoice.store_id' => $this->Session->read('Store.id'), 'Invoice.invoice_date < ?' => [$fromDate]], 'order' => ['Invoice.invoice_date ASC']]);
			}

		}

		$this->set(compact('invoices', 'prevInvoices', 'showForm', 'hideHeader', 'viewType', 'fromDate', 'toDate'));
	}

	public function transactionLogReport()
	{
		$showForm = true;
		$hideHeader = false;
		$viewType = 'normal';
		$formSubmitted = false;

		if ($this->request->isPost()) {
			$formSubmitted = true;
			$data = $this->request->data;

			$viewType = $data['Report']['view_type'];
			if ($viewType == 'print') {
				$showForm = false;
				$hideHeader = true;
				$this->layout = 'print_view';
			}

			if ($viewType == 'download') {
				$showForm = false;
				$hideHeader = true;
				Configure::write('debug', 0);
				$this->layout = 'csv';
				$this->response->type('csv');
				$fileName = 'transaction_logs_report-' . time() . '.csv';
				$this->response->download($fileName);

			}

			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			App::uses('TransactionLog', 'Model');
			$this->TransactionLog = new TransactionLog();

			$conditions = ['TransactionLog.store_id' => $this->Session->read('Store.id'), 'TransactionLog.payment_date BETWEEN ? AND ?' => [$fromDate, $toDate]];

			if ($data['Report']['payment_type']) {
				$conditions['TransactionLog.payment_type'] = $data['Report']['payment_type'];
			}
			if ($data['Report']['tag_id']) {
				$conditions['TransactionLog.tag_id'] = $data['Report']['tag_id'];
			}

			$params = [
				'conditions' => $conditions,
				'order' => ['TransactionLog.payment_date' => 'ASC', 'TransactionLog.created' => 'ASC'],
			];
			$logs = $this->TransactionLog->find('all', $params);
		}

		// find tags
		App::uses('Tag', 'Model');
		$this->Tag = new Tag();
		$tags = $this->Tag->find('list', ['conditions' => ['store_id' => $this->Session->read('Store.id')]]);

		$this->set(compact('logs', 'tags', 'showForm', 'hideHeader', 'viewType', 'fromDate', 'toDate', 'formSubmitted'));
	}


	public function dealerBrandPurchases()
	{
		App::uses('ProductCategory', 'Model');
		$this->ProductCategory = new ProductCategory();
		$productCategoriesList = $this->ProductCategory->find('list', ['conditions' => ['ProductCategory.store_id' => $this->Session->read('Store.id')]]);

		App::uses('Product', 'Model');
		$this->Product = new Product();
		$productsList = $this->Product->find('list', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id')]]);


		App::uses('Dealer', 'Model');
		$this->Dealer = new Dealer();
		$dealersList = $this->Dealer->find('list', ['conditions' => ['Dealer.store_id' => $this->Session->read('Store.id')]]);

		App::uses('Brand', 'Model');
		$this->Brand = new Brand();
		$brandsList = $this->Brand->find('list', ['conditions' => ['Brand.store_id' => $this->Session->read('Store.id')]]);

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$dealer_ids = null;
			if (!empty($data['Report']['dealer_id'])) {
				$dealer_ids = implode(',', $data['Report']['dealer_id']);
				$brandsList = $this->Brand->find('list', ['conditions' => ['Brand.store_id' => $this->Session->read('Store.id'), "Brand.dealer_id IN ($dealer_ids)"]]);
			}

			if (!empty($data['Report']['category_id'])) {
				$categoryID = $this->request->data['Report']['category_id'];
				if ($categoryID) {
					$productsList = $this->Product->find('list', ['conditions' => ['Product.store_id' => $this->Session->read('Store.id'), 'Product.product_category_id' => $categoryID]]);
				}
			}
		}
		$this->set(compact('productCategoriesList', 'productsList', 'brandsList', 'dealersList'));
	}

	public function generateDealerBrandPurchases()
	{
		$result = null;
		$detailed_result = null;
		$dealer_brand_result = null;
		$hideHeader = true;
		$hideSideBar = true;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			$dealer_ids = null;
			if (!empty($data['Report']['dealer_id'])) {
				$dealer_ids = implode(',', $data['Report']['dealer_id']);
			}

			$show_brand_purchase_report = false;
			$show_product_purchase_report = false;

			if ($data['Report']['report_type']) {
				if ($data['Report']['report_type'] == 'show_brand_purchase_report') {
					$show_brand_purchase_report = true;
				}
				if ($data['Report']['report_type'] == 'show_product_purchase_report') {
					$show_product_purchase_report = true;
				}
			}

			$brand_ids = null;
			if (!empty($data['Report']['brand_id'])) {
				$brand_ids = implode(',', $data['Report']['brand_id']);
			}

			$printView = ($data['Report']['view_type'] == 'print') ? true : false;

			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			$store_id = $this->Session->read('Store.id');

			$having_condition = ($dealer_ids) ? " having d.id in ($dealer_ids) " : null;
			if ($having_condition) {
				$having_condition .= ($brand_ids) ? " and b.id in ($brand_ids) " : null;
			} else {
				$having_condition = ($brand_ids) ? " having b.id in ($brand_ids) " : null;
			}

			$query = "
				select d.id dealer_id, d.name dealer_name, b.id brand_id, b.name brand_name, p.id product_id, p.name product_name, p.box_qty product_qty_per_box,
				sum(pu.total_amount) total_amount, sum(pu.total_special_margin) total_special_margin, sum(pu.total_units) total_units

				from dealers d

				left join brands b on b.dealer_id = d.id and b.store_id=$store_id
				left join products p on p.brand_id = b.id and p.store_id=$store_id
				left join purchases pu on pu.product_id = p.id and pu.store_id=$store_id and pu.purchase_date >= '$fromDate' and pu.purchase_date <= '$toDate'

				where d.store_id=$store_id

				group by pu.store_id, d.id, b.id, pu.product_id
				$having_condition
				order by d.name, b.name, p.name
			";

			$result = $this->Report->query($query);


			if (!empty($result)) {
				$i = 0;
				foreach ($result as $row) {
					$i++;

					$dealer_name = $row['d']['dealer_name'];
					$brand_name = ($row['b']['brand_name']) ? $row['b']['brand_name'] : '-';
					$product_name = ($row['p']['product_name']) ? $row['p']['product_name'] : '-';
					$total_units = $row[0]['total_units'];
					$units_per_box = $row['p']['product_qty_per_box'];
					$total_boxes = null;
					$balance_units = null;

					if ($total_units and $units_per_box) {
						$total_boxes = (int)($total_units / $units_per_box);
						$balance_units = ($total_units % $units_per_box);
					}


					$detailed_result[$i]['dealer_name'] = $dealer_name;
					$detailed_result[$i]['brand_name'] = $brand_name;
					$detailed_result[$i]['product_name'] = $product_name;
					$detailed_result[$i]['total_units'] = $total_units;
					$detailed_result[$i]['units_per_box'] = $units_per_box;
					$detailed_result[$i]['total_boxes'] = $total_boxes;
					$detailed_result[$i]['balance_units'] = $balance_units;

					$dealer_brand_result[$dealer_name][$brand_name]['boxes'][] = $total_boxes;
					$dealer_brand_result[$dealer_name][$brand_name]['units'][] = $balance_units;
				}
			}
		} else {
			$this->Session->setFlash('Invalid Request');
		}
		$this->set(compact('result', 'hideHeader', 'fromDate', 'toDate', 'hideSideBar', 'dealer_brand_result', 'detailed_result', 'show_brand_purchase_report', 'show_product_purchase_report'));
	}

	public function bankReport()
	{

	}

	public function generateBankReport()
	{
		$result = null;

		$hideHeader = true;
		$hideSideBar = true;
		$showPrevBalance = false;
		$prev_balance = 0;

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = ($data['Report']['view_type'] == 'print') ? true : false;
			$showPrevBalance = ($data['Report']['show_prev_balance']) ? true : false;

			$paymentType = ($data['Report']['payment_type']) ? $data['Report']['payment_type'] : null;
			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			App::uses('Bank', 'Model');
			$this->Bank = new Bank();
			$conditions = ['Bank.store_id' => $this->Session->read('Store.id'), 'Bank.payment_date BETWEEN ? AND ?' => [$fromDate, $toDate]];
			if ($paymentType) {
				$conditions[] = ['Bank.payment_type' => $paymentType];
			}
			$result = $this->Bank->find('all', ['conditions' => $conditions, 'order' => ['Bank.payment_date', 'Bank.created']]);

			if ($showPrevBalance) {
				$conditions = ['Bank.store_id' => $this->Session->read('Store.id'), 'Bank.payment_date < ?' => [$fromDate]];
				if ($paymentType) {
					$conditions[] = ['Bank.payment_type' => $paymentType];
				}
				$result2 = $this->Bank->find('all', ['conditions' => $conditions, 'order' => ['Bank.payment_date', 'Bank.created']]);

				if (!empty($result2)) {
					foreach ($result2 as $row) {
						if ($row['Bank']['payment_type'] == 'credit') {
							$prev_balance += $row['Bank']['amount'];
						}
						if ($row['Bank']['payment_type'] == 'debit') {
							$prev_balance -= $row['Bank']['amount'];
						}
					}
				}
			}
		} else {
			$this->Session->setFlash('Invalid Request');
		}

		$this->set(compact('result', 'hideHeader', 'fromDate', 'toDate', 'paymentType', 'hideSideBar', 'prev_balance', 'showPrevBalance'));
	}

	public function transactionsReport()
	{
		App::uses('TransactionCategory', 'Model');
		$this->TransactionCategory = new TransactionCategory();

		$conditions = ['TransactionCategory.store_id' => $this->Session->read('Store.id')];
		$categoriesList = $this->TransactionCategory->find('list', ['conditions' => $conditions]);
		$this->set(compact('categoriesList'));
	}

	public function generateTransactionsReport()
	{
		$result = null;
		$salaries = null;
		$hideHeader = true;
		$hideSideBar = true;
		$purchases = [];
		$sales = [];

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$printView = false;
			$categoryID = $data['Report']['category_id'];
			$paymentType = ($data['Report']['payment_type']) ? $data['Report']['payment_type'] : null;
			$fromDate = $data['Report']['from_date'];
			$toDate = $data['Report']['to_date'];

			if ($printView) {
				$this->layout = 'print_view';
			}

			App::uses('Transaction', 'Model');
			$this->Transaction = new Transaction();
			$conditions = ['Transaction.store_id' => $this->Session->read('Store.id'), 'Transaction.payment_date BETWEEN ? AND ?' => [$fromDate, $toDate]];

			if ($paymentType) {
				$conditions[] = ['Transaction.payment_type' => $paymentType];
			}
			if ($categoryID) {
				$conditions[] = ['Transaction.transaction_category_id' => $categoryID];
			}
			$result = $this->Transaction->find('all', ['conditions' => $conditions, 'order' => ['Transaction.payment_date', 'Transaction.created']]);
		} else {
			$this->Session->setFlash('Invalid Request');
		}

		$this->set(compact('result', 'hideHeader', 'fromDate', 'toDate', 'paymentType', 'hideSideBar'));
	}
}
