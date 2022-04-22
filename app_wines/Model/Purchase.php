<?php
App::uses('AppModel', 'Model');
class Purchase extends AppModel {
    public $name = 'Purchase';
	var $belongsTo = array('Product');
}