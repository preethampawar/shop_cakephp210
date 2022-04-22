<?php
if ($viewType != 'download') { ?>
	<h1>Custom Stock Report</h1>

	<?php echo $this->Form->create('Report'); ?>
	<div>
		<div class="mb-3">
			<label for="fromDate" class="form-label">From Date</label>
			<input type="date" name="data[Report][from_date]" class="form-control form-control-sm"
				   value="<?php echo $fromDate; ?>" id="fromDate" required>
		</div>
		<div class="mb-3">
			<label for="toDate" class="form-label">To Date</label>
			<input type="date" name="data[Report][to_date]" class="form-control form-control-sm"
				   value="<?php echo $toDate; ?>" id="fromDate" required>
		</div>
		<div class="mb-3">
			<?php
			$options = ['download' => 'Download'];
			echo $this->Form->input('view_type', ['empty' => 'Normal View', 'label' => 'Download/Select View', 'type' => 'select', 'options' => $options, 'escape' => false, "class" => "form-control form-control-sm"]);
			?>
		</div>

		<div class="mb-3">
			<?php echo $this->Form->input('product_id', ['empty' => 'All', 'label' => 'Select Product', 'type' => 'select', 'options' => $productsList, 'escape' => false, "class" => "form-control form-control-sm"]); ?>
		</div>


		<button type="submit" class="btn btn-purple btn-md form-control mb-3">Generate Report</button>

	</div>
	<?php echo $this->Form->end(); ?>

	<?php
	if (isset($result)) {
		if (!empty($result)) {
			?>
			<br>
			<h4>Results from '<?php echo date('d-m-Y', strtotime($fromDate)); ?>' to
				'<?php echo date('d-m-Y', strtotime($toDate)); ?>'</h4>

			<?php
			$totalSales = 0;
			$totalPurchasesMRP = 0;
			$totalPurchasesInvoice = 0;
			foreach ($result as $row) {
				$saleStock = $row['0']['stock_sale'];
				$closingStock = $row['0']['closing_stock'];
				$unitSellingPrice = $row['p']['unit_selling_price'];
				$unitBuyingPrice = ($row['p']['box_qty'] > 0) ? ($row['p']['box_buying_price'] / $row['p']['box_qty']) : 0;
				$saleValue = ($row['rs_sales']['total_sale_value']) ? $row['rs_sales']['total_sale_value'] : 0;
				$saleValue = number_format($saleValue, 2, '.', '');

				$purchaseValueMrp = number_format($closingStock * $unitSellingPrice, 2, '.', '');
				$purchaseValueInvoice = number_format($closingStock * $unitBuyingPrice, 2, '.', '');

				$totalSales += $saleValue;
				$totalPurchasesMRP += $purchaseValueMrp;
				$totalPurchasesInvoice += $purchaseValueInvoice;
			}
			?>
			<table class="table table-sm small table-striped mb-4 table-bordered">
				<tbody>
				<tr>
					<td>Sale Value in MRP:</td>
					<td><?php echo number_format($totalSales, 2, '.', ''); ?></td>
				</tr>
				<tr>
					<td>Closing Stock in MRP:</td>
					<td><?php echo number_format($totalPurchasesMRP, 2, '.', ''); ?></td>
				</tr>
				<tr>
					<td>Closing Stock as per Invoice:</td>
					<td><?php echo number_format($totalPurchasesInvoice, 2, '.', ''); ?></td>
				</tr>
				</tbody>
			</table>

			<div class="form-check form-switch">
				<input class="form-check-input" name="showallproducts" id="showallproducts" type="checkbox"
					   id="flexSwitchCheckDefault" onclick='$(".HideRow").toggle()'>
				<label class="form-check-label" for="showallproducts">Hide products with "0" stock</label>
			</div>

			<div class="table-responsive">
				<table class='table table-striped table-sm search-table small'>
					<thead>
					<tr>
						<th>Sl.No.</th>
						<th>Product Name</th>
						<th>Opening Stock</th>
						<th>Stock Added</th>
						<th>Breakage Stock</th>
						<th>Closing Stock</th>
						<th>Sale Stock</th>
						<th>Unit Selling Price</th>
						<th>Sale Value-MRP</th>
						<th>CS Value-MRP</th>
						<th>CS Value-Invoice</th>
					</tr>
					</thead>
					<tbody>
					<?php
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
						$saleValueWithCurrentPrice = number_format($saleStock * $unitSellingPrice, 2, '.', '');
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
							$osClass = ($openingStock) ? 'cBlue bold' : null;
							$saClass = ($stockAdded) ? 'cRed bold' : null;
							$baClass = ($breakageStock) ? 'cRed bold' : null;
							$csClass = ($closingStock) ? 'cBlue bold' : null;
							$ssClass = ($saleStock) ? 'cGreen bold' : null;
							$svClass = ($saleValue > 0) ? 'cGreen bold' : null;
							$pvmrpClass = ($purchaseValueMrp > 0) ? 'cRed bold' : null;
							$pvClass = ($purchaseValueInvoice > 0) ? 'bold' : null;

							$rowClass = ($openingStock or $stockAdded or $closingStock or $saleStock) ? 'class="ShowRow"' : 'class="HideRow"';

							?>
							<tr <?php echo $rowClass; ?>>
								<td><?php echo $i; ?></td>
								<td><?php echo $productName; ?></td>
								<td class='<?php echo $osClass; ?>'><?php echo $openingStock; ?></td>
								<td class='<?php echo $saClass; ?>'><?php echo $stockAdded; ?></td>
								<td class='<?php echo $baClass; ?>'><?php echo $breakageStock; ?></td>
								<td class='<?php echo $csClass; ?>'><?php echo $closingStock; ?></td>
								<td class='<?php echo $ssClass; ?>'><?php echo $saleStock; ?></td>
								<td>
									<?php
									echo $unitSellingPrice;
									if ($saleValueWithCurrentPrice != $saleValue) {
										echo '<span title="Price Changed"> *</span>';
									}
									?>
								</td>
								<td class='<?php echo $svClass; ?>'>
									<?php
									echo $saleValue;
									//echo ' - ';
									//echo $totalSaleValue;
									?>
								</td>
								<td class='<?php echo $pvmrpClass; ?>'><?php echo $purchaseValueMrp; ?></td>
								<td class='<?php echo $pvClass; ?>'><?php echo $purchaseValueInvoice; ?></td>
							</tr>
							<?php
						}
					}
					?>
					</tbody>
					<tfoot>
					<th colspan='8'>&nbsp;</th>
					<th><?php echo number_format($totalSales, 2, '.', ''); ?><br>Sale Value-MRP</th>
					<th><?php echo number_format($totalPurchasesMRP, 2, '.', ''); ?><br>CS Value-MRP</th>
					<th><?php echo number_format($totalPurchasesInvoice, 2, '.', ''); ?><br>CS Value-Invoice</th>
					</tfoot>
				</table>
			</div>
			<?php
		} else {
			echo ' - No records found';
		}
	}
} else {
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
