<?php
App::uses('AppModel', 'Model');

class ProductVisit extends AppModel
{
	public $name = 'ProductVisit';

	var $belongsTo = ['Category', 'Product'];

	function getMostViewedProducts()
	{
		App::uses('CakeSession', 'Model/Datasource');

		$conditions = ['ProductVisit.site_id' => CakeSession::read('Site.id')];
		$fields = ['ProductVisit.id', 'ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name'];

		$products = $this->find('all', ['conditions' => $conditions, 'order' => ['ProductVisit.visit_count DESC'], 'fields' => $fields, 'limit' => '14']);
		if (!empty($products)) {
			App::uses('Image', 'Model');
			$this->Image = new Image;

			foreach ($products as $index => $row) {
				$image = $this->Image->find('first', ['conditions' => ['Image.product_id' => $row['Product']['id']], 'order' => ['Image.highlight DESC'], 'recursive' => '-1', 'fields' => ['Image.id', 'Image.caption'], 'limit' => '1']);
				$products[$index]['Image'] = $image['Image'];
			}
		}
		return $products;
	}

	function getRecentProductViewsByUser()
	{
		App::uses('CakeSession', 'Model/Datasource');

		$conditions = ['ProductVisit.site_id' => CakeSession::read('Site.id')];
		$fields = ['ProductVisit.id', 'ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name'];

		$products = $this->find('all', ['conditions' => $conditions, 'order' => ['ProductVisit.modified DESC'], 'fields' => $fields, 'limit' => '12']);
		if (!empty($products)) {
			App::uses('Image', 'Model');
			$this->Image = new Image;

			foreach ($products as $index => $row) {
				$image = $this->Image->find('first', ['conditions' => ['Image.product_id' => $row['Product']['id']], 'order' => ['Image.highlight DESC'], 'recursive' => '-1', 'fields' => ['Image.id', 'Image.caption'], 'limit' => '1']);
				$products[$index]['Image'] = $image['Image'];
			}
		}
		return $products;
	}
}
