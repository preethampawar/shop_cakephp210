<?php
$title_for_layout = ($paymentType == 'income') ? 'Credit Report' : (($paymentType == 'expense') ? 'Debit Report' : 'Credit & Debit Report');
echo $this->set('title_for_layout', $title_for_layout);
?>

<div class="text-end">
	<a href="/reports/transactionsReport/" class="btn btn-warning btn-sm">Back</a>
</div>

<h1 class="mt-3"><?php echo $title_for_layout; ?></h1>
<div class="text-muted mt-3 small">From <?php echo date('d M Y', strtotime($fromDate)); ?>
	to <?php echo date('d M Y', strtotime($toDate)); ?></div>

<?php
if ($result) {
	?>
	<div class="table-responsive small mt-3">
		<table class='table table-sm'>
			<thead>
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Category</th>
				<th>Debit</th>
				<th>Credit</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			$totalIncome = 0;
			$totalExpenses = 0;
			if ($result) {
				foreach ($result as $row) {
					$i++;
					$totalIncome += ($row['Transaction']['payment_type'] == 'income') ? $row['Transaction']['payment_amount'] : 0;
					$totalExpenses += ($row['Transaction']['payment_type'] == 'expense') ? $row['Transaction']['payment_amount'] : 0;
					?>
					<tr>
						<td><?php echo $i; ?>.</td>
						<td class="text-nowrap"><?php echo date('d-m-Y', strtotime($row['Transaction']['payment_date'])); ?></td>
						<td>
							<?php echo $row['Transaction']['transaction_category_name']; ?>
							<div class="text-muted small"><?php echo $row['Transaction']['description']; ?></div>
						</td>
						<td class="text-danger"><?php echo ($row['Transaction']['payment_type'] == 'expense') ? $row['Transaction']['payment_amount'] : ' '; ?></td>
						<td class="text-success"><?php echo ($row['Transaction']['payment_type'] == 'income') ? $row['Transaction']['payment_amount'] : ' '; ?></td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
			<tfoot>
			<tr>
				<th colspan='3' style="text-align:right">Total:</th>
				<th class="text-danger"><?php echo (($paymentType == 'expense') or ($paymentType == '')) ? number_format($totalExpenses, 2, '.', '') : null; ?></th>
				<th class="text-success"><?php echo (($paymentType == 'income') or ($paymentType == '')) ? number_format($totalIncome, 2, '.', '') : null; ?></th>
			</tr>
			<tr>
				<th colspan='3' style="text-align:right"></th>
				<td class="text-danger">Debit</td>
				<td class="text-success">Credit</td>
			</tr>
			</tfoot>
		</table>
	</div>
	<?php
} else {
	echo 'No records found';
}
?>
