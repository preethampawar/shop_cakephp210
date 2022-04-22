<?php
echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom'); // jQuery UI
echo $this->Html->script('jquery-ui-1.8.18.custom.min', ['async' => 'async']); // jQuery UI
?>

<?php
App::uses('Product', 'Model');
$this->Product = new Product;
$allCategories = $this->Product->getSiteCategoriesProducts(['cols' => 'complete', 'productConditions' => ['Product.featured' => '1']], $this->Session->read('Site.id'));
?>
<section id="ProductsInfo">
	<article>
		<header><h2>Featured</h2></header>
		<?php
		if (!empty($allCategories)) {
			$k = 1;
			$productCount = 1;
			$adCount = 0;
			$categoriesCount = count($allCategories);
			foreach ($allCategories as $row) {
				$categoryID = $row['Category']['id'];
				$categoryName = ucwords($row['Category']['name']);
				$categoryNameSlug = Inflector::slug($categoryName, '-');

				if (!empty($row['CategoryProducts'])) {
					//debug($row);
					?>
					<?php
					foreach ($row['CategoryProducts'] as $row2) {

						$productID = $row2['Product']['id'];
						$productName = ucwords($row2['Product']['name']);
						$productNameSlug = Inflector::slug($productName, '-');

						$productTitle = $productName;
						$productLength = strlen($productTitle);
						if ($productLength > 24) {
							$productTitle = substr($productTitle, 0, 22) . '...';
						}


						$productDsc = $row2['Product']['description'];

						$descLength = strlen($productDsc);
						if ($descLength > 250) {
							$desc = substr($productDsc, 0, 250);
							$desc .= '...';
							$productDsc = $desc;
						}
						$imageID = 0;
						if (!empty($row2['Images'])) {
							$imageID = (isset($row2['Images'][0]['Image']['id'])) ? $row2['Images'][0]['Image']['id'] : 0;
						}

						//calculate height of product container div
						$height = '205px';
						if ($this->Session->read('Site.request_price_quote')) {
							$height = '270px';
						}
						?>
						<div
							style="float:left; border:1px solid #eee; margin:0 7px 20px 0; padding:0px;  width:200px; height:<?php echo $height; ?>;">
							<div style="padding:5px 0px;">
								<div style="text-align:center;">
									<strong><?php
										//echo $productTitle;
										echo $this->Html->link($productTitle, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, ['title' => $productName, 'escape' => false]); ?></strong>
									<p>
										<?php
										$productImage = $this->Img->showImage('img/images/' . $imageID, ['height' => '150', 'width' => '150', 'type' => 'crop', 'quality' => '85', 'filename' => $productNameSlug], ['style' => '', 'height' => '150', 'width' => '150', 'alt' => $productName, 'id' => 'image' . $categoryID . '-' . $imageID]);
										echo $this->Html->link($productImage, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, ['title' => $productName, 'escape' => false]);
										?>
									</p>
								</div>
								<?php
								if ($this->Session->read('Site.request_price_quote')) { ?>
									<div style="text-align:center;">
										<?php echo $this->Form->submit('Request Price Quote', ['div' => false, 'escape' => false, 'style' => 'width:180px; margin-bottom:8px;', 'onclick' => "showRequestPriceQuoteForm('$categoryID','$productID', '$productTitle')"]); ?>

										<?php echo $this->Form->submit('+ Add To My Shopping List', ['div' => false, 'escape' => false, 'style' => 'width:180px;', 'onclick' => "showAddToCartForm('$categoryID','$productID', '$productTitle')"]); ?>
									</div>
									<?php
								}
								?>
							</div>
						</div>
						<?php
						if ($productCount == 3) {
							if ($adCount < 3) {
								$adCount++;
								?>
								<div
									style="float:left; border:1px solid #eee; margin:0 7px 20px 0; padding:0px;  width:200px; height:<?php echo $height; ?>; overflow:hidden;">

									<script async
											src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
									<!-- featured_products_main_page_200x270 -->
									<ins class="adsbygoogle"
										 style="display:inline-block;width:200px;height:260px"
										 data-ad-client="ca-pub-1985514378863670"
										 data-ad-slot="9934216819"></ins>
									<script>
										(adsbygoogle = window.adsbygoogle || []).push({});
									</script>
								</div>
								<?php
							}
							$productCount = 0;
						}

						$productCount++;

					}
					?>

					<?php
				}
				$k++;
			}
			?>
			<?php
		} else {
			?>
			<p>No Products Found</p>
			<?php
		}
		?>
		<div class='clear'></div>

	</article>
</section>
