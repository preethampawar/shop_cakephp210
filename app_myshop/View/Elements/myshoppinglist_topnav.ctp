<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

App::uses('Product', 'Model');
$productModel = new Product;
$siteId = $this->Session->read('Site.id');
$products = $productModel->getAllProducts($siteId, null, null, $filter = ['type' => 'show_in_cart']);

$showInCartProducts = [];
if ($products) {
	// remove same products in multiple categories
	foreach ($products as $row) {
		$showInCartProducts[$row['Product']['id']] = $row;
	}
}
unset($products);
unset($productModel);
unset($shoppingCartModel);

$inCartProductIds = [];
$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$totalItems = 0;
if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
		$totalItems += $row['quantity'];
	}
?>

	<div class="bg-white">

		<?php
		$i = 0;
		$cartValue = 0;
		$cartMrpValue = 0;
		$totalDiscount = 0;
		$deliveryCharges = (float)$this->Session->read('Site.shipping_charges');
		$minOrderForFreeShipping = (float)$this->Session->read('Site.free_shipping_min_amount');


		foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
			$i++;
			$shoppingCartProductID = $row['id'];
			$categoryID = $row['category_id'];
			$categoryName = $row['category_name'];
			$categoryNameSlug = Inflector::slug($categoryName, '-');

			$productID = $row['product_id'];
			$productName = $row['product_name'];
			$productNameSlug = Inflector::slug($productName, '-');
			$qty = $row['quantity'] ?: 0;
			$mrp = $row['mrp'];
			$discount = $row['discount'];
			$salePrice = $mrp - $discount;
			$showDiscount = $mrp != $salePrice;
			$totalProductPurchaseValue = $salePrice * $qty;
			$cartValue += $totalProductPurchaseValue;

			$assetDomainUrl = Configure::read('AssetDomainUrl');
			$loadingImageUrl = $assetDomainUrl . 'assets/images/loading/loading.gif';
			$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
			$imageDetails = $this->App->getHighlightImage($productUploadedImages);
			$thumbUrl = '/img/noimage.jpg';
			$loadingImageUrl = '/loading4.jpg';
			$imageTagId = random_int(1, 10000);
			$productCartValue = $qty * $salePrice;
			$productCartMRPValue = $qty * $mrp;
			$totalDiscount += $qty * $discount;
			$cartMrpValue += $productCartMRPValue;

			if ($imageDetails) {
				$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
			}

			$inCartProductIds[] = $productID;
		?>

			<div class="mt-2 mb-4">
				<div id="topNavCartRow<?php echo $categoryID . '-' . $productID; ?>">
					<div class="p-1 pb-3 border rounded">
						<div class="bg-light rounded p-2">
							<div class="d-flex justify-content-between">
								<a href="/products/getDetails/<?= $categoryID; ?>/<?= $productID; ?>/<?= $productNameSlug ?>" class="link-primary text-decoration-none"><?= $productName ?></a>

								<div>
									<?php
									$title = $categoryName . ' &raquo; ' . $productName . '<br>Quantity: ' . $qty;
									?>
									<span href="#" onclick="showDeleteProductFromCartPopup('<?= $shoppingCartProductID ?>', '<?= $title ?>')" class="text-danger p-2" title="<?= $title ?>" role="button"><i class="bi bi-x"></i></span>
								</div>
							</div>

						</div>
						<div class="d-flex mt-2">
							<div>
								<img src="<?php echo $loadingImageUrl; ?>" data-original="<?php echo $thumbUrl; ?>" loading="lazy" class="lazy img-fluid" role="button" alt="<?php echo $productName; ?>" id="<?php echo $imageTagId; ?>" width="75" height="75" />
							</div>
							<div class="ms-2">
								<div class="small text-muted">
									Quantity: <?= $qty ?><br>
									Price: <?php echo $this->App->price($salePrice); ?>/unit,
									<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
								</div>
								<div class="d-flex mt-2">
									<div>
										<span class="text-danger fw-bold fs-5"><?php echo $this->App->price($productCartValue); ?></span>
									</div>
									<?php if ($showDiscount) : ?>
										<div class="ms-2 mt-1">
											<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($productCartMRPValue); ?></span>
										</div>
									<?php endif; ?>
								</div>
								<?php if ($showDiscount) : ?>
									<div class="text-success fw-bold small">
										Save <?php echo $this->App->priceOfferInfo($productCartValue, $productCartMRPValue); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<div class="mt-2">
							<div class="small">Quantity:</div>
							<div class="d-flex">

								<input type="number" id="ProductQuantity<?= $shoppingCartProductID ?>" name="data[Product][quantity]" class="form-control form-control-sm w-50" min="1" max="100" data-shopping-cart-product-id="<?= $shoppingCartProductID ?>" data-actual-qty="<?= $qty ?>" value="<?= $qty ?>" required>
								<div>
									<button class="btn btn-sm btn-primary ms-2" onclick="updateProductQtyFromShoppingCart('<?php echo $categoryID; ?>', '<?php echo $productID; ?>', $('#ProductQuantity<?= $shoppingCartProductID ?>').val(), '<?= $shoppingCartProductID ?>')">
										Update
									</button>
								</div>
								<div id="updatingCartSpinner<?= $shoppingCartProductID ?>" class="spinner-border spinner-border-sm text-primary ms-3 mt-2 small d-none" role="status">
									<span class="visually-hidden">updating...</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		?>

		<div class="mt-3 p-3 shadow rounded small">
			<?php
			if ($minOrderForFreeShipping > 0 && $cartValue >= $minOrderForFreeShipping) {
				$deliveryCharges = 0;
			}

			$payableAmount = $cartValue + $deliveryCharges;

			$applyPromoDiscount = false;
			$promoDiscountValue = 0;
			$purchaseThisMuchToAvailPromoCode = 0;
			$promoCodeInfo = $this->Session->check('PromoCode') ? $this->Session->read('PromoCode') : null;
			$promoCode = null;

			if ($promoCodeInfo) {
				$promoCode = $promoCodeInfo['name'];
				$minPurchaseValue = (float)$promoCodeInfo['min_purchase_value'];
				$promoDiscountValue = (float)$promoCodeInfo['discount_value'];

				if ($cartValue >= $minPurchaseValue) {
					$applyPromoDiscount = true;
				} else {
					$purchaseThisMuchToAvailPromoCode = $minPurchaseValue - $cartValue;
				}
			}

			if ($applyPromoDiscount) {
				$totalDiscount = $totalDiscount + $promoDiscountValue;
				$payableAmount = $payableAmount - $promoDiscountValue;
			}
			?>

			<h5>Price Details</h5>
			<hr>

			<div class="d-flex justify-content-between mt-3">
				<span>Price (<?= $totalItems ?> items)</span>
				<span><?= $this->App->price($cartMrpValue) ?></span>
			</div>

			<div class="d-flex justify-content-between mt-3">
				<span>Discount</span>
				<span class="text-success">- <?= $this->App->price($totalDiscount) ?></span>
			</div>

			<!-------------- -->
			<?php if ($applyPromoDiscount) { ?>
				<div class="small text-danger">
					Promo Code Applied - <?= $this->App->price($promoDiscountValue) ?> OFF
				</div>
			<?php } ?>

			<?php if ($promoCodeInfo && $applyPromoDiscount === false) { ?>
				<div class="small text-danger">
					Add item(s) worth <?= $this->App->price($purchaseThisMuchToAvailPromoCode) ?> or more to get <?= $this->App->price($promoDiscountValue) ?> OFF
				</div>
			<?php } ?>
			<!-------------- -->

			<div class="d-flex justify-content-between mt-3">
				<span>Delivery Charges</span>
				<span><?= $deliveryCharges > 0 ? $this->App->price($deliveryCharges) : '<span class="text-success">FREE</span>' ?></span>
			</div>
			<div>
				<?php
				if ($deliveryCharges > 0 && $minOrderForFreeShipping > $payableAmount) {
				?>
					<span class="text-danger small">Free delivery on Orders above <?= $this->App->price($minOrderForFreeShipping) ?></span>
				<?php
				}
				?>
			</div>

			<hr class="my-2">
			<div class="d-flex justify-content-between mt-2 fw-bold fs-6">
				<span>Total Amount</span>
				<span><?= $this->App->price($payableAmount) ?></span>
			</div>

		</div>

		<?php
		if ($this->Session->check('Site.show_promo_codes') && (bool)$this->Session->read('Site.show_promo_codes') === true) {
		?>
			<div class="mt-4 p-3 pb-4 shadow rounded small">
				<h6>Have Promo Code (or) Discount Code?</h6>
				<hr>
				<div class="d-flex justify-content-sm-between">
					<input type="text" name="promoc" id="promoCodeVal" class="form-control form-control-sm" placeholder="Enter code here">
					<button class="btn btn-outline-primary btn-sm ms-2" onclick="applyPromoCode()">Apply</button>
				</div>

				<?php
				if ($this->Session->check('PromoCode')) {
				?>
					<div class="mt-3 mb-0 alert alert-secondary bg-light text-center">
						Applied Promo Code: <b><?= $this->Session->read('PromoCode.name') ?></b>

						<div class="mt-2 text-center">
							<span class="btn btn-sm btn-outline-danger rounded-pill" onclick="removePromoCode()"><i class="bi bi-x me-1"></i> Remove Promo Code</span>
						</div>
					</div>
				<?php
				}
				?>

				<?= $this->element('promocodes_list') ?>
			</div>
		<?php
		}
		?>

		<div class="mt-5 text-center">
			<button class="btn btn-orange" onclick="showOrderDeliveryDetails()">PLACE ORDER</button>

			<a href="#" type="button" class="small mt-3 d-none" data-bs-dismiss="offcanvas" aria-label="Close">Hide Cart</a>
		</div>

		<?php
		if ($showInCartProducts) {

			// Filter out show_in_cart related products which are already there in cart
			foreach ($showInCartProducts as $row2) {
				if (in_array($row2['Product']['id'], $inCartProductIds)) {
					unset($showInCartProducts[$row2['Product']['id']]);
				}
			}
		}

		if ($showInCartProducts) {
		?>
			<section id="EssentialProducts">
				<article>
					<header>
						<div class="alert alert-info mt-5 shadow" role="button">
							<i class="bi bi-arrow-down-circle"></i> Recommended
						</div>
					</header>

					<div class="row g-3 p-0">
						<?php
						foreach ($showInCartProducts as $row2) {
							if (in_array($row2['Product']['id'], $inCartProductIds)) {
								continue;
							}

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

							<div class="col-6" id="productEssentialsCard<?php echo $categoryID . '-' . $productID; ?>">
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

				</article>
			</section>
		<?php
		}
		?>
	</div>

<?php
} else {
?>
	<div class="bg-white">
		No items in your cart.
	</div>
<?php
}
?>
<br><br><br><br>