<?php
$data = [];
if (!empty($allProducts)) {
	$n = 0;
	$loadingImageUrl = '/loading4.jpg';
	$deliveryCharges = (float)$this->Session->read('Site.shipping_charges');
	$minOrderForFreeShipping = (float)$this->Session->read('Site.free_shipping_min_amount');

	foreach ($allProducts as $row) {
		$categoryID = $row['Category']['id'];
		$categoryName = $row['Category']['name'];
		$categoryNameSlug = Inflector::slug($categoryName, '-');

		if (!empty($row['CategoryProducts'])) {
			$z = 0;
			$data[$n]['Category'] = ['id' => $categoryID, 'name' => $categoryName, 'slug' => $categoryNameSlug];

			foreach ($row['CategoryProducts'] as $row2) {
				$productID = $row2['Product']['id'];
				$productName = $row2['Product']['name'];
				$productShortDesc = $row2['Product']['short_desc'];
				$productNameSlug = Inflector::slug($productName, '-');
				$productTitle = $productName;
				$assetDomainUrl = Configure::read('AssetDomainUrl');
				$productUploadedImages = $row2['Product']['images'] ? json_decode($row2['Product']['images']) : [];
				$imageDetails = $this->App->getHighlightImage($productUploadedImages);
				$thumbUrl = "/img/noimage.jpg";
				$imageTagId = random_int(1, 10000);
				$productDetailsPageUrl = '/products/getDetails/' . $categoryID . '/' . $productID . '/' . $productNameSlug;

				if ($imageDetails) {
					$thumbUrl = $assetDomainUrl . $imageDetails['thumb']->imagePath;
				}

				$productImageUrl = $thumbUrl;
				$mrp = $row2['Product']['mrp'];
				$discount = $row2['Product']['discount'];
				$salePrice = $mrp - $discount;
				$noStock = $row2['Product']['no_stock'];
				$cartEnabled = $this->Session->read('Site.shopping_cart');
				$hideProductPrice = $row2['Product']['hide_price'];
				$avgRating = $row2['Product']['avg_rating'];
				$ratingsCount = $row2['Product']['ratings_count'];


				$data[$n]['Category']['products'][] = [
					'id' => $productID,
					'name' => $productName,
					'slug' => $productNameSlug,
					'shortDesc' => $productShortDesc,
					'imageUrl' => $productImageUrl,
					'imageTagId' => $imageTagId,
					'mrp' => $mrp,
					'discount' => $discount,
					'salePrice' => $salePrice,
					'cartEnabled' => $cartEnabled,
					'noStock' => $noStock,
					'hideProductPrice' => $hideProductPrice,
					'avgRating' => $avgRating,
					'ratingsCount' => $ratingsCount,
					'categoryId' => $categoryID,
					'productDetailsPageUrl' => $productDetailsPageUrl,
					'loadingImageUrl' => $loadingImageUrl,
					'deliveryCharges' => $deliveryCharges,
					'minOrderForFreeShipping' => $minOrderForFreeShipping,
				];
			}

			$n++;
		}
	}
}

$this->response->header('Content-type', 'application/json');
$this->response->body(
	json_encode([
		'data' => $data,
	], JSON_THROW_ON_ERROR)
);
$this->response->send();
exit;
