<?php
App::uses('AppModel', 'Model');

class OrderProduct extends AppModel
{
	public $name = 'OrderProduct';

	var $belongsTo = ['Order'];
}

?>
