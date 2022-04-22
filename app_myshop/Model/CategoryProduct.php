<?php
App::uses('AppModel', 'Model');

class CategoryProduct extends AppModel
{
	public $name = 'CategoryProduct';

	var $belongsTo = ['Category', 'Product'];


	public function getCategoryProductsCount($siteId) {

		$categoryProductConditions = [
			'CategoryProduct.site_id' => $siteId,
		];

		$categoryConditions = [
			'Category.site_id' => $siteId,
			'Category.active' => '1',
			'Category.parent_id' => null,
			'Category.deleted' => '0'
		];

		$productConditions = [
			'Product.site_id' => $siteId,
			'Product.active' => true,
			'Product.deleted NOT' => true
		];

		$this->bindModel(['belongsTo'=> ['Category' => ['conditions' => $categoryConditions], 'Product' => ['conditions' => $productConditions]]]);
		$data = $this->find('all', ['conditions' => $categoryProductConditions, 'recursive' => '1', 'order' => 'Category.name ASC', 'fields' => ['Category.id', 'Product.id']]);

		$tmp = [];
		foreach($data as $row) {
			if ($row['Product']['id']) {
				$tmp[$row['Category']['id']] = isset($tmp[$row['Category']['id']]) ? ($tmp[$row['Category']['id']] + 1) : 1;
			}
		}
		unset($data);

		return $tmp;

	}

}

?>
