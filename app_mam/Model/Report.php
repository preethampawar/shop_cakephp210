<?php
App::uses('AppModel', 'Model');
class Report extends AppModel {
    public $name = 'Report';
	var $useTable = 'data';
	var $belongsTo = array('Company');		
	var $hasMany = array('DataGroup'=>array('className'=>'DataGroup', 'foreignKey'=>'data_id'));
}
?>