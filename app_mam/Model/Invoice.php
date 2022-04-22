<?php
App::uses('AppModel', 'Model');
class Invoice extends AppModel {
    public $name = 'Invoice';
	
	var $useTable = 'invoices';
}