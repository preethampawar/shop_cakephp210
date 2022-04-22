<?php
$csv=implode(',', array('CategoryName', 'BrandName', 'ProductName', 'ProductCode', 'BoxPrice', 'BoxQuantity', 'UnitPrice'))."\r\n";
if($storeProducts) {
	foreach($storeProducts as $row) {
		if(!empty($row['Product'])) {
			$k=0;
			foreach($row['Product'] as $product) {
				$tmp = array();
				$tmp[] = html_entity_decode($row['ProductCategory']['name']);
				$tmp[] = html_entity_decode(($storeBrands[$product['brand_id']] ?? ''));
				$tmp[] = html_entity_decode($product['name']);
				$tmp[] = html_entity_decode($product['product_code']);
				$tmp[] = $product['box_buying_price'];
				$tmp[] = $product['box_qty'];
				$tmp[] = $product['unit_selling_price'];
				$csv.=implode($tmp, ',')."\r\n";
			}
		}
	}
}
else {
	$csv.='No products';
}
echo $csv;
?>
