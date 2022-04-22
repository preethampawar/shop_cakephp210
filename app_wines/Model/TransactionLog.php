<?php
App::uses('AppModel', 'Model');
class TransactionLog extends AppModel {
    public $name = 'TransactionLog';
	public $belongsTo = array('Tag'); 
}