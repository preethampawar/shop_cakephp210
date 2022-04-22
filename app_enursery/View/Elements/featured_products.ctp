<?php
App::uses('Product', 'Model');
$this->Product = new Product;
$allCategories = $this->Product->getSiteCategoriesProductsImages(array('cols'=>'complete', 'productConditions'=>array('Product.featured'=>'1')));		

$productsCount = 0;
if(!empty($allCategories)) {
	foreach($allCategories as $row) {
		if(!empty($row['CategoryProducts'])) {
			$productsCount = $productsCount+(count($row['CategoryProducts']));	
		}
	}
}
?>
<section id="ProductsInfo">
	<article>
		<header class="featuredLabel">
			<b>Featured Products</b> | 
			
			<?php echo $this->Html->link('Show All Products', '/products/showAll', array('style'=>'text-decoration: underline;'));?>
		
		</header>	
		<hr>
		<?php
		if(!empty($allCategories)) {
			$k=1;
			$pCount = 0;
			$categoriesCount = count($allCategories);			
			foreach($allCategories as $row) {	
				$categoryID = $row['Category']['id'];
				$categoryName = ucwords($row['Category']['name']);
				$categoryNameSlug = Inflector::slug($categoryName, '-');
								
				if(!empty($row['CategoryProducts'])) {
				//debug($row);
				?>
					<?php
					foreach($row['CategoryProducts'] as $row2) {
						$pCount++;
						$productID = $row2['Product']['id'];
						$productName = ucwords($row2['Product']['name']);
						$productNameSlug = Inflector::slug($productName, '-');
						$showRequestPriceQuote = $row2['Product']['request_price_quote'];

						
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
									<div class="fitText">
										<strong><?php 
										//echo $productTitle; 
										echo $this->Html->link($productTitle, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$productName, 'escape'=>false));?></strong>
									</div>
									<p>
										<?php 										
										$productImageUrl = $this->Html->url($this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'85', 'filename'=>$productNameSlug), array('style'=>'', 'height'=>'150','width'=>'150', 'alt'=>$productName, 'id'=>'image'.$categoryID.'-'.$imageID), true), true);
										$imageTagId = 'image'.$categoryID.'-'.$imageID;
										$image = '<img 
												src = "/img/plants.gif" 
												data-original="'.$productImageUrl.'" 
												height="150" 
												width="150" 
												class="lazy" 
												alt="'.$productName.'"
												id="'.$imageTagId.'" 
											/>';
										
										echo $this->Html->link($image, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$productName, 'escape'=>false, 'id'=>'imagelink'.$productID));
										?>										
									</p>
								</div>
								<?php 
								if($this->Session->read('Site.request_price_quote') and $showRequestPriceQuote) { ?>
									<div style="text-align:center;">
										<?php echo $this->Form->submit('Request Price Quote', array('div'=>false, 'escape'=>false, 'style'=>'width:150px; margin-bottom:5px;', 'onclick'=>"showRequestPriceQuoteForm('$categoryID','$productID', '$productTitle')"));?>
										
										<?php echo $this->Form->submit('+ Add To Cart', array('div'=>false, 'escape'=>false, 'style'=>'width:150px;', 'onclick'=>"showAddToCartForm('$categoryID','$productID', '$productTitle')"));?>
									</div>	
								<?php 
								} 
								?>	
							</div>
						</div>
						<?php
						if($this->Session->read('isMobile')) {
							if($pCount == 9) {
								break;
							}
						}
					}
				}
				$k++;
				
				if($this->Session->read('isMobile')) {
					if($pCount == 9) {
						break;
					}
				}
			}
			?>
		<?php
		}
		else {
		?>			
			<p>No Products Found</p>
		<?php
		}
		?>
		<div class='clear'></div>
	</article>	
</section>	