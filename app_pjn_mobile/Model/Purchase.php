<?php
App::uses('AppModel', 'Model');

class Purchase extends AppModel
{
	public $name = 'Purchase';
	public $belongsTo = ['Product'];
}
