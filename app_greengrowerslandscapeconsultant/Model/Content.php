<?php
App::uses('AppModel', 'Model');

class Content extends AppModel {
	var $name = 'Content';
	var $belongsTo = array('Site');
	
	function getFooterNavContent() {
		App::uses('CakeSession', 'Model/Datasource');	
		$siteID = CakeSession::read('Site.id');
		$conditions = array('Content.site_id'=>$siteID, 'Content.footer_menu'=>'1', 'Content.active'=>'1');
		$contents = $this->find('all', array('conditions'=>$conditions, 'order'=>'Content.priority'));
		return $contents;
	}
	
	function getTopNavContent() {
		App::uses('CakeSession', 'Model/Datasource');	
		$siteID = CakeSession::read('Site.id');
		$conditions = array('Content.site_id'=>$siteID, 'Content.top_nav_menu'=>'1', 'Content.active'=>'1');
		$contents = $this->find('all', array('conditions'=>$conditions, 'recursive'=>'-1', 'order'=>'Content.priority'));
		
		return $contents;
	}
	
	function getAllContent() {
		App::uses('CakeSession', 'Model/Datasource');
		$siteID = CakeSession::read('Site.id');
		$conditions = array('Content.site_id'=>$siteID, 'Content.active'=>'1');
		$contents = $this->find('all', array('conditions'=>$conditions, 'recursive'=>'-1'));
		
		return $contents;
	}
	
}
