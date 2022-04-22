<?php
if ($this->layout == 'splash') {
	return;
}

$buyerView = true;
$deliveryView = false;

if ($this->Session->check('inDeliveryView') && $this->Session->read('inDeliveryView') === true) {
	$buyerView = false;
	$deliveryView = true;
}

if ($buyerView) {
	$this->set('homeLinkActive', true);

	$keywords = ($this->Session->read('Site.meta_keywords')) ? $this->Session->read('Site.meta_keywords') : $this->Session->read('Site.title');
	$description = ($this->Session->read('Site.meta_description')) ? $this->Session->read('Site.meta_description') : $this->Session->read('Site.title');

	$siteCaption = $this->Session->read('Site.caption');
	$title_for_layout = $this->Session->read('Site.title');
	$title_for_layout .= (!empty($siteCaption)) ? ' - ' . $siteCaption : '';
	$this->set('title_for_layout', '');
	$this->set('canonical', '/');

	$this->Html->meta('keywords', $keywords, ['inline' => false]);
	$this->Html->meta('description', $description, ['inline' => false]);

	$url = $this->Html->url('/', true);

	$customMeta = '';
	$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $url, 'inline' => false]);
	$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'website', 'inline' => false]);
	$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => $title_for_layout, 'inline' => false]);
	$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => strip_tags($description), 'inline' => false]);
	// $customMeta.=$this->Html->meta(array('property' => 'og:image', 'content' => 'http://saibaba.enursery.in/img/imagecache/450_25_600x600_auto_450.jpg', 'inline'=>false));

	$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);

	$catListCacheKey = $this->Session->read('CacheKeys.catList');
	$categoryListMenu = Cache::read($catListCacheKey, 'verylong');

	echo $this->element('homepage_categories', ['categoryListMenu' => $categoryListMenu]);
?>
	
<?php
/*
?>
	<div id="homepage_categories_menu_container">
		<div class="table-responsive mb-3 " aria-hidden="true">
			<div class="hstack gap-3">
				<div class="text-center placeholder-glow" style="width: 9rem;">
					<div class="placeholder rounded-circle" style="width: 144px; height: 144px;"></div>
					<div class="card-body">
						<span class="placeholder col-10"></span>
						<span class="placeholder col-6"></span>
					</div>
				</div>
				<div class="text-center placeholder-glow" style="width: 9rem;">
					<div class="placeholder rounded-circle" style="width: 144px; height: 144px;"></div>
					<div class="card-body">
						<span class="placeholder col-10"></span>
						<span class="placeholder col-6"></span>
					</div>
				</div>
				<div class="text-center placeholder-glow" style="width: 9rem;">
					<div class="placeholder rounded-circle" style="width: 144px; height: 144px;"></div>
					<div class="card-body">
						<span class="placeholder col-10"></span>
						<span class="placeholder col-6"></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="home_page_category_products">
		<div class="table-responsive mb-3 " aria-hidden="true">
			<div class="hstack gap-3">
				<div class="card placeholder-glow" style="width: 10rem;">
					<div class="placeholder card-img-top" style="height: 126px"></div>
					<div class="card-body p-2">
						<span class="placeholder col-10"></span>
						<span class="placeholder col-6"></span>
						<span class="placeholder col-12 placeholder-lg mt-3"></span>
					</div>
				</div>
				<div class="card placeholder-glow" style="width: 10rem;">
					<div class="placeholder card-img-top" style="height: 126px"></div>
					<div class="card-body p-2">
						<span class="placeholder col-10"></span>
						<span class="placeholder col-6"></span>
						<span class="placeholder col-12 placeholder-lg mt-3"></span>
					</div>
				</div>
				<div class="card placeholder-glow" style="width: 10rem;">
					<div class="placeholder card-img-top" style="height: 126px"></div>
					<div class="card-body p-2">
						<span class="placeholder col-10"></span>
						<span class="placeholder col-6"></span>
						<span class="placeholder col-12 placeholder-lg mt-3"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	*/
	?>
<?php
	echo $this->element('featured_products', ['limit' => 42, 'homepage' => true]);

	$this->set('customMeta', $customMeta);
} else {
}
?>
<br>