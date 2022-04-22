<?php
App::uses('CakeEmail', 'Network/Email');

class GroupPaperRatesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	/**
	 * Function to show list of category products
	 */
	public function index($groupId = null)
	{
		App::uses('Group', 'Model');
		$this->Group = new Group();

		$conditions = ['Group.site_id' => $this->Session->read('Site.id'), 'Group.active' => 1];
		$groupList = $this->Group->find('list', ['conditions' => $conditions]);

		$groupInfo = null;
		if ($groupId) {
			if (!$groupInfo = $this->getGroupInfo($groupId)) {
				$error = 'Group not found.';
				$this->Session->setFlash('Group not found');
				$this->redirect('/GroupPaperRates/index/');
			}
			$conditions = ['GroupPaperRate.group_id' => $groupId];
		} else {
			$conditions = ['GroupPaperRate.site_id' => $this->Session->read('Site.id')];
		}
		$this->paginate = [
			'conditions' => $conditions,
			'order' => ['GroupPaperRate.date' => 'DESC', 'GroupPaperRate.created' => 'DESC'],
			'limit' => 100,
			'recursive' => '-1',
		];
		$paperRates = $this->paginate();
		
		if ($this->Session->check('date')) {
			$data['GroupPaperRate']['date'] = $this->Session->read('date');
			$this->data = $data;
		}
		$this->set(compact('paperRates', 'groupInfo', 'groupList', 'groupId'));
	}

	public function add()
	{
		$error = null;

		App::uses('Group', 'Model');
		$this->Group = new Group();

		$conditions = ['Group.site_id' => $this->Session->read('Site.id'), 'Group.active' => 1];
		$groupList = $this->Group->find('list', ['conditions' => $conditions]);

		if ($this->request->isPost() or $this->request->isPut()) {
			$data = $this->request->data;

			$groupId = $data['GroupPaperRate']['group_id'];
			$date = $data['GroupPaperRate']['date'];			

			$error = $this->paperRatesFormValidation($data);
			
			// check if category is available
			if (!$error) {
				if (!$groupInfo = $this->getGroupInfo($groupId)) {
					$error = 'Group not found.';
				} else {
					// check if duplicate record is entered.
					$conditions = ['GroupPaperRate.group_id' => $groupId, 'GroupPaperRate.date' => $date, 'GroupPaperRate.rate' => $data['GroupPaperRate']['rate']];
					if ($this->GroupPaperRate->find('first', ['conditions' => $conditions])) {
						$error = 'Duplicate entry. A similar record already exists.';
					}
				}
			}

			if (!$error) {
				$data['GroupPaperRate']['id'] = null;
				$data['GroupPaperRate']['site_id'] = $this->Session->read('Site.id');
				$data['GroupPaperRate']['user_id'] = $this->Session->read('User.id');
				$data['GroupPaperRate']['date'] = $date;
				$data['GroupPaperRate']['group_name'] = $groupInfo['Group']['name'];

				if ($this->GroupPaperRate->save($data)) {
					$this->Session->write('date', $date);
					$msg = 'New record created.';
					$this->successMsg($msg);
					$this->redirect('/GroupPaperRates/');
				}
			}
		} else {
			if ($this->Session->check('date')) {
				$data['GroupPaperRate']['date'] = $this->Session->read('date');
				$this->data = $data;
			}
		}

		if ($error) {
			$this->errorMsg($error);
		}

		$this->set(compact('groupList'));
	}

	public function paperRatesFormValidation($data = null)
	{
		$error = null;

		if ($data) {
			if (!isset($data['GroupPaperRate']['group_id'])) {
				$error = 'Group not found';
			}
			if ((!isset($data['GroupPaperRate']['rate'])) OR (!Validation::decimal($data['GroupPaperRate']['rate'])) OR ($data['GroupPaperRate']['rate'] <= 0)) {
				$error = 'Rate should be greater than 0';
			}
		} else {
			$error = 'Empty record';
		}
		return $error;
	}

	public function remove($recordID = null)
	{
		if ($this->request->isPost()) {
			if ($paperRatesInfo = $this->GroupPaperRate->findById($recordID)) {
				$this->GroupPaperRate->delete($recordID);
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