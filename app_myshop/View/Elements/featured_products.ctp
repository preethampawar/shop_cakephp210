<?php
App::uses('Product', 'Model');
$productModel = new Product();
$allCategories = $productModel->getAllProducts($this->Session->read('Site.id'), true);
?>

<section id="ProductsInfo">
	<article>
		<header class="featuredLabel">
			<b>
			<?php echo $this->Html->link('Best Deals', '/', ['class' => 'active text-decoration-none']); ?> |
			</b>
			<?php echo $this->Html->link('Show All Products', '/products/showAll', ['class' => 'text-decoration-none']); ?>

		</header>
		<hr>
		<?php
		if (!empty($allCategories)) {
			$pCount = 0;
			$categoriesCount = count($allCategories);
			$assetDomainUrl = Configure::read('AssetDomainUrl');
			?>
			<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 g-lg-x-4 p-0">
				<?php
				foreach ($allCategories as $row) {
					$categoryID = $row['Category']['id'];
					$categoryName = ucwords($row['Category']['name']);
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$pCount++;
					$productID = $row['Product']['id'];
					$productName = ucwords($row['Product']['name']);
					$productNameSlug = Inflector::slug($productName, '-');
					$productTitle = $productName;


					$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
					$imageDetails = $this->App->getHighlightImage($productUploadedImages);
					$thumbUrl = "/img/noimage.jpg";
					$imageTagId = random_int(1, 10000);

					if($imageDetails) {
						$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
					}

					$productImageUrl = $thumbUrl;
					$mrp = $row['Product']['mrp'];
					$discount = $row['Product']['discount'];
					$salePrice = $mrp - $discount;
					$noStock = $row['Product']['no_stock'];
					$cartEnabled = $this->Session->read('Site.shopping_cart');
					$hideProductPrice = $row['Product']['hide_price'];

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


//					if (!empty($row['CategoryProducts'])) {
//						foreach ($row['CategoryProducts'] as $row2) {
//							$pCount++;
//							$productID = $row2['Product']['id'];
//							$productName = ucwords($row2['Product']['name']);
//							$productNameSlug = Inflector::slug($productName, '-');
//							$productTitle = $productName;
//
//							$assetDomainUrl = Configure::read('AssetDomainUrl');
//							$productUploadedImages = $row2['Product']['images'] ? json_decode($row2['Product']['images']) : [];
//							$imageDetails = $this->App->getHighlightImage($productUploadedImages);
//							$thumbUrl = "/img/noimage.jpg";
//							$imageTagId = random_int(1, 10000);
//
//							if($imageDetails) {
//								$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
//							}
//
//							$productImageUrl = $thumbUrl;
//							$mrp = $row2['Product']['mrp'];
//							$discount = $row2['Product']['discount'];
//							$salePrice = $mrp - $discount;
//
//							echo $this->element('product_card', [
//									'productImageUrl' => $productImageUrl,
//									'productName' => $productName,
//									'imageTagId' => $imageTagId,
//									'productTitle' => $productTitle,
//									'categoryID' => $categoryID,
//									'productID' => $productID,
//									'categoryNameSlug' => $categoryNameSlug,
//									'productNameSlug' => $productNameSlug,
//									'mrp' => $mrp,
//									'discount' => $discount,
//									'salePrice' => $salePrice,
//								]
//							);
//						}
//					}
				}
				?>
			</div>
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
