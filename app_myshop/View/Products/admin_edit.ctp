<?php
$this->set('enableTextEditor', true);
?>
<style type="text/css">
	.checkbox label {
		padding-left: 5px;
	}
</style>

<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/admin/categories/">Products</a></li>
		<li class="breadcrumb-item"><a
				href="/admin/categories/showProducts/<?php echo $categoryID; ?>"><?php echo $categoryInfo['Category']['name']; ?></a>
		</li>
		<li class="breadcrumb-item active" aria-current="page"><?php echo $productInfo['Product']['name']; ?></li>
	</ol>
</nav>

<div id="EditProduct<?= $productInfo['Product']['id'] ?>">
	<h1>Product - "<?php echo $productInfo['Product']['name']; ?>"</h1>

	<form action="/admin/products/edit/<?= $productInfo['Product']['id'] ?>/<?= $categoryID ?>"
		  id="ProductAdminEditForm" method="post" accept-charset="utf-8" ref="form">

		<div class="mt-3 text-end">
			<button v-if="submitDisabled" type="button" class="btn btn-secondary btn-sm disabled">Save Changes
			</button>
			<button v-else type="submit" class="btn btn-primary btn-sm">Save Changes</button>
			<a href="/admin/categories/showProducts/<?php echo $categoryID; ?>"
			   class="btn btn-outline-warning btn-sm ms-3">Cancel</a>
		</div>

		<div class="mt-3">
			<h6>Product Settings</h6>
			<hr>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Product][active]" value="0">
				<input
					type="checkbox"
					id="ProductActive"
					name="data[Product][active]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Product']['active'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="ProductActive">Active (Published)</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Product][featured]" value="0">
				<input
					type="checkbox"
					id="ProductFeatured"
					name="data[Product][featured]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Product']['featured'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="ProductFeatured">Show in Best Deals</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Product][no_stock]" value="0">
				<input
					type="checkbox"
					id="ProductNoStock"
					name="data[Product][no_stock]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Product']['no_stock'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="ProductNoStock">Out of stock</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Product][hide_price]" value="0">
				<input
					type="checkbox"
					id="ProductHidePrice"
					name="data[Product][hide_price]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Product']['hide_price'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="ProductAllowRelativePriceUpdate">Hide Price Information</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Product][allow_relative_price_update]" value="0">
				<input
					type="checkbox"
					id="ProductAllowRelativePriceUpdate"
					name="data[Product][allow_relative_price_update]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Product']['allow_relative_price_update'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="ProductAllowRelativePriceUpdate">Allow Relative Price Update</label>
			</div>
		</div>

		<div class="py-3">
			<div class="mt-3 d-flex justify-content-between align-items-center">
				<h6>Images</h6>
				<a href="<?php echo '/admin/images/manageProductImages/' . $productInfo['Product']['id'] . '/' . $categoryID; ?>"
				   class="btn btn-warning btn-sm">Manage Images</a>
			</div>
			<hr>
			<?php
			if (!empty($productInfo['Product']['images'])) {
				$productImages = $this->App->getRearrangedImages($productInfo['Product']['images']);
				$productId = $productInfo['Product']['id'];
				$assetDomainUrl = Configure::read('AssetDomainUrl');
				?>
				<div class="">

					<div class="d-block">

						<?php
						foreach ($productImages as $row) {
							$image = $row['thumb'];
							$imageOri = $row['ori'];
							$imageUrl = $assetDomainUrl . $image->imagePath;
							$imageHighlight = $image->highlight;
							$imageCommonId = $image->commonId;
							$highlightImagePath = '/admin/products/highlightImage/' . $productId . '/' . $imageCommonId;
							$deleteImagePath = '/admin/products/deleteImage/' . $productId . '/' . $imageCommonId;
							$deleteOriImagePath = '/admin/products/deleteImage/' . $productId . '/' . $imageCommonId;
							$deleteImages = [
								$image->imagePath,
								$imageOri->imagePath,
							];
							$deleteImages = base64_encode(json_encode($deleteImages));
							$deleteImagesUrl = $assetDomainUrl . 'deleteImage.php?images=' . $deleteImages . '&i=' . time();
							?>
							<div
								class="me-2 mb-2 shadow-sm rounded float-start clear <?php echo $imageHighlight ? 'border border-warning' : ''; ?>">
								<img src="<?= $imageUrl ?> " loading="lazy" width="150" height="150" class="img-fluid">
								<div
									class="text-center "
									onclick="showDeleteImagePopup('<?= $deleteImagesUrl; ?>', '<?= $deleteImagePath; ?>', 'Delete Image', 'Are you sure you want to delete this image?')"
								>
									<span class="fa fa-times-circle text-danger p-2" role="button"></span>
								</div>
							</div>

							<?php
						}
						?>
					</div>
					<div style="clear:both"></div>
				</div>
				<?php
			} else {
				?>
				<div class="mb-2">
					No images found.
					<a href="<?php echo '/admin/images/manageProductImages/' . $productInfo['Product']['id'] . '/' . $categoryID; ?>">Click
						here</a> to add images.
				</div>
				<?php
			}
			?>
		</div>

		<div class="mt-2">
			<div class="pt-3">
				<h6>Select Category</h6>
			</div>
			<hr>

			<div class="pb-3">
				<?php
				App::uses('Category', 'Model');
				$this->Category = new Category;
				$categories = $this->Category->admin_getCategoryList();
				if (!empty($categories)) {
					$categoryOptions = [];
					foreach ($categories as $catID => $categoryName) {
						$categoryOptions[$catID] = ucwords($categoryName);
					}
					asort($categoryOptions);
					echo $this->Form->input(
						'Category.id',
						[
							'type' => 'select',
							'label' => false,
							'multiple' => 'checkbox',
							'options' => $categoryOptions,
							'selected' => $selectedCategories,
							'required' => true,
						]
					);

				}
				?>
			</div>

			<div class="my-3">
				<h6>Update Details</h6>
			</div>
			<hr>
			<div>
				<div class="my-3">
					<label for="ProductName" class="form-label">Product Name</label>
					<input
						type="text"
						id="ProductName"
						name="data[Product][name]"
						value="<?php echo $this->data['Product']['name']; ?>"
						class="form-control form-control-sm"
						placeholder="Enter product name"
						minlength="2"
						required
					>
				</div>
				<div class="mb-3">
					<label for="ProductDescription" class="form-label">Group</label>
					<?php
					echo $this->Form->select('Product.group_id', $groups, [
							'empty' => '- Select Group -',
							'class' => 'form-select form-select-sm'
					]);
					?>
				</div>
				<div class="mb-3">
					<label for="ProductDescription" class="form-label">Description</label>
					<textarea
						id="ProductDescription"
						name="data[Product][description]"
						class="form-control form-control-sm tinymce"
						placeholder="Enter product description"
					><?php echo $this->data['Product']['description']; ?></textarea>
				</div>
				<div class="my-3">
					<label for="ProductShortDesc" class="form-label">Short information in Product Card</label>
					<textarea
							id="ProductShortDesc"
							name="data[Product][short_desc]"
							class="form-control form-control-sm tinymce"
							placeholder="Enter short description"
					><?php echo $this->data['Product']['short_desc']; ?></textarea>
					<div class="small text-muted">Note: Short description will be displayed in Product tiles below the product name.</div>
				</div>
				<div class="my-3">
					<label for="ProductMrp" class="form-label">MRP (<?= $this->App->price('') ?>)</label>
					<input
						v-model="mrp"
						type="number"
						id="ProductMrp"
						name="data[Product][mrp]"
						value="<?php echo $this->data['Product']['mrp']; ?>"
						class="form-control form-control-sm"
						placeholder="Enter product mrp"
						min="1"
						max="999999"
						required
					>
				</div>
				<div class="my-3">
					<label for="ProductDiscount" class="form-label">Discount Value (<?= $this->App->price('') ?>
						)</label>
					<input
						v-model="discount"
						type="number"
						id="ProductDiscount"
						name="data[Product][discount]"
						value="<?php echo $this->data['Product']['discount']; ?>"
						class="form-control form-control-sm"
						placeholder="Enter discount price"
						min="0"
						max="999999"
					>
					<div v-if="showDiscountError" class="text-danger">Discount value cannot be greater than MRP</div>
				</div>
				<div v-show="!submitDisabled" class="mb-3">
					<div class="small text-danger">
						After discount: <span><?= $this->App->price('') ?>{{ sellingPrice }}</span> <span>({{ discountPercentage }}%)</span>
					</div>
				</div>

				<div class="my-3">
					<label for="ProductMrp" class="form-label">Minimum Order Quantity</label>
					<input
							type="number"
							id="ProductMinOrderQty"
							name="data[Product][min_order_qty]"
							value="<?php echo $this->data['Product']['min_order_qty']; ?>"
							class="form-control form-control-sm"
							placeholder="Minimum order quantity"
							min="1"
							max="1000"
							required
					>
				</div>

				<div class="my-3">
					<label for="ProductMetaKeywords" class="form-label">Meta Keywords (SEO)</label>
					<textarea
							id="ProductMetaKeywords"
							name="data[Product][meta_keywords]"
							class="form-control form-control-sm"
							placeholder="Enter meta keywords"
							rows="2"
					><?php echo $this->data['Product']['meta_keywords']; ?></textarea>
					<div class="small text-muted">Note: Enter 5 to 10 unique keywords separated by commas. Do not enter any special chars.</div>
				</div>

				<div class="my-3">
					<label for="ProductMetaDesc" class="form-label">Meta Description (SEO)</label>
					<textarea
							id="ProductMetaDesc"
							name="data[Product][meta_description]"
							class="form-control form-control-sm"
							placeholder="Enter a short description of this product"
							rows="2"
					><?php echo $this->data['Product']['meta_description']; ?></textarea>
					<div class="small text-muted">Note: Enter a short description of this product. Preferably, a very short paragraph without any special chars.</div>
				</div>

			</div>
			<br>
			<div class="my-3 py-3 d-inline">
				<button v-if="submitDisabled" type="button" class="btn btn-secondary btn-sm disabled">Save Changes
				</button>
				<button v-else type="submit" class="btn btn-primary btn-sm">Save Changes</button>
				<a href="/admin/categories/showProducts/<?php echo $categoryID; ?>"
				   class="btn btn-outline-warning btn-sm ms-3">Cancel</a>
			</div>
		</div>

		<?php
		echo $this->Form->end();
		?>
</div>
<br><br>

<?php $this->set('loadVueJs', true); ?>
<script>
	var editProduct = new Vue({
		el: "#EditProduct<?= $productInfo['Product']['id'] ?>",
		data: {
			mrp: '<?php echo (int)$this->data['Product']['mrp']; ?>',
			discount: '<?php echo (int)$this->data['Product']['discount']; ?>',
			submitDisabled: true,
			showDiscountError: false,
			discountPercentage: 0,
		},
		computed: {
			sellingPrice: function () {
				let val;
				let sp;
				if (!this.mrp) {
					this.mrp = 0;
				}

				if (!this.discount) {
					this.discount = 0;
				}

				this.discountPercentage = 0;
				if (parseInt(this.mrp) > 0) {
					val = parseInt(this.mrp) - parseInt(this.discount);
					if (val >= 0) {
						this.showDiscountError = false;
						this.submitDisabled = false;

						//this.discountPercentage =
						sp = parseInt(this.mrp) - val;
						this.discountPercentage = Math.ceil((sp * 100 / parseInt(this.mrp)));

						if (parseInt(this.mrp) !== parseInt(this.discount) && this.discountPercentage == 100) {
							this.discountPercentage = 99;
						}
					} else {
						this.showDiscountError = true;
						this.submitDisabled = true;
					}
				}
				return val;
			}
		},
		methods: {
			checkSellingPrice: function () {
				if (this.sellingPrice < 0) {
					showConfirmPopup('#', '', 'MRP price cannot be less than discount value');
				}
			},

			saveChanges: function () {

			}
		}
	})

</script>
