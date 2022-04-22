<?php
App::uses('AppModel', 'Model');

class Product extends AppModel
{
	public $name = 'Product';

	public $hasMany = ['CategoryProduct'];

	/**
	 * Function to get site categories->products->images
	 * $options        array    'cols' array()    contains requested column names ex: 'Category.name, Category.id, etc'
	 * 'categoryConditions'    array()        contains user defined conditions related to categories table ex: Category.name='%xyz%' etc.
	 * 'productConditions'    array()        contains user defined conditions related to products table ex: Product.active=1 etc.
	 * 'allImages'        bool    'true' will return all images and 'false' will return only the highlighted image.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function getSiteCategoriesProducts($options = [], $siteID)
	{

		$categoryFields = ['Category.id', 'Category.name'];
		$productFields = ['Product.id', 'Product.name', 'Product.request_price_quote'];
		$categoryConditions = ['Category.site_id' => $siteID, 'Category.active' => '1'];

		// check if all the table fields are requested
		if (isset($options['cols']) and !empty($options['cols'])) {
			if ($options['cols'] == 'complete') {
				$categoryFields = [];
				$productFields = [];
			}
		}

		// Check if category conditions are specified
		if (isset($options['categoryConditions']) and !empty($options['categoryConditions'])) {
			$categoryConditions[] = $options['categoryConditions'];
		}

		App::uses('Category', 'Model');
		$this->Category = new Category;

		$this->Category->recursive = -1;
		$this->Category->unbindModel(['belongsTo' => ['ParentCategory']]);
		$categories = $this->Category->find('all', ['conditions' => $categoryConditions, 'fields' => $categoryFields, 'recursive' => '-1', 'order' => 'Category.name']);
		$allCategories = [];
		if (!empty($categories)) {
			foreach ($categories as $i => $cat) {
				$categoryID = $cat['Category']['id'];
				$allCategories[$i] = $cat;

				// Find category products
				// Check if product conditions are specified
				$productConditions = [];
				if (isset($options['productConditions']) and !empty($options['productConditions'])) {
					$productConditions[] = $options['productConditions'];
				}
				$productConditions[] = ['CategoryProduct.category_id' => $categoryID, 'Product.active' => '1'];

				App::uses('CategoryProduct', 'Model');
				$this->CategoryProduct = new CategoryProduct;
				$this->CategoryProduct->recursive = 0;
				$this->CategoryProduct->unbindModel(['belongsTo' => ['Category']]);
				$categoryProducts = $this->CategoryProduct->find('all', ['conditions' => $productConditions, 'order' => 'Product.name', 'fields' => $productFields]);

				$tmp = [];
				if (!empty($categoryProducts)) {
					foreach ($categoryProducts as $index => $row) {
						$tmp[$index]['Product'] = $row['Product'];
					}
				}
				$allCategories[$i]['CategoryProducts'] = $tmp;
			}
		}

		return $allCategories;
	}

	public function getAllProducts($siteId, $featured = null)
	{
		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$this->CategoryProduct->recursive = 0;

		$fields = ['Category.id', 'Category.name', 'Product.id', 'Product.name', 'Product.images', 'Product.mrp', 'Product.discount', 'Product.no_stock', 'Product.hide_price'];
		$productConditions[] = ['Category.active' => '1', 'Category.site_id' => $siteId, 'Product.active' => '1',  'Product.site_id' => $siteId];

		if ($featured) {
			array_push($productConditions, ['Product.featured' => '1']);
		}

		return $this->CategoryProduct->find('all', ['conditions' => $productConditions, 'order' => 'Product.name', 'fields' => $fields]);
	}
}
