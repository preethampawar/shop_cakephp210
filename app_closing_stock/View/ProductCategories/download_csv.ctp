<?php
$csv = implode(['CategoryName', 'ProductName', 'BoxPrice', 'BoxQuantity', 'UnitPrice', 'SpecialMargin'], ",") . "\r\n";
if ($storeProducts) {
	foreach ($storeProducts as $row) {
		if (!empty($row['Product'])) {
			$k = 0;
			foreach ($row['Product'] as $product) {
				$tmp = [];
				$tmp[] = html_entity_decode($row['ProductCategory']['name']);
				$tmp[] = html_entity_decode($product['name']);
				$tmp[] = $product['box_buying_price'];
				$tmp[] = $product['box_qty'];
				$tmp[] = $product['unit_selling_price'];
				$tmp[] = ($product['special_margin'] > 0) ? $product['special_margin'] : 0;
				$csv .= implode($tmp, ',') . "\r\n";
			}
		}
	}
} else {
	$csv .= 'No products';
}
echo $csv;
?>
