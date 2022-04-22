<?php
App::uses('AppModel', 'Model');

class ShoppingCartProduct extends AppModel
{
	public $name = 'ShoppingCartProduct';

	var $belongsTo = ['ShoppingCart', 'Category', 'Product'];
}

?>
