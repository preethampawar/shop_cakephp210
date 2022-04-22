<?php
App::uses('AppModel', 'Model');
class CategoryProduct extends AppModel {
    public $name = 'CategoryProduct';
   
	var $belongsTo = array('Category', 'Product');		
}
?>