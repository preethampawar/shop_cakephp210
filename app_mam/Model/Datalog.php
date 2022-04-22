<?php
App::uses('AppModel', 'Model');
class Datalog extends AppModel {
    public $name = 'Datalog';
	
	var $useTable = 'datalogs';
	var $belongsTo = array('Data');
	
}
?>