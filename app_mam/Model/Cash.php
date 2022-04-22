<?php
App::uses('AppModel', 'Model');
class Cash extends AppModel {
    public $name = 'Cash';
	
	var $useTable = 'data';
	var $belongsTo = array('Category');	
	var $hasOne = array('Inventory'=>array('className'=>'Inventory', 'foreignKey'=>'data_id'));
		
	public function afterSave() {
		$msg = (isset($this->data['Cash']['message'])) ? $this->data['Cash']['message'] : '';
		$tmp = $this->read();
		$dataInfo['Datalog'] = $tmp['Cash'];
		$dataInfo['Datalog']['data_id'] = $tmp['Cash']['id'];
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