<?php

class DefaultPriceListController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
	}


	public function index()
	{
		$hideHeader = false;
		$hideSideBar = true;

		$this->set(compact('hideHeader', 'hideSideBar'));
	}

}

?>
