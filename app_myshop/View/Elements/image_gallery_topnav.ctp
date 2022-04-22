<?php $imageGalleryLinkActive = (isset($imageGalleryLinkActive)) ? $imageGalleryLinkActive : null; ?>

<li <?php echo $imageGalleryLinkActive; ?> id="photogallery">
	<?php echo $this->Html->link('Photo Gallery', '#', ['style' => 'text-decoration:none; cursor:default;', 'escape' => false, 'title' => 'Photo Gallery']); ?>

	<ul>
		<?php if ($this->Session->read('Site.show_products')) { ?>
			<li><?php echo $this->Html->link('Products Gallery', '/ImageGallery/productsGallery', ['title' => 'Products Photo Gallery']); ?></li>
		<?php } ?>

		<?php if ($this->Session->read('Site.show_landing_page')) { ?>
			<li><?php echo $this->Html->link('Highlights', '/ImageGallery/highlights', ['title' => 'Highlights']); ?></li>
		<?php } ?>
	</ul>
</li>


