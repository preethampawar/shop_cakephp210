<?php
$this->set('enableBannerImageCropper', true);
$siteId = $this->Session->read('Site.id');
$bannerId = $bannerInfo['Banner']['id'];
$imageUploadRelPath = '/site_id/' . $siteId . '/' . 'banners' . '/' . $bannerId;
$bannerUploadedImages = $bannerInfo['Banner']['images'] ? json_decode($bannerInfo['Banner']['images']) : [];
$assetDomainUrl = Configure::read('AssetDomainUrl');
$bannerUploadedImages = $this->App->getRearrangedImages($bannerUploadedImages);
?>

<div>

	<div class="my-3 d-flex justify-content-between align-items-center">
		<h5>Manage Images</h5>
		<a href='/admin/banners/edit/<?php echo $bannerInfo['Banner']['id']; ?>' class="btn btn-warning btn-sm">Go Back</a>
	</div>
	<h6><?php echo $bannerInfo['Banner']['title']; ?></h6>

	<div class="mt-3 shadow border-0 p-3 ">
		<h6>Upload Banner Image</h6>
		<input
			type="file"
			name="upload_image"
			id="upload_image"
			class="btn btn-sm btn-outline-secondary"
			data-image-rel-path="<?php echo $imageUploadRelPath; ?>"
			data-product-id="<?php echo $bannerId; ?>"
			accept="image/*"
		/>
		<br/>
		<div class="text-danger mt-3 small">Min 1200 x 480 pixels (2.5:1 ratio)</div>

		<div class="mt-3">
			<a href="/admin/banners/edit/<?php echo $bannerInfo['Banner']['id']; ?>"
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
			if($bannerUploadedImages) {
				foreach($bannerUploadedImages as $row) {
					$image = $row['thumb'];
					$imageOri = $row['ori'];
					$imageUrl = $assetDomainUrl.$imageOri->imagePath;
					$imageHighlight = $imageOri->highlight;
					$imageCommonId = $imageOri->commonId;
					$highlightImagePath = '/admin/banners/highlightImage/' . $bannerId . '/' . $imageCommonId;
					$deleteImagePath = '/admin/banners/deleteImage/' . $bannerId . '/' . $imageCommonId;
					// $deleteOriImagePath = '/admin/banners/deleteImage/' . $bannerId . '/' . $imageCommonId;
					$deleteImages = [
						$image->imagePath,
						$imageOri->imagePath,
					];
					$deleteImages = base64_encode(json_encode($deleteImages));
					$deleteImagesUrl = $assetDomainUrl . 'deleteImage.php?images=' . $deleteImages . '&i=' . time();

					?>
					<li class="list-group-item">
						<img src="<?= $imageUrl ?> " loading="lazy" width="250" height="100" class="mb-2">

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

