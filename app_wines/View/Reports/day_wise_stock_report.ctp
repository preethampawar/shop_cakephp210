<?php 
if($viewType!='download') {
	$title_for_layout = 'Day wise stock report';
	$this->set('title_for_layout',$title_for_layout);
	?>
	<h1><?php echo $title_for_layout;?></h1>

	<?php if($showForm) { ?>

		<?php $this->start('reports_menu');?>
		<?php echo $this->element('reports_menu');?>
		<?php $this->end();?>

	<?php echo $this->Form->create('Report', array('target'=>'_blank')); ?>
		<div id="paramsDiv">	
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('product_id', array('empty'=>'All', 'label'=>'Select Product', 'type'=>'select', 'options'=>$productsList, 'escape'=>false));?>
			</div>
			<div style="float:left; clear:none;">
				<?php 
				$options = array('print'=>'Print View', 'download'=>'Download');
				echo $this->Form->input('view_type', array('empty'=>'Normal View', 'label'=>'Download/Select View', 'type'=>'select', 'options'=>$options, 'escape'=>false, 'default'=>'download'));
				?>
			</div>
			<div style="float:left; clear:both;">
				<?php echo $this->Form->input('from_date', array('label'=>'From Date', 'required'=>true, 'type'=>'date'));?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('to_date', array('label'=>'To Date', 'required'=>true, 'type'=>'date'));?>
			</div>
			<div style="float:left; clear:none; margin-top:5px;">
				<br>
				&nbsp;&nbsp;<?php echo $this->Form->submit('Get Report', array('id'=>'SubmitForm', 'title'=>'', 'type'=>'submit', 'onclick'=>'return submitButtonMsg()', 'div'=>false));?>
			</div>
			<div style="clear:both;"></div>
		</div>
	<?php echo $this->Form->end();?>
	<?php } ?>		

	<?php
	if(isset($result)) {
		if(!empty($result)) {
		?>
			<h3>Product Stock Report: From '<?php echo date('d M Y', strtotime($fromDate));?>' to '<?php echo date('d M Y', strtotime($toDate));?>'</h3>
			<?php echo $this->Form->input('showallproducts', array('type'=>'checkbox', 'checked'=>true, 'label'=>'Show all products(with/without stock)', 'title'=>'Uncheck to show products having stock', 'onclick'=>'$(".HideRow").toggle()'));?>
			<table class='table table-striped table-condensed search-table' style="color:grey;">
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
					$i=0;
					$totalSales = 0;
					$totalPurchasesMRP = 0;
					$totalPurchasesInvoice = 0;
					foreach($result as $row) {
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
						$unitBuyingPrice = ($row['p']['box_qty']>0) ? ($row['p']['box_buying_price']/$row['p']['box_qty']) : 0;
						$saleValueWithCurrentPrice = number_format($saleStock*$unitSellingPrice, 2, '.', '');
						$saleValue = ($row['rs_sales']['total_sale_value']) ? $row['rs_sales']['total_sale_value'] : 0;
						$saleValue = number_format($saleValue, 2, '.', '');
						
						$purchaseValueMrp = number_format($closingStock*$unitSellingPrice, 2, '.', '');
						$purchaseValueInvoice = number_format($closingStock*$unitBuyingPrice, 2, '.', '');
						
						$totalSales += $saleValue;
						$totalPurchasesMRP +=  $purchaseValueMrp;
						$totalPurchasesInvoice +=  $purchaseValueInvoice;						
						
						$show = true;
						if($this->data['Report']['product_id']) {
							if($productID == $this->data['Report']['product_id']) {
								$show = true;
							}
							else {
								$show = false;
							}
						}
						if($show) {
							$osClass = ($openingStock) ? 'cBlue bold' : null;
							$saClass = ($stockAdded) ? 'cRed bold' : null;
							$baClass = ($breakageStock) ? 'cRed bold' : null;
							$csClass = ($closingStock) ? 'cBlue bold' : null;
							$ssClass = ($saleStock) ? 'cGreen bold' : null;
							$svClass = ($saleValue > 0) ? 'cGreen bold' : null;
							$pvmrpClass = ($purchaseValueMrp > 0) ? 'cRed bold' : null;
							$pvClass = ($purchaseValueInvoice > 0) ? 'bold' : null;
							
							$rowClass = ($openingStock OR $stockAdded OR $closingStock OR $saleStock) ? 'class="ShowRow"' : 'class="HideRow"';
						
							?>
							<tr <?php echo $rowClass;?>>
								<td><?php echo $i;?></td>
								<td><?php echo $productName;?></td>
								<td class='<?php echo $osClass;?>'><?php echo $openingStock;?></td>
								<td class='<?php echo $saClass;?>'><?php echo $stockAdded;?></td>
								<td class='<?php echo $baClass;?>'><?php echo $breakageStock;?></td>
								<td class='<?php echo $csClass;?>'><?php echo $closingStock;?></td>
								<td class='<?php echo $ssClass;?>'><?php echo $saleStock;?></td>
								<td>
								<?php 
								echo $unitSellingPrice;
								if($saleValueWithCurrentPrice != $saleValue) {
									echo '<span title="Price Changed"> *</span>';
								}
								?>
								</td>
								<td class='<?php echo $svClass;?>'>
									<?php 
										echo $saleValue;
										//echo ' - ';
										//echo $totalSaleValue;
									?>
								</td>
								<td class='<?php echo $pvmrpClass;?>'><?php echo $purchaseValueMrp;?></td>
								<td class='<?php echo $pvClass;?>'><?php echo $purchaseValueInvoice;?></td>
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
		<?php
		}
		else {
			echo ' - No records found';
		}
	}
}
else {
	// generate report in csv format
	$csv='Product Stock Report: From '.date('d M Y', strtotime($fromDate)).' to '.date('d M Y', strtotime($toDate))."\r\n";
	$csv.="\r\n";
	if(!empty($result)) {					
		$csv.=implode(array('Category', 'Product', 'Opening Stock', 'Stock Added', 'Breakage Stock', 'Closing Stock', 'Sale Stock', 'Unit Price', 'Sale Value-MRP', "CS Value-MRP", 'CS Value-Invoice'), ",")."\r\n";
		
		$i=0;
		$totalSales = 0;
		$totalPurchasesMRP = 0;
		$totalPurchasesInvoice = 0;
		
		
		foreach($result as $row) {
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
			$unitBuyingPrice = ($row['p']['box_qty']>0) ? ($row['p']['box_buying_price']/$row['p']['box_qty']) : 0;
			//$saleValue = number_format($saleStock*$unitSellingPrice, 2, '.', '');
			$saleValue = ($row['rs_sales']['total_sale_value']) ? $row['rs_sales']['total_sale_value'] : 0;
			$saleValue = number_format($saleValue, 2, '.', '');
			$purchaseValueMrp = number_format($closingStock*$unitSellingPrice, 2, '.', '');
			$purchaseValueInvoice = number_format($closingStock*$unitBuyingPrice, 2, '.', '');
			
			$totalSales += $saleValue;
			$totalPurchasesMRP +=  $purchaseValueMrp;
			$totalPurchasesInvoice +=  $purchaseValueInvoice;
						
			$show = true;
			if($this->data['Report']['product_id']) {
				if($productID == $this->data['Report']['product_id']) {
					$show = true;
				}
				else {
					$show = false;
				}
			}
			if($show) {
				$tmp=array();
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
				$csv.=implode($tmp,',')."\r\n";
			}			
		}
		// show total
		$csv.="\r\n";
		$tmp=array();
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
		$csv.=implode($tmp,',')."\r\n";	
	}
	else {
		$csv.='No records found';
	}
	
	echo $csv;
}
?>