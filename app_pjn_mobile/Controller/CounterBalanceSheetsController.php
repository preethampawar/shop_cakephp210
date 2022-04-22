<?php
App::uses('Validation', 'Utility');

class CounterBalanceSheetsController extends AppController
{

	public $name = 'CounterBalanceSheets';

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
		$conditions = ['CounterBalanceSheet.store_id' => $this->Session->read('Store.id')];
		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['CounterBalanceSheet.from_date' => 'DESC', 'CounterBalanceSheet.created' => 'DESC'],
			'limit' => 100,
			'recursive' => '-1',
		];
		$sheets = $this->paginate();

		$this->set(compact('sheets'));
	}

	public function addCounterBalanceSheetFormValidation($data = null)
	{
		$error = null;

		if ($data) {
			if (!isset($data['CounterBalanceSheet']['employee_id'])) {
				$error = 'employee not found';
			}
			if ((!isset($data['CounterBalanceSheet']['payment_amount'])) OR (!Validation::decimal($data['CounterBalanceSheet']['payment_amount'])) OR ($data['CounterBalanceSheet']['payment_amount'] <= 0)) {
				$error = 'Payment amount should be greater than 0';
			}
		} else {
			$error = 'Empty CounterBalanceSheet details';
		}
		return $error;
	}

	public function add()
	{
		$error = null;

		$fromDate = date('Y-m-d', strtotime('-1 Weeks +1 Day'));
		$toDate = date('Y-m-d');

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			$fromDate = $data['CounterBalanceSheet']['from_date']['year'] . '-' . $data['CounterBalanceSheet']['from_date']['month'] . '-' . $data['CounterBalanceSheet']['from_date']['day'];
			$toDate = $data['CounterBalanceSheet']['to_date']['year'] . '-' . $data['CounterBalanceSheet']['to_date']['month'] . '-' . $data['CounterBalanceSheet']['to_date']['day'];
		}

		// Get sales information
		App::uses('Sale', 'Model');
		$this->Sale = new Sale();
		$conditions = ['Sale.store_id' => $this->Session->read('Store.id'), 'Sale.sale_date >=' => $fromDate, 'Sale.sale_date <=' => $toDate];
		$sales = $this->Sale->find('all', ['conditions' => $conditions, 'order' => 'Sale.sale_date', 'recursive' => '-1']);
		$sale_amount = 0;
		if ($sales) {
			foreach ($sales as $row) {
				$sale_amount += $row['Sale']['total_amount'];
			}
		}

		// Get expense information
		App::uses('Cashbook', 'Model');
		$this->Cashbook = new Cashbook();
		$conditions = ['Cashbook.store_id' => $this->Session->read('Store.id'), 'Cashbook.payment_date >=' => $fromDate, 'Cashbook.payment_date <=' => $toDate];
		$cashbook_records = $this->Cashbook->find('all', ['conditions' => $conditions, 'order' => 'Cashbook.payment_date', 'recursive' => '-1']);
		$expense_amount = 0;
		if ($cashbook_records) {
			foreach ($cashbook_records as $row) {
				if ($row['Cashbook']['payment_type'] == 'expense') {
					$expense_amount += $row['Cashbook']['payment_amount'];
				} else {
					$expense_amount -= $row['Cashbook']['payment_amount'];
				}
			}
		}

		// Get transaction log information
		App::uses('TransactionLog', 'Model');
		$this->TransactionLog = new TransactionLog();
		$conditions = ['TransactionLog.store_id' => $this->Session->read('Store.id'), 'TransactionLog.payment_date >=' => $fromDate, 'TransactionLog.payment_date <=' => $toDate];
		$logs = $this->TransactionLog->find('all', ['conditions' => $conditions, 'order' => 'TransactionLog.payment_date']);
		$log_expense_amount = 0;
		$transactions = [];
		$transaction_balance = 0;
		if ($logs) {
			foreach ($logs as $row) {
				$transactions[$row['TransactionLog']['tag_id']]['name'] = $row['Tag']['name'];
				if ($row['TransactionLog']['payment_type'] == 'expense') {
					$transaction_balance += $row['TransactionLog']['amount'];

					if (isset($transactions[$row['TransactionLog']['tag_id']]['expense'])) {
						$old_expense = $transactions[$row['TransactionLog']['tag_id']]['expense'];
						$transactions[$row['TransactionLog']['tag_id']]['expense'] = $old_expense + $row['TransactionLog']['amount'];
					} else {
						$transactions[$row['TransactionLog']['tag_id']]['expense'] = $row['TransactionLog']['amount'];
					}
				} else {
					$transaction_balance -= $row['TransactionLog']['amount'];
					if (isset($transactions[$row['TransactionLog']['tag_id']]['income'])) {
						$old_income = $transactions[$row['TransactionLog']['tag_id']]['income'];
						$transactions[$row['TransactionLog']['tag_id']]['income'] = $old_income + $row['TransactionLog']['amount'];
					} else {
						$transactions[$row['TransactionLog']['tag_id']]['income'] = $row['TransactionLog']['amount'];
					}
				}
			}
		}

		// save data
		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;
			if ($data['CounterBalanceSheet']['save_data']) {

				$data['CounterBalanceSheet']['from_date'] = $data['CounterBalanceSheet']['selected_from_date'];
				$data['CounterBalanceSheet']['to_date'] = $data['CounterBalanceSheet']['selected_to_date'];
				unset($data['CounterBalanceSheet']['save_data']);
				unset($data['CounterBalanceSheet']['selected_from_date']);
				unset($data['CounterBalanceSheet']['selected_to_date']);
				$data['CounterBalanceSheet']['store_id'] = $this->Session->read('Store.id');
				$data['CounterBalanceSheet']['id'] = null;

				if ($this->CounterBalanceSheet->save($data)) {
					$msg = 'Success! Counter Balance Sheet has been created.';
					$this->Session->setFlash($msg, 'default', ['class' => 'success']);
					$this->redirect(['controller' => 'CounterBalanceSheets', 'action' => 'index']);
				} else {
					$msg = 'Error! An error occurred while connecting with the server. Please try again later.';
					$this->Session->setFlash($msg, 'default', ['class' => 'success']);
				}
			}
		}

		// find recent counter balance sheet records
		$conditions = ['CounterBalanceSheet.store_id' => $this->Session->read('Store.id')];
		$sheets = $this->CounterBalanceSheet->find('all', ['conditions' => $conditions, 'order' => 'CounterBalanceSheet.created DESC', 'recursive' => '-1', 'limit' => '10']);

		if ($error) {
			$this->Session->setFlash($error);
		}
		$this->set(compact('sale_amount', 'expense_amount', 'sheets', 'fromDate', 'toDate', 'transactions', 'transaction_balance'));
	}

	public function details($id)
	{
		$sheet = $this->CounterBalanceSheet->findById($id);

		if ($sheet) {
			// Get transaction log information
			$fromDate = $sheet['CounterBalanceSheet']['from_date'];
			$toDate = $sheet['CounterBalanceSheet']['to_date'];

			App::uses('TransactionLog', 'Model');
			$this->TransactionLog = new TransactionLog();
			$conditions = ['TransactionLog.store_id' => $this->Session->read('Store.id'), 'TransactionLog.payment_date >=' => $fromDate, 'TransactionLog.payment_date <=' => $toDate];
			$logs = $this->TransactionLog->find('all', ['conditions' => $conditions, 'order' => 'TransactionLog.payment_date']);
			$log_expense_amount = 0;
			$transactions = [];
			if ($logs) {
				foreach ($logs as $row) {
					$transactions[$row['TransactionLog']['tag_id']]['name'] = $row['Tag']['name'];
					if ($row['TransactionLog']['payment_type'] == 'expense') {
						if (isset($transactions[$row['TransactionLog']['tag_id']]['expense'])) {
							$old_expense = $transactions[$row['TransactionLog']['tag_id']]['expense'];
							$transactions[$row['TransactionLog']['tag_id']]['expense'] = $old_expense + $row['TransactionLog']['amount'];
						} else {
							$transactions[$row['TransactionLog']['tag_id']]['expense'] = $row['TransactionLog']['amount'];
						}
					} else {
						if (isset($transactions[$row['TransactionLog']['tag_id']]['income'])) {
							$old_income = $transactions[$row['TransactionLog']['tag_id']]['income'];
							$transactions[$row['TransactionLog']['tag_id']]['income'] = $old_income + $row['TransactionLog']['amount'];
						} else {
							$transactions[$row['TransactionLog']['tag_id']]['income'] = $row['TransactionLog']['amount'];
						}
					}
				}
			}
		}

		$this->set(compact('sheet', 'transactions'));
	}


	public function remove($CounterBalanceSheetID = null)
	{
		if ($this->request->isPost()) {
			if ($CounterBalanceSheetInfo = $this->CounterBalanceSheet->findById($CounterBalanceSheetID)) {
				$this->CounterBalanceSheet->delete($CounterBalanceSheetID);
				$this->Session->setFlash('Success! Record has been deleted.', 'default', ['class' => 'success']);
			} else {
				$this->Session->setFlash('Record not found');
			}
		} else {
			$this->Session->setFlash('Invalid request');
		}

		$this->redirect($this->request->referer());
	}


}

?>
