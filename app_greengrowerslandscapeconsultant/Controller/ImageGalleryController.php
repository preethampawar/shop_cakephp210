<?php
App::uses('CakeEmail', 'Network/Email');
class ImageGalleryController extends AppController {
	var $name = 'ImageGallery';
	
	public function beforeFilter() {
		parent::beforeFilter();		
		
		// Allow only if image gallery is enabled on site
		if(!$this->Session->read('Site.image_gallery')) {
			//$this->Session->setFlash('Image gallery on this site has been disabled.', 'default', array('class'=>'notice'));
			//$this->redirect($this->request->referer());
		}
		
		$this->Auth->allow('productsGallery', 'highlights', 'getHightlightImages', 'show');
	}
	
	function productsGallery() {	
		$hideLeftMenu = true;		
		
		App::uses('Product', 'Model');
		$this->Product = new Product;
		
		$allCategories = $this->Product->getSiteCategoriesProductsImages(array('allImages'=>true));
		$this->set(compact('allCategories', 'hideLeftMenu'));		
	}
	
	/* Show photo gallery of landing page images */
	function show() {
		$hideLeftMenu = true;
		if(!$this->checkLandingPage()) {
			$this->redirect('/');
		}
		$contentInfo = $this->getLandingPageInfo();	
		
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		$images = $this->getLandingPageImages();
		$contentInfo['Images'] = ($images) ? $images : array();
		
		$this->set(compact('contentInfo', 'hideLeftMenu'));
	}
	
	function getLandingPageImages($limit=null) {
		if(!$this->checkLandingPage()) {
			return null;
		}
		$contentInfo = $this->getLandingPageInfo();	
		
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		if(!$limit) {
			$images = $this->Image->find('all', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id'])));
		}
		else {
			$images = $this->Image->find('all', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id']), 'limit'=>$limit));		
		}
		
		return $images;
	}
	
	/* Highlight gallery for landing page */
	function highlights() {			
		$hideLeftMenu = true;
		if(!$this->checkLandingPage()) {
			$this->redirect('/');
		}
		$contentInfo = $this->getLandingPageInfo();	
		
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		$images = $this->getHightlightImages();
		$contentInfo['Images'] = ($images) ? $images : array();
		
		$this->set(compact('contentInfo', 'hideLeftMenu'));
	}
	
	function getHightlightImages($limit=null) {
		if(!$this->checkLandingPage()) {
			return null;
		}
		$contentInfo = $this->getLandingPageInfo();	
		
		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		if(!$limit) {
			$images = $this->Image->find('all', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id'], 'Image.highlight'=>1)));
		}
		else {
			$images = $this->Image->find('all', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id'], 'Image.highlight'=>1), 'limit'=>$limit));		
		}
		
		return $images;
	}
	
}
?>