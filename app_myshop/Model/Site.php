<?php
App::uses('AppModel', 'Model');

class Site extends AppModel
{

	var $name = 'Site';
	var $hasMany = ['Domain'];
	var $displayField = 'name';
}

?>
