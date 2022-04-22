<?php
$this->set('homeLinkActive', true);

$keywords = ($this->Session->read('Site.meta_keywords')) ? $this->Session->read('Site.meta_keywords') : $this->Session->read('Site.title');
$description = ($this->Session->read('Site.meta_description')) ? $this->Session->read('Site.meta_description') : $this->Session->read('Site.title');

$siteCaption = $this->Session->read('Site.caption');
$title_for_layout = $this->Session->read('Site.title');
$title_for_layout .= (!empty($siteCaption)) ? ' - ' . $siteCaption : '';

//$this->set('title_for_layout', '');

$this->Html->meta('keywords', $keywords, ['inline' => false]);
$this->Html->meta('description', $description, ['inline' => false]);
?>

<?php
$url = $this->Html->url('/', true);

$customMeta = '';
$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $url, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'website', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => $title_for_layout, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => $description, 'inline' => false]);
// $customMeta.=$this->Html->meta(array('property' => 'og:image', 'content' => 'http://saibaba.enursery.in/img/imagecache/450_25_600x600_auto_450.jpg', 'inline'=>false));

$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);
//$customMeta.=$this->Html->meta(array('property' => 'fb:admins', 'content' => '530846121', 'inline'=>false));
?>

<?php
//if ($this->Session->read('Site.featured_products')) {
//	echo $this->element('featured_products');
//}
echo $this->element('featured_products');

$this->set('customMeta', $customMeta);
?>
<br>
