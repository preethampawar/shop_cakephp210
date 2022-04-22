<?php
{
	// generate report in csv format
	$csv = 'Product Stock Report: From ' . date('d M Y', strtotime($fromDate)) . ' to ' . date('d M Y', strtotime($toDate)) . "\r\n";
	$csv .= "\r\n";
	if (!empty($result)) {
		$csv .= implode(['Category', 'Product', 'Opening Stock', 'Stock Added', 'Breakage Stock', 'Closing Stock', 'Sale Stock', 'Unit Price', 'Sale Value-MRP', "CS Value-MRP", 'CS Value-Invoice'], ",") . "\r\n";

		$i = 0;
		$totalSales = 0;
		$totalPurchasesMRP = 0;
		$totalPurchasesInvoice = 0;


		foreach ($result as $row) {
			$i++;
			$productID = $row['p']['id'];
			$productName = $row['p']['name'];
			$productCategoryID = $row['p']['product_category_id'];
			$productCategoryName = $row['c']['name'];
			$openingStock = $row['0']['opening_stock'];
			$stockAdded = $row['0']['stock_added'];
			$saleStock = $row['0']['stock_sale'];
			$breakageStock = $row['0']['stock_breakage'];
			$closingStock = $row['0']['closing_stock'];
			$unitSellingPrice = $row['p']['unit_selling_price'];
			$unitBuyingPrice = ($row['p']['box_qty'] > 0) ? ($row['p']['box_buying_price'] / $row['p']['box_qty']) : 0;
			//$saleValue = number_format($saleStock*$unitSellingPrice, 2, '.', '');
			$saleValue = ($row['rs_sales']['total_sale_value']) ? $row['rs_sales']['total_sale_value'] : 0;
			$saleValue = number_format($saleValue, 2, '.', '');
			$purchaseValueMrp = number_format($closingStock * $unitSellingPrice, 2, '.', '');
			$purchaseValueInvoice = number_format($closingStock * $unitBuyingPrice, 2, '.', '');

			$totalSales += $saleValue;
			$totalPurchasesMRP += $purchaseValueMrp;
			$totalPurchasesInvoice += $purchaseValueInvoice;

			$show = true;
			if ($this->data['Report']['product_id']) {
				if ($productID == $this->data['Report']['product_id']) {
					$show = true;
				} else {
					$show = false;
				}
			}
			if ($show) {
				$tmp = [];
				$tmp[] = $productCategoryName;
				$tmp[] = $productName;
				$tmp[] = $openingStock;
				$tmp[] = $stockAdded;
				$tmp[] = $breakageStock;
				$tmp[] = $closingStock;
				$tmp[] = $saleStock;
				$tmp[] = $unitSellingPrice;
				$tmp[] = $saleValue;
				$tmp[] = $purchaseValueMrp;
				$tmp[] = $purchaseValueInvoice;
				$csv .= implode($tmp, ',') . "\r\n";
			}
		}
		// show total
		$csv .= "\r\n";
		$tmp = [];
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = $totalSales;
		$tmp[] = $totalPurchasesMRP;
		$tmp[] = $totalPurchasesInvoice;
		$csv .= implode($tmp, ',') . "\r\n";
	} else {
		$csv .= 'No records found';
	}

	echo $csv;
}
?>
