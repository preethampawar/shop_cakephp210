<?php

class TransactionCategoriesController extends AppController
{

	public $name = 'TransactionCategories';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	/**
	 * Function to show list of categories
	 */
	public function index()
	{
		$hideSideBar = false;
		$conditions = ['TransactionCategory.store_id' => $this->Session->read('Store.id')];
		$categories = $this->TransactionCategory->find('all', [
			'order' => ['TransactionCategory.name' => 'ASC'],
			'conditions' => $conditions,
			'recursive' => '-1',
		]);
		$this->set(compact('categories', 'hideSideBar'));
	}

	public function add()
	{
		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			App::uses('Validation', 'Utility');

			$data['TransactionCategory'] = $this->request->data['TransactionCategory'];
			$data['TransactionCategory']['name'] = trim($data['TransactionCategory']['name']);

			if (!empty($data['TransactionCategory']['name'])) {
				if (!Validation::between($data['TransactionCategory']['name'], 2, 55)) {
					$error = 'TransactionCategory name should be between 2 and 55 characters';
				}
				$data['TransactionCategory']['name'] = htmlentities($data['TransactionCategory']['name'], ENT_QUOTES);

				//find if a similar category exists for the selected store
				$conditions = ['TransactionCategory.name' => $data['TransactionCategory']['name'], 'TransactionCategory.store_id' => $this->Session->read('Store.id')];
				if ($this->TransactionCategory->find('first', ['conditions' => $conditions])) {
					$error = 'TransactionCategory "' . $data['TransactionCategory']['name'] . '" already exists';
				}
			} else {
				$error = 'TransactionCategory name cannot be empty';
			}

			if (!$error) {
				$data['TransactionCategory']['id'] = null;
				$data['TransactionCategory']['store_id'] = $this->Session->read('Store.id');
				if ($this->TransactionCategory->save($data)) {
					$msg = 'TransactionCategory "' . $data['TransactionCategory']['name'] . '" Created.';
					$this->successMsg($msg);
				} else {
					$error = 'An error occurred while creating a new category';
				}
			}
		}
		if ($error) {
			$this->errorMsg($error);
		}
		$this->redirect('/TransactionCategories/');
	}

	public function edit($transactionCategoryId = null)
	{
		$hideSideBar = false;

		if (!$pCatInfo = $this->getTransactionCategoryInfo($transactionCategoryId)) {
			$this->errorMsg('TransactionCategory not found.');
			$this->redirect('/TransactionCategories/');
		}

		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			App::uses('Validation', 'Utility');

			$data['TransactionCategory'] = $this->request->data['TransactionCategory'];
			$data['TransactionCategory']['name'] = trim($data['TransactionCategory']['name']);

			if (!empty($data['TransactionCategory']['name'])) {
				if (!Validation::between($data['TransactionCategory']['name'], 2, 55)) {
					$error = 'TransactionCategory name should be between 2 and 55 characters';
				}
				$data['TransactionCategory']['name'] = htmlentities($data['TransactionCategory']['name'], ENT_QUOTES);

				//find if a similar category exists for the selected store
				$conditions = ['TransactionCategory.name' => $data['TransactionCategory']['name'], 'TransactionCategory.store_id' => $this->Session->read('Store.id'), 'TransactionCategory.id <>' => $transactionCategoryId];
				if ($this->TransactionCategory->find('first', ['conditions' => $conditions])) {
					$error = 'TransactionCategory "' . $data['TransactionCategory']['name'] . '" already exists';
				}
			} else {
				$error = 'TransactionCategory name cannot be empty';
			}

			if (!$error) {
//				debug($data);
//				exit;

				$data['TransactionCategory']['id'] = $transactionCategoryId;
				$data['TransactionCategory']['store_id'] = $this->Session->read('Store.id');
				if ($this->TransactionCategory->save($data)) {
					$this->successMsg('TransactionCategory Updated.');
					$this->redirect('/TransactionCategories/');
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
			$this->errorMsg($error);
		}
	}

	public function getTransactionCategoryInfo($transactionCategoryId = null)
	{
		if (!$transactionCategoryId) {
			return [];
		} else {
			$conditions = ['TransactionCategory.id' => $transactionCategoryId, 'TransactionCategory.store_id' => $this->Session->read('Store.id')];
			if ($expenseCategoryInfo = $this->TransactionCategory->find('first', ['conditions' => $conditions])) {
				return $expenseCategoryInfo;
			}
		}
		return [];
	}

	public function delete($transactionCategoryId = null)
	{
		if (!$info = $this->getTransactionCategoryInfo($transactionCategoryId)) {
			$this->Session->setFlash('TransactionCategory not found', 'default', ['class' => 'error']);
		} else {
			// delete category records
			App::uses('Transaction', 'Model');
			$this->Transaction = new Transaction();
			$conditions = ['Transaction.category_id' => $transactionCategoryId, 'Transaction.store_id' => $this->Session->read('Store.id')];
			$this->Transaction->deleteAll($conditions);

			// delete category info
			$this->TransactionCategory->delete($transactionCategoryId);
			$this->Session->setFlash('"' . $info['TransactionCategory']['name'] . '" TransactionCategory deleted', 'default', ['class' => 'success']);
		}
		$this->redirect($this->request->referer());
	}

}

?>
