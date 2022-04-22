<section id="ProductsInfo">
	<?= $this->element('homepage_tabmenu', ['featuredPage' => true]) ?>

	<?php
	if (!empty($allProducts)) {
		$k = 1;
		$categoriesCount = count($allProducts);

		foreach ($allProducts as $row) {
			$categoryID = $row['Category']['id'];
			$categoryName = $row['Category']['name'];
			$categoryNameSlug = Inflector::slug($categoryName, '-');
			?>
			<article class="mt-4">
				<header>
					<h5><?php echo $categoryName; ?></h5>
					<hr>
				</header>
				<?php
				$showOneProductOnSmallScreen = Configure::read('ShowOneProductOnSmallScreen') ?? false;
				$productsRowClass = "row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 g-lg-x-4 p-0";
				if ($showOneProductOnSmallScreen) {
					$productsRowClass = "row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 g-lg-x-4 p-0";
				}

				if (!empty($row['CategoryProducts'])) {
					?>
					<div class="<?= $productsRowClass ?>">
						<?php
						$z = 0;
						foreach ($row['CategoryProducts'] as $row2) {
							$productID = $row2['Product']['id'];
							$productName = $row2['Product']['name'];
							$productShortDesc = $row2['Product']['short_desc'];
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
							$avgRating = $row2['Product']['avg_rating'];
							$ratingsCount = $row2['Product']['ratings_count'];

							echo $this->element('product_card', [
									'productImageUrl' => $productImageUrl,
									'productName' => $productName,
									'productShortDesc' => $productShortDesc,
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
									'avgRating' => $avgRating,
									'ratingsCount' => $ratingsCount,
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
