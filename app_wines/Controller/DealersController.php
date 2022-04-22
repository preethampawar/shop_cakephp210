<?php
class DealersController extends AppController {
    
	function beforeFilter() {
		parent::beforeFilter();
		$this->checkStoreInfo();
	}

    public function index() {		
		$store_id = $this->Session->read('Store.id');
		
		$query = "
			select d.id, d.name, d.created
			from dealers d
			where d.store_id = $store_id
			order by d.name
		";
		$result = $this->Dealer->query($query);
		$this->set(compact('result'));
    }
	
    public function showDealerBrandProducts() {		
		$store_id = $this->Session->read('Store.id');
		
		$query = "
			select d.id, d.name, d.created, b.id, b.name, p.id, p.name
			from dealers d
				left join brands b on b.dealer_id = d.id and b.store_id = $store_id
				left join products p on p.brand_id = b.id and p.store_id = $store_id
			where d.store_id = $store_id
			order by d.name, b.name, p.name
		";
		$result = $this->Dealer->query($query);
		$this->set(compact('result'));
    }

    public function view($dealerID = null) {
		/*
		$this->Dealer->bindModel(array('hasMany'=>array('Salary'=>array(
																'className'=>'Salary', 
																'order'=>'payment_date DESC'
																)
														)));		
		*/												
        if(!($dealerInfo = $this->Dealer->findById($dealerID))) {
			$this->Session->setFlash('Dealer not found');
			$this->redirect(array('controller'=>'dealers', 'action'=>'index'));
		}
        $this->set(compact('dealerInfo'));
    }

	public function add() {
		$errorMsg = null;
		if($this->request->data) {
			$data = $this->request->data;
			if(isset($data['Dealer']['name'])) {					
					
				if($data['Dealer']['name']=trim($data['Dealer']['name'])) {
					$conditions = array('Dealer.name'=>$data['Dealer']['name'], 'Dealer.store_id'=>$this->Session->read('Store.id'));
					if($this->Dealer->find('first', array('conditions'=>$conditions))) {
						$errorMsg = "'".$data['Dealer']['name']."'". ' already exists';
					}
					else {
						$data['Dealer']['store_id'] = $this->Session->read('Store.id');
						if($this->Dealer->save($data)) {
							$dealerInfo = $this->Dealer->read();
							$msg = 'Dealer added successfully';
							$this->Session->setFlash($msg, 'default', array('class'=>'success'));
							$this->redirect(array('controller'=>'dealers', 'action'=>'add'));
						}
						else {
							$errorMsg = 'An error occurred while communicating with the server';
						}
					}
				}
				else {
					$errorMsg = 'Enter Dealer Name';
				}
				
			}
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		
		// get recent dealers
		$store_id = $this->Session->read('Store.id');
		
		$query = "
			select d.id, d.name, d.created, b.id, b.name, p.id, p.name
			from dealers d
				left join brands b on b.dealer_id = d.id and b.store_id = $store_id
				left join products p on p.brand_id = b.id and p.store_id = $store_id
			where d.store_id = $store_id
			order by d.created desc, b.created desc, p.created desc
			limit 5
		";
		$result = $this->Dealer->query($query);
		$this->set(compact('result'));
		
	}

    public function edit($dealerID=null) {
		$errorMsg = null;
		
		if(!($dealerInfo = $this->CommonFunctions->getDealerInfo($dealerID))) {
			$this->Session->setFlash('Dealer not found');
			$this->redirect('/dealers/');
		}
			
		if($this->request->data) {
			$data = $this->request->data;
			if(isset($data['Dealer']['name'])) {
				if($data['Dealer']['name']=trim($data['Dealer']['name'])) {					
						
					$conditions = array('Dealer.name'=>$data['Dealer']['name'], 'Dealer.store_id'=>$this->Session->read('Store.id'), 'Dealer.id <>'=>$dealerID);
					if($this->Dealer->find('first', array('conditions'=>$conditions))) {
						$errorMsg = "'".$data['Dealer']['name']."'". ' already exists';
					}
					else {
						$data['Dealer']['id'] = $dealerID;
						$data['Dealer']['store_id'] = $this->Session->read('Store.id');
						if($this->Dealer->save($data)) {
							$msg = 'Dealer updated successfully';							
							$this->Session->setFlash($msg, 'default', array('class'=>'success'));
							$this->redirect('/dealers/');
						}
						else {
							$errorMsg = 'An error occured while communicating with the server';
						}
					}
				}
				else {
					$errorMsg = 'Enter Dealer Name';
				}
			}
		}
		else {
			$this->data = $dealerInfo;
		}
		($errorMsg) ? $this->Session->setFlash($errorMsg) : null;
		 $this->set(compact('dealerInfo'));
	}

    public function remove($dealerID=null) {
		if($dealerID) {
			if(!($dealerInfo = $this->CommonFunctions->getDealerInfo($dealerID))) {
				$this->Session->setFlash('Dealer not found');
			}
			else {
				// delete dealer information
				$this->Dealer->delete($dealerID);				
				$this->Session->setFlash('Dealer "'.$dealerInfo['Dealer']['name'].'" has been removed', 'default', array('class'=>'success'));
			}
		}
		else {
			$this->Session->setFlash('Unauthorized access');
		}
		$this->redirect($this->request->referer());
	}
	
}