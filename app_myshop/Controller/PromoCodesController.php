<?php
App::uses('CakeEmail', 'Network/Email');

class PromoCodesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	function admin_index()
	{
		$sort = ['PromoCode.created' => 'DESC'];

		$conditions = [
			'PromoCode.site_id' => $this->Session->read('Site.id'),
			'PromoCode.deleted' => 0
		];
		$promoCodes = $this->PromoCode->find('all', ['conditions' => $conditions, 'order' => $sort]);

		$this->set(compact('promoCodes'));
	}

	function admin_add()
	{
		$errorMsg = null;

		if ($this->request->isPost()) {
			$data = $this->request->data;

			if (empty($data['PromoCode']['name'])) {
				$errorMsg = 'Promo Code is required';
			} else {
				$conditions = [
					'PromoCode.name' => $data['PromoCode']['name'],
					'PromoCode.site_id' => $this->Session->read('Site.id'),
					'PromoCode.deleted' => 0
				];

				if ($this->PromoCode->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Promo Code already exist.";
				}
			}

			if (!$errorMsg) {
				$data['PromoCode']['name'] = $data['PromoCode']['name'];
				$data['PromoCode']['site_id'] = $this->Session->read('Site.id');

				if ($this->PromoCode->save($data)) {
					$promocodeInfo = $this->PromoCode->read();

					$this->successMsg('PromoCode created successfully.');
					$this->redirect('/admin/promo_codes/');
				} else {
					$errorMsg = 'An error occurred while updating data';
				}
			}
		}

		($errorMsg) ? $this->errorMsg($errorMsg) : '';

		$this->set(compact('errorMsg'));
	}

	function admin_edit($promoCodeId)
	{
		if (!$contentInfo = $this->isSitePromoCode($promoCodeId)) {
			$this->errorMsg('Promo Code Not Found');
			$this->redirect('/admin/promo_codes/');
		}

		$errorMsg = null;

		if ($this->request->isPut()) {
			$data = $this->request->data;

			if (empty($data['PromoCode']['name'])) {
				$errorMsg = 'name is a required field';
			} else {
				$conditions = [
					'PromoCode.name' => $data['PromoCode']['name'],
					'PromoCode.id NOT' => $promoCodeId,
					'PromoCode.site_id' => $this->Session->read('Site.id'),
					'PromoCode.deleted' => 0
				];

				if ($this->PromoCode->find('first', ['conditions' => $conditions])) {
					$errorMsg = "Promo Code with same name already exist.";
				}
			}

			if (!$errorMsg) {
				$data['PromoCode']['id'] = $promoCodeId;

				if ($this->PromoCode->save($data)) {
					$this->successMsg('Promo Code updated successfully');
					$this->redirect('/admin/promo_codes/edit/'.$promoCodeId);
				} else {
					$errorMsg = 'An errorMsg occurred while updating data';
				}
			}
		} else {
			$this->data = $contentInfo;
		}
		$this->set(compact('errorMsg', 'contentInfo'));
	}

	public function admin_activate($promoCodeId, $type)
	{
		if (!$contentInfo = $this->isSitePromoCode($promoCodeId)) {
			$this->errorMsg('PromoCode Not Found');
			$this->redirect('/admin/promo_codes/');
		}

		$data['PromoCode']['id'] = $promoCodeId;
		$data['PromoCode']['active'] = ($type == 'true') ? '1' : '0';

		if ($this->PromoCode->save($data)) {
			$this->successMsg('PromoCode modified successfully');
		} else {
			$this->errorMsg('An error occurred while updating data');
		}
		$this->redirect('/admin/promo_codes/');
	}

	public function admin_delete($promoCodeId)
	{
		if (!$contentInfo = $this->isSitePromoCode($promoCodeId)) {
			$this->errorMsg('PromoCode Not Found.');
		} else {
			$tmp['PromoCode']['id'] = $promoCodeId;
			$tmp['PromoCode']['deleted'] = 1;
			$this->PromoCode->save($tmp);
			$this->successMsg('Promo Code deleted successfully.');
		}
		$this->redirect('/admin/promo_codes/');
	}

	public function applyCode($encodedPromoCode)
	{
		$this->layout = 'ajax';
		$error = null;
		$successMsg = null;

		try {
			$promoCode = base64_decode($encodedPromoCode);
			$promoCodeInfo = $this->isActivePromoCode($promoCode);

			if ($promoCodeInfo) {
				$this->validatePromoCodeById($promoCodeInfo['PromoCode']['id']);

				$this->Session->delete('PromoCode');
				$this->Session->write('PromoCode', $promoCodeInfo['PromoCode']);
				$successMsg = 'Promo Code applied successfully.';
			} else {
				throw new Exception('Invalid Promo Code.');
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
		}

		$this->set('error', $error);
		$this->set('successMsg', $successMsg);
	}

	private function validatePromoCodeById($promoCodeId) {
		$this->PromoCode->recursive = -1;
		$promoCodeInfo = $this->PromoCode->findById($promoCodeId);

		if (!$promoCodeInfo) {
			throw new Exception('Promo Code not found.');
		}

		$promoCodeUsage = $promoCodeInfo['PromoCode']['redeem_type'];

		if ($promoCodeUsage === PromoCode::PROMO_CODE_REDEEM_TYPE_SINGLE) {
			// promo code usage is only single time per user. allowed only for logged in users

			if (!$this->Session->check('User.id')) {
				throw new Exception('You have to <a href="/users/login">login</a> to apply this Promo Code <b>"' . $promoCodeInfo['PromoCode']['name'] . '"</b>.');
			}

			// check if the logged in user has already used this promo code
			App::uses('Order', 'Model');
			$orderModel = new Order();

			$conditions = [
				'Order.promo_code_id' => $promoCodeInfo['PromoCode']['id'],
				'Order.user_id' => $this->Session->read('User.id'),
				'Order.site_id' => $this->Session->read('Site.id'),
			];

			if ($orderModel->find('first', ['conditions' => $conditions, 'recursive' => -1])) {
				throw new Exception('Sorry! You have already used this promo code.');
			}
		}

		return $promoCodeInfo;
	}

	public function removeCode()
	{
		$this->layout = false;

		if ($this->Session->check('PromoCode')) {
			$this->Session->delete('PromoCode');
		}

		$success = 'Promo Code successfully removed.';
		$errorMsg = null;

		$this->response->body('{"error": "' . false . '", "successMsg": "' . $success . '", "errorMsg": "' . $errorMsg . '"}');
		$this->response->type('application/json');
		$this->response->send();
		exit;
	}

	private function isActivePromoCode($promoCode)
	{
		$now = date('Y-m-d 00:00:00');
		$condition = [
			'PromoCode.active' => 1,
			'PromoCode.name' => $promoCode,
			'PromoCode.site_id' => $this->Session->read('Site.id'),
			'PromoCode.start_date <=' => $now,
			'PromoCode.end_date >=' => $now,
			'PromoCode.deleted' => 0,
		];
		$this->PromoCode->recursive = -1;
		$promoCodeInfo = $this->PromoCode->find('first', ['conditions' => $condition]);

		if (empty($promoCodeInfo)) {
			return false;
		}

		return $promoCodeInfo;
	}


}
