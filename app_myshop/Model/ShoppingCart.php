<?php
App::uses('AppModel', 'Model');

class ShoppingCart extends AppModel
{
	public $name = 'ShoppingCart';

	var $hasMany = ['ShoppingCartProduct'];

	function getShoppingCartProducts($shoppingCartId = null)
	{
		$shoppingCart = null;
		if ($shoppingCartId) {
			$this->bindModel(['hasMany' => ['ShoppingCartProduct' => ['order' => 'ShoppingCartProduct.product_name']]]);
			$shoppingCart = $this->find('first', ['conditions' => ['ShoppingCart.id' => $shoppingCartId], 'recursive'=>2]);
		}
		return $shoppingCart;
	}
}

?>
