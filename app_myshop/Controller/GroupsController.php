<?php
App::uses('CakeEmail', 'Network/Email');

class GroupsController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	function index()
	{
		$conditions = [
			'Group.site_id' => $this->Session->read('Site.id'),
			'Group.deleted' => 0,
		];
		$this->paginate = [
			'limit' => 50,
			'order' => ['Group.created' => 'DESC'],
			'conditions' => $conditions,
			'recursive' => -1
		];
		$groups = $this->paginate();
		$title_for_layout = 'Groups';
		$this->set(compact('groups', 'title_for_layout'));
	}

	function admin_index()
	{
		$sort = ['Group.name' => 'ASC'];

		$conditions = [
			'Group.site_id' => $this->Session->read('Site.id'),
			'Group.deleted' => 0,
		];
		$groups = $this->Group->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('groups'));
	}

	function admin_add()
	{
		$errorMsg = null;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (empty($data['Group']['name'])) {
				$errorMsg = 'Group name is required';
			} else {
				$conditions = [
					'Group.name' => $data['Group']['name'],
					'Group.site_id' => $this->Session->read('Site.id'),
					'Group.deleted' => 0
				];

				if ($this->Group->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Group with same name already exist.";
				}
			}

			if (!$errorMsg) {
				$data['Group']['name'] = $data['Group']['name'];
				$data['Group']['site_id'] = $this->Session->read('Site.id');

				if ($this->Group->save($data)) {
					$groupInfo = $this->Group->read();

					$this->successMsg('Group created successfully.');
					$this->redirect('/admin/groups/');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		}

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg'));
	}

	function admin_edit($groupId)
	{
		if (!$contentInfo = $this->isSiteGroup($groupId)) {
			$this->errorMsg('Group Not Found');
			$this->redirect('/admin/groups/');
		}

		$errorMsg = null;

		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['Group']['name'])) {
				$errorMsg = 'Group name is required';
			} else {
				$conditions = [
					'Group.name' => $data['Group']['name'],
					'Group.id NOT' => $groupId,
					'Group.site_id' => $this->Session->read('Site.id'),
					'Group.deleted' => 0
				];

				if ($this->Group->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Group with same name already exist.";
				}


			}

			if (!$errorMsg) {
				$data['Group']['id'] = $groupId;

				if ($this->Group->save($data)) {

					$groupRates = $this->Group->find('list', ['fields' => ['Group.id', 'Group.rate'], 'conditions' => ['Group.site_id' => $this->Session->read('Site.id'), 'Group.deleted' => 0]]);

					App::uses('Product', 'Model');
					$productModel = new Product();
					$productModel->unbindModel(['hasMany' => 'CategoryProduct']);
					$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.deleted' => 0, 'Product.group_id' => $groupId];
					$products = $productModel->find('all',
						[
							'conditions' => $conditions,
							'fields' => [
								'Product.id',
								'Product.name',
								'Product.mrp',
								'Product.allow_relative_price_update',
								'Product.relative_base_price',
								'Product.group_id',
								'Product.relative_price_relation',
								'Product.discount',
							],
							'order' => ['Product.name ASC', 'Product.group_id ASC']
						]
					);

					foreach($products as $row) {
						$mrp = 0;
						$tmp = [];
						$tmp['Product']['id'] = $row['Product']['id'];
						$tmp['Product']['group_id'] = $row['Product']['group_id'];
						$tmp['Product']['relative_price_relation'] = trim($row['Product']['relative_price_relation']);
						$tmp['Product']['relative_base_price'] = trim($row['Product']['relative_base_price']);
						$tmp['Product']['allow_relative_price_update'] = (int)$row['Product']['allow_relative_price_update'];

						if ($row['Product']['group_id']) {
							$baseRate = (float)($groupRates[$row['Product']['group_id']] ?? 0);
							$relativeBaseRate = $tmp['Product']['relative_base_price'];

							if ($tmp['Product']['allow_relative_price_update']) {

								try {
									switch($tmp['Product']['relative_price_relation']) {
										case '+':
											$mrp = (float)eval("return ($baseRate+($relativeBaseRate));");
											break;
										case '-':
											$mrp = (float)eval("return ($baseRate-($relativeBaseRate));");
											break;
										case '*':
											$mrp = (float)eval("return ($baseRate*$relativeBaseRate);");
											break;
									}
								} catch (Throwable $e) {
									//debug($e->getMessage());
								}
							}

							if ($mrp) {
								$tmp['Product']['mrp'] = ceil($mrp);
							}
						}

						$productModel->save($tmp);
					}

					$this->successMsg('Group information and associated products MRP updated successfully');
					$this->redirect('/admin/groups');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}

		if ($errorMsg) {
			$this->errorMsg($errorMsg);
		}

		$this->set(compact('errorMsg', 'contentInfo'));
	}

	public function admin_activate($groupId, $type)
	{
		if (!$contentInfo = $this->isSiteGroup($groupId)) {
			$this->errorMsg('Group Not Found');
			$this->redirect('/admin/groups/');
		}

		$data['Group']['id'] = $groupId;
		$data['Group']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->Group->save($data)) {
			$this->successMsg('Group modified successfully');
		} else {
			$this->errorMsg('An error occurred while updating data');
		}
		$this->redirect('/admin/groups/');
	}

	public function admin_delete($groupId)
	{
		if (!$groupInfo = $this->isSiteGroup($groupId)) {
			$this->errorMsg('Group Not Found');
		} else {

			$data['Group']['id'] = $groupId;
			$data['Group']['active'] = 0;
			$data['Group']['deleted'] = 1;

			if ($this->Group->save($data)) {
				$this->successMsg('Group deleted successfully.');
			} else {
				$this->errorMsg('An error occurred. Could not delete Group.');
			}
			$this->redirect('/admin/groups/');
		}
		$this->redirect('/admin/groups/');
	}

	function admin_products($groupId = null)
	{
		$errorMsg = null;
		$groupRates = $this->Group->find('list', ['fields' => ['Group.id', 'Group.rate'], 'conditions' => ['Group.site_id' => $this->Session->read('Site.id'), 'Group.deleted' => 0]]);

		App::uses('Product', 'Model');
		$productModel = new Product();
		$productModel->unbindModel(['hasMany' => 'CategoryProduct']);

		if ($this->request->isPost()) {
			$data = $this->request->data;

			foreach($data['Group'] as $row) {
				$mrp = 0;
				$tmp = [];
				$tmp['Product']['id'] = $row['Product']['id'];
				$tmp['Product']['group_id'] = $row['Product']['group_id'];
				$tmp['Product']['relative_price_relation'] = trim($row['Product']['relative_price_relation']);
				$tmp['Product']['relative_base_price'] = trim($row['Product']['relative_base_price']);
				$tmp['Product']['allow_relative_price_update'] = (int)$row['Product']['allow_relative_price_update'];
				if ($row['Product']['group_id']) {
					$baseRate = (float)($groupRates[$row['Product']['group_id']] ?? 0);
					$relativeBaseRate = $tmp['Product']['relative_base_price'];

					if ($tmp['Product']['allow_relative_price_update']) {
						try {
							switch($tmp['Product']['relative_price_relation']) {
								case '+':
									$mrp = (float)eval("return ($baseRate+($relativeBaseRate));");
									break;
								case '-':
									$mrp = (float)eval("return ($baseRate-($relativeBaseRate));");
									break;
								case '*':
									$mrp = (float)eval("return ($baseRate*$relativeBaseRate);");
									break;
							}
						} catch (Throwable $e) {
							//debug($e->getMessage());
						}
					}

					if ($mrp) {
						$tmp['Product']['mrp'] = ceil($mrp);
					}

				}

				$productModel->save($tmp);
			}
		}

		$groups = $this->Group->find('all', ['conditions' => ['Group.site_id' => $this->Session->read('Site.id'), 'Group.deleted' => 0], 'order' => ['Group.name']]);

		$groupsList = [];
		foreach($groups as $index => $row) {
			$groupName = $row['Group']['name'] . ' [' . $row['Group']['rate'] . ']';
			$groupsList[$row['Group']['id']] = $groupName;
		}
		unset($groups);



		$conditions = ['Product.site_id' => $this->Session->read('Site.id'), 'Product.deleted' => 0];

		if ($groupId) {
			$conditions['Product.group_id'] = $groupId;
		}

		$products = $productModel->find('all',
			[
				'conditions' => $conditions,
				'fields' => [
					'Product.id',
					'Product.name',
					'Product.mrp',
					'Product.allow_relative_price_update',
					'Product.relative_base_price',
					'Product.group_id',
					'Product.relative_price_relation',
					'Product.discount',
				],
				'order' => ['Product.name ASC', 'Product.group_id ASC']
			]
		);
		// debug($products);

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg', 'products', 'groupsList', 'groupId', 'groupRates'));
	}

}

?>
