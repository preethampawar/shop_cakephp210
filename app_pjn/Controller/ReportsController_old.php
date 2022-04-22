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

		$fromDate = null;
		$toDate = null;
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
				Configure::write('debug', 0);
				$this->layout = 'csv';
				$this->response->type('csv');
				$fileName = 'StockReport-' . time() . '.csv';
				$this->response->download($fileName);
			}
			$showForm = false;
			$hideHeader = true;

			$productID = $data['Report']['product_id'];
			$fromDate = $data['Report']['from_date']['year'] . '-' . $data['Report']['from_date']['month'] . '-' . $data['Report']['from_date']['day'];
			$toDate = $data['Report']['to_date']['year'] . '-' . $data['Report']['to_date']['month'] . '-' . $data['Report']['to_date']['day'];
			$store_id = $this->Session->read('Store.id');
			if (strtotime($fromDate) > strtotime($toDate)) {
				$error = '"From Date" cannot be greater than "To Date"';
			}

			if (!$error) {
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
		SELECT p.id,p.name,p.product_category_id,p.unit_selling_price,p.box_buying_price,p.box_qty, c.name, (COALESCE(os_purchases.pu_qty,0)-COALESCE(os_sales.s_qty,0)) opening_stock,
			COALESCE(rs_purchases.pu_qty,0) stock_added, COALESCE(rs_sales.s_qty,0) stock_sale,
			COALESCE(cs_breakages.b_qty,0) stock_breakage,
			(COALESCE(cs_purchases.pu_qty,0)-COALESCE(cs_sales.s_qty,0)-COALESCE(cs_breakages.b_qty,0)) closing_stock
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

		#closing stock
		  LEFT JOIN
			#get closing sales
			(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date<=@to_date GROUP BY s.product_id) cs_sales ON cs_sales.product_id=p.id
		  LEFT JOIN
			#get closing purchases
			(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date<=@to_date GROUP BY pu.product_id) cs_purchases ON cs_purchases.product_id=p.id
			#get breakage stock
		  LEFT JOIN
			(SELECT b.product_id, SUM(total_units) b_qty FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date<=@to_date GROUP BY b.product_id) cs_breakages ON cs_breakages.product_id=p.id

		#purchase and sales stock in date range
		  LEFT JOIN
			#get purchases in date range
			(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date BETWEEN @from_date AND @to_date GROUP BY pu.product_id) rs_purchases ON rs_purchases.product_id=p.id
		  LEFT JOIN
			#get sales in date range
			(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date BETWEEN @from_date AND @to_date GROUP BY s.product_id) rs_sales ON rs_sales.product_id=p.id
		WHERE p.store_id=@store_id
		ORDER BY p.name
			";

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
		SELECT p.id,p.name,p.product_category_id,p.unit_selling_price,p.box_buying_price,p.box_qty, c.name,
			(COALESCE(os_purchases.pu_qty,0)-COALESCE(os_sales.s_qty,0)) opening_stock,
			COALESCE(rs_purchases.pu_qty,0) stock_added,
			COALESCE(rs_sales.s_qty,0) stock_sale,
			COALESCE(cs_breakages.b_qty,0) stock_breakage,
			(COALESCE(cs_purchases.pu_qty,0)-COALESCE(cs_sales.s_qty,0)-COALESCE(cs_breakages.b_qty,0)) closing_stock
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

		#closing stock
		  LEFT JOIN
			#get closing sales
			(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date<=@to_date GROUP BY s.product_id) cs_sales ON cs_sales.product_id=p.id
		  LEFT JOIN
			#get closing purchases
			(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date<=@to_date GROUP BY pu.product_id) cs_purchases ON cs_purchases.product_id=p.id
			#get breakage stock
		  LEFT JOIN
			(SELECT b.product_id, SUM(total_units) b_qty FROM breakages b WHERE b.store_id=@store_id AND b.breakage_date<=@to_date GROUP BY b.product_id) cs_breakages ON cs_breakages.product_id=p.id


		#purchase and sales stock in date range
		  LEFT JOIN
			#get purchases in date range
			(SELECT pu.product_id, SUM(total_units) pu_qty FROM purchases pu WHERE pu.store_id=@store_id AND pu.purchase_date BETWEEN @from_date AND @to_date GROUP BY pu.product_id) rs_purchases ON rs_purchases.product_id=p.id
		  LEFT JOIN
			#get sales in date range
			(SELECT s.product_id, SUM(total_units) s_qty FROM sales s WHERE s.store_id=@store_id AND s.sale_date BETWEEN @from_date AND @to_date GROUP BY s.product_id) rs_sales ON rs_sales.product_id=p.id
		WHERE p.id=@product_id AND p.store_id=@store_id
		ORDER BY p.name
		";
		$this->Report->query("SET @store_id='$store_id'");
		$this->Report->query("SET @from_date='$fromDate'");
		$this->Report->query("SET @to_date='$toDate'");
		$this->Report->query("SET @product_id='$productID'");
		$result = $this->Report->query($query);
		return $result;
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
}
