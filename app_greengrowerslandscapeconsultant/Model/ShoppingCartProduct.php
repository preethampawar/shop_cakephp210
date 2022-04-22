<?php
App::uses('AppModel', 'Model');
class ShoppingCartProduct extends AppModel {
    public $name = 'ShoppingCartProduct';
   
	var $belongsTo = array('ShoppingCart', 'Category', 'Product');		
}
?>