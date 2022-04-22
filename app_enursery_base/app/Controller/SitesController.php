<?php
class SitesController extends AppController {

	var $name = 'Sites';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('getActiveSitesList', 'sitemap', 'notFound');	
	}
	
	function getSiteList()
	{		
		return $this->Site->find('list');
	}
	
	function getActiveSitesList($getProducts=false)
	{		
		$this->Site->bindModel(array('belongsTo'=>array('User'), 'hasMany'=>array('Domain'=>array('order'=>array('Domain.default DESC'), 'limit'=>'1'))));
		$sites = $this->Site->find('all', array('conditions'=>array('Site.active'=>'1', 'Site.name NOT'=>'www', 'Site.show_in_clients_list'=>'1', 'Site.suspended'=>'0'), 
			'fields'=>array('Site.id', 'Site.name', 'Site.title', 'Site.description', 'Site.contact_email', 'Site.contact_phone', 'Site.show_products', 'Site.service_type', 'Site.address', 'Site.caption', 'User.id', 'User.name', 'User.email', 'User.phone', 'User.city', 'User.state', 'User.country', 'User.address'), 'order'=>array('Site.views DESC', 'Site.created ASC')));		
		if($getProducts) {
			App::uses('ProductVisit', 'Model');
			$this->ProductVisit = new ProductVisit;
			
			if(!empty($sites)) {
				foreach($sites as $index=>$row) {
					$conditions = array('ProductVisit.site_id'=>$row['Site']['id']);
					$this->ProductVisit->bindModel(array('belongsTo'=>array('Product'=>array('conditions'=>array('Product.active'=>'1'), 'fields'=>array('Product.id', 'Product.name')), 'Category'=>array('conditions'=>array('Category.active'=>'1'), 'fields'=>array('Category.id', 'Category.name')))));
					$products = $this->ProductVisit->find('all', array('conditions'=>$conditions, 'order'=>array('ProductVisit.visit_count DESC'), 'fields'=>array('ProductVisit.visit_count', 'Product.id', 'Product.name', 'Category.id', 'Category.name'), 'limit'=>100));
					$sites[$index]['ProductInfo'] = $products;
				}
			}			
		}
		
		return $sites;
	}
	
	function sitemap() {
		$this->layout=null;	
		$this->response->type('xml');
	}
	
	function notFound() {
		$this->layout = 'notfound';
	}
	
	function admin_index() {
		$this->checkSuperAdmin();
		//$siteID = $this->Session->read('Site.id');		
		$this->paginate = array(
				'limit' => 25,
				'order' => array('Site.created' => 'desc')
			);	
		$sites = $this->paginate();
		$this->set(compact('sites'));
	}
		
	function admin_edit($siteID)
	{
		$this->checkSuperAdmin();
		$siteInfo = $this->Site->findById($siteID);
		
		if($this->request->isPut() or $this->request->isPost())
		{		
			$data['Site'] = $this->data['Site'];
			$data['Site']['id'] = $siteID;		
			if($this->Site->save($data))
			{
				$siteInfo = $this->Site->findById($siteID);
				$this->Session->setFlash('Data saved successfully', 'default', array('class'=>'success'));				
			}
			else
			{
				$this->Session->setFlash('Failed to save data', 'default', array('class'=>'error'));
			}
		}
		else {		
			$this->data = $siteInfo;
		}
		$this->set('siteInfo', $siteInfo);
	}
	
	function admin_addDomain($siteID) {
		if($this->request->isPost())
		{	
			App::uses('Domain', 'Model');
			$this->Domain = new Domain;
			$error = false;
			$data['Domain'] = $this->request->data['Domain'];
			if(empty($data['Domain']['name'])) {
				$error = true;
				$this->Session->setFlash('Enter Domain Name', 'default', array('class'=>'error'));
			}			
			elseif($this->Domain->findByName($data['Domain']['name'])) {
				$error = true;
				$this->Session->setFlash('Domain "'.$data['Domain']['name'].'" is already registered', 'default', array('class'=>'error'));
			}
			
			if(!$error) {				
				$data['Domain']['id'] = null;
				$data['Domain']['site_id'] = $siteID;
				if($this->Domain->save($data))
				{
					$this->Session->setFlash('Domain name added successfully', 'default', array('class'=>'success'));				
				}
				else
				{
					$this->Session->setFlash('Failed to create domain name', 'default', array('class'=>'error'));
				}
			}
		}
		else {		
			$this->Session->setFlash('You are not authorized to perform this action', 'default', array('class'=>'error'));
		}
		$this->redirect('/admin/sites/edit/'.$siteID);
	}
	
	function admin_deleteDomain($domainID, $siteID) {
		if($this->request->isGet())
		{	
			App::uses('Domain', 'Model');
			$this->Domain = new Domain;
			$domainInfo = $this->Domain->findById($domainID);
			if($domainInfo)	{
				if($domainInfo['Domain']['default']) {
					$this->Session->setFlash('You cannot delete a default domain', 'default', array('class'=>'error'));
				}
				else {			
					if($this->Domain->delete($domainID))
					{
						$this->Session->setFlash('Domain name deleted successfully', 'default', array('class'=>'success'));				
					}
					else
					{
						$this->Session->setFlash('Failed to delete domain name', 'default', array('class'=>'error'));
					}		
				}
			}
			else {
				$this->Session->setFlash('Domain not found', 'default', array('class'=>'error'));
			}
		}
		else {		
			$this->Session->setFlash('You are not authorized to perform this action', 'default', array('class'=>'error'));
		}
		$this->redirect('/admin/sites/edit/'.$siteID);
	}
	
	function admin_setDefaultDomain($domainID, $siteID) {
		if($this->request->isGet())
		{	
			App::uses('Domain', 'Model');
			$this->Domain = new Domain;
			$domainInfo = $this->Domain->findById($domainID);
			if($domainInfo)	{
				if($domainInfo['Domain']['default']) {
					$this->Session->setFlash('You cannot delete a default domain', 'default', array('class'=>'error'));
				}
				else {			
					// reset all domains
					$conditions = array('Domain.site_id'=>$siteID);
					$domains = $this->Domain->findAllBySiteId($siteID);
					foreach($domains as $row) {
						$data = array();
						$data['Domain']['id'] = $row['Domain']['id'];
						$data['Domain']['site_id'] = $siteID;
						$data['Domain']['default'] = '0';
						$this->Domain->save($data);						
					}					
					
					// make the selected domain default
					$data = array();
					$data['Domain']['id'] = $domainID;
					$data['Domain']['default'] = true;
					if($this->Domain->save($data)) {
						$this->Session->setFlash('Domain successfully set to default', 'default', array('class'=>'success'));				
					}
					else {
						$this->Session->setFlash('Failed to set domain as default', 'default', array('class'=>'error'));
					}					
				}
			}
			else {
				$this->Session->setFlash('Domain not found', 'default', array('class'=>'error'));
			}
		}
		else {		
			$this->Session->setFlash('You are not authorized to perform this action', 'default', array('class'=>'error'));
		}
		$this->redirect('/admin/sites/edit/'.$siteID);
	}
	
	function admin_siteInfo() {		
		if($siteInfo = $this->Site->findByUserId($this->Session->read('User.id'))) {		
			$this->data = $siteInfo;
			$this->set(compact('siteInfo'));
		}
		else {
			$this->Session->setFlash('Site not found', 'default', array('class'=>'error'));
			$this->redirect('/');
		}
	}
}	
?>