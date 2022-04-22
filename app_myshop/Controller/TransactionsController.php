<?php
App::uses('Validation', 'Utility');

class TransactionsController extends AppController
{

	public $name = 'Transaction';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkSeller();
		// $this->checkStoreInfo();
	}

	/**
	 * Function to show list of category products
	 */
	public function index($transactionCategoryId = null)
	{
		App::uses('TransactionCategory', 'Model');
		$this->TransactionCategory = new TransactionCategory();

		$conditions = ['TransactionCategory.site_id' => $this->Session->read('Site.id'), 'TransactionCategory.active' => 1];
		$categoriesList = $this->TransactionCategory->find('list', ['conditions' => $conditions]);

		//debug($categoriesList);


		$hideSideBar = false;
		$categoryInfo = null;
		if ($transactionCategoryId) {
			if (!$categoryInfo = $this->getTransactionCategoryInfo($transactionCategoryId)) {
				$error = 'TransactionCategory not found.';
				$this->Session->setFlash('TransactionCategory not found');
				$this->redirect('/transactions/index/');
			}
			$conditions = ['Transaction.transaction_category_id' => $transactionCategoryId];
		} else {
			$conditions = ['Transaction.site_id' => $this->Session->read('Site.id')];
		}
		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['Transaction.payment_date' => 'DESC', 'Transaction.created' => 'DESC'],
			'limit' => 100,
			'recursive' => '-1',
		];
		$cashbook = $this->paginate();
		if ($this->Session->check('paymentDate')) {
			$data['Transaction']['payment_date'] = $this->Session->read('paymentDate');
			$this->data = $data;
		}
		$this->set(compact('cashbook', 'categoryInfo', 'hideSideBar', 'categoriesList', 'transactionCategoryId'));
	}

	public function add()
	{
		$error = null;

		App::uses('TransactionCategory', 'Model');
		$this->TransactionCategory = new TransactionCategory();

		$conditions = ['TransactionCategory.site_id' => $this->Session->read('Site.id'), 'TransactionCategory.active' => 1];
		$categoriesList = $this->TransactionCategory->find('list', ['conditions' => $conditions]);

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			$transactionCategoryId = $data['Transaction']['transaction_category_id'];
			$paymentDate = $data['Transaction']['payment_date'];
			$data['Transaction']['payment_date'] = $paymentDate;
			$data['Transaction']['transaction_category_id'] = $transactionCategoryId;

			$error = $this->addCashbookFormValidation($data);
			// check if category is available
			if (!$error) {
				if (!$categoryInfo = $this->getTransactionCategoryInfo($transactionCategoryId)) {
					$error = 'TransactionCategory not found.';
				} else {
					// check if duplicate record is entered.
					$conditions = ['Transaction.transaction_category_id' => $transactionCategoryId, 'Transaction.payment_date' => $paymentDate, 'Transaction.payment_amount' => $data['Transaction']['payment_amount'], 'Transaction.description' => $data['Transaction']['description']];
					if ($this->Transaction->find('first', ['conditions' => $conditions])) {
						$error = 'Duplicate entry. A similar record already exists.';
					}
				}
			}

			if (!$error) {
				$data['Transaction']['id'] = null;
				$data['Transaction']['site_id'] = $this->Session->read('Site.id');
				$data['Transaction']['payment_date'] = $paymentDate;
				$data['Transaction']['transaction_category_name'] = $categoryInfo['TransactionCategory']['name'];

				if ($this->Transaction->save($data)) {
					$this->Session->write('paymentDate', $paymentDate);
					$msg = 'New record created.';
					$this->successMsg($msg);
					$this->redirect('/transactions/');
				}
			}
		} else {
			if ($this->Session->check('paymentDate')) {
				$data['Transaction']['payment_date'] = $this->Session->read('paymentDate');
				$this->data = $data;
			}
		}

		if ($error) {
			$this->errorMsg($error);
		}
		// $this->redirect($this->request->referer());
		$this->set(compact('categoriesList'));
	}

	public function addCashbookFormValidation($data = null)
	{
		$error = null;

		if ($data) {
			if (!isset($data['Transaction']['transaction_category_id'])) {
				$error = 'TransactionCategory not found';
			}
//			if ((!isset($data['Transaction']['payment_amount'])) OR (!Validation::decimal($data['Transaction']['payment_amount'])) OR ($data['Transaction']['payment_amount'] <= 0)) {
//				$error = 'Payment amount should be greater than 0';
//			}
		} else {
			$error = 'Empty record';
		}
		return $error;
	}

	public function remove($recordID = null)
	{
		if ($this->request->isPost()) {
			if ($cashbookInfo = $this->Transaction->findById($recordID)) {
				$this->Transaction->delete($recordID);
				$this->successMsg('Record deleted.');
			} else {
				$this->errorMsg('Record not found.');
			}
		} else {
			$this->errorMsg('Invalid request.');
		}

		$this->redirect($this->request->referer());
	}
}
