<?php
$title_for_layout = 'Purchase Report';
echo $this->set('title_for_layout', $title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>
<h4>From <?php echo date('d M Y', strtotime($fromDate));?> to <?php echo date('d M Y', strtotime($toDate));?></h4>

<?php
if($result_allrecords) {
?>
	<table class='table'>
		<thead>
			<tr>
				<th>Sl.No</th>
				<th>Invoice</th>
				<th>Date</th>
				<th>Category</th>
				<th>Product</th>
				<th>Boxes</th>
				<th>Box Price</th>
				<th>Spl.Margin/Box</th>
				<th>Spl.Margin Amount</th>				
				<th>Total Units</th>
				<th>Unit Price</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i=0;
		$totalAmount=0;
		$totalSplMarginAmount=0;
		
		foreach($result_allrecords as $row) {
			$i++;
			$boxQty = $row['Purchase']['box_qty'];
			$totalUnits = $row['Purchase']['total_units'];
			$boxPrice = $row['Purchase']['box_buying_price'];
			
			$splMargin = $row['Purchase']['special_margin'];
			$totalSplMargin = $row['Purchase']['total_special_margin'];
			$totalSplMarginAmount+=$totalSplMargin;
			
			$unitPrice = $row['Purchase']['unit_price'];
			$purchaseAmount=$row['Purchase']['total_amount'];
			$totalAmount+=$purchaseAmount;
		?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Purchase']['invoice_name'];?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Purchase']['purchase_date']));?></td>
				<td><?php echo $row['Purchase']['category_name'];?></td>
				<td><?php echo $row['Purchase']['product_name'];?></td>
				<td><?php echo $boxQty;?></td>
				<td><?php echo $boxPrice;?></td>
				<td style="text-align:center;"><?php echo ($splMargin>0) ? $splMargin : 0;?></td>
				<td style="text-align:right;"><?php echo ($totalSplMargin>0) ? $totalSplMargin : 0;?></td>
				<td style="text-align:center;"><?php echo $totalUnits;?></td>
				<td style="text-align:center;"><?php echo $unitPrice;?></td>
				<td style="text-align:right;"><?php echo $purchaseAmount;?></td>
			</tr>			
		<?php
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan='7' style="text-align:right">&nbsp;</th>
				<th colspan='2' style="text-align:right">Total Spl.Margin: <?php echo number_format($totalSplMarginAmount, 2, '.', '');?> </th>
				<th colspan='3' style="text-align:right">Total Amount: <?php echo number_format($totalAmount, 2, '.', '');?></th>
			</tr>
			<tr>
				<th colspan='12' style="text-align:right">Grand Total: <?php echo number_format(($totalSplMarginAmount+$totalAmount), 2, '.', '');?></th>
			</tr>
		</tfoot>
	</table>
<?php
}
elseif($result) {
?>
	<table class='table'>
		<thead>
			<tr>
				<th>Sl.No</th>
				<th>Category</th>
				<th>Product</th>
				<th>Total Units</th>
				<th style="text-align:right;">Spl.Margin</th>
				<th style="text-align:right;">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i=0;
		$totalAmount=0;
		$totalSplMarginAmount=0;
		
		foreach($result as $row) {
			$i++;
			$totalUnits = $row[0]['total_units'];
			$purchaseAmount = $row[0]['total_amount'];
			$totalAmount+=$purchaseAmount;
			
			$totalSplMargin = $row[0]['total_special_margin'];
			$totalSplMarginAmount+=$totalSplMargin;
		?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Purchase']['category_name'];?></td>
				<td><?php echo $row['Purchase']['product_name'];?></td>
				<td><?php echo $totalUnits;?></td>
				<td style="text-align:right;"><?php echo ($totalSplMargin>0) ? $totalSplMargin : 0;?></td>
				<td style="text-align:right;"><?php echo $purchaseAmount;?></td>
			</tr>			
		<?php
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan='4' style="text-align:right;">Total </th>
				<th style="text-align:right"><?php echo number_format($totalSplMarginAmount, 2, '.', '');?></th>
				<th style="text-align:right;"><?php echo number_format($totalAmount, 2, '.', '');?></th>
			</tr>
			<tr>
				<th colspan='4' style="text-align:right;">Grand Total </th>
				<th colspan='2' style="text-align:right"><?php echo number_format(($totalSplMarginAmount+$totalAmount), 2, '.', '');?></th>
			</tr>
		</tfoot>
	</table>
<?php
}
else {
	echo 'No records found';
}
?>