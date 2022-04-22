<?php
App::uses('AppModel', 'Model');

class Supplier extends AppModel
{
	var $name = 'Supplier';
	var $belongsTo = ['Site'];
	var $useTable = 'suppliers';
}
