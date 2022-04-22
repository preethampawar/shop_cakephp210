<section>
	<div class="text-end">
		<a href="/admin/images/manageBannerImages/<?= $this->data['Banner']['id'] ?>" class="ms-2 btn btn-info btn-sm me-2">Manage Images</a>
		<a href="/admin/banners/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<header><h2>Edit Banner</h2></header>

		<?= $this->Form->create() ?>

		<div class="form-check form-switch mt-3">
			<input type="hidden" name="data[Banner][active]" value="0">
			<input
					type="checkbox"
					id="BannerActive"
					name="data[Banner][active]"
					value="1"
					class="form-check-input"
					<?php echo isset($this->data['Banner']['active']) && $this->data['Banner']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="BannerActive">Active</label>
		</div>

		<div class="mt-3">
			<label for="BannerTitle">Title <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Banner.title', [
					'type' => 'text',
					'placeholder' => 'Enter Title',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'minlength' => "2",
					'maxlength' => "55",
					'required' => true,
			]) ?>
		</div>
		<div class="mt-3">
			<label for="BannerDescription">Description</label>
			<?= $this->Form->input('Banner.description', [
					'type' => 'textarea',
					'placeholder' => 'Enter Title',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'rows' => "2",
			]) ?>
		</div>
		<div class="mt-3">
			<label for="BannerUrl">Redirection URL</label>
			<?= $this->Form->input('Banner.url', [
					'type' => 'url',
					'placeholder' => 'Enter Redirection URL',
					'label' => false,
					'class' => 'form-control form-control-sm',
			]) ?>
		</div>
		<div class="mt-4">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

		<?= $this->Form->end() ?>
	</article>
</section>


<br><br>
<hr>
<div class="">

	<div class="d-flex justify-content-between">
		<h6>Banner Images</h6>
		<a href="/admin/images/manageBannerImages/<?= $this->data['Banner']['id'] ?>" class="ms-2 btn btn-info btn-sm me-2">Manage Images</a>
	</div>

	<ul class="list-group list-group-flush mt-3">
		<?php
		$bannerUploadedImages = $this->data['Banner']['images'] ? json_decode($this->data['Banner']['images']) : [];
		$assetDomainUrl = Configure::read('AssetDomainUrl');
		$bannerUploadedImages = $this->App->getRearrangedImages($bannerUploadedImages);
		$bannerId = $this->data['Banner']['id'];

		if($bannerUploadedImages) {
			foreach($bannerUploadedImages as $row) {
				$image = $row['thumb'];
				$imageOri = $row['ori'];
				$encodedImagePath = base64_encode($image->imagePath);
				$imageUrl = $assetDomainUrl.$image->imagePath;
				$imageHighlight = $image->highlight;
				$imageCommonId = $image->commonId;
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
					<img src="<?= $imageUrl ?> " loading="lazy" width="200" height="80" class="mb-2">
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
<br><br>
