<?php
App::uses('AppModel', 'Model');
class Site extends AppModel {

	var $name = 'Site';
	var $hasMany = array('Domain');
	var $displayField = 'name';
}	
?>