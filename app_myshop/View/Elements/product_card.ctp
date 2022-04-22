<?php
$this->set('loadVueJs', false);
$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$showDiscount = $mrp != $salePrice;
$assetDomainUrl = Configure::read('AssetDomainUrl');
$loadingImageUrl = '/loading4.jpg';
$productSlug = Inflector::slug($productTitle, '-');
$productDetailsPageUrl = '/products/getDetails/' . $categoryID . '/' . $productID . '/' . $productSlug;

$avgRating = $avgRating ?? 0;
$ratingsCount = $ratingsCount ?? 0;
$productShortDesc = $productShortDesc ?? '';

$deliveryCharges = (float)$this->Session->read('Site.shipping_charges');
$minOrderForFreeShipping = (float)$this->Session->read('Site.free_shipping_min_amount');
?>

<div class="col mb-3 bg-white hoverHighlightPink" id="productCard<?php echo $categoryID . '-' . $productID; ?>">
	<div class="card h-100 shadow p-0 mb-1 text-dark border-0" id="productCard<?php echo $productID; ?>">
		<?php if ($showDiscount): ?>
		<div class="position-relative">
			<div class="position-absolute top-0 start-0 small">
				<span class="small bg-orange p-1 fw-bold border border-start-0 border-top-0 border-white"><?php echo $this->App->priceOfferInfo($salePrice, $mrp, 'amount'); ?> OFF</span>
			</div>
		</div>
		<?php endif; ?>

		<a href="<?= $productDetailsPageUrl ?>" class="text-decoration-underline">
			<img
				src="<?php echo $loadingImageUrl; ?>"
				data-original="<?php echo $productImageUrl; ?>"
				class="lazy w-100 img-fluid card-img-top"
				role="button"
				alt="<?php echo $productName; ?>"
				id="<?php echo $imageTagId; ?>"
				width="200"
				height="200"
			/>
		</a>

		<div class="card-body p-2 pt-0 text-center">
			<a href="<?= $productDetailsPageUrl ?>" class="text-purple text-decoration-none">
				<h6 class="mt-3 small"><?php echo $productTitle; ?></h6>
			</a>

			<div class="mt-2 small text-center d-flex justify-content-center">
				<?= $this->element('show_rating_stars', ['rating' => $avgRating, 'count' => $ratingsCount]) ?>
			</div>

			<?php if (!$hideProductPrice): ?>
				<div class="mt-1 d-flex justify-content-between">
					<h5>
						<span class="text-danger"><?php echo $this->App->price($salePrice); ?></span>
					</h5>
					<?php if ($showDiscount): ?>
						<div class="ms-3 small mt-1">
							<span class="text-muted text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<?php if ($showDiscount): ?>
					<div class="small fw-bold text-center">
						<span class="text-success">Save <?php echo $this->App->priceOfferInfo($salePrice, $mrp); ?></span>
					</div>
				<?php endif; ?>

				<?php if ($deliveryCharges == 0 && $minOrderForFreeShipping == 0): ?>
					<div class="small text-center">
						<span class="text-orange small">+ Free Delivery</span>
					</div>
				<?php endif; ?>

				<?php
				if (trim($productShortDesc)) {
					?>
					<div class="x-small text-orange mt-2"><?= trim($productShortDesc) ?></div>
					<?php
				}
				?>
			<?php endif; ?>
		</div>


		<?php if (!$hideProductPrice && $cartEnabled): ?>
			<div class="card-footer text-center border-top-0 pt-3 pb-3">
				<div class="card-text">
					<?php if (!$noStock): ?>
						<div class="text-center p-0">
							<button type="button" class="btn btn-sm btn-primary" onclick="showAddProductQtyModal('<?= $categoryID ?>', '<?= $productID ?>')">
								Add to cart
							</button>
						</div>
					<?php else: ?>
						<button type="button" class="btn btn-sm btn-outline-secondary disabled">Out of stock</button>
					<?php endif; ?>

					<?= $this->element('sharebutton', [
							'title' => $productName,
							'text' => '',
							'url' => $this->Html->url($productDetailsPageUrl, true),
							'files' => '[]',
							'class' => 'mt-3',
							]); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

