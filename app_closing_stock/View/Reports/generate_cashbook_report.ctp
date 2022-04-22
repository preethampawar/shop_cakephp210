<?php
$title_for_layout = ($paymentType == 'income') ? 'Income Report' : (($paymentType == 'expense') ? 'Expense Report' : 'Expenses & Income Report');
echo $this->set('title_for_layout', $title_for_layout);
?>

<div class="text-end">
	<a href="/reports/cashbookReport/" class="btn btn-warning btn-sm">Cancel</a>
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
				<th>Expenses</th>
				<th>Income</th>
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
					$totalIncome += ($row['Cashbook']['payment_type'] == 'income') ? $row['Cashbook']['payment_amount'] : 0;
					$totalExpenses += ($row['Cashbook']['payment_type'] == 'expense') ? $row['Cashbook']['payment_amount'] : 0;
					?>
					<tr>
						<td><?php echo $i; ?>.</td>
						<td class="text-nowrap"><?php echo date('d-m-Y', strtotime($row['Cashbook']['payment_date'])); ?></td>
						<td>
							<?php echo $row['Cashbook']['category_name']; ?>
							<div class="text-muted small"><?php echo $row['Cashbook']['description']; ?></div>
						</td>
						<td><?php echo ($row['Cashbook']['payment_type'] == 'expense') ? $row['Cashbook']['payment_amount'] : ' '; ?></td>
						<td><?php echo ($row['Cashbook']['payment_type'] == 'income') ? $row['Cashbook']['payment_amount'] : ' '; ?></td>
					</tr>
					<?php
				}
			}
			?>
			</tbody>
			<tfoot>
			<tr>
				<th colspan='3' style="text-align:right">Total:</th>
				<th><?php echo (($paymentType == 'expense') or ($paymentType == '')) ? number_format($totalExpenses, 2, '.', '') : null; ?></th>
				<th><?php echo (($paymentType == 'income') or ($paymentType == '')) ? number_format($totalIncome, 2, '.', '') : null; ?></th>
			</tr>
			<tr>
				<th colspan='3' style="text-align:right"></th>
				<td>Expenses</td>
				<td>Income</td>
			</tr>
			</tfoot>
		</table>
	</div>
	<?php
} else {
	echo 'No records found';
}
?>
