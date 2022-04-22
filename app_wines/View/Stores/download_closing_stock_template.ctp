<?php
$csv=implode(array('CategoryName', 'ProductName', 'ClosingStock', 'ClosingDate'), ",")."\r\n";
if($storeProducts) {
	foreach($storeProducts as $row) {
		if(!empty($row['Product'])) {
			$k=0;
			foreach($row['Product'] as $product) {
				$tmp = array();
				$tmp[] = html_entity_decode($row['ProductCategory']['name']);	
				$tmp[] = html_entity_decode($product['name']);	
				$tmp[] = NULL;	
				$tmp[] = NULL;	
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
