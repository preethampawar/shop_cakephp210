<?php $this->start('employees_report_menu'); ?>
<?php echo $this->element('counter_balance_sheet_menu'); ?>
<?php echo $this->element('counter_balance_sheet_report_menu'); ?>
<?php echo $this->element('income_expense_report_menu'); ?>
<?php $this->end(); ?>

	<h2>Counter Balance Records</h2>
<?php
if ($sheets) {
	?>
	<?php
	// prints X of Y, where X is current page and Y is number of pages
	echo 'Page ' . $this->Paginator->counter();
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';

	// Shows the next and previous links
	echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	// Shows the page numbers
	echo $this->Paginator->numbers();

	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	?>
	<table class='table' style="width:100%">
		<thead>
		<tr>
			<th>#</th>
			<th>From Date</th>
			<th>To Date</th>
			<th>Opening Balance</th>
			<th>Total Sales</th>

			<th>Counter Cash</th>
			<th>By Card</th>
			<th>Expenses</th>
			<th>Closing Balance</th>

			<th>Transaction Balance</th>
			<th>Short</th>
			<th></th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($sheets as $row) {
			$i++;

			$total_avl_value = $row['CounterBalanceSheet']['opening_balance'] + $row['CounterBalanceSheet']['total_sales'];
			$total_exp_value = $row['CounterBalanceSheet']['counter_cash'] + $row['CounterBalanceSheet']['counter_cash_by_card'] + $row['CounterBalanceSheet']['expenses'] + $row['CounterBalanceSheet']['closing_balance'];
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['CounterBalanceSheet']['from_date'])); ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['CounterBalanceSheet']['to_date'])); ?></td>
				<td style="color: green;"><?php echo $row['CounterBalanceSheet']['opening_balance']; ?></td>
				<td style="color: green;"><?php echo $row['CounterBalanceSheet']['total_sales']; ?></td>

				<td style="color: red;"><?php echo $row['CounterBalanceSheet']['counter_cash']; ?></td>
				<td style="color: red;"><?php echo $row['CounterBalanceSheet']['counter_cash_by_card']; ?></td>
				<td style="color: red;"><?php echo $row['CounterBalanceSheet']['expenses']; ?></td>
				<td style="color: red;"><?php echo $row['CounterBalanceSheet']['closing_balance']; ?></td>

				<td style="color: red;"><?php echo $row['CounterBalanceSheet']['transaction_balance']; ?></td>
				<td><b><?php echo $row['CounterBalanceSheet']['short_value']; ?><b></td>
				<td><?php echo $this->Html->link('Details', ['controller' => 'CounterBalanceSheets', 'action' => 'details', $row['CounterBalanceSheet']['id']]); ?></td>

				<td>
					<form method="post" style=""
						  name="CounterBalanceSheet_<?php echo $row['CounterBalanceSheet']['id']; ?>"
						  id="CounterBalanceSheet_<?php echo $row['CounterBalanceSheet']['id']; ?>"
						  action="<?php echo $this->Html->url("/CounterBalanceSheets/remove/" . $row['CounterBalanceSheet']['id']); ?>">
						<input type="submit" value="Remove" name="Remove"
							   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#CounterBalanceSheet_<?php echo $row['CounterBalanceSheet']['id']; ?>').submit(); } event.returnValue = false; return false;">
					</form>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
	if (count($sheets) > 10) {
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page ' . $this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		// Shows the next and previous links
		echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();

		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	}
	?>
<?php } else { ?>
	<p>No records found.</p>
<?php } ?>
