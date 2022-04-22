<?php
App::uses('AppModel', 'Model');
class ProductSaleReport extends AppModel {
    public $name = 'ProductSaleReport';
	public $useTable = 'product_sale_report';
	public $displayField = 'product_name';
}