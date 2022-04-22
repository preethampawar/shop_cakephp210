<?php
class DefaultPriceListController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();				
	}	
	
	
	public function index() {
		$hideHeader = false;
		$hideSideBar = true;
		$products = $this->DefaultPriceList->find('all'); 
		
		$this->set(compact('products', 'hideSideBar'));
	}
	
}
?>