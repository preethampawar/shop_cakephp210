<?php
$this->set('enableLightbox', false);

$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$categoryID = $categoryInfo['Category']['id'];
$categoryName = ucwords($categoryInfo['Category']['name']);
$categoryNameSlug = Inflector::slug($categoryName, '-');

$productID = $productInfo['Product']['id'];
$productName = ucwords($productInfo['Product']['name']);
$productNameSlug = Inflector::slug($productName, '-');
$productDesc = $productInfo['Product']['description'];
$showRequestPriceQuote = $productInfo['Product']['request_price_quote'];

$assetDomainUrl = Configure::read('AssetDomainUrl');
$productUploadedImages = $productInfo['Product']['images'] ? json_decode($productInfo['Product']['images']) : [];
$imageDetails = $this->App->getRearrangedImages($productUploadedImages);
$mrp = $productInfo['Product']['mrp'];
$discount = $productInfo['Product']['discount'];
$salePrice = $mrp - $discount;
$showDiscount = $mrp !== $salePrice;
$noStock = $productInfo['Product']['no_stock'];
$cartEnabled = $this->Session->read('Site.shopping_cart');
$hideProductPrice = $productInfo['Product']['hide_price'];
?>

<div itemscope itemtype="http://schema.org/Product">
	<?php
	if (!$isAjax) {
		?>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a
						href="/products/show/<?php echo $categoryID; ?>"><?= $categoryName; ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $productName ?></li>
			</ol>
		</nav>

		<h2 class="mb-3"><?= $productName; ?></h2>
		<?php
	}
	?>
	<?php
	$imageUrl = null;
	if (!empty($imageDetails)) {
		$this->set('enableLightbox', true);
		?>
		<div id="productImages" class="product-details-page-slider">
			<?php
			$higlightImage = '';
			$k = 0;
			foreach ($imageDetails as $row) {
				$k++;
				$imageID = random_int(1, 10000);
				$imageCaption = ($row['ori']->caption) ? $row['ori']->caption : $productName;
				$imageUrl = $assetDomainUrl . $row['ori']->imagePath;
				$imageThumbUrl = $assetDomainUrl . $row['thumb']->imagePath;
				?>
				<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
					<a href="<?php echo $imageUrl; ?>" title='<?php echo $imageCaption; ?>'
					   data-lightbox="productImages<?php echo $productID; ?>">
						<img itemprop="image" src="<?php echo $imageThumbUrl; ?>" alt="<?php echo $productName; ?>"
							 width='150' height='150' loading="lazy"/>
					</a>
				</div>
				<?php
			}
			?>
			<div style="clear: both"></div>
		</div>
		<?php
	}
	?>

	<div id="productDetails">
		<section>
			<article>
				<?php if (!$hideProductPrice): ?>

					<div class="mt-2 bg-light p-2 rounded">
						<h5><?= $productName; ?></h5>
						<div class="d-flex mt-3">
							<h4>
								<span
									class="text-danger font-weight-bold"><?php echo $this->App->price($salePrice); ?></span>
							</h4>
							<?php if ($showDiscount): ?>
								<div class="ml-3">
									<span
										class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
								</div>
							<?php endif; ?>
						</div>

						<?php if ($showDiscount): ?>
							<div class="small text-left">
								Save - <?php echo $this->App->priceOfferInfo($salePrice, $mrp); ?>
							</div>
						<?php endif; ?>

						<?php if ($cartEnabled && !$noStock): ?>
							<form id="AddToCart<?php echo $productID; ?>"
								  action="/shopping_carts/add/<?php echo $categoryID; ?>/<?php echo $productID; ?>"
								  method="post" class="flex">
								<div class="row mt-3">
									<div class="col">
										<label for="ShoppingCartProductQuantity<?php echo $productID; ?>"
											   class="small">Select Quantity</label>
										<select
											name="data[ShoppingCartProduct][quantity]"
											id="ShoppingCartProductQuantity<?php echo $productID; ?>"
											class="form-control form-control-sm"
											style="margin-top: 1px;"
										>
											<?php echo $selectBoxQuantityOptions; ?>
										</select>
									</div>
									<div class="col">
										<button type="submit" class="btn btn-sm btn-primary active mt-4">Add To Cart
										</button>
									</div>
								</div>
							</form>
						<?php elseif ($cartEnabled && $noStock): ?>
							<div class="row mt-3">
								<div class="col">
									<button type="button" class="btn btn-sm btn-outline-secondary disabled">Out of
										stock
									</button>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php
				if (!empty($productDesc)) {
					?>
					<div class="mt-3">
						<div itemprop="description" class="overflow-auto mt-2">
							<?php echo $productDesc; ?>
						</div>
					</div>
					<?php
				}
				?>
			</article>
		</section>
	</div>

	<div class="mt-4">
		<?php
		if (!empty($this->Session->read('Site.contact_info'))):
			?>
			<div class="text-center small alert alert-info">
				<h4 class="mb-3 text-decoration-underline">Contact</h4>
				<?= $this->Session->read('Site.contact_info') ?>
			</div>
		<?php
		endif;
		?>

		<?php
		if (!empty($this->Session->read('Site.payment_info'))):
			?>

			<div class="text-center small alert alert-info">
				<h4 class="mb-3 text-decoration-underline">Payment Details</h4>
				<?= $this->Session->read('Site.payment_info') ?>
			</div>
		<?php
		endif;
		?>

		<?php
		if (!empty($this->Session->read('Site.tos'))):
			?>
			<div class="text-center small alert alert-warning">
				Please read our <a href="/sites/tos">Terms of Service</a> before you place an order with us.
			</div>
		<?php
		endif;
		?>
	</div>








