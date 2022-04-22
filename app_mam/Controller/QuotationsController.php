<?php
class QuotationsController extends AppController {
	var $name = 'Quotations';	
		
	function index() {
		$conditions = array('Quotation.company_id'=>$this->Session->read('Company.id'));
		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Quotation.user_id'=>$this->Session->read('User.id'));
		}
		
		$this->paginate = array(
				'limit' => 50,
				'order' => array('Quotation.created' => 'desc'),
				'conditions' => $conditions
			);
		$quotations = $this->paginate();
		
		$this->set(compact('quotations'));
	}
	
	function create() {
		$template = 'default';
		if($this->Session->check('QuotationTemplate')) {
			$template = $this->Session->read('QuotationTemplate');
		}
		
		if($this->request->isPost()) {
			$data = $this->request->data;
			$qData['Quotation'] = $data['Quotation'];
			$itemsData['EntityItem'] = $data['EntityItem'];
			
			$qData['Quotation']['from_name'] = htmlentities($qData['Quotation']['from_name'], ENT_QUOTES);
			$qData['Quotation']['from_address'] = htmlentities($qData['Quotation']['from_address'], ENT_QUOTES);
			$qData['Quotation']['to_name'] = htmlentities($qData['Quotation']['to_name'], ENT_QUOTES);
			$qData['Quotation']['to_address'] = htmlentities($qData['Quotation']['to_address'], ENT_QUOTES);
			$qData['Quotation']['comments'] = htmlentities($qData['Quotation']['comments'], ENT_QUOTES);
			$qData['Quotation']['user_id'] = $this->Session->read('User.id');
			$qData['Quotation']['company_id'] = $this->Session->read('Company.id');
			$qData['Quotation']['id'] = null;			
			
			$qData['Quotation']['template'] = $template;
			
			if($this->Quotation->save($qData)) {			
				$quotationInfo = $this->Quotation->read();
				if(!empty($itemsData['EntityItem'])) {
					App::uses('EntityItem', 'Model');
					$this->EntityItem = new EntityItem;
					foreach($itemsData['EntityItem']['item'] as $index=>$row) {
						$item = array();
						$item['EntityItem']['id'] = null;
						$item['EntityItem']['user_id'] = $this->Session->read('User.id');
						$item['EntityItem']['company_id'] = $this->Session->read('Company.id');
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
					$this->Session->setFlash('Quotation created successfully', 'default', array('class'=>'success'));
					$this->redirect('/quotations/');
				}
				else {
					$this->Session->setFlash('You need to add atleast one item', 'default', array('class'=>'error'));
				}	
			}			
		}
		$this->set('template', $template);
	}
	
	function selectTemplate($template = null) {
		if($template) {
			switch($template) {
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
	
	function details($quotationID) {
		$this->layout = 'print';		
		$companyID = $this->Session->read('Company.id');				
		
		$this->Quotation->bindModel(array('hasMany'=>array('EntityItem'), 'belongsTo'=>array('Company', 'User')));
		$conditions = array('Quotation.company_id'=>$companyID, 'Quotation.id'=>$quotationID);
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Quotation.user_id'=>$this->Session->read('User.id'));
		}
		$quotationInfo = $this->Quotation->find('first', array('conditions'=>$conditions));
		if(empty($quotationInfo)) {
			$this->Session->setFlash('Quotation not found', 'default', array('class'=>'error'));
			$this->redirect('/quotations/');
		}
		
		$this->set('quotationID', $quotationID);
		$this->set('quotationInfo', $quotationInfo);
	}
	
	function download($quotationID) {
		$this->layout = 'download_word';
		$companyID = $this->Session->read('Company.id');				
		$this->Quotation->bindModel(array('hasMany'=>array('EntityItem'), 'belongsTo'=>array('Company', 'User')));
		$conditions = array('Quotation.company_id'=>$companyID, 'Quotation.id'=>$quotationID);		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Quotation.user_id'=>$this->Session->read('User.id'));
		}
		
		$quotationInfo = $this->Quotation->find('first', array('conditions'=>$conditions));
		if(empty($quotationInfo)) {
			$this->Session->setFlash('Quotation not found', 'default', array('class'=>'error'));
			$this->redirect('/quotations/');
		}
		
		$this->set('quotationInfo', $quotationInfo);
		$this->set('quotationID', $quotationID);
	}

	function delete($quotationID) {
		$companyID = $this->Session->read('Company.id');				
		$this->Quotation->bindModel(array('hasMany'=>array('EntityItem')));
		$conditions = array('Quotation.company_id'=>$companyID, 'Quotation.id'=>$quotationID);		
		if($this->Session->read('UserCompany.user_level') == '2') {
			$conditions[] = array('Quotation.user_id'=>$this->Session->read('User.id'));
		}
		
		$quotationInfo = $this->Quotation->find('first', array('conditions'=>$conditions));
		if($quotationInfo) {
			App::uses('EntityItem', 'Model');
			$this->EntityItem = new EntityItem;
			$conditions = array('EntityItem.quotation_id'=>$quotationID);
			$this->Quotation->delete($quotationID);
			$this->EntityItem->deleteAll($conditions);
			$this->Session->setFlash('Quotation deleted successfully', 'default', array('class'=>'success'));
		}
		else {
			$this->Session->setFlash('Quotation not found', 'default', array('class'=>'error'));
		}
		$this->redirect('/quotations/');
	}	
}	
?>