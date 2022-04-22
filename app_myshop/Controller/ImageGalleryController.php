<?php
App::uses('CakeEmail', 'Network/Email');

class ImageGalleryController extends AppController
{
	var $name = 'ImageGallery';

	public function beforeFilter()
	{
		parent::beforeFilter();

		// Allow only if image gallery is enabled on site
		if (!$this->Session->read('Site.image_gallery')) {
			$this->Session->setFlash('Image gallery on this site has been disabled.', 'default', ['class' => 'notice']);
			$this->redirect($this->request->referer());
		}

		$this->Auth->allow('productsGallery', 'highlights', 'getHightlightImages');
	}

	function productsGallery()
	{
		$hideLeftMenu = true;

		App::uses('Product', 'Model');
		$this->Product = new Product;

		$allCategories = $this->Product->getSiteCategoriesProducts(['allImages' => true], $this->Session->read('Site.id'));
		$this->set(compact('allCategories', 'hideLeftMenu'));
	}

	/* Highlight gallery for landing page */
	function highlights()
	{
		$hideLeftMenu = true;
		if (!$this->checkLandingPage()) {
			$this->redirect('/');
		}
		$contentInfo = $this->getLandingPageInfo();

		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		$images = $this->getHightlightImages();
		$contentInfo['Images'] = ($images) ? $images : [];

		$this->set(compact('contentInfo', 'hideLeftMenu'));
	}

	function getHightlightImages($limit = null)
	{
		if (!$this->checkLandingPage()) {
			return null;
		}
		$contentInfo = $this->getLandingPageInfo();

		App::uses('Image', 'Model');
		$this->Image = new Image;
		$this->Image->recursive = -1;
		if (!$limit) {
			$images = $this->Image->find('all', ['conditions' => ['Image.content_id' => $contentInfo['Content']['id']]]);
		} else {
			$images = $this->Image->find('all', ['conditions' => ['Image.content_id' => $contentInfo['Content']['id']], 'limit' => $limit]);
		}

		return $images;
	}

}

?>
