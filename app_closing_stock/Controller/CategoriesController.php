<?php

class CategoriesController extends AppController
{

	public $name = 'Categories';

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
		$hideSideBar = true;
		$conditions = ['Category.store_id' => $this->Session->read('Store.id')];
		$categories = $this->Category->find('all', [
			'order' => ['Category.name' => 'ASC'],
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

			$data['Category'] = $this->request->data['Category'];
			$data['Category']['name'] = trim($data['Category']['name']);

			if (!empty($data['Category']['name'])) {
				if (!Validation::between($data['Category']['name'], 2, 55)) {
					$error = 'Category name should be between 2 and 55 characters';
				}
				$data['Category']['name'] = htmlentities($data['Category']['name'], ENT_QUOTES);

				//find if a similar category exists for the selected store
				$conditions = ['Category.name' => $data['Category']['name'], 'Category.store_id' => $this->Session->read('Store.id')];
				if ($this->Category->find('first', ['conditions' => $conditions])) {
					$error = 'Category "' . $data['Category']['name'] . '" already exists';
				}
			} else {
				$error = 'Category name cannot be empty';
			}

			if (!$error) {
				$data['Category']['id'] = null;
				$data['Category']['store_id'] = $this->Session->read('Store.id');
				if ($this->Category->save($data)) {
					$msg = 'Category "' . $data['Category']['name'] . '" Created.';
					$this->successMsg($msg);
				} else {
					$error = 'An error occurred while creating a new category';
				}
			}
		}
		if ($error) {
			$this->errorMsg($error);
		}
		$this->redirect('/categories/');
	}

	public function edit($categoryID = null)
	{
		$hideSideBar = true;

		if (!$pCatInfo = $this->getCategoryInfo($categoryID)) {
			$this->errorMsg('Category not found.');
			$this->redirect('/categories/');
		}

		$error = null;
		if (isset($this->request->data) and !empty($this->request->data)) {
			App::uses('Validation', 'Utility');

			$data['Category'] = $this->request->data['Category'];
			$data['Category']['name'] = trim($data['Category']['name']);

			if (!empty($data['Category']['name'])) {
				if (!Validation::between($data['Category']['name'], 2, 55)) {
					$error = 'Category name should be between 2 and 55 characters';
				}
				$data['Category']['name'] = htmlentities($data['Category']['name'], ENT_QUOTES);

				//find if a similar category exists for the selected store
				$conditions = ['Category.name' => $data['Category']['name'], 'Category.store_id' => $this->Session->read('Store.id'), 'Category.id <>' => $categoryID];
				if ($this->Category->find('first', ['conditions' => $conditions])) {
					$error = 'Category "' . $data['Category']['name'] . '" already exists';
				}
			} else {
				$error = 'Category name cannot be empty';
			}

			if (!$error) {
				$data['Category']['id'] = $categoryID;
				$data['Category']['store_id'] = $this->Session->read('Store.id');
				if ($this->Category->save($data)) {
					$this->successMsg('Category Updated.');
					$this->redirect('/categories/');
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

	public function getCategoryInfo($categoryID = null)
	{
		if (!$categoryID) {
			return [];
		} else {
			$conditions = ['Category.id' => $categoryID, 'Category.store_id' => $this->Session->read('Store.id')];
			if ($expenseCategoryInfo = $this->Category->find('first', ['conditions' => $conditions])) {
				return $expenseCategoryInfo;
			}
		}
		return [];
	}

	public function delete($categoryID = null)
	{
		if (!$info = $this->getCategoryInfo($categoryID)) {
			$this->Session->setFlash('Category not found', 'default', ['class' => 'error']);
		} else {
			// delete category records
			App::uses('Cashbook', 'Model');
			$this->Cashbook = new Cashbook();
			$conditions = ['Cashbook.category_id' => $categoryID, 'Cashbook.store_id' => $this->Session->read('Store.id')];
			$this->Cashbook->deleteAll($conditions);

			// delete category info
			$this->Category->delete($categoryID);
			$this->Session->setFlash('"' . $info['Category']['name'] . '" Category deleted', 'default', ['class' => 'success']);
		}
		$this->redirect($this->request->referer());
	}

}

?>
