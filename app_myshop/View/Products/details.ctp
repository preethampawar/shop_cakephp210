<?php
$categoryID = $categoryInfo['Category']['id'];
$categoryName = ucwords($categoryInfo['Category']['name']);
$categoryNameSlug = Inflector::slug($categoryName, '-');

$productID = $productInfo['Product']['id'];
$productName = ucwords($productInfo['Product']['name']);
$productNameSlug = Inflector::slug($productName, '-');
$productDesc = $productInfo['Product']['description'];
$showRequestPriceQuote = $productInfo['Product']['request_price_quote'];
?>

<?php echo $this->set('title_for_layout', $productName); ?>

<?php
if (!empty($productInfo['Product']['meta_keywords'])) {
	$this->Html->meta('keywords', $productInfo['Product']['meta_keywords'], ['inline' => false]);
} else {
	$this->Html->meta('keywords', $categoryName . ',' . $productName . ',buy ' . $productName . ',get price quote for ' . $productName, ['inline' => false]);
}
?>

<?php
if (!empty($productInfo['Product']['meta_description'])) {
	$desc = $productInfo['Product']['meta_description'];
} else {
	$desc = 'Item ' . $productName . ' belongs to ' . $categoryName . '. Contact us to get price quote or purchase this item(' . $productName . ')';
}
$this->Html->meta('description', $desc, ['inline' => false]);
?>

<nav>
	<div itemscope itemtype="http://schema.org/BreadcrumbList">
		<ul id="productNav">
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<?php echo $this->Html->link('<span itemprop="name">' . $categoryName . '</span>', '/products/show/' . $categoryID . '/' . $categoryNameSlug, ['itemscope', 'itemtype' => 'http://schema.org/ListItem', 'escape' => false]); ?>
				<meta itemprop="position" content="1"/>
			</li>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<?php echo $this->Html->link('<span itemprop="name">' . $productName . '</span>', '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, ['itemscope', 'itemtype' => 'http://schema.org/ListItem', 'escape' => false]); ?>
				<meta itemprop="position" content="2"/>
			</li>
		</ul>
		<div class='clear'></div>
	</div>
</nav>

<div itemscope itemtype="http://schema.org/Product">
	<?php
	$this->set('enableLightbox', false);
	$imageUrl = null;
	if (!empty($productImages)) {
		$this->set('enableLightbox', true);
		?>
		<div id="productImages" class="product-details-page-slider">
			<?php
			$higlightImage = '';
			$k = 0;
			foreach ($productImages as $row) {
				$k++;
				$imageID = $row['Image']['id'];
				$imageCaption = ($row['Image']['caption']) ? $row['Image']['caption'] : $productName;
				$imageCaptionSlug = Inflector::slug($imageCaption, '-');

				$imageUrl = $this->Html->url($this->Img->showImage('img/images/' . $imageID, ['height' => '600', 'width' => '600', 'type' => 'auto', 'quality' => '85', 'filename' => $imageCaptionSlug], ['style' => '', 'alt' => $productName, 'title' => $imageCaption], true), true);
				$imageThumbUrl = $this->Html->url($this->Img->showImage('img/images/' . $imageID, ['height' => '150', 'width' => '150', 'type' => 'crop', 'quality' => '85', 'filename' => $productNameSlug], ['style' => '', 'alt' => $productName, 'title' => $imageCaption], true), true);
				if ($row['Image']['highlight']) {
					$higlightImage = $imageUrl;
				}

				?>

				<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
					<a href="<?php echo $imageUrl; ?>" title='<?php echo $imageCaption; ?>' data-lightbox="roadtrip">
						<img itemprop="image" src="<?php echo $imageThumbUrl; ?>" alt="<?php echo $productName; ?>"
							 width='150' height='150'/>
						<?php
						// echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop'), array('style'=>'', 'alt'=>$productName, 'title'=>$imageCaption));
						?>
					</a>
				</div>

				<!--			<div>-->
				<!--				<a href="--><?php //echo $imageUrl;?><!--" title='--><?php //echo $imageCaption;?><!--'>-->
				<!--					<img itemprop="image" alt="--><?php //echo $imageCaption;?><!--" data-lazy="--><?php //echo $imageThumbUrl;?><!--" src="--><?php //echo $imageThumbUrl;?><!--"  title='--><?php //echo $imageCaption;?><!--'/>					-->
				<!--				</a>-->
				<!--			</div>		-->
				<?php
			}
			?>
			<div style="clear: both"></div>
			<br><br>
		</div>

		<?php
	}
	?>

	<div id="productDetails">
		<section>
			<article>
				<header><h2><span itemprop="name"><?php echo $productName; ?></span></h2></header>
				<?php
				if ($this->Session->read('Site.request_price_quote') and $showRequestPriceQuote) {
					echo $this->Form->create('ShoppingCart', ['url' => '/shopping_carts/add/' . $categoryID . '/' . $productID, 'encoding' => false]); ?>

					<div class="floatLeft" style="width:100px; margin-right:10px;">
						<?php
						$qtyOptions = Configure::read('Product.quantity');
						echo $this->Form->input('ShoppingCartProduct.quantity', ['options' => $qtyOptions, 'empty' => false]);
						?>
					</div>
					<div class="floatLeft" style="width:100px; margin-right:10px;">
						<?php
						$sizeOptions = Configure::read('Product.size');
						echo $this->Form->input('ShoppingCartProduct.size', ['options' => $sizeOptions, 'empty' => '-']);
						?>
					</div>
					<div class="floatLeft" style="width:150px; margin-right:10px;">
						<?php
						$ageOptions = Configure::read('Product.age');
						echo $this->Form->input('ShoppingCartProduct.age', ['options' => $ageOptions, 'empty' => '-']);
						?>
					</div>
					<div class="floatLeft" style="margin-right:10px;">
						<br>
						<?php echo $this->Form->submit('Add To Cart &raquo;', ['escape' => false]); ?>
					</div>
					<div class='clear'></div>

					<?php
					echo $this->Form->end();
				}
				?>
				<?php
				if (!empty($productDesc)) {
					?>
					<div>
						<br><br>
						<h3>Description</h3>
						<span itemprop="description"><?php echo $productDesc; ?></span>
					</div>
					<?php
				}
				?>
				<br>
				<p><strong>Total Page Views = <?php echo $visits; ?></strong></p>

			</article>
		</section>
	</div>
	<br>
	<?php
	$uri = $this->request->here();
	$domain = $this->request->host();
	$url = 'http://' . $domain . $uri;
	?>
	<div>
		<div style="float:left">
			<g:plusone annotation="bubble" size="standard"></g:plusone>
		</div>
		<div style="float:left">
			<div class="fb-like" data-send="true" data-href="<?php echo $url; ?>" data-width="350"
				 data-show-faces="true"></div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php
	$customMeta = '';
	$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => $productName, 'inline' => false]);
	$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'product', 'inline' => false]);
	$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $url, 'inline' => false]);

	$customMeta .= ($imageUrl) ? $this->Html->meta(['property' => 'og:image', 'content' => ($higlightImage) ? $higlightImage : $imageUrl, 'inline' => false]) : '';
	$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);
	//$customMeta.=$this->Html->meta(array('property' => 'fb:admins', 'content' => '530846121', 'inline'=>false));
	$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => $desc, 'inline' => false]);
	$this->set('customMeta', $customMeta);
	?>

	<div class="fb-comments" data-href="<?php echo $url; ?>" data-num-posts="10" data-width="600"></div>
	<br><br>




