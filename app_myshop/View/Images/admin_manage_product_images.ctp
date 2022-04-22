<?php
$this->set('enableImageCropper', true);
$siteId = $this->Session->read('Site.id');
$productId = $productInfo['Product']['id'];
$imageUploadRelPath = '/site_id/' . $siteId . '/' . 'products' . '/' . $productId;
$productUploadedImages = $productInfo['Product']['images'] ? json_decode($productInfo['Product']['images']) : [];
$assetDomainUrl = Configure::read('AssetDomainUrl');
$productUploadedImages = $this->App->getRearrangedImages($productUploadedImages);
$type = $this->Session->read('ProductImage.type');
$width = $this->Session->read('ProductImage.width');
$height = $this->Session->read('ProductImage.height');
$squareActive = $type == 'square' ? ' active ' : '';
$rectangleActive = $type == 'rectangle' ? ' active ' : '';
$verticalActive = $type == 'vertical' ? ' active ' : '';
?>

<div>

	<div class="my-3 d-flex justify-content-between align-items-center">
		<h5>Manage Images</h5>
		<a href='/admin/products/edit/<?php echo $productInfo['Product']['id']; ?>/<?php echo $categoryID; ?>' class="btn btn-warning btn-sm">Go Back</a>
	</div>
	<h6><?php echo $productInfo['Product']['name']; ?></h6>

	<div class="mt-3 shadow border-0 p-3 ">
		<h6>Upload Image</h6>

		<label class="form-label">Select Image Size (Width X Height)</label>
		<div>
			<a
				class="btn btn-sm btn-outline-primary <?= $squareActive ?>"
				href="/admin/images/manageProductImages/<?= $productId ?>/<?= $categoryID ?>/square/300/300">Square (1:1)</a>
			<a
				class="btn btn-sm btn-outline-primary ms-3 <?= $rectangleActive ?>"
				href="/admin/images/manageProductImages/<?= $productId ?>/<?= $categoryID ?>/rectangle/300/225">Rectangle (4:3)</a>
			<a
				class="btn btn-sm btn-outline-primary ms-3 <?= $verticalActive ?>"
				href="/admin/images/manageProductImages/<?= $productId ?>/<?= $categoryID ?>/vertical/225/300">Vertical (3:4)</a>
		</div>

		<br>

		<input
			type="file"
			name="upload_image"
			id="upload_image"
			class="btn btn-sm btn-outline-secondary"
			data-image-rel-path="<?php echo $imageUploadRelPath; ?>"
			data-product-id="<?php echo $productId; ?>"
			accept="image/*"
		/>
		<br/>
		<div class="mt-3">
			<a href="/admin/products/edit/<?php echo $productInfo['Product']['id']; ?>/<?php echo $categoryID; ?>"
			   class="btn btn-sm btn-secondary p-0 px-1">Cancel</a>
		</div>

		<div id="uploaded_image"></div>

		<div id="uploadimageModal" class="modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Upload & Crop Image</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body text-center">
						<div id="image_preview" class="w-100"></div>
						<div class="d-none small" id="imageUploadProcessingDiv">
							<img src="/img/plants2.gif" alt="Upload in progress" width="40" height="40">
							<span></span>
						</div>
						<div class="imageUploadError text-danger d-none"></div>
						<button class="btn btn-success crop_image">Crop & Upload Image</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" aria-label="Close">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<h6 class="mt-5">Images List</h6>
	<hr>
	<div class="">
		<ul class="list-group list-group-flush">
			<?php
			if($productUploadedImages) {
				foreach($productUploadedImages as $row) {
					$image = $row['thumb'];
					$imageOri = $row['ori'];
					$encodedImagePath = base64_encode($image->imagePath);
					$imageUrl = $assetDomainUrl.$image->imagePath;
					$imageHighlight = $image->highlight;
					$imageCommonId = $image->commonId;
					$highlightImagePath = '/admin/products/highlightImage/' . $productId . '/' . $imageCommonId;
					$deleteImagePath = '/admin/products/deleteImage/' . $productId . '/' . $imageCommonId;
					// $deleteOriImagePath = '/admin/products/deleteImage/' . $productId . '/' . $imageCommonId;
					$deleteImages = [
						$image->imagePath,
						$imageOri->imagePath,
					];
					$deleteImages = base64_encode(json_encode($deleteImages));
					$deleteImagesUrl = $assetDomainUrl . 'deleteImage.php?images=' . $deleteImages . '&i=' . time();

					?>
					<li class="list-group-item">
						<img src="<?= $imageUrl ?> " loading="lazy" width="150" height="150" class="img-fluid">

						<?php
						if (!$imageHighlight) {
							?>
							<a href="<?php echo $highlightImagePath;?>" class="btn btn-sm btn-primary ms-2">Highlight</a>
							<?php
						} else {
							?>
							<span class="btn btn-sm btn-warning ms-2 disabled "><span class="fa fa-check-circle"></span> Highlighted</span>
							<?php
						}
						?>
						<button
							class="btn btn-sm btn-outline-danger ms-2"
							onclick="showDeleteImagePopup('<?= $deleteImagesUrl; ?>', '<?= $deleteImagePath; ?>', 'Delete Image', 'Are you sure you want to delete this image?')"
						>
							Delete
						</button>
					</li>
					<?php
				}
			} else {
				?>
				<li class="list-group-item">
					No images
				</li>
				<?php
			}
			?>

		</ul>
	</div>
</div>


<br><br>

