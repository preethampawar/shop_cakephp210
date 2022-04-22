<?php
if ($viewType != 'download') {
	?>
	<?php $this->start('reports_menu'); ?>
	<?php echo $this->element('reports_menu'); ?>
	<?php $this->end(); ?>

	<?php
	$title_for_layout = 'Transaction Log Report';
	$this->set('title_for_layout', $title_for_layout);
	?>
	<h1><?php echo $title_for_layout; ?></h1>
	<?php
	if ($showForm) {
		?>
		<?php
		echo $this->Form->create('Report');
		?>
		<div id="paramsDiv">
			<div style="float:left; clear:none;">
				<?php
				$options = ['print' => 'Print View', 'download' => 'Download'];
				echo $this->Form->input('view_type', ['empty' => 'Normal View', 'label' => 'Download/Select View', 'type' => 'select', 'options' => $options, 'escape' => false]);
				?>
			</div>

			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('payment_type', ['label' => 'Payment Type', 'empty' => '-- All --', 'type' => 'select', 'options' => ['expense' => 'Payment Made', 'income' => 'Payment Received'], 'default' => 'expense']); ?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('tag_id', ['label' => 'Tag', 'empty' => '-- All --', 'type' => 'select', 'options' => $tags, 'class' => 'autoSuggest']); ?>
			</div>

			<div style="float:left; clear:both;">
				<?php
				echo $this->Form->input('from_date', ['label' => 'From Date', 'required' => true, 'type' => 'date', 'default' => date('Y-m-d', strtotime('-1 months'))]); ?>
			</div>
			<div style="float:left; clear:none; margin-left:10px; ">
				<?php echo $this->Form->input('to_date', ['label' => 'To Date', 'required' => true, 'type' => 'date']); ?>
			</div>
			<div style="float:left; clear:none; padding-top:15px;margin-left:10px; ">
				<?php echo $this->Form->submit('Search', ['id' => 'SubmitForm', 'title' => '', 'type' => 'submit', 'onclick' => 'return submitButtonMsg()']); ?>
			</div>
			<div style="clear:both;"></div>
		</div>
		<?php
		echo $this->Form->end();
		?>
		<?php
	}
	?>

	<?php
	if (isset($logs) and !empty($logs)) {
		?>
		<table class='table' style="width:100%">
			<thead>
			<tr>
				<th>#</th>
				<th>Payment Date</th>
				<th>Tag</th>
				<th>Description</th>
				<th>Payments Made</th>
				<th>Payments Received</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			$total_payments_made = 0;
			$total_payments_received = 0;

			foreach ($logs as $row) {
				$i++;

				$payments_made = ($row['TransactionLog']['payment_type'] == 'expense') ? $row['TransactionLog']['amount'] : 0;
				$payments_received = ($row['TransactionLog']['payment_type'] == 'income') ? $row['TransactionLog']['amount'] : 0;

				$total_payments_made += $payments_made;
				$total_payments_received += $payments_received;
				?>

				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['TransactionLog']['payment_date'])); ?></td>
					<td><?php echo $tags[$row['TransactionLog']['tag_id']]; ?></td>
					<td><?php echo $row['TransactionLog']['title']; ?></td>
					<td style="text-align:right;"><?php echo ($row['TransactionLog']['payment_type'] == 'expense') ? $row['TransactionLog']['amount'] : '-'; ?></td>
					<td style="text-align:right;"><?php echo ($row['TransactionLog']['payment_type'] == 'income') ? $row['TransactionLog']['amount'] : '-'; ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan='4' style="text-align:right; font-weight:bold">Total:</td>

				<td style="text-align:right; font-weight:bold"><?php echo $total_payments_made; ?></td>
				<td style="text-align:right; font-weight:bold"><?php echo $total_payments_received; ?></td>
			</tr>
			</tfoot>
		</table>
		<?php
	} else if ($formSubmitted) {
		echo '<p> - No Records Found</p>';
	}
} else {
	// generate report in csv format
	$csv = 'Transaction Log Report: From ' . date('d M Y', strtotime($fromDate)) . ' to ' . date('d M Y', strtotime($toDate)) . "\r\n";
	$csv .= "\r\n";
	if (!empty($logs)) {
		$csv .= implode(['#', 'Payment Date', 'Tag', 'Description', 'Payments Made', 'Payments Received'], ",") . "\r\n";
		$k = 0;
		$total_payments_made = 0;
		$total_payments_received = 0;

		foreach ($logs as $row) {
			$k++;

			$payment_date = date('d-m-Y', strtotime($row['TransactionLog']['payment_date']));
			$tag = $tags[$row['TransactionLog']['tag_id']];
			$description = $row['TransactionLog']['title'];
			$payments_made = ($row['TransactionLog']['payment_type'] == 'expense') ? $row['TransactionLog']['amount'] : 0;
			$payments_received = ($row['TransactionLog']['payment_type'] == 'income') ? $row['TransactionLog']['amount'] : 0;

			$total_payments_made += $payments_made;
			$total_payments_received += $payments_received;

			$tmp = [];
			$tmp[] = $k;
			$tmp[] = " " . $payment_date . " ";
			$tmp[] = $tag;
			$tmp[] = $description;
			$tmp[] = $payments_made;
			$tmp[] = $payments_received;

			$csv .= implode($tmp, ',') . "\r\n";
		}
		$tmp = [];
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = '';
		$tmp[] = 'Total: ';
		$tmp[] = $total_payments_made;
		$tmp[] = $total_payments_received;
		$csv .= implode($tmp, ',') . "\r\n";
	} else {
		$csv .= 'No records found';
	}

	echo $csv;
}
?>
