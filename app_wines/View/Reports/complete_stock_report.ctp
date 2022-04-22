<?php $this->start('reports_menu');?>
<?php echo $this->element('reports_menu');?>
<?php $this->end();?>


<h1>Complete Stock Report</h1>

<?php
if(!empty($result)) {	
?>	
	<table class='table table-striped table-condensed search-table'>
		<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Category</th>
				<th>Product</th>
				<th>Purchase Stock</th>
				<th>Sale Stock</th>
				<th>Breakage Stock</th>
				<th>Closing Stock</th>
				<th>Purchase Value</th>				
				<th>Sale Value</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($result as $row) {
				$i++;
				$productID = $row['ProductStockReport']['product_id'];
				$productName = $row['ProductStockReport']['product_name'];
				$categoryName = $row['ProductStockReport']['category_name'];
				$productCategoryID = $row['ProductStockReport']['category_id'];
				$purchaseStock = $row['ProductStockReport']['purchase_qty'];
				$saleStock = $row['ProductStockReport']['sale_qty'];
				$breakageStock = $row['ProductStockReport']['breakage_qty'];				
				$closingStock = $row['ProductStockReport']['balance_qty'];				
				$saleValue = $row['ProductStockReport']['sale_amount'];
				$purchaseValue = $row['ProductStockReport']['purchase_amount'];				
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $categoryName;?></td>
				<td><?php echo $productName;?></td>
				<td><?php echo $purchaseStock;?></td>
				<td><?php echo $saleStock;?></td>
				<td><?php echo $breakageStock;?></td>
				<td><?php echo $closingStock;?></td>
				<td><?php echo $purchaseValue;?></td>
				<td><?php echo $saleValue;?></td>
			</tr>
			<?php				
			}
			?>
		</tbody>
	</table>
<?php
}
else {
	echo 'No products found';
}
?>