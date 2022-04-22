<?php
$this->set('loadVueJs', true);
$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$showDiscount = $mrp != $salePrice;
$assetDomainUrl = Configure::read('AssetDomainUrl');
$loadingImageUrl = '/loading2.gif';
// http://www.apnastores.com/assets/images/loading/loading.gif
?>

<div class="col mb-3" id="vueProductCard<?php echo $categoryID . '-' . $productID; ?>">
	<div class=" card h-100 shadow p-0 mb-1 bg-white text-dark border-0" id="productCard<?php echo $productID; ?>">

		<img
			src="<?php echo $loadingImageUrl; ?>"
			data-original="<?php echo $productImageUrl; ?>"
			class="lazy w-100"
			alt="<?php echo $productName; ?>"
			id="<?php echo $imageTagId; ?>"
			v-on:click="showProductDetails('<?php echo $categoryID; ?>', '<?php echo $productID; ?>', '<?php echo $categoryNameSlug; ?>', '<?php echo $productNameSlug; ?>');"
		/>

		<!--		<img-->
		<!--			id="--><?php //echo $imageTagId; ?><!--"-->
		<!--			src="--><?php //echo $productImageUrl; ?><!--"-->
		<!--			alt="--><?php //echo $productTitle;?><!--"-->
		<!--			loading="lazy"-->
		<!--			class="w-100">-->


		<div class="card-body p-2 pt-0 text-center">
			<h6 class="mt-3">
				<span
					class=""
					role="button"
					v-on:click="showProductDetails('<?php echo $categoryID; ?>', '<?php echo $productID; ?>', '<?php echo $categoryNameSlug; ?>', '<?php echo $productNameSlug; ?>');"
				>
					<?php echo $productTitle; ?>
				</span>
			</h6>

			<?php if (!$hideProductPrice): ?>
				<div class="mt-3 d-flex justify-content-between">
					<h5>
						<span class="text-danger"><?php echo $this->App->price($salePrice); ?></span>
					</h5>
					<?php if ($showDiscount): ?>
						<div class="pl-2">
							<span
								class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<?php if ($showDiscount): ?>
					<div class="small text-center">
						<span
							class="text-success">Save <?php echo $this->App->priceOfferInfo($salePrice, $mrp); ?></span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>


		<?php if (!$hideProductPrice && $cartEnabled): ?>
			<div class="card-footer text-center bg-white border-top-0 pt-0 pb-3">
				<div class="card-text">
					<?php if (!$noStock): ?>
						<form id="AddToCart<?php echo $productID; ?>"
							  action="/shopping_carts/add/<?php echo $categoryID; ?>/<?php echo $productID; ?>"
							  method="post" class="flex">

							<div v-if="showUpdateCartDiv" id="saveCartDiv<?php echo $productID; ?>">
								<select
									name="data[ShoppingCartProduct][quantity]"
									id="ShoppingCartProductQuantity<?php echo $categoryID . '-' . $productID; ?>"
									class="form-control form-control-sm"
								>
									<?php echo $selectBoxQuantityOptions; ?>
								</select>

								<div class="mt-1 text-center p-0 d-flex justify-content-evenly">
									<button type="button" class="btn btn-sm btn-outline-secondary mt-1"
											v-on:click="showUpdateCartDiv = false">Cancel
									</button>
									<button
										type="button"
										class="btn btn-sm btn-primary active mt-1"
										v-on:click="addToCart('<?php echo $categoryID; ?>', '<?php echo $productID; ?>')"
									>
										+ Add
									</button>
								</div>
							</div>
							<div v-else class="text-center p-0">
								<button type="button" class="btn btn-sm btn-primary active  mt-1"
										v-on:click="showUpdateCart('ShoppingCartProductQuantity<?php echo $categoryID . '-' . $productID; ?>')">
									Add to cart
								</button>
							</div>
						</form>
					<?php else: ?>
						<button type="button" class="btn btn-sm btn-outline-secondary disabled">Out of stock</button>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div id="productModal<?php echo $productID; ?>" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?php echo $productTitle; ?></h5>
					<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<div class="d-flex">
						<div role="status" class="spinner-border text-primary small">
							<span class="visually-hidden">Loading..</span>
						</div>
						<span class="ml-2">Loading product details...</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var app = new Vue({
		el: '#vueProductCard<?php echo $categoryID . '-' . $productID; ?>',
		data: {
			showUpdateCartDiv: false,
		},
		methods: {
			showUpdateCart: function (elementToBeFocused) {
				this.showUpdateCartDiv = true;
				this.$nextTick(() => $('#' + elementToBeFocused).focus());
			},
			showProductDetails: function (categoryId, productId, categoryNameSlug, productNameSlug) {
				let myModal = new bootstrap.Modal(document.getElementById('productModal' + productId), {
					keyboard: false
				});
				myModal.show();

				let productDetailsUrl = '/products/getDetails/' + categoryId + '/' + productId + '/' + categoryNameSlug + '/' + productNameSlug;
				const data = getPage(productDetailsUrl);
				data.then(function (response) {
					$("#productModal" + productId + " .modal-body").html(response);
				});
			},
			addToCart: function (categoryId, productId) {
				const addToCartUrl = '/shopping_carts/addToCart';
				const quantity = $('#ShoppingCartProductQuantity' + categoryId + '-' + productId).val();
				let data = {
					'ShoppingCartProduct': {
						'quantity': quantity,
						'categoryId': categoryId,
						'productId': productId,
					}
				}
				const response = postData(addToCartUrl, data);

				let that = this;
				response.then(function (data) {
					$('#ToastMessage').removeClass('d-none');
					$('#toastDiv').removeClass('bg-primary');
					$('#toastDiv').removeClass('bg-danger');
					$('#toastDiv').removeClass('bg-notice');

					if (data.success == 1) {
						$('#toastDiv').addClass('bg-primary');
						$("#toastDiv .toast-body").html("<i class='fa fa-check-circle'></i> Product successfully added to cart.");
						that.showUpdateCartDiv = false;
						loadShoppingCart();
					} else {
						$('#toastDiv').addClass('bg-danger');
						$("#toastDiv .toast-body").html("<i class='fa fa-exclamation-circle'></i> " + data.errorMessage);
					}
					showToastMessages();
				});
			}
		}
	})
</script>

