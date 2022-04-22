<?php
App::uses('CakeEmail', 'Network/Email');

class CategoriesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('getCategories');
	}


	function getCategories()
	{
		$conditions = array('Category.site_id' => $this->Session->read('Site.id'), 'Category.active' => '1', 'Category.parent_id' => null);
		$categories = $this->Category->find('all', array('conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC'));
		return $categories;
	}

	function admin_getCategories()
	{
		$conditions = array('Category.site_id' => $this->Session->read('Site.id'), 'Category.parent_id' => null);
		$categories = $this->Category->find('all', array('conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC'));
		return $categories;
	}

	function admin_index()
	{
		$categoryInfoLinkActive = true;
		$this->set('categoryInfoLinkActive', $categoryInfoLinkActive);
	}

	function admin_add()
	{
		$categoryInfoLinkActive = true;
		$errorMsg = array();
		if ($this->request->isPost()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Category']['name'])) {
				$errorMsg[] = 'Enter Category Name';
			}
			// Sanitize data
			$data['Category']['name'] = Sanitize::paranoid($data['Category']['name'], array(' ', '-'));
			if (!$errorMsg) {
				$conditions = array('Category.site_id' => $this->Session->read('Site.id'), 'Category.name' => $data['Category']['name']);
				if ($this->Category->find('first', array('conditions' => $conditions))) {
					$errorMsg[] = 'Category "' . $data['Category']['name'] . '" already exists';
				} else {
					$data['Category']['site_id'] = $this->Session->read('Site.id');
					if ($this->Category->save($data)) {
						$this->Session->setFlash('Category successfully added', 'default', array('class' => 'success'));
						$this->redirect('/admin/categories/add');
					} else {
						$errorMsg[] = 'An error occured while communicating with the server';
					}
				}
			}
		}

		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('errorMsg', 'categoryInfoLinkActive'));
	}

	function admin_edit($categoryID)
	{
		$errorMsg = array();
		$categoryInfoLinkActive = true;
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', array('class' => 'default'));
			$this->redirect('/admin/categories/');
		}

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			// Validate name
			if (Validation::blank($data['Category']['name'])) {
				$errorMsg[] = 'Enter Category Name';
			}
			// Sanitize data
			$data['Category']['name'] = Sanitize::paranoid($data['Category']['name'], array(' ', '-'));
			if (!$errorMsg) {
				$conditions = array('Category.site_id' => $this->Session->read('Site.id'), 'Category.name' => $data['Category']['name'], 'Category.id NOT' => $categoryID);
				if ($this->Category->find('first', array('conditions' => $conditions))) {
					$errorMsg[] = 'Category "' . $data['Category']['name'] . '" already exists';
				} else {
					$data['Category']['site_id'] = $this->Session->read('Site.id');
					$data['Category']['id'] = $categoryID;
					if ($this->Category->save($data)) {
						$this->Session->setFlash('Category successfully added', 'default', array('class' => 'success'));
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
		$this->set(compact('errorMsg', 'categoryInfo', 'categoryInfoLinkActive'));
	}

	function admin_delete($categoryID)
	{
		if ($categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->deleteCategory($categoryID);
			$this->Session->setFlash('Category successfully deleted', 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash('Category Not Found', 'default', array('class' => 'error'));
		}
		$this->redirect('/admin/categories/');
	}

	function admin_showProducts($categoryID)
	{
		$errorMsg = null;
		if (!$categoryInfo = $this->isSiteCategory($categoryID)) {
			$this->Session->setFlash('Category Not Found', 'default', array('class' => 'default'));
			$this->redirect('/admin/categories/');
		}

		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$conditions = array('CategoryProduct.category_id' => $categoryID);

		$this->CategoryProduct->unbindModel(array('belongsTo' => array('Category')));
		$categoryProducts = $this->CategoryProduct->findAllByCategoryId($categoryID);

		$tmp = array();
		$productsList = array();
		if (!empty($categoryProducts)) {
			foreach ($categoryProducts as $row) {
				$tmp[$row['Product']['id']] = $row;
				$productsList[$row['Product']['id']] = ucwords($row['Product']['name']);
			}
			asort($productsList);
			$categoryProducts = $tmp;
		}

		$this->set(compact('errorMsg', 'categoryInfo', 'categoryProducts', 'productsList'));
	}

}

?>
