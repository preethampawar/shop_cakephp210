<?php
$this->set('loadVueJs', false);

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


<div class="card h-100 shadow p-0 mb-1 text-dark border-0 w-100" id="productCard<?php echo $productID; ?>">
	<?php if ($showDiscount) : ?>
		<div class="position-relative">
			<div class="position-absolute top-0 start-0 small">
				<span class="small bg-orange p-1 fw-bold border border-start-0 border-top-0 border-white"><?php echo $this->App->priceOfferInfo($salePrice, $mrp, 'amount'); ?> OFF</span>
			</div>
		</div>
	<?php endif; ?>

	<a href="<?= $productDetailsPageUrl ?>" class="text-decoration-underline">
		<img src="<?php echo $loadingImageUrl; ?>" data-original="<?php echo $productImageUrl; ?>" class="lazy w-100 img-fluid card-img-top" role="button" alt="<?php echo $productName; ?>" id="<?php echo $imageTagId; ?>" width="200" height="200" />
	</a>

	<div class="card-body p-2 pt-3 text-left">
		<a href="<?= $productDetailsPageUrl ?>" class="text-dark text-decoration-none small">
			<span class="" style="font-size:0.9em"><?php echo $productTitle; ?></span>
		</a>

		<div class="mt-2 mb-3 small">
			<?= $this->element('show_rating_stars', ['rating' => $avgRating, 'count' => $ratingsCount]) ?>
		</div>

		<?php if (!$hideProductPrice) : ?>
			<div class="mt-1 d-flex justify-content-between">
				<h6>
					<span class="text-danger"><?php echo $this->App->price($salePrice); ?></span>
				</h6>
				<?php if ($showDiscount) : ?>
					<div class="small">
						<span class="text-muted text-decoration-line-through small">MRP <?php echo $this->App->price($mrp); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<?php if ($showDiscount) : ?>
				<div class="small text-center">
					<span class="text-success">Save <?php echo $this->App->priceOfferInfo($salePrice, $mrp); ?></span>
				</div>
			<?php endif; ?>

			<?php if ($deliveryCharges == 0 && $minOrderForFreeShipping == 0) : ?>
				<div class="small text-center">
					<span class="text-orange small">+ Free Delivery</span>
				</div>
			<?php endif; ?>

			<?php
			if (trim($productShortDesc)) {
			?>
				<div class="x-small text-orange mt-2 text-center"><?= trim($productShortDesc) ?></div>
			<?php
			}
			?>
		<?php endif; ?>

		<?php if (!$hideProductPrice && $cartEnabled) : ?>
			<div class="mt-5">
				<div class="position-absolute bottom-0 end-0 w-100 p-2 py-3 mt-2">
					<?php if (!$noStock) : ?>
						<div class="text-center p-0 mt-3">
							<?= $this->element('add_to_cart_button', ['categoryID' => $categoryID, 'productID' => $productID]) ?>
						</div>
					<?php else : ?>
						<button type="button" class="btn btn-sm btn-outline-secondary disabled">Out of stock</button>
					<?php endif; ?>

					<?php
					/* 
							echo $this->element('sharebutton', [
							'title' => $productName,
							'text' => '',
							'url' => $this->Html->url($productDetailsPageUrl, true),
							'files' => '[]',
							'class' => 'mt-3',
						]); 
						*/
					?>
				</div>
			</div>

		<?php endif; ?>
	</div>
</div>