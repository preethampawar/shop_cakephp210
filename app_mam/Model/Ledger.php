<?php
App::uses('AppModel', 'Model');
class Ledger extends AppModel {
    public $name = 'Ledger';
	var $useTable = 'data';
	var $belongsTo = array('Company');		
}
?>