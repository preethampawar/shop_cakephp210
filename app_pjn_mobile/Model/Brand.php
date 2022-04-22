<?php
App::uses('AppModel', 'Model');

class Brand extends AppModel
{
	public $name = 'Brand';
	public $hasMany = ['Product'];
	public $belongsTo = ['Dealer'];
}
