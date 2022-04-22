<?php
App::uses('AppModel', 'Model');
class Brand extends AppModel {
    public $name = 'Brand';   
	var $hasMany = array('Product');
	var $belongsTo = array('Dealer');
}