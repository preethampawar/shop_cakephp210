<?php
App::uses('AppModel', 'Model');

class Product extends AppModel {
	var $name = 'Product';	
	var $hasMany = array('Image');
	
	public function getAllProducts($site_id)
	{		
		$this->hasMany = array('Image'=>array('order'=>'Image.highlight DESC', 'limit'=>1, 'fields'=>array('Image.id', 'Image.extension', 'Image.type', 'Image.caption')));		
		$conditions = array('Product.active'=>1, 'Product.site_id'=>$site_id);
		$fields = array('Product.id', 'Product.name', 'Product.category_id', 'Product.description', 'Product.featured', 'Product.site_id');
		$data = $this->find('all', array('conditions'=>$conditions, 'order'=>'Product.name', 'fields'=>$fields));		
		return $data;
	}
	
	public function getActiveCategoryProducts($site_id, $category_id, $product_id = null)
	{
		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct();
		$category_products = $this->CategoryProduct->getActiveCategoryProducts($site_id, $category_id, $product_id);
		return $category_products;
	}
}
