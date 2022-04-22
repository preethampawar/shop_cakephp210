<?php
$title = '';
if ($filter['type'] === 'price') {
	if ($filter['startValue'] == 0 && $filter['endValue'] > 0) {
		$title = 'Below ';
		$title .= $this->App->price($filter['endValue']);
	}
	if ($filter['startValue'] > 0 && $filter['endValue'] == 0) {
		$title = 'Above ';
		$title .= $this->App->price($filter['startValue']);
	}
	if ($filter['startValue'] > 0 && $filter['endValue'] > 0) {
		$title = $this->App->price($filter['startValue']);
		$title .= ' - ';
		$title .= $this->App->price($filter['endValue']);
	}
	if ($filter['startValue'] == 0 && $filter['endValue'] == 0) {
		$title = 'All Products ';
	}
}

$alertClass = 'alert alert-secondary bg-light';
switch ($filter['startValue']) {
	case 0:		
		$alertClass = $filter['endValue'] > 0 ? 'alert alert-warning' : 'alert alert-secondary';
		break;
	case 99:
		$alertClass = 'alert alert-info';
		break;
	case 199:
		$alertClass = 'alert alert-success';
		break;
	case 299:
		$alertClass = 'alert alert-primary';
		break;
	case 399:
		$alertClass = 'alert alert-info';
		break;
}
?>

<?php
$this->set('title_for_layout', $title);
?>

<?= $this->element('homepage_tabmenu', ['homepage' => null]) ?>

<section id="ProductInfo">
	<article>
		<header>
			<div class="<?= $alertClass ?> p-2 mt-2 shadow-sm" role="button">
				<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/0/99/asc">
					<?= $title ?> (<?= count($products) ?> items)
				</a>
			</div>
		</header>
		<?php
		if (!empty($products)) {
		?>
			<div class="row g-3 g-lg-x-4 p-0">
				<?php
				foreach ($products as $row2) {
					$categoryID = $row2['Category']['id'];
					$categoryName = ucwords($row2['Category']['name']);
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$productID = $row2['Product']['id'];
					$productName = ucwords($row2['Product']['name']);
					$productShortDesc = $row2['Product']['short_desc'];
					$productNameSlug = Inflector::slug($productName, '-');
					$productTitle = $productName;
					$assetDomainUrl = Configure::read('AssetDomainUrl');
					$productUploadedImages = $row2['Product']['images'] ? json_decode($row2['Product']['images']) : [];
					$imageDetails = $this->App->getHighlightImage($productUploadedImages);
					$thumbUrl = "/img/noimage.jpg";
					$imageTagId = random_int(1, 10000);

					if ($imageDetails) {
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
		}
		?>
	</article>
</section>
<br>