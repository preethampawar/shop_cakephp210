<div id="content">
	<div class="text-end">
		<a href="/admin/categories/showProducts/<?= $categoryInfo['Category']['id'] ?>" class="btn btn-sm btn-outline-primary">Manage Products</a>
		<a href='/admin/categories/' class="btn btn-warning btn-sm ms-2">Go Back</a>
	</div>
	<section>
		<h2>Edit Category: <?php echo $categoryInfo['Category']['name']; ?></h2>
		<?php
		echo $this->Form->create();
		?>

		<div class="form-check form-switch my-3">
			<input type="hidden" name="data[Category][active]" value="0">
			<input
				type="checkbox"
				id="CategoryActive"
				name="data[Category][active]"
				value="1"
				class="form-check-input"
				<?php echo $this->data['Category']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="CategoryActive">Active</label>
		</div>
		<div class="mb-3">
			<label for="CategoryName" class="form-label">Category Name</label>
			<input
				type="text"
				id="CategoryName"
				name="data[Category][name]"
				value="<?php echo $this->data['Category']['name']; ?>"
				class="form-control form-control-sm"
				placeholder="Enter category name"
				minlength="2"
				required
			>
			<div class='text-muted small'>Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>
		</div>

		<div class="my-3">
			<label for="CategoryMetaKeywords" class="form-label">Meta Keywords (SEO)</label>
			<textarea
					id="CategoryMetaKeywords"
					name="data[Category][meta_keywords]"
					class="form-control form-control-sm"
					placeholder="Enter meta keywords"
					rows="2"
			><?php echo $this->data['Category']['meta_keywords']; ?></textarea>
			<div class="small text-muted">Note: Enter 5 to 10 unique keywords separated by commas. Do not enter any special chars.</div>
		</div>

		<div class="my-3">
			<label for="CategoryMetaDesc" class="form-label">Meta Description (SEO)</label>
			<textarea
					id="CategoryMetaDesc"
					name="data[Category][meta_description]"
					class="form-control form-control-sm"
					placeholder="Enter a short description of this category"
					rows="2"
			><?php echo $this->data['Category']['meta_description']; ?></textarea>
			<div class="small text-muted">Note: Enter a short description of this category. Preferably, a very short paragraph without any special chars.</div>
		</div>

		<div class="mt-4">
			<button class="btn btn-sm btn-primary" type="submit">Update</button>
		</div>

		<?php
		echo $this->Form->end();
		?>


	</section>
</div>

<br>
<hr>
<div class="">

	<div class="d-flex justify-content-between">
		<h6>Category Images</h6>
		<a href="/admin/images/manageCategoryImages/<?= $this->data['Category']['id'] ?>" class="ms-2 btn btn-info btn-sm me-2">Manage Images</a>
	</div>

	<ul class="list-group list-group-flush mt-3">
		<?php
		$categoryUploadedImages = $this->data['Category']['images'] ? json_decode($this->data['Category']['images']) : [];
		$assetDomainUrl = Configure::read('AssetDomainUrl');
		$categoryUploadedImages = $this->App->getRearrangedImages($categoryUploadedImages);
		$categoryId = $this->data['Category']['id'];

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
				<li class="list-group-item py-3">
					<img src="<?= $imageUrl ?> " loading="lazy" width="300" height="300" class="mb-2">

					<?php
					if ($imageHighlight) {
						?>
						<span class="btn btn-sm btn-warning ms-2 disabled "><span class="fa fa-check-circle"></span> Highlighted</span>
						<?php
					}
					?>
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
