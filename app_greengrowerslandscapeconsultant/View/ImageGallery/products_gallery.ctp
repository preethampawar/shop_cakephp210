<?php
$this->set('title_for_layout', 'Products Photo Gallery');
$this->Html->meta('keywords', 'products gallery, photo gallery, all products', array('inline'=>false));
$this->Html->meta('description', 'View all products belonging to '.$this->Session->read('Site.title').' at one place. Showing products photo gallery from '.$this->Session->read('Site.title'), array('inline'=>false));
?>
<script>
$('#photogallery').attr('class', 'active');
</script>

<?php 
echo $this->Html->css('jquery.lightbox-0.5'); // jQuery Light box
echo $this->Html->script('jquery.lightbox-0.5'); // jQuery Light box	
?>
<section id="ProductInfo">
	<article>
		<header>
			<h2>Products Gallery</h2>
		</header>
		
		<div id="productImages">
			<?php
			$imageCount = 0;
			$captionDivs = '';
			$requestWindows = '';
			if(!empty($allCategories)) {
				foreach($allCategories as $categoryInfo) {
				
					if(!empty($categoryInfo['CategoryProducts'])) {
						foreach($categoryInfo['CategoryProducts'] as $row) {
							$productID = $row['Product']['id'];
							$productName = ucwords($row['Product']['name']);
							$productNameSlug = Inflector::slug($productName, '-');
							
							$categoryID = $categoryInfo['Category']['id'];
							$categoryName = ucwords($categoryInfo['Category']['name']);
							$categoryNameSlug = Inflector::slug($categoryName, '-');
							
							$categoryLink = $this->Html->link($categoryName, '/products/show/'.$categoryID.'/'.$categoryNameSlug, array('title'=>$categoryName, 'escape'=>false, 'style'=>'background-color:transparent;', 'target'=>'_blank'));
							$productLink = $this->Html->link($productName, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$categoryName.' &raquo; '.$productName, 'escape'=>false, 'style'=>'background-color:transparent;', 'target'=>'_blank'));
							
							if(!empty($row['Images'])) {										
								foreach($row['Images'] as $image) {
									//debug($image['Image']['id']); exit;
								
									$imageCount++;
									$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
									$imageCaption = (!empty($image['Image']['caption'])) ? $image['Image']['caption'] : '';
									
									$imageUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'600','width'=>'600','type'=>'auto', 'quality'=>'75', 'filename'=>$productNameSlug), array('style'=>'', 'alt'=>$productName, 'title'=>$imageCaption, 'escape'=>false), true);
									//$imageThumbUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop'), array('style'=>'', 'alt'=>$productName, 'title'=>$imageCaption, 'escape'=>false), true);	

									// $rpqButton = $this->Form->submit('Request Price Quote &raquo;', array('div'=>false, 'escape'=>false, 'style'=>'width:200px; margin-bottom:8px;', 'onclick'=>"showRequestPriceQuoteForm('$categoryID','$productID', '$productName')"));
									// $addToCartButton = $this->Form->submit('Request Price Quote &raquo;', array('div'=>false, 'escape'=>false, 'style'=>'width:200px; margin-bottom:8px;', 'onclick'=>"showAddToCartForm('$categoryID','$productID', '$productName')"));	
									
									?>
									<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
										<a href="<?php echo $imageUrl;?>" title='<?php echo $categoryLink.' &raquo; '.$productLink.'<p>'.$imageCaption.'</p>';?>'>
											<?php 
											echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'50', 'filename'=>$productNameSlug), array('style'=>'', 'alt'=>$productName, 'title'=>$imageCaption));
											?>			
										</a>
									</div>
									
									<?php																			
								}
								
							}		
						}
					}
				}
				?>			
			<?php	
			}
			?>
			<div class='clear'></div>
		</div>
        
		<?php		
		if($imageCount) {			
		?>
			<script type="text/javascript">
			$(function() {
				$('#productImages a').lightBox();
			});
			</script>
		<?php
		}
		else {
		?>
			<p>No Images Found</p>		
		<?php
		}
		?>
		<br><br>
	</article>
</section>
<div>
	<div style="float:left">
		<g:plusone annotation="bubble" size="standard"></g:plusone>					
	</div>
	<div style="float:left">
		<div class="fb-like" data-send="true" data-width="350" data-show-faces="true"></div>
	</div>
	<div style="clear:both;"></div>
</div>
