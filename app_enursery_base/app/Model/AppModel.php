<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	/**
	 * Function to get landing page information.
	 */
	function getLandingPageInfoWithImages($siteId, $showSiteInfo=false) {
		App::uses('Content', 'Model');
		$this->Content = new Content;
		
		$conditions = array('Content.site_id'=>$siteId, 'Content.landing_page'=>'1');		
		$contentInfo = $this->Content->find('first', array('conditions'=>$conditions));
		
		if(empty($contentInfo)) {
			$data = array();
			$data['Content']['id'] = null;
			$data['Content']['landing_page'] = '1';
			$data['Content']['site_id'] = $this->getSiteID();					
			$data['Content']['title'] = 'Landing Page';	
			if($this->Content->save($data)) {
				$contentInfo = $this->Content->read();
			}	
		}		
		
		if(!empty($contentInfo)) {		
			App::uses('Image', 'Model');
			$this->Image = new Image;
			$this->Image->recursive = -1;
			$images = $this->Image->findAllByContentId($contentInfo['Content']['id']);
			
			$images = $this->Image->find('all', array('conditions'=>array('Image.content_id'=>$contentInfo['Content']['id']), 'order'=>array('Image.highlight DESC', 'Image.created DESC')));
			
			$contentInfo['Images'] = ($images) ? $images : array();
		}
		if(!$showSiteInfo and isset($contentInfo['Site'])) {
			unset($contentInfo['Site']);
		}
		
		return $contentInfo;
	} 
	
	function getHightlightImages($limit=null) {		
		$contentInfo = $this->getLandingPageInfoWithImages();	
		
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
}
