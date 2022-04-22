<?php $imageGalleryLinkActive = (isset($imageGalleryLinkActive)) ? $imageGalleryLinkActive : null;?>

<?php if($this->Session->read('Site.show_products')) { 	?> 
	<li <?php echo $imageGalleryLinkActive;?> id="photogallery1"><?php echo $this->Html->link('Products Gallery', '/ImageGallery/productsGallery', array('title'=>'Products Photo Gallery'));?></li>
<?php } ?>

<?php if($this->Session->read('Site.show_landing_page')) { 	?> 
	<li <?php echo $imageGalleryLinkActive;?> id="photogallery"><?php echo $this->Html->link('Photo Gallery', '/ImageGallery/show', array('title'=>'Photo Gallery'));?></li>
<?php } ?>



	