<?php
App::uses('AppModel', 'Model');

class Blog extends AppModel {
	var $name = 'Blog';
	var $belongsTo = array('Site');
	var $useTable = 'blog';
}
?>
