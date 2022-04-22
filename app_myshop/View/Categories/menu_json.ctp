<?php
$data = [];

$catListCacheKey = $this->Session->read('CacheKeys.catList');
$categories = Cache::read($catListCacheKey, 'verylong');

foreach ($categories as $row) {
	$categoryUploadedImages = $row['Category']['images'] ? json_decode($row['Category']['images']) : [];
	$categoryHighlightImage = $this->App->getHighlightImage($categoryUploadedImages);

	$categoryId = $row['Category']['id'];
	$categoryName = $row['Category']['name'];
	$productsCount = $row['Category']['products_count'] ?? 0;
	$categoryNameSlug = Inflector::slug($categoryName, '-');	
	$categoryProductsUrl = $this->Html->url('/products/show/' . $categoryId . '/' . $categoryNameSlug, true);	
	$assetDomainUrl = Configure::read('AssetDomainUrl');
	$imageUrl = '';

	if ($categoryHighlightImage) {
		$image = $categoryHighlightImage['thumb'];
		$imageUrl = $assetDomainUrl.$image->imagePath;
	}

	$data[] = [
		'id' => $categoryId,
		'name' => $categoryName,
		'productsCount' => $productsCount,
		// 'highlightImage' => $categoryHighlightImage,
		'imageUrl' => $imageUrl,
		'productsUrl' => $categoryProductsUrl,
	];
}


$this->response->header('Content-type', 'application/json');
$this->response->body(json_encode([
		'data' => $data,
	], JSON_THROW_ON_ERROR)
);
$this->response->send();
exit;
?>