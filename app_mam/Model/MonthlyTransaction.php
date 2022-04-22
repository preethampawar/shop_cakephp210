<?php
App::uses('AppModel', 'Model');
class MonthlyTransaction extends AppModel {
    public $name = 'MonthlyTransaction';
	var $useTable = 'monthly_transactions';
	var $belongsTo = array('Company');		
}
?>