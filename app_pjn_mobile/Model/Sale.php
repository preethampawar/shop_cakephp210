<?php
App::uses('AppModel', 'Model');

class Sale extends AppModel
{
	public $name = 'Sale';
	public $belongsTo = ['Product'];
}
