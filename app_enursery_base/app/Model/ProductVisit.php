<?php
App::uses('AppModel', 'Model');
class ProductVisit extends AppModel {
    public $name = 'ProductVisit';
	
	var $belongsTo = array('Category', 'Product');
	
}