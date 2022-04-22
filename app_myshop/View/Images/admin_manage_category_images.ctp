<?php
$this->set('enableCategoryImageCropper', true);
$siteId = $this->Session->read('Site.id');
$categoryId = $categoryInfo['Category']['id'];
$imageUploadRelPath = '/site_id/' . $siteId . '/' . 'categories' . '/' . $categoryId;
$categoryUploadedImages = $categoryInfo['Category']['images'] ? json_decode($categoryInfo['Category']['images']) : [];
$assetDomainUrl = Configure::read('AssetDomainUrl');
$categoryUploadedImages = $this->App->getRearrangedImages($categoryUploadedImages);
?>

<div>

	<div class="my-3 d-flex justify-content-between align-items-center">
		<h5>Manage Images</h5>
		<a href='/admin/categories/edit/<?php echo $categoryInfo['Category']['id']; ?>' class="btn btn-warning btn-sm">Go Back</a>
	</div>
	<h6><?php echo $categoryInfo['Category']['name']; ?></h6>

	<div class="mt-3 shadow border-0 p-3 ">
		<h6>Upload Category Image</h6>
		<input
			type="file"
			name="upload_image"
			id="upload_image"
			class="btn btn-sm btn-outline-secondary"
			data-image-rel-path="<?php echo $imageUploadRelPath; ?>"
			data-product-id="<?php echo $categoryId; ?>"
			accept="image/*"
		/>
		<br/>
		<div class="text-danger mt-3 small">Min dimensions - 300 x 300 pixels</div>

		<div class="mt-3">
			<a href="/admin/categories/edit/<?php echo $categoryInfo['Category']['id']; ?>"
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
			if($categoryUploadedImages) {
				foreach($categoryUploadedImages as $row) {
					$image = $row['thumb'];
					$imageOri = $row['ori'];
					$encodedImagePath = base64_encode($image->imagePath);
					$imageUrl = $assetDomainUrl.$image->imagePath;
					$imageHighlight = $image->highlight;
					$imageCommonId = $image->commonId;
					$highlightImagePath = '/admin/categories/highlightImage/' . $categoryId . '/' . $imageCommonId;
					$deleteImagePath = '/admin/categories/deleteImage/' . $categoryId . '/' . $imageCommonId;
					// $deleteOriImagePath = '/admin/categories/deleteImage/' . $categoryId . '/' . $imageCommonId;
					$deleteImages = [
						$image->imagePath,
						$imageOri->imagePath,
					];
					$deleteImages = base64_encode(json_encode($deleteImages));
					$deleteImagesUrl = $assetDomainUrl . 'deleteImage.php?images=' . $deleteImages . '&i=' . time();

					?>
					<li class="list-group-item pt-3 pb-3">
						<img src="<?= $imageUrl ?> " loading="lazy" width="300" height="300" class="mb-2">

						<?php
						if (!$imageHighlight) {
							?>
							<a href="<?php echo $highlightImagePath;?>" class="btn btn-sm btn-primary ms-2">Highlight</a>
							<?php
						} else {
							?>
							<span class="btn btn-sm btn-warning ms-2 disabled "><span class="bi bi-check-circle"></span> Highlighted</span>
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

