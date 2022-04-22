<?php

class BrandsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	public function index()
	{
		$this->Brand->recursive = 0;

		$conditions = ['Brand.store_id' => $this->Session->read('Store.id')];
		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['Brand.created' => 'DESC'],
			'limit' => 100,
			'recursive' => '-1',
		];
		$brands = $this->paginate();
		$this->set(compact('brands'));
	}

	public function showDealerBrands()
	{
		$this->Brand->recursive = 0;

		$dealer_ids = null;
		if ($this->request->data) {
			$data = $this->request->data;
			if ($data['Brand']['dealer_id']) {
				$dealer_ids = implode(',', $data['Brand']['dealer_id']);
			}
		}

		$conditions = ['Brand.store_id' => $this->Session->read('Store.id'), 'Brand.dealer_id > 0'];
		if ($dealer_ids) {
			$conditions = ["Brand.dealer_id IN ($dealer_ids)"];
		}
		$brands = $this->Brand->find('all', ['conditions' => $conditions, 'order' => ['Dealer.name', 'Brand.name']]);

		App::uses('Dealer', 'Model');
		$this->Dealer = new Dealer();
		$conditions = ['Dealer.store_id' => $this->Session->read('Store.id')];

		$dealers = $this->Dealer->find('list', ['conditions' => $conditions]);

		$this->set(compact('brands', 'dealers'));
	}

	public function view($brandID = null)
	{
		/*
		$this->Brand->bindModel(array('hasMany'=>array('Salary'=>array(
																'className'=>'Salary',
																'order'=>'payment_date DESC'
																)
														)));
		*/
		if (!($brandInfo = $this->Brand->findById($brandID))) {
			$this->Session->setFlash('Brand not found');
			$this->redirect(['controller' => 'brands', 'action' => 'index']);
		}
		$this->set(compact('brandInfo'));
	}

	public function add()
	{
		$errorMsg = null;
		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Brand']['name'])) {

				if ($data['Brand']['name'] = trim($data['Brand']['name'])) {
					$conditions = ['Brand.name' => $data['Brand']['name'], 'Brand.store_id' => $this->Session->read('Store.id')];
					if ($this->Brand->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Brand']['name'] . "'" . ' already exists';
					} else {
						$data['Brand']['store_id'] = $this->Session->read('Store.id');
						if ($this->Brand->save($data)) {
							$brandInfo = $this->Brand->read();
							$msg = 'Brand added successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'success']);
							$this->redirect(['controller' => 'brands', 'action' => 'add']);
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				} else {
					$errorMsg = 'Enter Brand Name';
				}

			}
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;

		$conditions = ['Brand.store_id' => $this->Session->read('Store.id')];
		$params = [
			'conditions' => $conditions,
			'order' => ['Brand.created' => 'DESC'],
			'limit' => 5,
			'recursive' => '-1',
		];
		$brands = $this->Brand->find('all', $params);
		$this->set(compact('brands'));
	}

	public function addDealerBrands()
	{
		$errorMsg = null;
		$selected_dealer_id = null;
		$dealer_brands = null;

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Brand']['dealer_id']) and !empty($data['Brand']['dealer_id'])) {
				if ($data['Brand']['submit'] == 1) {
					if ((isset($data['Brand']['id'])) and !empty($data['Brand']['id'])) {
						foreach ($data['Brand']['id'] as $brand_id) {
							$tmp = null;
							$tmp['Brand']['id'] = $brand_id;
							$tmp['Brand']['dealer_id'] = $data['Brand']['dealer_id'];
							if (!$this->Brand->save($tmp)) {
								$errorMsg .= "Dealer with Brand ID = '$brand_id' could not be saved <br>";
							}
						}
						if (!$errorMsg) {
							$successMsg = 'Data saved successfully';
							$this->Session->setFlash($successMsg, 'default', ['class' => 'success']);
							$this->redirect('/brands/addDealerBrands');
						}
					} else {
						$errorMsg = 'Select Brand';
					}
				}
				$selected_dealer_id = $data['Brand']['dealer_id'];

			}
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;

		$conditions = ['Brand.store_id' => $this->Session->read('Store.id')];
		$brands = $this->Brand->find('list', ['conditions' => $conditions]);

		App::uses('Dealer', 'Model');
		$this->Dealer = new Dealer();
		$conditions = ['Dealer.store_id' => $this->Session->read('Store.id')];
		$dealers = $this->Dealer->find('list', ['conditions' => $conditions]);


		if ($selected_dealer_id) {
			$dealer_brands = $this->Brand->find('list', ['conditions' => ['Brand.dealer_id' => $selected_dealer_id], 'fields' => ['id']]);
		}

		$this->set(compact('brands', 'dealers', 'dealer_brands'));
	}

	public function addBrandProducts()
	{
		App::uses('Product', 'Model');
		$this->Product = new Product();

		if ($this->request->data) {
			$data = $this->request->data;
			if (!empty($data['brand_id'])) {
				foreach ($data['brand_id'] as $product_id => $brand_id) {
					$tmp = null;
					$tmp['Product']['id'] = $product_id;
					$tmp['Product']['brand_id'] = $brand_id;
					$this->Product->save($tmp);
				}

				$msg = 'Data updated successfully';
				$this->Session->setFlash($msg, 'default', ['class' => 'success']);
				$this->redirect('/brands/addBrandProducts');
			}
		}

		$conditions = ['Brand.store_id' => $this->Session->read('Store.id')];
		$brands = $this->Brand->find('list', ['conditions' => $conditions, 'order' => ['Brand.name']]);

		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		$products = $this->Product->find('list', ['conditions' => $conditions, 'order' => 'Product.name']);
		$product_brand_list = $this->Product->find('list', ['conditions' => $conditions, 'fields' => ['Product.id', 'Product.brand_id']]);

		$this->set(compact('brands', 'products', 'product_brand_list'));
	}

	public function showAllBrandProducts()
	{
		App::uses('Product', 'Model');
		$this->Product = new Product();

		$brand_ids = null;
		if ($this->request->data) {
			$data = $this->request->data;
			if ($data['Brand']['brand_id']) {
				$brand_ids = implode(',', $data['Brand']['brand_id']);
			}
		}

		$conditions = ['Product.store_id' => $this->Session->read('Store.id')];
		if ($brand_ids) {
			$conditions = ['Product.store_id' => $this->Session->read('Store.id'), "Product.brand_id IN ($brand_ids)"];
		}
		$this->Product->bindModel(['belongsTo' => ['Brand']]);

		$products = $this->Product->find('all', ['conditions' => $conditions, 'order' => ['Brand.name', 'Product.name']]);

		$conditions = ['Brand.store_id' => $this->Session->read('Store.id')];
		$brands = $this->Brand->find('list', ['conditions' => $conditions, 'order' => ['Brand.name']]);

		$this->set(compact('products', 'brands'));
	}

	public function edit($brandID = null)
	{
		$errorMsg = null;

		if (!($brandInfo = $this->CommonFunctions->getBrandInfo($brandID))) {
			$this->Session->setFlash('Brand not found');
			$this->redirect('/brands/');
		}

		if ($this->request->data) {
			$data = $this->request->data;
			if (isset($data['Brand']['name'])) {
				if ($data['Brand']['name'] = trim($data['Brand']['name'])) {

					$conditions = ['Brand.name' => $data['Brand']['name'], 'Brand.store_id' => $this->Session->read('Store.id'), 'Brand.id <>' => $brandID];
					if ($this->Brand->find('first', ['conditions' => $conditions])) {
						$errorMsg = "'" . $data['Brand']['name'] . "'" . ' already exists';
					} else {
						$data['Brand']['id'] = $brandID;
						$data['Brand']['store_id'] = $this->Session->read('Store.id');
						if ($this->Brand->save($data)) {
							$msg = 'Brand updated successfully';
							$this->Session->setFlash($msg, 'default', ['class' => 'success']);
							$this->redirect('/brands/');
						} else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				} else {
					$errorMsg = 'Enter Brand Name';
				}
			}
		} else {
			$this->data = $brandInfo;
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		$this->set(compact('brandInfo'));
	}

	public function remove($brandID = null)
	{
		if ($brandID) {
			if (!($brandInfo = $this->CommonFunctions->getBrandInfo($brandID))) {
				$this->Session->setFlash('Brand not found');
			} else {
				if (!empty($brandInfo['Product'])) {
					App::uses('Product', 'Model');
					$this->Product = new Product();

					foreach ($brandInfo['Product'] as $product) {
						$tmp = null;
						$tmp['Product']['id'] = $product['id'];
						$tmp['Product']['brand_id'] = null;
						$this->Product->save($tmp);
					}
				}

				// delete brand information
				$this->Brand->delete($brandID);
				$this->Session->setFlash('Brand "' . $brandInfo['Brand']['name'] . '" has been removed', 'default', ['class' => 'success']);
			}
		} else {
			$this->Session->setFlash('Unauthorized access');
		}
		$this->redirect($this->request->referer());
	}

	public function removeDealer($brandID = null)
	{
		if ($brandID) {
			if (!($brandInfo = $this->CommonFunctions->getBrandInfo($brandID))) {
				$this->Session->setFlash('Brand not found');
			} else {
				$tmp['Brand']['id'] = $brandID;
				$tmp['Brand']['dealer_id'] = null;
				$this->Brand->save($tmp);
				$this->Session->setFlash('Brand "' . $brandInfo['Brand']['name'] . '" has been removed', 'default', ['class' => 'success']);
			}
		} else {
			$this->Session->setFlash('Unauthorized access');
		}
		$this->redirect(['action' => 'showDealerBrands']);
	}

}
