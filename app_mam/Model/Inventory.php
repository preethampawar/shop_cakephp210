<?php
App::uses('AppModel', 'Model');
class Inventory extends AppModel {
    public $name = 'Inventory';
	
	var $useTable = 'inventory';
	var $belongsTo = array('Category', 'Company', 'Data');		
}
?>