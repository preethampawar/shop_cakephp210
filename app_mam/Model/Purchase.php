<?php
App::uses('AppModel', 'Model');
class Purchase extends AppModel {
    public $name = 'Purchase';
	
	var $useTable = 'data';
	var $belongsTo = array('Category');
	
	public function afterSave() {
		$msg = (isset($this->data['Purchase']['message'])) ? $this->data['Purchase']['message'] : '';
		$tmp = $this->read();
		$dataInfo['Datalog'] = $tmp['Purchase'];
		$dataInfo['Datalog']['data_id'] = $tmp['Purchase']['id'];
		$dataInfo['Datalog']['message'] = $msg;
		
		unset($dataInfo['Datalog']['id']);
		unset($dataInfo['Datalog']['created']);
		unset($dataInfo['Datalog']['modified']);
		
		App::uses('Datalog', 'Model');
		$this->Datalog = new Datalog;
		$this->Datalog->save($dataInfo);
		
		return true;
	}
}
?>