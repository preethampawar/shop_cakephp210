<?php
$title_for_layout = 'Sales Report';
echo $this->set('title_for_layout', $title_for_layout);
?>
	<h1><?php echo $title_for_layout; ?></h1>
	<h4>From <?php echo date('d M Y', strtotime($fromDate)); ?> to <?php echo date('d M Y', strtotime($toDate)); ?></h4>

<?php
if ($result_allrecords) {
	?>
	<table class='table'>
		<thead>
		<tr>
			<th>Sl.No</th>
			<th>Date</th>
			<th>Category</th>
			<th>Product</th>
			<th>Total Units</th>
			<th>Unit Price</th>
			<th>Amount</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		$totalAmount = 0;

		foreach ($result_allrecords as $row) {
			$i++;
			$totalUnits = $row['Sale']['total_units'];
			$unitPrice = $row['Sale']['unit_price'];
			$saleAmount = $row['Sale']['total_amount'];
			$totalAmount += $saleAmount;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date'])); ?></td>
				<td><?php echo $row['Sale']['category_name']; ?></td>
				<td><?php echo $row['Sale']['product_name']; ?></td>
				<td><?php echo $totalUnits; ?></td>
				<td><?php echo $unitPrice; ?></td>
				<td><?php echo $saleAmount; ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<th colspan='6' style="text-align:right">Total Amount:</th>
			<th><?php echo number_format($totalAmount, 2, '.', ''); ?></th>
		</tr>
		</tfoot>
	</table>
	<?php
} else if ($result) {
	?>
	<table class='table'>
		<thead>
		<tr>
			<th>Sl.No</th>
			<th>Category</th>
			<th>Product</th>
			<th>Total Units</th>
			<th>Amount</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		$totalAmount = 0;

		foreach ($result as $row) {
			$i++;
			$totalUnits = $row[0]['total_units'];
			$saleAmount = $row[0]['total_amount'];
			$totalAmount += $saleAmount;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $row['Sale']['category_name']; ?></td>
				<td><?php echo $row['Sale']['product_name']; ?></td>
				<td><?php echo $totalUnits; ?></td>
				<td><?php echo $saleAmount; ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<th colspan='4' style="text-align:right">Total Amount:</th>
			<th><?php echo number_format($totalAmount, 2, '.', ''); ?></th>
		</tr>
		</tfoot>
	</table>
	<?php
} else {
	echo 'No records found';
}
?>
