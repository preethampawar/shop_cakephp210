<?php
App::uses('CakeEmail', 'Network/Email');

class CategoriesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		// $this->Auth->allow('getCategories');
	}


	function getCategories()
	{
		$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.active' => '1', 'Category.parent_id' => null];
		$categories = $this->Category->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC']);
		return $categories;
	}

	function admin_getCategories()
	{
		$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.parent_id' => null];
		$categories = $this->Category->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC']);
		return $categories;
	}

	function admin_index()
	{
		$categoryInfoLinkActive = true;
		$this->set('categoryInfoLinkActive', $categoryInfoLinkActive);
	}

	function admin_add()
	{
		$errorMsg = '';
		if ($this->request->isPost()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Category']['name'])) {
				$errorMsg = 'Enter Category Name';
			}
			// Sanitize data
			$data['Category']['name'] = Sanitize::paranoid($data['Category']['name'], [' ', '-']);
			if (!$errorMsg) {
				$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.name' => $data['Category']['name']];
				if ($this->Category->find('first', ['conditions' => $conditions])) {
					$errorMsg = 'Category "' . $data['Category']['name'] . '" already exists';
				} else {
					$data['Category']['site_id'] = $this->Session->read('Site.id');
					if ($this->Category->save($data)) {
						$this->successMsg('Category successfully added');
					} else {
						$errorMsg = 'An error occurred while communicating with the server';
					}
				}
			}
		}

		$errorMsg ? $this->errorMsg($errorMsg) : '';

		$this->redirect('/admin/categories/index');
	}

	function admin_edit($categoryID)
	{
		$errorMsg = [];
		$categoryInfoLinkActive = true;
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'default']);
			$this->redirect('/admin/categories/');
		}

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Category']['name'])) {
				$errorMsg[] = 'Enter Category Name';
			}
			// Sanitize data
			$data['Category']['name'] = Sanitize::paranoid($data['Category']['name'], [' ', '-']);
			if (!$errorMsg) {
				$conditions = ['Category.site_id' => $this->Session->read('Site.id'), 'Category.name' => $data['Category']['name'], 'Category.id NOT' => $categoryID];
				if ($this->Category->find('first', ['conditions' => $conditions])) {
					$errorMsg[] = 'Category "' . $data['Category']['name'] . '" already exists';
				} else {
					$data['Category']['site_id'] = $this->Session->read('Site.id');
					$data['Category']['id'] = $categoryID;
					if ($this->Category->save($data)) {
						$this->successMsg('Category successfully updated');
						$this->redirect('/admin/categories/add');
					} else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}
			}
		} else {
			$this->data = $categoryInfo;
		}

		$errorMsg = implode('<br>', $errorMsg);

		$errorMsg ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg', 'categoryInfo', 'categoryInfoLinkActive'));
	}

	function admin_delete($categoryID)
	{
		if ($categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->deleteCategory($categoryID);
			$this->successMsg('Category successfully deleted');
		} else {
			$this->errorMsg('Category Not Found');
		}

		$this->redirect('/admin/categories/');
	}

	function admin_showProducts($categoryID)
	{
		$errorMsg = null;
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', ['class' => 'default']);
			$this->redirect('/admin/categories/');
		}

		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$conditions = ['CategoryProduct.category_id' => $categoryID];

		$this->CategoryProduct->unbindModel(['belongsTo' => ['Category']]);
		$categoryProducts = $this->CategoryProduct->findAllByCategoryId($categoryID);

		$tmp = [];
		$productsList = [];
		if (!empty($categoryProducts)) {
			foreach ($categoryProducts as $row) {
				$tmp[$row['Product']['id']] = $row;
				$productsList[$row['Product']['id']] = ucwords($row['Product']['name']);
			}
			asort($productsList);
			$categoryProducts = $tmp;
		}

		$productsLimitExceeded  = $this->productsLimitExceeded();

		$this->set(compact('errorMsg', 'categoryInfo', 'categoryProducts', 'productsList', 'productsLimitExceeded'));
	}

}

?>
