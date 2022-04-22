<section id="ProductsInfo">
	<header class="featuredLabel">
		<?php echo $this->Html->link('Best Deals', '/', ['class' => 'active text-decoration-none']); ?> |
		<b>
		<?php echo $this->Html->link('Show All Products', '/products/showAll', ['class' => 'text-decoration-none']); ?>
		</b>
	</header>
	<hr>
	<h6 class="mb-4">Showing all products by category</h6>
	<?php
	if (!empty($allProducts)) {
		$k = 1;
		$categoriesCount = count($allProducts);

		foreach ($allProducts as $row) {
			$categoryID = $row['Category']['id'];
			$categoryName = ucwords($row['Category']['name']);
			$categoryNameSlug = Inflector::slug($categoryName, '-');
			?>
			<article class="mb-4">
				<header>
					<h2><?php echo $categoryName; ?></h2>
					<hr>
				</header>
				<?php
				if (!empty($row['CategoryProducts'])) {
					?>
					<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 g-lg-x-4 p-0">
						<?php
						$z = 0;
						foreach ($row['CategoryProducts'] as $row2) {
							$productID = $row2['Product']['id'];
							$productName = ucwords($row2['Product']['name']);
							$productNameSlug = Inflector::slug($productName, '-');
							$productTitle = $productName;
							$assetDomainUrl = Configure::read('AssetDomainUrl');
							$productUploadedImages = $row2['Product']['images'] ? json_decode($row2['Product']['images']) : [];
							$imageDetails = $this->App->getHighlightImage($productUploadedImages);
							$thumbUrl = "/img/noimage.jpg";
							$imageTagId = random_int(1, 10000);

							if($imageDetails) {
								$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
							}

							$productImageUrl = $thumbUrl;
							$mrp = $row2['Product']['mrp'];
							$discount = $row2['Product']['discount'];
							$salePrice = $mrp - $discount;
							$noStock = $row2['Product']['no_stock'];
							$cartEnabled = $this->Session->read('Site.shopping_cart');
							$hideProductPrice = $row2['Product']['hide_price'];

							echo $this->element('product_card', [
									'productImageUrl' => $productImageUrl,
									'productName' => $productName,
									'imageTagId' => $imageTagId,
									'productTitle' => $productTitle,
									'categoryID' => $categoryID,
									'productID' => $productID,
									'categoryNameSlug' => $categoryNameSlug,
									'productNameSlug' => $productNameSlug,
									'mrp' => $mrp,
									'discount' => $discount,
									'salePrice' => $salePrice,
									'cartEnabled' => $cartEnabled,
									'noStock' => $noStock,
									'hideProductPrice' => $hideProductPrice,
								]
							);
						}
						?>
					</div>

					<?php
				} else {
					?>
					<p>No products found</p>
					<?php
				}
				?>
			</article>
			<?php
			$k++;
		}
	} else {
		?>
		<p>No Products Found</p>
		<?php
	}
	?>
</section>
<br>
