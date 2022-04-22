<?php 
$this->set('homeLinkActive', true);

$keywords = ($this->Session->read('Site.meta_keywords')) ? $this->Session->read('Site.meta_keywords') : $this->Session->read('Site.title').'Plant Nursery,Buy plants online,Plant Nurseries';
$description = ($this->Session->read('Site.meta_description')) ? $this->Session->read('Site.meta_description') : $this->Session->read('Site.title').' is a online plant nursery store where you can buy plants and gardening items';

$siteCaption = $this->Session->read('Site.caption');
$title_for_layout = $this->Session->read('Site.title');
$title_for_layout.=(!empty($siteCaption)) ? ' - '.$siteCaption : '';

$this->set('title_for_layout', '');
$this->Html->meta('keywords', $keywords, array('inline'=>false));
$this->Html->meta('description', $description, array('inline'=>false));

?>

<?php	
	$url = $this->Html->url('/', true);
	
	$customMeta = '';
	$customMeta.=$this->Html->meta(array('property' => 'og:url', 'content' => $url, 'inline'=>false));	
	$customMeta.=$this->Html->meta(array('property' => 'og:type', 'content' => 'website', 'inline'=>false));
	$customMeta.=$this->Html->meta(array('property' => 'og:title', 'content' => $title_for_layout, 'inline'=>false));
	$customMeta.=$this->Html->meta(array('property' => 'og:description', 'content' => $description, 'inline'=>false));
	// $customMeta.=$this->Html->meta(array('property' => 'og:image', 'content' => 'http://saibaba.enursery.in/img/imagecache/450_25_600x600_auto_450.jpg', 'inline'=>false));
	
	$customMeta.=$this->Html->meta(array('property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline'=>false));
	//$customMeta.=$this->Html->meta(array('property' => 'fb:admins', 'content' => '530846121', 'inline'=>false));
?>	

<?php
$hasProducts = false;
if($this->Session->read('Site.show_products')) { 		
	$hasProducts = true;
	if($this->Session->read('Site.featured_products')) { 
		echo $this->element('featured_products');			
	}
}
	
if(!$hasProducts) {
	$hideLeftMenu = true;
	$this->set('hideLeftMenu', $hideLeftMenu);
}

App::uses('Content', 'Model');
$this->Content = new Content;	
$contentInfo = $this->Content->getLandingPageInfoWithImages();	
if(!empty($contentInfo['Images'])) {
	$imageUrl = '';
	foreach($contentInfo['Images'] as $image) {
		$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
		$imageCaption = (!empty($image['Image']['caption'])) ? trim($image['Image']['caption']) : '';
		$captionSlug = Inflector::slug($imageCaption, '-');
		
		$imageUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'500','width'=>'960','type'=>'crop', 'quality'=>'95', 'filename'=>$captionSlug), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);
		
		$customMeta.=$this->Html->meta(array('property' => 'og:image', 'content' => $this->Html->url($imageUrl, true), 'inline'=>false));
		
		break;
	}
	$customMeta.=$this->Html->meta(array('property' => 'fb:admins', 'content' => '530846121', 'inline'=>false));
}

echo $this->element('recent_blog_posts');

if($this->Session->read('isMobile') == true) {
	echo $this->element('route_map');	
	echo $this->element('get_in_contact');	
}

$this->set('customMeta', $customMeta);
?>