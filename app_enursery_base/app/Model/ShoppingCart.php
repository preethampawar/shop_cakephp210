<?php
App::uses('AppModel', 'Model');
class ShoppingCart extends AppModel {
    public $name = 'ShoppingCart';
	
	var $hasMany = array('ShoppingCartProduct');
	
	function getShoppingCartProducts() {
		$shoppingCart = null;
		App::uses('CakeSession', 'Model/Datasource');	
		
		if(CakeSession::check('ShoppingCart.id')) {
			
			$shoppingCartID = CakeSession::read('ShoppingCart.id');
			$this->bindModel(array('hasMany'=>array('ShoppingCartProduct'=>array('order'=>'ShoppingCartProduct.product_name'))));
			$shoppingCart = $this->find('first', array('conditions'=>array('ShoppingCart.id'=>$shoppingCartID)));
		}
		return $shoppingCart;
	} 
}
?>