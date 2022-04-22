<?php
App::uses('AppModel', 'Model');
class Category extends AppModel {
    public $name = 'Category';
    public $actsAs = array('Tree');
	
	var $belongsTo = array('ParentCategory'=>array('className'=>'Category', 'foreignKey'=>'parent_id'));	
	
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => false,
				'message' => 'Category name is a required field'
			),
			'between' => array(
				'rule' => array('between', 2, 55),
				'message' => 'Category name should be minimum of 2 characters and maximum of 55 characters'
			)
		)
	);
	
	function getCategories() {
		App::uses('CakeSession', 'Model/Datasource');
		$conditions = array('Category.site_id'=>CakeSession::read('Site.id'), 'Category.active'=>'1', 'Category.parent_id'=>null);		
		$categories = $this->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'order'=>'Category.name ASC'));		
		return $categories;
	}
	
	function admin_getCategories() {
		App::uses('CakeSession', 'Model/Datasource');
		$conditions = array('Category.site_id'=>CakeSession::read('Site.id'), 'Category.parent_id'=>null);		
		$categories = $this->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'order'=>'Category.name ASC'));		
		return $categories;
	}
	
	function admin_getCategoryList() {
		App::uses('CakeSession', 'Model/Datasource');
		$conditions = array('Category.site_id'=>CakeSession::read('Site.id'), 'Category.parent_id'=>null);		
		$categories = $this->find('list', array('conditions'=>$conditions, 'recursive'=>'-1', 'order'=>'Category.name ASC'));		
		return $categories;
	}
}