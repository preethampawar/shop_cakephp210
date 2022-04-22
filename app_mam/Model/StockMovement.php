<?php
App::uses('AppModel', 'Model');
class StockMovement extends AppModel {
    public $name = 'StockMovement';
	
	var $useTable = 'stock_movement';
	var $belongsTo = array('Category', 'Company', 'Data');		
}
?>