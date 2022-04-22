 <!-- ########################################################################################## -->
 <?php
 if($this->Session->read('isMobile') == false) {
 ?>
    <?php if($this->Session->read('Site.image_gallery')) 
	{ 
	?>
		<?php 
		if($this->Session->read('Site.show_products')) { 
		?>
			<section class="one_quarter">
			  <h2 class="title">From Product Gallery</h2>
			  <?php
			  App::uses('Product', 'Model');
			  $this->Product = new Product;
			  $this->Product->unbindModel(array('hasMany'=>array('CategoryProduct')));
			  $this->Product->bindModel(array('hasMany'=>array('Image'=>array('limit'=>'1', 'order'=>'Image.highlight DESC'))));
			  $products = $this->Product->find('all', array('conditions'=>array('Product.site_id'=>$this->Session->read('Site.id'), 'Product.active'=>'1'), 'limit'=>'9', 'recursive'=>'1'));
			  $images = array();
			  if(!empty($products)) {
				foreach($products as $row) {
					$productName = $row['Product']['name'];
					$productNameSlug = Inflector::slug($productName, '-');
					if(!empty($row['Image'])) {
						$imageID = $row['Image'][0]['id'];
						$imageCaption = $row['Image'][0]['caption'];
						$images[] = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'50', 'filename'=>$productNameSlug), array('style'=>'', 'height'=>'60' ,'width'=>'60', 'alt'=>$productName, 'title'=>$imageCaption, 'escape'=>false));
					}
				}
			  }
			  if(!empty($images)) {
			  ?>
				  <div class="ft_gallery clear">
					<ul>
						<?php
						foreach($images as $image) {
							echo '<li>'.$image.'</li>';
						}
						?>			  
					</ul>
					<div class="more"><?php echo $this->Html->link('Visit Photo Gallery...', '/ImageGallery/productsGallery');?></div>
				  </div>
			  <?php
			  }
			  else {
				echo 'No Images';
			  }
			  ?>
			</section>
			
		<?php
		}	
		elseif($this->Session->read('Site.show_landing_page')) { 
			App::uses('AppModel', 'Model');
			$this->AppModel = new AppModel;
			$images = $this->AppModel->getHightlightImages(12);			
		?>
			<section class="two_quarter">
				<h2 class="title">From Photo Gallery</h2>
				<?php
				if(!empty($images)) {
				?>
					<div class="ft_gallery clear">
						<ul>
							<?php
							foreach($images as $image) {
								$imageID = $image['Image']['id'];
								$imageCaption = $image['Image']['caption'];
								$imageCaptionSlug = Inflector::slug($imageCaption, '-');
								$image = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'50', 'filename'=>$imageCaptionSlug), array('style'=>'', 'height'=>'60' ,'width'=>'60', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false));
								
								echo '<li>'.$image.'</li>';
							}
							?>			  
						</ul>
					<div class="more"><?php echo $this->Html->link('Visit Photo Gallery...', '/ImageGallery/highlights');?></div>
				  </div>
				<?php
				}
				else {
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
			<li><?php echo $this->Html->link('Home Page', '/', array('title'=>'Home Page'));?></li>

			<?php 
			if($this->Session->read('Site.show_products')) {
			?>
				<li>
					<?php	echo $this->Html->link('Products', '/products', array('title'=>'Products Page'));	?>
				</li>
			<?php
			}
			?>

			<?php 
			if($this->Session->read('Site.image_gallery')) { 
				if($this->Session->read('Site.show_products')) { 
			?>
				<li><?php echo $this->Html->link('Photo Gallery', '/ImageGallery/productsGallery', array('title'=>'Show product gallery'));?></li>
				<?php 
				} 
				elseif($this->Session->read('Site.show_landing_page')) {  
				?>
				<li><?php echo $this->Html->link('Photo Gallery', '/ImageGallery/highlights', array('title'=>'Show photo gallery'));?></li>
				<?php 
				}  
			} 
			?>
			<?php 
				$map = ($this->Session->read('Site.embed_map')) ? $this->Session->read('Site.embed_map') : '';	
				echo ($map) ? '<li>'.$this->Html->link('Route map', array('controller'=>'sites', 'action'=>'routemap'), array('escape'=>false)).'</li>' : null;
			?>
			<?php echo $this->element('footer_content_links');?>
        </ul>
      </nav>
    </section>


	
	<section>
		<h2 class="title">Site Visitors: <?php echo ($this->Session->read('SiteVisits')) ? $this->Session->read('SiteVisits') : '1';?>+</h2>
	</section>
	<?php 
	if($this->Session->read('isMobile') == false) {
		if($this->Session->read('Site.show_products')) { 
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
  