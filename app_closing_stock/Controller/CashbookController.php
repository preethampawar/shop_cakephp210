<?php
App::uses('Validation', 'Utility');

class CashbookController extends AppController
{

	public $name = 'Cashbook';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	/**
	 * Function to show list of category products
	 */
	public function index($categoryID = null)
	{
		App::uses('Category', 'Model');
		$this->Category = new Category();

		$conditions = ['Category.store_id' => $this->Session->read('Store.id'), 'Category.active' => 1];
		$categoriesList = $this->Category->find('list', ['conditions' => $conditions]);

		//debug($categoriesList);


		$hideSideBar = true;
		$categoryInfo = null;
		if ($categoryID) {
			if (!$categoryInfo = $this->CommonFunctions->getCategoryInfo($categoryID)) {
				$error = 'Category not found.';
				$this->Session->setFlash('Category not found');
				$this->redirect('/cashbook/index/');
			}
			$conditions = ['Cashbook.category_id' => $categoryID];
		} else {
			$conditions = ['Cashbook.store_id' => $this->Session->read('Store.id')];
		}
		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['Cashbook.payment_date' => 'DESC', 'Cashbook.created' => 'DESC'],
			'limit' => 100,
			'recursive' => '-1',
		];
		$cashbook = $this->paginate();
		if ($this->Session->check('paymentDate')) {
			$data['Cashbook']['payment_date'] = $this->Session->read('paymentDate');
			$this->data = $data;
		}
		$this->set(compact('cashbook', 'categoryInfo', 'hideSideBar', 'categoriesList', 'categoryID'));
	}

	public function add()
	{
		$error = null;

		App::uses('Category', 'Model');
		$this->Category = new Category();

		$conditions = ['Category.store_id' => $this->Session->read('Store.id'), 'Category.active' => 1];
		$categoriesList = $this->Category->find('list', ['conditions' => $conditions]);

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			$categoryID = $data['Cashbook']['category_id'];
			$paymentDate = $data['Cashbook']['payment_date'];
			$data['Cashbook']['payment_date'] = $paymentDate;
			$data['Cashbook']['category_id'] = $categoryID;

			$error = $this->addCashbookFormValidation($data);
			// check if category is available
			if (!$error) {
				if (!$categoryInfo = $this->CommonFunctions->getCategoryInfo($categoryID)) {
					$error = 'Category not found.';
				} else {
					// check if duplicate record is entered.
					$conditions = ['Cashbook.category_id' => $categoryID, 'Cashbook.payment_date' => $paymentDate, 'Cashbook.payment_amount' => $data['Cashbook']['payment_amount'], 'Cashbook.description' => $data['Cashbook']['description']];
					if ($this->Cashbook->find('first', ['conditions' => $conditions])) {
						$error = 'Duplicate entry. A similar record already exists.';
					}
				}
			}

			if (!$error) {
				$data['Cashbook']['id'] = null;
				$data['Cashbook']['store_id'] = $this->Session->read('Store.id');
				$data['Cashbook']['payment_date'] = $paymentDate;
				$data['Cashbook']['category_name'] = $categoryInfo['Category']['name'];

				if ($this->Cashbook->save($data)) {
					$this->Session->write('paymentDate', $paymentDate);
					$msg = 'New record created.';
					$this->successMsg($msg);
					$this->redirect('/cashbook/');
				}
			}
		} else {
			if ($this->Session->check('paymentDate')) {
				$data['Cashbook']['payment_date'] = $this->Session->read('paymentDate');
				$this->data = $data;
			}
		}

		if ($error) {
			$this->Session->setFlash($error);
		}
		// $this->redirect($this->request->referer());
		$this->set(compact('categoriesList'));
	}

	public function addCashbookFormValidation($data = null)
	{
		$error = null;

		if ($data) {
			if (!isset($data['Cashbook']['category_id'])) {
				$error = 'Category not found';
			}
			if ((!isset($data['Cashbook']['payment_amount'])) OR (!Validation::decimal($data['Cashbook']['payment_amount'])) OR ($data['Cashbook']['payment_amount'] <= 0)) {
				$error = 'Payment amount should be greater than 0';
			}
		} else {
			$error = 'Empty record';
		}
		return $error;
	}

	public function remove($recordID = null)
	{
		if ($this->request->isPost()) {
			if ($cashbookInfo = $this->Cashbook->findById($recordID)) {
				$this->Cashbook->delete($recordID);
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

?>
