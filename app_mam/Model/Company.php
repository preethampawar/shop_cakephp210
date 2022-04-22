<?php
App::uses('AppModel', 'Model');
class Company extends AppModel {
    public $name = 'Company';
	
	var $useTable = 'companies';
	
	var $belongsTo = array('User');
	var $hasMany = array('UserCompany');
}
?>