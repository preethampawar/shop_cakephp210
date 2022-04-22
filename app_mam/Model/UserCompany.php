<?php
App::uses('AppModel', 'Model');
class UserCompany extends AppModel {
    public $name = 'UserCompany';
	
	var $useTable = 'user_companies';
	
	var $belongsTo = array('User', 'Company');
}
?>