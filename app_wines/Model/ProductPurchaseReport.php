<?php
App::uses('AppModel', 'Model');
class ProductPurchaseReport extends AppModel {
    public $name = 'ProductPurchaseReport';
	public $useTable = 'product_purchase_report';
	public $displayField = 'product_name';
}