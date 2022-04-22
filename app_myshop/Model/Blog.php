<?php
App::uses('AppModel', 'Model');

class Blog extends AppModel
{
	var $name = 'Blog';
	var $belongsTo = ['Site'];
	var $useTable = 'blog';
}

?>
