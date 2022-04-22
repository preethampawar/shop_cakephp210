<?php
App::uses('AppModel', 'Model');
class Transaction extends AppModel {
    public $name = 'Transaction';
	
	var $useTable = 'data';
	var $belongsTo = array('Category');	
		
	public function afterSave($created, $options = []) {
		$msg = (isset($this->data['Transaction']['message'])) ? $this->data['Transaction']['message'] : '';
		$tmp = $this->read();
		$dataInfo['Datalog'] = $tmp['Transaction'];
		$dataInfo['Datalog']['data_id'] = $tmp['Transaction']['id'];
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