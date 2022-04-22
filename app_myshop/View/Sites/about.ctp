<div>
	<h1>About Us</h1>
	<br>
	<?php
	echo $this->Session->read('Site.description');
	?>
</div>

<?php
$pageUrl = $this->Html->url($this->request->here, true);
$customMeta = '';
$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $pageUrl, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'website', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => 'About Us', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => strip_tags($this->Session->read('Site.description')), 'inline' => false]);
// $customMeta .= ($productImageUrl) ? $this->Html->meta(['property' => 'og:image', 'content' => $productImageUrl, 'inline' => false]) : '';
$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);

$this->set('customMeta', $customMeta);
$this->set('title_for_layout', 'About Us');
?>
