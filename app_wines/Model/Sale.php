<?php
App::uses('AppModel', 'Model');
class Sale extends AppModel {
    public $name = 'Sale';	
	var $belongsTo = array('Product');
}