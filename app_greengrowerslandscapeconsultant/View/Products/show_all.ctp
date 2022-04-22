<?php echo $this->set('title_for_layout', 'Products Catalog');?>

<?php 
// echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom'); // jQuery UI 
// echo $this->Html->script('jquery-ui-1.8.18.custom.min', array('async'=>'async')); // jQuery UI	
?>

<section id="ProductsInfo">
	
		<?php
		$categoriesList = array();
		if(!empty($allCategories)) {
			$k=1;
			$categoriesCount = count($allCategories);
			foreach($allCategories as $row) {	
				$categoryID = $row['Category']['id'];
				$categoryName = ucwords($row['Category']['name']);
				$categoryNameSlug = Inflector::slug($categoryName, '-');
				if($k < 10) {
					$categoriesList[] = $categoryName;
				}
				?>
				<article>
					<header>
						<h2><?php echo $categoryName;?></h2>
					</header>	
						<?php				
						if(!empty($row['CategoryProducts'])) {
						?>
							<div class="productsContainer">
								<?php
								$z =0;
								foreach($row['CategoryProducts'] as $row2) {
									$productID = $row2['Product']['id'];
									$productName = ucwords($row2['Product']['name']);
									$productNameSlug = Inflector::slug($productName, '-');
									
									
									$productTitle = $productName;						
									$productLength = strlen($productTitle);
									if($productLength > 24) {
										$productTitle = substr($productTitle, 0, 22).'...';	
									}
									
									$productDsc = $row2['Product']['description'];
									
									$descLength = strlen($productDsc);
									if($descLength > 250) {
										$desc = substr($productDsc, 0, 250);
										$desc.='...';
										$productDsc = $desc;							
									}
									$imageID = 0;
									if(!empty($row2['Images'])) {
										$imageID = (isset($row2['Images'][0]['Image']['id'])) ? $row2['Images'][0]['Image']['id'] : 0;
									}			

									//calculate height of product container div
									$height = '205px';
									if($this->Session->read('Site.request_price_quote')) {
										$height = '265px';
									}						
									?>
									
									<div style="height:<?php echo $height;?>;" class="productBox">
										<div style="padding:5px 0px;">
											<div style="text-align:center;">
												<strong><?php 
												//echo $productTitle; 
												echo $this->Html->link($productTitle, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$productName, 'escape'=>false));?></strong>
												<p>
												<?php 
													$productImage = $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'75', 'filename'=>$productNameSlug), array('style'=>'', 'height'=>'150','width'=>'150', 'alt'=>$productName, 'id'=>'image'.$categoryID.'-'.$imageID));
													echo $this->Html->link($productImage, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$productName, 'escape'=>false));
												?>
												</p>
											</div>
											<?php 
											if($this->Session->read('Site.request_price_quote')) { ?>
												<div style="text-align:center;">
													<?php echo $this->Form->submit('Request Price Quote', array('div'=>false, 'escape'=>false, 'style'=>'width:175px; margin-bottom:5px;', 'onclick'=>"showRequestPriceQuoteForm('$categoryID','$productID', '$productTitle')"));?>
													
													<?php echo $this->Form->submit('+ Add To My Shopping List', array('div'=>false, 'escape'=>false, 'style'=>'width:175px;', 'onclick'=>"showAddToCartForm('$categoryID','$productID', '$productTitle')"));?>
												</div>										
											<?php 
											} 
											?>	
										</div>
									</div>								
								<?php								
								}
								?>
								
								<div class='clear'></div>
								<?php 
								if($categoriesCount != $k) { 
								?> 
									<div style="border-bottom:1px dotted #666;">&nbsp;</div><br><br>								
									<?php 									 
								}
								?>
							</div>
							
						<?php
						}
						else {
						?>
							<p> - No Products Found</p>
							<div style="border-bottom:1px dotted #666;">&nbsp;</div><br><br>
						<?php
						}
						?>			
				</article>
				<?php
				$k++;
			}
		}
		else {
		?>			
			<p>No Products Found</p>
		<?php
		}
		?>
</section>	
<br><br>
<div>
	<div style="float:left">
		<g:plusone annotation="bubble" size="standard"></g:plusone>					
	</div>
	<div style="float:left">
		<div class="fb-like" data-send="true" data-width="350" data-show-faces="true"></div>
	</div>
	<div style="clear:both;"></div>
</div>
<br><br>

<?php
if(!empty($categoriesList)) {
	$categoriesList = implode(',', $categoriesList);
	$this->Html->meta('keywords', $categoriesList, array('inline'=>false));
}
else {
	$this->Html->meta('keywords', 'Products available with '.$this->Session->read('Site.title'), array('inline'=>false));	
}
?>
<?php $this->Html->meta('description', 'Showing all products belonging to '.$this->Session->read('Site.title').'. Contact us to purchase products from '.$this->Session->read('Site.title').'.', array('inline'=>false)); ?>
