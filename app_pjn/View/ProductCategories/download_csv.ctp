<?php
$csv = implode(['CategoryName', 'BrandName', 'ProductName', 'BoxPrice', 'BoxQuantity', 'UnitPrice'], ",") . "\r\n";
if ($storeProducts) {
	foreach ($storeProducts as $row) {
		if (!empty($row['Product'])) {
			$k = 0;
			foreach ($row['Product'] as $product) {

				$productCategoryName = $row['ProductCategory']['name'] ?? '';
				$brandName = $product['Brand']['name'] ?? '';
				$productName = $product['name'] ?? '';

				$tmp = [];
				$tmp[] = html_entity_decode($row['ProductCategory']['name']);
				$tmp[] = html_entity_decode($product['Brand']['name']);
				$tmp[] = html_entity_decode($product['name']);
				$tmp[] = $product['box_buying_price'];
				$tmp[] = $product['box_qty'];
				$tmp[] = $product['unit_selling_price'];
				$csv .= implode($tmp, ',') . "\r\n";
			}
		}
	}
} else {
	$csv .= 'No products';
}
echo $csv;
?>
