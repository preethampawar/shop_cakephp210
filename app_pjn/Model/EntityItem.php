<?php
App::uses('AppModel', 'Model');

class EntityItem extends AppModel
{
	public $name = 'EntityItem';
	public $belongsTo = ['Quotation'];

}

?>
