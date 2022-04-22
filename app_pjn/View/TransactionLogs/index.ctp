<?php $this->start('employees_report_menu'); ?>
<?php echo $this->element('counter_balance_sheet_menu'); ?>
<?php echo $this->element('counter_balance_sheet_report_menu'); ?>
<?php echo $this->element('income_expense_report_menu'); ?>
<?php $this->end(); ?>

	<h1>Transaction Logs</h1>
<?php
if ($logs) {
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
			<th>Payment Date</th>
			<th>Payment Type</th>
			<th>Payment Amount</th>
			<th>Tag</th>
			<th>Description</th>
			<th>Created Date</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($logs as $row) {
			$i++;
			?>

			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['TransactionLog']['payment_date'])); ?></td>
				<td><?php echo ($row['TransactionLog']['payment_type'] == 'expense') ? 'Payment Made' : 'Payment Received'; ?></td>
				<td><?php echo $row['TransactionLog']['amount']; ?></td>
				<td><?php echo $tags[$row['TransactionLog']['tag_id']]; ?></td>
				<td><?php echo $row['TransactionLog']['title']; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['TransactionLog']['created'])); ?></td>

				<td>
					<form method="post" style="" name="TransactionLog_<?php echo $row['TransactionLog']['id']; ?>"
						  id="TransactionLog_<?php echo $row['TransactionLog']['id']; ?>"
						  action="<?php echo $this->Html->url("/TransactionLogs/remove/" . $row['TransactionLog']['id']); ?>">
						<input type="submit" value="Remove" name="Remove"
							   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#TransactionLog_<?php echo $row['TransactionLog']['id']; ?>').submit(); } event.returnValue = false; return false;">
					</form>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>


	<?php
	if (count($logs) > 10) {
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
