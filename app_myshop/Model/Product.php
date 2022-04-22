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
		$productFields = ['Product.id', 'Product.name', 'Product.short_desc', 'Product.request_price_quote'];
		$categoryConditions = ['Category.site_id' => $siteID, 'Category.active' => '1', 'Category.deleted' => '0'];

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
				$productConditions[] = ['CategoryProduct.category_id' => $categoryID, 'Product.active' => '1', 'Product.deleted' => '0'];

				App::uses('CategoryProduct', 'Model');
				$this->CategoryProduct = new CategoryProduct;
				$this->CategoryProduct->recursive = 0;
				$this->CategoryProduct->unbindModel(['belongsTo' => ['Category']]);
				$categoryProducts = $this->CategoryProduct->find('all', ['conditions' => $productConditions, 'order' => ['CategoryProduct.sort', 'Product.name'], 'fields' => $productFields]);

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

	public function getAllProducts($siteId, $featured = null, $limit = null, $filter = [])
	{
		App::uses('CategoryProduct', 'Model');
		$this->CategoryProduct = new CategoryProduct;
		$this->CategoryProduct->recursive = 0;

		$fields = [
			'Category.id',
			'Category.name',
			'Product.id',
			'Product.name',
			'Product.short_desc',
			'Product.images',
			'Product.mrp',
			'Product.discount',
			'Product.no_stock',
			'Product.hide_price',
			'Product.avg_rating',
			'Product.ratings_count',
			'Product.sort',
		];
		$productConditions[] = ['Category.active' => '1', 'Category.deleted' => '0', 'Category.site_id' => $siteId, 'Product.active' => '1', 'Product.deleted' => '0', 'Product.site_id' => $siteId];

		$order = ['CategoryProduct.sort', 'Category.name'];

		if ($featured) {
			$order = ['Product.sort'];
			array_push($productConditions, ['Product.featured' => '1']);
		}

		if ($filter) {
			switch ($filter['type']) {
				case 'price':
					$startValue = (float)$filter['startValue'] ?? 0;
					$endValue = (float)$filter['endValue'] ?? 0;
					$sort = (string)$filter['sort'] ?? 'asc';

					if (!in_array($filter['sort'], ['asc', 'desc'])) {
						$sort = 'asc';
					}
					$orderBy = sprintf('(Product.mrp - Product.discount) %s', $sort);
					$order = [$orderBy];

					array_push($fields, '(Product.mrp - Product.discount) Sale');
					$condition = '';

					if ($startValue == 0 && $endValue > 0) {
						$condition = sprintf('(Product.mrp - Product.discount) <= %d', $endValue);
					}
					if ($startValue > 0 && $endValue == 0) {
						$condition = sprintf('(Product.mrp - Product.discount) >= %d', $startValue);
					}
					if ($startValue > 0 && $endValue > 0) {
						$condition = sprintf('(Product.mrp - Product.discount) BETWEEN %d AND %d', $startValue, $endValue);
					}

					if (!empty($condition)) {
						array_push($productConditions, [$condition]);
					}
					break;

				case 'show_in_cart':
					array_push($productConditions, ['Product.show_in_cart' => '1']);
					$orderBy = sprintf('(Product.mrp - Product.discount) %s', 'ASC');
					$order = [$orderBy];
					break;

				default:
					break;
			}
		}

		return $this->CategoryProduct->find('all', ['conditions' => $productConditions, 'order' => $order, 'fields' => $fields, 'limit' => $limit]);
	}
}
