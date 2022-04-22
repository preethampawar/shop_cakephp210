<?php
$limit = $limit ?? 0;
$homepage = $homepage ?? null;

App::uses('Product', 'Model');
$productModel = new Product();
$allCategories = $productModel->getAllProducts($this->Session->read('Site.id'), true, $limit);
?>

<section id="ProductsInfo">
	<article>
		<?= $this->element('homepage_tabmenu', ['homepage' => $homepage]) ?>

		<header>
			<div class="alert alert-danger p-2 mt-2 shadow-sm" role="button">
				<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/0/99/asc">
					<div class="d-inline-block" style="width: 20px;"><i class="bi bi-stars text-orange"></i></div> Hot Deals <?= !$homepage ? '(' . (count($allCategories)) . ') items' : '' ?>
				</a>
			</div>
		</header>

		<?php
		if (!empty($allCategories)) {
			$pCount = 0;
			$categoriesCount = count($allCategories);
			$assetDomainUrl = Configure::read('AssetDomainUrl');

			$showOneProductOnSmallScreen = Configure::read('ShowOneProductOnSmallScreen') ?? false;
			$productsRowClass = "row g-3 g-lg-x-4 p-0";
			if ($showOneProductOnSmallScreen) {
				$productsRowClass = "row g-3 g-lg-x-4 p-0";
			}
		?>
			<div class="row g-3 g-lg-x-4 p-0">
				<?php
				foreach ($allCategories as $row) {
					$categoryID = $row['Category']['id'];
					$categoryName = $row['Category']['name'];
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$pCount++;
					$productID = $row['Product']['id'];
					$productName = $row['Product']['name'];
					$productShortDesc = $row['Product']['short_desc'];
					$productNameSlug = Inflector::slug($productName, '-');
					$productTitle = $productName;


					$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
					$imageDetails = $this->App->getHighlightImage($productUploadedImages);
					$thumbUrl = "/img/noimage.jpg";
					$imageTagId = random_int(1, 10000);

					if ($imageDetails) {
						$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
					}

					$productImageUrl = $thumbUrl;
					$mrp = $row['Product']['mrp'];
					$discount = $row['Product']['discount'];
					$salePrice = $mrp - $discount;
					$noStock = $row['Product']['no_stock'];
					$cartEnabled = $this->Session->read('Site.shopping_cart');
					$hideProductPrice = $row['Product']['hide_price'];
					$avgRating = $row['Product']['avg_rating'];
					$ratingsCount = $row['Product']['ratings_count'];
				?>

					<div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3" id="productCard<?php echo $categoryID . '-' . $productID; ?>">
						<?php
						echo $this->element(
							'product_card',
							[
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
						?>
					</div>
				<?php
				}
				?>
			</div>

			<?php
			if ($homepage) {
			?>
				<div class="mt-5 mb-5 text-center" id="showMoreDeals">
					<a href="/products/showFeatured" class="btn btn-orange btn-sm">Show more deals...</a>
				</div>
				<hr>
			<?php
			}
			?>

		<?php
		} else {
		?>
			<p>No Products Found</p>
		<?php
		}
		?>
	</article>
</section>