<?php
App::uses('AppModel', 'Model');
class AvailableStock extends AppModel {
    public $name = 'AvailableStock';
	
	var $useTable = 'available_stock';
	var $belongsTo = array('Category', 'Company', 'Data');	
}
?>