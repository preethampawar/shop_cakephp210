<?php
App::uses('Validation', 'Utility');

class CustomersController extends AppController
{

    var $name = 'Customers';

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->checkStoreInfo();

        ini_set('max_execution_time', '10000');
        ini_set('memory_limit', '256M');

		$this->layout = 'new';
    }

    function index()
    {

    }

	public function customerSales()
	{
		$storeId = $this->Session->read('Store.id');

	}

	public function addSale()
	{
		$storeId = $this->Session->read('Store.id');
	}
}
