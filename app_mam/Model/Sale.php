<?php
App::uses('AppModel', 'Model');
class Sale extends AppModel {
    public $name = 'Sale';
	
	var $useTable = 'data';
	var $belongsTo = array('Category');
	var $hasOne = array('AvailableStock'=>array('className'=>'AvailableStock', 'foreignKey'=>'data_id'));
			
	public function afterSave() {
		$msg = (isset($this->data['Sale']['message'])) ? $this->data['Sale']['message'] : '';
		$tmp = $this->read();
		$dataInfo['Datalog'] = $tmp['Sale'];
		$dataInfo['Datalog']['data_id'] = $tmp['Sale']['id'];
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