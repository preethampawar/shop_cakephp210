<?php
$csv = implode(['CategoryName', 'ProductName', 'ClosingStock', 'ClosingDate'], ",") . "\r\n";
if ($storeProducts) {
	foreach ($storeProducts as $row) {
		if (!empty($row['Product'])) {
			$k = 0;
			foreach ($row['Product'] as $product) {
				$tmp = [];
				$tmp[] = html_entity_decode($row['ProductCategory']['name']);
				$tmp[] = html_entity_decode($product['name']);
				$tmp[] = null;
				$tmp[] = null;
				$csv .= implode($tmp, ',') . "\r\n";
			}
		}
	}
} else {
	$csv .= 'No products';
}
echo $csv;
?>
