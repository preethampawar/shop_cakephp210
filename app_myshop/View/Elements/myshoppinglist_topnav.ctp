<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

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

	<div class="shadow pb-0 pt-0 mb-4 border bg-info text-dark">
		<nav class="navbar navbar-light bg-light text-dark py-1">
			<div class="container-fluid py-1 text-small">
				<div class="px-0 py-0 text-dark border-0" role="button" data-toggle="collapse"
						data-target="#topNavCart" aria-controls="topNavCart" aria-expanded="false"
						aria-label="Toggle navigation">
					<span class="fa fa-cart-arrow-down"></span>
					<span class="font-weight-bold small">My Cart</span>
					<span class="fa fa-caret-down"></span>
				</div>
				<div><b><?php echo $totalItems; ?></b> item(s) in <a href="#topNavCart" data-toggle="collapse"
																	 data-target="#topNavCart"
																	 aria-controls="topNavCart" aria-expanded="false"
																	 aria-label="Toggle navigation">cart</a>.
				</div>
			</div>
		</nav>
		<div class="collapse" id="topNavCart">
			<div class="bg-white p-2 x-small">

					<?php
					$i = 0;
					$cartValue = 0;
					foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
						$i++;
						$shoppingCartProductID = $row['id'];
						$categoryID = $row['category_id'];
						$categoryName = ucwords($row['category_name']);
						$categoryNameSlug = Inflector::slug($categoryName, '-');

						$productID = $row['product_id'];
						$productName = ucwords($row['product_name']);
						$productNameSlug = Inflector::slug($productName, '-');
						$qty = $row['quantity'] ?: 0;
						$mrp = $row['mrp'];
						$discount = $row['discount'];
						$salePrice = $mrp - $discount;
						$showDiscount = $mrp != $salePrice;
						$totalProductPurchaseValue = $salePrice * $qty;
						$cartValue += $totalProductPurchaseValue;

						$assetDomainUrl = Configure::read('AssetDomainUrl');
						$loadingImageUrl = $assetDomainUrl.'assets/images/loading/loading.gif';
						$productUploadedImages = $row['Product']['images'] ? json_decode($row['Product']['images']) : [];
						$imageDetails = $this->App->getHighlightImage($productUploadedImages);
						$thumbUrl = '/img/noimage.jpg';
						$imageTagId = random_int(1, 10000);
						$productCartValue = $qty * $salePrice;
						$productCartMRPValue = $qty * $mrp;
						if($imageDetails) {
							$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
						}
						?>

						<div class="p-0 ml-2">
							<div id="vueTopNavCartRow<?php echo $categoryID .'-'. $productID; ?>" >
								<div class="card border-0" style="max-width: 25rem;">
									<div class="card-body p-0">
										<!-- product title -->
										<div class="d-none">
											<div class="d-flex justify-content-between">
												<?php echo $this->Html->link(
													$productName,
													'/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug,
													['title' => $categoryNameSlug . ' &raquo; ' . $productNameSlug, 'escape' => false, 'class'=>'text-decoration-none']
												); ?>

												<div>
													<?php
													echo $this->Html->link(
														'Delete',
														'/ShoppingCarts/deleteShoppingCartProduct/' . $shoppingCartProductID,
														[
															'title' => 'Delete: ' . $categoryNameSlug . ' &raquo; ' . $productNameSlug,
															'escape' => false,
															'class' => 'btn btn-sm px-1 py-0 btn-outline-secondary ml-2'
														],
														'Are you sure you want to delete this product. ' . $categoryName . ' &raquo; ' . $productName . ', quantity: ' . $qty
													);
													?>
												</div>
											</div>
										</div>


										<div class="card-text p-1">

											<div class="d-flex justify-content-between">
												<div class="d-flex">
													<img
														src="<?php echo $thumbUrl; ?>"
														loading="lazy"
														class=""
														alt="<?php echo $productName; ?>"
														id="<?php echo $imageTagId; ?>"
														style="width: 75px; height: 75px"
														v-on:click="showProductDetails('<?php echo $categoryID; ?>', '<?php echo $productID; ?>');"
													/>
													<div class="ml-2">
														<?php echo $this->Html->link(
															$productName,
															'/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug,
															['title' => $categoryNameSlug . ' &raquo; ' . $productNameSlug, 'escape' => false, 'class'=>'text-decoration-none']
														); ?>
														<div class="mt-1 d-flex">
															<span class="text-danger font-weight-bold"><?php echo $this->App->price($productCartValue);?></span>
															<?php if($showDiscount): ?>
															<div class="ml-2">
																<span class="small text-decoration-line-through"> <?php echo $this->App->price($productCartMRPValue);?></span>
															</div>
															<?php endif; ?>
														</div>
														<?php if($showDiscount): ?>

															<div class="text-success x-small">
																Save <?php echo $this->App->priceOfferInfo($productCartValue, $productCartMRPValue); ?>
															</div>
														<?php endif; ?>

														<div class="d-flex mt-2 d-none">
															<input
																type="number"
																id="ProductQuantity<?= $shoppingCartProductID ?>"
																name="data[Product][quantity]"
																class="form-control form-control-sm"
																min="1"
																max="100"
																data-shopping-cart-product-id="<?= $shoppingCartProductID ?>"
																data-actual-qty="<?= $qty ?>"
																value="<?= $qty ?>"
																required
															>
															<div>
																<button class="btn btn-sm btn-outline-primary ml-2">Update</button>
															</div>

														</div>

													</div>
												</div>
												<div class="ml-1">
													<div class="d-flex flex-column" style="width: 25px;">
														<div>
															<button v-on:click="increaseProductQty" class="btn btn-sm btn-primary w-100 rounded-none mb-1 p-0"><span class="fa fa-plus"></span></button>
														</div>
														<div>
															<div id="productQty<?= $shoppingCartProductID ?>" class="text-center font-weight-bold">{{productQty}}</div>
															<input v-model="productQty" type="hidden" class="form-control form-control-sm p-0 w-100 border-0 text-center" value="<?= $qty ?>">
														</div>
														<div>
															<div v-if="showDeleteButton === false">

																<button v-on:click="reduceProductQty" class="btn btn-sm btn-primary w-100 mt-1 p-0"><span class="fa fa-minus"></span></button>
															</div>

															<div v-if="showDeleteButton === true">
																<?php
																echo $this->Html->link(
																	'<span class="far fa-trash-alt"></span>',
																	'/ShoppingCarts/deleteShoppingCartProduct/' . $shoppingCartProductID,
																	[
																		'title' => 'Delete Item',
																		'escape' => false,
																		'class' => 'btn btn-sm btn-danger w-100 mt-1 p-0'
																	],
																	'Are you sure you want to delete this product. ' . $categoryName . ' &raquo; ' . $productName . ', quantity: ' . $qty
																);
																?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<script>
								var app = new Vue({
									el: '#vueTopNavCartRow<?php echo $categoryID .'-'. $productID; ?>',
									data: {
										showUpdateCartDiv: false,
										productQty: <?= $qty ?>,
										showDeleteButton: false,
									},
									watch: {
										productQty: function () {
											console.log(this.productQty);
											if (1 < parseInt(this.productQty)) {
												this.showDeleteButton = false;
											} else {
												this.showDeleteButton = true;
											}
										}
									},
									methods: {
										reduceProductQty: function() {
											this.productQty = parseInt(this.productQty) - 1;
										},
										increaseProductQty: function() {
											this.productQty = parseInt(this.productQty) + 1;
										},
										showUpdateCart: function (elementToBeFocused) {
											this.showUpdateCartDiv = true;
											this.$nextTick(() => $('#' + elementToBeFocused).focus());
										},
										showProductDetails: function (categoryId, productId) {
											let data;
											let productDetailsUrl;
											let myModal = new bootstrap.Modal(document.getElementById('productModal' + productId), {
												keyboard: false
											});
											myModal.show();

											productDetailsUrl = '/products/getDetails/' + categoryId + '/' + productId;
											data = getPage(productDetailsUrl);
											data.then(function (response) {
												$("#productModal" + productId + " .modal-body").html(response);
											});
										}
									}
								})
							</script>
						</div>
						<hr>
						<?php
					}
					?>

				<div style="text-align:center;">
					<?php
					echo $this->Form->create(null, ['url' => '/RequestPriceQuote', 'method' => 'get', 'encoding' => false]);
					?>
					<button class="btn btn-primary">Book Order</button>
					<?php
					//echo $this->Form->submit('Book Order', ['escape' => false, 'div' => false]);
					echo $this->Form->end();
					?>
					<br>
					<a href="#topNavCart"
					   data-toggle="collapse"
					   data-target="#topNavCart"
					   aria-controls="topNavCart"
					   aria-expanded="false"
					   aria-label="Toggle navigation"
					>
						Hide Cart
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php
} else {
	?>
	<?php
}
?>

