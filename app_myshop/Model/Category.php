<?php
App::uses('AppModel', 'Model');

class Category extends AppModel
{
	public $name = 'Category';
	public $actsAs = ['Tree'];

	var $belongsTo = ['ParentCategory' => ['className' => 'Category', 'foreignKey' => 'parent_id']];

	var $validate = [
		'name' => [
			'notEmpty' => [
				'rule' => 'notEmpty',
				'required' => false,
				'message' => 'Category name is a required field',
			],
			'between' => [
				'rule' => ['between', 2, 55],
				'message' => 'Category name should be minimum of 2 characters and maximum of 55 characters',
			],
		],
	];

	public function getCategories($siteId)
	{
		$conditions = ['Category.site_id' => $siteId, 'Category.active' => '1', 'Category.parent_id' => null, 'Category.deleted' => '0'];
		$this->unbindModel(['belongsTo'=>['ParentCategory']]);
		return $this->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC']);
	}

	public function admin_getCategories()
	{
		App::uses('CakeSession', 'Model/Datasource');
		$conditions = ['Category.site_id' => CakeSession::read('Site.id'), 'Category.parent_id' => null, 'Category.deleted' => '0'];
		return $this->find('all', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC']);
	}

	public function admin_getCategoryList()
	{
		App::uses('CakeSession', 'Model/Datasource');
		$conditions = ['Category.site_id' => CakeSession::read('Site.id'), 'Category.parent_id' => null, 'Category.deleted' => '0'];
		return $this->find('list', ['conditions' => $conditions, 'recursive' => '-1', 'order' => 'Category.name ASC']);
	}
}
