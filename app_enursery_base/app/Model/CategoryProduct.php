<?php
App::uses('AppModel', 'Model');

class CategoryProduct extends AppModel {
	var $name = 'CategoryProduct';
	var $belongsTo = array('Category', 'Product');
	
	// get only active products
	public function getActiveCategoryProducts($site_id, $category_id, $product_id=null) {
		$fields = array('Product.id', 'Product.name', 'Product.featured', 'Product.request_price_quote', 'Product.description');
		$productImageLimit = 1;
		if($product_id) {
			$productImageLimit = 20;
		}
		$this->recursive = 2;
		$this->Product->bindModel(array('hasMany'=>array('Image'=>array('fields'=>array('Image.id'), 'order'=>'Image.highlight DESC', 'limit'=>$productImageLimit, 'fields'=>array('Image.id', 'Image.extension', 'Image.type', 'Image.caption')))));
		if($category_id) {
			$conditions = array('CategoryProduct.category_id'=>$category_id, 'CategoryProduct.site_id'=>$site_id, 'Product.active'=>1);	
		} else {
			$conditions = array('CategoryProduct.site_id'=>$site_id, 'Product.active'=>1);
		}
		if($product_id) {
			$conditions[] = ['CategoryProduct.product_id'=>$product_id];
		}
		$data  = $this->find('all', array('conditions'=>$conditions, 'order'=>array('Product.name')));		
		return $data;
	}
	
	// get active and inactive products
	public function getCategoryProducts($site_id, $category_id, $product_id=null) {
		$fields = array('Product.id', 'Product.name', 'Product.featured', 'Product.request_price_quote');
		$productImageLimit = 1;
		if($product_id) {
			$productImageLimit = 20;
		}		
		$this->recursive = 2;
		$this->Product->bindModel(array('hasMany'=>array('Image'=>array('fields'=>array('Image.id'), 'order'=>'Image.highlight DESC', 'limit'=>$productImageLimit, 'fields'=>array('Image.id', 'Image.extension', 'Image.type', 'Image.caption')))));
		if($category_id) {
			$conditions = array('CategoryProduct.category_id'=>$category_id, 'CategoryProduct.site_id'=>$site_id);			
		} else {
			$conditions = array('CategoryProduct.site_id'=>$site_id);
		}
		if($product_id) {
			$conditions[] = ['CategoryProduct.product_id'=>$product_id];
		}
		$data  = $this->find('all', array('conditions'=>$conditions, 'order'=>array('Product.name')));		
		return $data;
	}
}
