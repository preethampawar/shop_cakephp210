<?php
App::uses('AppModel', 'Model');
class Product extends AppModel {
    public $name = 'Product';
	
	var $hasMany = array('CategoryProduct');	
	
	
	/**
	 * Function to get site categories->products->images
	 * $options		array	'cols' array()	contains requested column names ex: 'Category.name, Category.id, etc'
							'categoryConditions'	array()		contains user defined conditions related to categories table ex: Category.name='%xyz%' etc.
							'productConditions'	array()		contains user defined conditions related to products table ex: Product.active=1 etc.
							'allImages'		bool	'true' will return all images and 'false' will return only the highlighted image.
		todo: remove this function from appcontroller. this is already present in products controller.					
	*/ 
	function getSiteCategoriesProductsImages($options=array()) {
		
		$categoryFields = array('Category.id', 'Category.name');
		$productFields = array('Product.id', 'Product.name', 'Product.request_price_quote');
		$imageFields = array('Image.id', 'Image.caption', 'Image.highlight');
		
		App::uses('CakeSession', 'Model/Datasource');	
		$siteID = CakeSession::read('Site.id');
		
		$categoryConditions = array('Category.site_id'=>$siteID, 'Category.active'=>'1');
		
			
		$getAllImages = false;
		// check if all the table fields are requested
		if(isset($options['cols']) and !empty($options['cols'])) {
			if($options['cols'] == 'complete') {
				$categoryFields = array();
				$productFields = array();
				$imageFields = array();
			}
		}
		
		// Check if category conditions are specified
		if(isset($options['categoryConditions']) and !empty($options['categoryConditions'])) {
			$categoryConditions[] = $options['categoryConditions']; 
		}
				
		// check if all product images are requested else return only one.
		if(isset($options['allImages']) and ($options['allImages'] == true)) {
			$getAllImages = true;	
		}		
		
		App::uses('Category', 'Model');
		$this->Category = new Category;		
		
		$categories = $this->Category->find('all', array('conditions'=>$categoryConditions, 'fields'=>$categoryFields, 'recursive'=>'-1', 'order'=>'Category.name'));
		
		$allCategories = array();
		
		if(!empty($categories)) {
			foreach($categories as $i=>$cat) {
				$categoryID = $cat['Category']['id'];
				$allCategories[$i]=$cat;
				
				// Find category products				
				// Check if product conditions are specified
				$productConditions = array();
				if(isset($options['productConditions']) and !empty($options['productConditions'])) {
					$productConditions[] = $options['productConditions']; 
				}				
				$productConditions[] = array('CategoryProduct.category_id'=>$categoryID, 'Product.active'=>'1');	
				
				App::uses('CategoryProduct', 'Model');
				$this->CategoryProduct = new CategoryProduct;						
				$categoryProducts = $this->CategoryProduct->find('all', array('conditions'=>$productConditions, 'order'=>'Product.name', 'fields'=>$productFields));
				
				$tmp = array();
				if(!empty($categoryProducts)) {
					App::uses('Image', 'Model');
					$this->Image = new Image;	
					
					foreach($categoryProducts as $index=>$row) {						
						$tmp[$index]['Product'] = $row['Product'];
					
						$this->Image->recursive = -1;
						$images = $this->Image->findAllByProductId($row['Product']['id'], $imageFields, array('Image.highlight DESC'));
						//---------------
						if(!$getAllImages) {
							if(!empty($images)) {
								foreach($images as $row2) {
									$tmp2['Image'] = $row2;
									if($row2['Image']['highlight']) {
										$tmp[$index]['Images'][] = $row2;
										break;
									}						
								}
								
								if(empty($tmp[$index]['Images'])) {
									$tmp[$index]['Images'][] = $tmp2['Image'];
								}
							}
							else {
								$tmp[$index]['Images'] = array();
							}
						}
						else {
							$tmp[$index]['Images'] = $images;							
						}
						//---------------					
					}
				}
				$allCategories[$i]['CategoryProducts'] = $tmp;
			}
		}
		
		return $allCategories;				
	}

	
}