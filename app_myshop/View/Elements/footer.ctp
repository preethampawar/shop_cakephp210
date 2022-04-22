<!-- ########################################################################################## -->
<?php
if ($this->Session->read('isMobile') == false) {
	?>
	<?php
	if ($this->Session->read('Site.image_gallery') and false) {
		?>
		<?php
		if ($this->Session->read('Site.show_products')) {
			?>
			<section class="one_quarter">
				<h2 class="title">From Product Gallery</h2>
				<?php
				App::uses('Product', 'Model');
				$this->Product = new Product;
				$this->Product->unbindModel(['hasMany' => ['CategoryProduct']]);
				$this->Product->bindModel(['hasMany' => ['Image' => ['limit' => '1', 'order' => 'Image.highlight DESC']]]);
				$products = $this->Product->find('all', ['conditions' => ['Product.site_id' => $this->Session->read('Site.id'), 'Product.active' => '1'], 'limit' => '9', 'recursive' => '1']);
				$images = [];
				if (!empty($products)) {
					foreach ($products as $row) {
						$productName = $row['Product']['name'];
						$productNameSlug = Inflector::slug($productName, '-');
						if (!empty($row['Image'])) {
							$imageID = $row['Image'][0]['id'];
							$imageCaption = $row['Image'][0]['caption'];
							$productImageUrl = $this->Html->url($this->Img->showImage('img/images/' . $imageID, ['height' => '60', 'width' => '60', 'type' => 'crop', 'quality' => '75', 'filename' => $productNameSlug], ['style' => '', 'height' => '60', 'width' => '60', 'alt' => $productName, 'title' => $imageCaption, 'escape' => false], true), true);
							$imageTagId = 'imageGallery' . '-' . $imageID;
							$image = '<img
										data-original="' . $productImageUrl . '"
										height="60"
										width="60"
										class="lazy"
										alt="' . $productName . '"
										id="' . $imageTagId . '"
									/>';

							$images[] = $image;
						}
					}
				}
				if (!empty($images)) {
					?>
					<div class="ft_gallery clear">
						<ul>
							<?php
							foreach ($images as $image) {
								echo '<li>' . $image . '</li>';
							}
							?>
						</ul>
						<div
							class="more"><?php echo $this->Html->link('Visit Photo Gallery...', '/ImageGallery/productsGallery'); ?></div>
					</div>
					<?php
				} else {
					echo 'No Images';
				}
				?>
			</section>

			<?php
		} else if ($this->Session->read('Site.show_landing_page')) {
			App::uses('AppModel', 'Model');
			$this->AppModel = new AppModel;
			$images = $this->AppModel->getHightlightImages(12);
			?>
			<section class="two_quarter">
				<h2 class="title">From Photo Gallery</h2>
				<?php
				if (!empty($images)) {
					?>
					<div class="ft_gallery clear">
						<ul>
							<?php
							foreach ($images as $image) {
								$imageID = $image['Image']['id'];
								$imageCaption = $image['Image']['caption'];
								$imageCaptionSlug = Inflector::slug($imageCaption, '-');

								$productImageUrl = $this->Html->url($this->Img->showImage('img/images/' . $imageID, ['height' => '60', 'width' => '60', 'type' => 'crop', 'quality' => '75', 'filename' => $imageCaptionSlug], ['style' => '', 'height' => '60', 'width' => '60', 'alt' => $imageCaption, 'title' => $imageCaption, 'escape' => false], true), true);
								$imageTagId = 'imagePhotoGallery' . '-' . $imageID;
								$image = '<img
												data-original="' . $productImageUrl . '"
												height="60"
												width="60"
												class="lazy"
												alt="' . $imageCaption . '"
												id="' . $imageTagId . '"
												title="' . $imageCaption . '"
											/>';

								echo '<li>' . $image . '</li>';
							}
							?>
						</ul>
						<div
							class="more"><?php echo $this->Html->link('Visit Photo Gallery...', '/ImageGallery/highlights'); ?></div>
					</div>
					<?php
				} else {
					echo 'No Images';
				}
				?>
			</section>

			<?php
		}
	}
	?>
	<?php
}
?>
<section class="one_quarter">
	<h2 class="title">Quick Links</h2>
	<nav>
		<ul>
			<li><?php echo $this->Html->link('Home Page', '/', ['title' => 'Home Page']); ?></li>

			<?php
			if ($this->Session->read('Site.show_products')) {
				?>
				<li>
					<?php echo $this->Html->link('Products', '/products', ['title' => 'Products Page']); ?>
				</li>
				<?php
			}
			?>

			<?php
			if ($this->Session->read('Site.image_gallery')) {
				if ($this->Session->read('Site.show_products')) {
					?>
					<li><?php echo $this->Html->link('Photo Gallery', '/ImageGallery/productsGallery', ['title' => 'Show product gallery']); ?></li>
					<?php
				} else if ($this->Session->read('Site.show_landing_page')) {
					?>
					<li><?php echo $this->Html->link('Photo Gallery', '/ImageGallery/highlights', ['title' => 'Show photo gallery']); ?></li>
					<?php
				}
			}
			?>
			<?php
			$map = ($this->Session->read('Site.embed_map')) ? $this->Session->read('Site.embed_map') : '';
			echo ($map) ? '<li>' . $this->Html->link('Route map', ['controller' => 'sites', 'action' => 'routemap'], ['escape' => false]) . '</li>' : null;
			?>
			<?php echo $this->element('footer_content_links'); ?>
		</ul>
		<br>
	</nav>
</section>


<section>
	<h2 class="title">Site
		Visitors: <?php echo ($this->Session->read('SiteVisits')) ? $this->Session->read('SiteVisits') : '1'; ?>+</h2>
</section>
<?php
if ($this->Session->read('isMobile') == false) {
	if ($this->Session->read('Site.show_products')) {
		echo $this->element('most_viewed_products');
	}
}
?>
<!--
<section class="one_quarter lastbox">
  <h2 class="title">Latest Tweets</h2>
  <div class="ft_tweets">
    <ul>
      <li><a href="#">@namehere</a> Justoid nonummy laoreet phasellent penatoque in antesque pellus elis eget tincidunt. Nequatdui laorem justo a non tellus laoremut vitae doloreet 1 day ago</li>
      <li><a href="#">@namehere</a> Justoid nonummy laoreet phasellent penatoque in antesque pellus elis eget tincidunt. Nequatdui laorem justo a non tellus laoremut vitae doloreet 1 day ago</li>
    </ul>
  </div>
</section>
-->
<!-- ########################################################################################## -->
