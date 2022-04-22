<?php

class QuotationsController extends AppController
{
	public $name = 'Quotations';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

	public function index()
	{
		$conditions = ['Quotation.store_id' => $this->Session->read('Store.id')];

		if ($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = ['Quotation.user_id' => $this->Auth->user('id')];
		}

		$this->paginate = [
			'limit' => 50,
			'order' => ['Quotation.created' => 'desc'],
			'conditions' => $conditions,
		];
		$quotations = $this->paginate();

		$this->set(compact('quotations'));
	}

	public function create()
	{
		$template = 'default';
		if ($this->Session->check('QuotationTemplate')) {
			$template = $this->Session->read('QuotationTemplate');
		}

		if ($this->request->isPost()) {
			$data = $this->request->data;
			$qData['Quotation'] = $data['Quotation'];
			$itemsData['EntityItem'] = $data['EntityItem'];

			$qData['Quotation']['from_name'] = htmlentities($qData['Quotation']['from_name'], ENT_QUOTES);
			$qData['Quotation']['from_address'] = htmlentities($qData['Quotation']['from_address'], ENT_QUOTES);
			$qData['Quotation']['to_name'] = htmlentities($qData['Quotation']['to_name'], ENT_QUOTES);
			$qData['Quotation']['to_address'] = htmlentities($qData['Quotation']['to_address'], ENT_QUOTES);
			$qData['Quotation']['comments'] = htmlentities($qData['Quotation']['comments'], ENT_QUOTES);
			$qData['Quotation']['user_id'] = $this->Auth->user('id');
			$qData['Quotation']['store_id'] = $this->Session->read('Store.id');
			$qData['Quotation']['id'] = null;

			$qData['Quotation']['template'] = $template;

			if ($this->Quotation->save($qData)) {
				$quotationInfo = $this->Quotation->read();
				if (!empty($itemsData['EntityItem'])) {
					App::uses('EntityItem', 'Model');
					$this->EntityItem = new EntityItem();
					foreach ($itemsData['EntityItem']['item'] as $index => $row) {
						$item = [];
						$item['EntityItem']['id'] = null;
						$item['EntityItem']['user_id'] = $this->Auth->user('id');
						$item['EntityItem']['store_id'] = $this->Session->read('Store.id');
						$item['EntityItem']['item'] = htmlentities($itemsData['EntityItem']['item'][$index], ENT_QUOTES);
						$item['EntityItem']['description'] = htmlentities($itemsData['EntityItem']['description'][$index], ENT_QUOTES);
						$item['EntityItem']['quantity'] = $itemsData['EntityItem']['quantity'][$index];
						$item['EntityItem']['unitrate'] = $itemsData['EntityItem']['unitrate'][$index];
						$item['EntityItem']['amount'] = $itemsData['EntityItem']['amount'][$index];
						$item['EntityItem']['size'] = (isset($itemsData['EntityItem']['size'][$index])) ? $itemsData['EntityItem']['size'][$index] : '';
						$item['EntityItem']['age'] = (isset($itemsData['EntityItem']['age'][$index])) ? $itemsData['EntityItem']['age'][$index] : '';
						$item['EntityItem']['quotation_id'] = $quotationInfo['Quotation']['id'];
						$this->EntityItem->save($item);
					}
					$this->Session->setFlash('Quotation created successfully', 'default', ['class' => 'success']);
					$this->redirect('/quotations/');
				} else {
					$this->Session->setFlash('You need to add atleast one item', 'default', ['class' => 'error']);
				}
			}
		}
		$this->set('template', $template);
	}

	public function selectTemplate($template = null)
	{
		if ($template) {
			switch ($template) {
				case 'default':
				case 'nursery':
					$this->Session->write('QuotationTemplate', $template);
					break;
				default:
					$this->Session->write('QuotationTemplate', 'default');
					break;
			}
			$this->redirect('/quotations/create');
		}
	}

	public function details($quotationID, $downloadType = 'quotation')
	{
		$this->layout = 'print';
		$companyID = $this->Session->read('Store.id');

		$this->Quotation->bindModel(['hasMany' => ['EntityItem'], 'belongsTo' => ['Store', 'User']]);
		$conditions = ['Quotation.store_id' => $companyID, 'Quotation.id' => $quotationID];
		if ($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = ['Quotation.user_id' => $this->Auth->user('id')];
		}
		$quotationInfo = $this->Quotation->find('first', ['conditions' => $conditions]);
		if (empty($quotationInfo)) {
			$this->Session->setFlash('Quotation not found', 'default', ['class' => 'error']);
			$this->redirect('/quotations/');
		}

		$this->set('quotationID', $quotationID);
		$this->set('quotationInfo', $quotationInfo);
		$this->set('downloadType', $downloadType);
	}

	public function download($quotationID, $downloadType = 'quotation')
	{
		$this->layout = 'download_word';
		$companyID = $this->Session->read('Store.id');
		$this->Quotation->bindModel(['hasMany' => ['EntityItem'], 'belongsTo' => ['Store', 'User']]);
		$conditions = ['Quotation.store_id' => $companyID, 'Quotation.id' => $quotationID];
		if ($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = ['Quotation.user_id' => $this->Auth->user('id')];
		}

		$quotationInfo = $this->Quotation->find('first', ['conditions' => $conditions]);
		if (empty($quotationInfo)) {
			$this->Session->setFlash('Quotation not found', 'default', ['class' => 'error']);
			$this->redirect('/quotations/');
		}

		$this->set('quotationInfo', $quotationInfo);
		$this->set('quotationID', $quotationID);
		$this->set('downloadType', $downloadType);
	}

	public function delete($quotationID)
	{
		$companyID = $this->Session->read('Store.id');
		$this->Quotation->bindModel(['hasMany' => ['EntityItem']]);
		$conditions = ['Quotation.store_id' => $companyID, 'Quotation.id' => $quotationID];
		if ($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = ['Quotation.user_id' => $this->Auth->user('id')];
		}

		$quotationInfo = $this->Quotation->find('first', ['conditions' => $conditions]);
		if ($quotationInfo) {
			App::uses('EntityItem', 'Model');
			$this->EntityItem = new EntityItem();
			$conditions = ['EntityItem.quotation_id' => $quotationID];
			$this->Quotation->delete($quotationID);
			$this->EntityItem->deleteAll($conditions);
			$this->Session->setFlash('Quotation deleted successfully', 'default', ['class' => 'success']);
		} else {
			$this->Session->setFlash('Quotation not found', 'default', ['class' => 'error']);
		}
		$this->redirect('/quotations/');
	}
}

?>
