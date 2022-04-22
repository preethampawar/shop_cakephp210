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

if ($categories and !empty($categories)) {
	?>
		<div class="mb-5">
			<div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
				<?php
				foreach($categories as $row) {
					$categoryId = $row['Category']['id'];
					$categoryName = $row['Category']['name'];
					$categoryNameSlug = Inflector::slug($categoryName, '-');

					$categoryUploadedImages = $row['Category']['images'] ? json_decode($row['Category']['images']) : [];
					$assetDomainUrl = Configure::read('AssetDomainUrl');
					$categoryHighlightImage = $this->App->getHighlightImage($categoryUploadedImages);
					$loadingImageUrl = '/loading4.jpg';
					$imageUrl = '';
					if ($categoryHighlightImage) {
						$image = $categoryHighlightImage['thumb'];
						$imageUrl = $assetDomainUrl.$image->imagePath;
					}

					if ($imageUrl) {
					?>
					<div class="col text-center" id="categoryCard<?= $categoryId ?>">

						<div class="card h-100 shadow" id="category<?php echo $categoryId; ?>">

							<a href='/products/show/<?= $categoryId ?>/<?= $categoryNameSlug ?>' class="text-decoration-none d-block">
								<img
										src="<?php echo $loadingImageUrl; ?>"
										data-original="<?php echo $imageUrl; ?>"
										class="lazy card-img-top img-fluid"
										role="button"
										alt="<?php echo $categoryName; ?>"
										width="300"
										height="300"
								/>
							</a>

							<div class="card-body text-center">
								<a href='/products/show/<?= $categoryId ?>/<?= $categoryNameSlug ?>' class="text-decoration-none">
									<h6><?= $categoryName ?></h6>
								</a>
							</div>
						</div>

					</div>
					<?php
					}
				}
				?>
			</div>
		</div>
	<?php
}


