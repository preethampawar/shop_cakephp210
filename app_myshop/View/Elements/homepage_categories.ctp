<?php
//App::uses('Category', 'Model');
//$categoryModel = new Category();
//$categories = $categoryModel->find(
//	'all',
//	[
//		'conditions' => [
//			'Category.site_id' => $this->Session->read('Site.id'),
//			'Category.deleted' => 0,
//			'Category.active' => 1,
//			'Category.images NOT' => null
//		],
//		'order' => [
//			'Category.name'
//		]
//	]
//);

$catListCacheKey = $this->Session->read('CacheKeys.catList');
$categories = Cache::read($catListCacheKey, 'verylong');
$showCategoriesDiv = false;

if ($categories and !empty($categories)) {
	foreach ($categories as $row) {
		$categoryUploadedImages = $row['Category']['images'] ? json_decode($row['Category']['images']) : [];
		$categoryHighlightImage = $this->App->getHighlightImage($categoryUploadedImages);

		if ($categoryHighlightImage) {
			$showCategoriesDiv = true;
		}
	}

	if ($showCategoriesDiv) {
?>

		<div class="table-responsive mb-2">
			<div class="hstack gap-3 align-items-start">
				<?php
				$loadingImageUrl = '/loading4.jpg';
				$imgCount = 1;

				foreach ($categories as $row) {
					$categoryId = $row['Category']['id'];
					$categoryName = $row['Category']['name'];
					$productsCount = $row['Category']['products_count'] ?? 0;
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$categoryUploadedImages = $row['Category']['images'] ? json_decode($row['Category']['images']) : [];
					$assetDomainUrl = Configure::read('AssetDomainUrl');
					$categoryHighlightImage = $this->App->getHighlightImage($categoryUploadedImages);
					$categoryProductsUrl = $this->Html->url('/products/show/' . $categoryId . '/' . $categoryNameSlug, true);

					$imageUrl = '';
					if ($categoryHighlightImage) {
						$image = $categoryHighlightImage['thumb'];
						$imageUrl = $assetDomainUrl . $image->imagePath;
					}

					if ($imageUrl) {
				?>

						<div id="categoryCard<?= $categoryId ?>" class="mb-2">
							<div class="text-center" id="category<?php echo $categoryId; ?>">
								<a href='<?= $categoryProductsUrl ?>' class="text-decoration-none d-block m-auto" style="width: 5rem;">
									<img src="<?php echo $imageUrl; ?>" class="img-fluid rounded-circle" role="button" alt="<?php echo $categoryName; ?>" width="300" height="300" <?= $imgCount > 3 ? 'loading="lazy"' : '' ?> />
								</a>

								<div class="card-body p-1">
									<a href='/products/show/<?= $categoryId ?>/<?= $categoryNameSlug ?>' class="text-decoration-none text-nowrap">
										<h6 class="small"><?= $categoryName ?></h6>
									</a>
									<div class="small">
										<a href='/products/show/<?= $categoryId ?>/<?= $categoryNameSlug ?>' class="text-decoration-none">
											<?= $productsCount ?> item(s)
										</a>

										<?= $this->element('sharebutton', [
											'title' => $categoryName,
											'text' => '',
											'url' => $categoryProductsUrl,
											'files' => '[]',
											'class' => 'ms-2',
											'showAsButton' => false,
										]); ?>
									</div>
								</div>
							</div>
						</div>
				<?php
						$imgCount++;
					}
				} ?>
			</div>
		</div>

<?php
	}
}
