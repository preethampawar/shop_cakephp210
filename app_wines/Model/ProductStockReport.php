<?php
App::uses('AppModel', 'Model');
class ProductStockReport extends AppModel {
    public $name = 'ProductStockReport';
	public $useTable = 'product_stock_report';
	public $primaryKey = 'product_id';
	public $displayField = 'product_name';
}