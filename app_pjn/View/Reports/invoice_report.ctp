<?php $this->start('reports_menu'); ?>
<?php echo $this->element('reports_menu'); ?>
<?php $this->end(); ?>

<?php
$title_for_layout = 'Invoice Report';
$this->set('title_for_layout', $title_for_layout);
?>
	<h1><?php echo $title_for_layout; ?></h1>

<?php echo $this->Form->create('Report'); ?>
	<div id="paramsDiv">
		<div style="float:left; clear:none;">
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
<?php echo $this->Form->end(); ?>


<?php
if (isset($invoices)) {
	if (!empty($invoices)) {
		?>
		<table class='table' style="width:100%;">
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Invoice No.</th>
				<th>Purchase Amount</th>
				<th>Invoice Date</th>
				<th>Details</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			foreach ($invoices as $row) {
				$k++;
				$invoice_amt = 0;
				$invoiceTax = $row['Invoice']['tax'];
				if (isset($invoiceAmount[$row['Invoice']['id']])) {
					$invoice_amt = number_format(($invoiceAmount[$row['Invoice']['id']] + $invoiceTax), '2', '.', '');
				}
				?>
				<tr>
					<td><?php echo $k; ?></td>
					<td>
						<?php
						echo $this->Html->link($row['Invoice']['name'], ['controller' => 'invoices', 'action' => 'details', $row['Invoice']['id']], ['title' => 'Invoice Details - ' . $row['Invoice']['name']]);
						?>
					</td>
					<td><?php echo ($invoice_amt > 0) ? $invoice_amt : 0; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
					<td style="width:180px; text-align:center;">
						<?php
						echo $this->Html->link('Normal View', ['controller' => 'reports', 'action' => 'generateInvoiceReport', $row['Invoice']['id'], 'normal'], ['title' => 'Generate Normal View Report - ' . $row['Invoice']['name'], 'target' => '_blank']);
						echo '&nbsp;|&nbsp;';
						echo $this->Html->link('Print View', ['controller' => 'reports', 'action' => 'generateInvoiceReport', $row['Invoice']['id'], 'print'], ['title' => 'Generate Print View Report - ' . $row['Invoice']['name'], 'target' => '_blank']);
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<p> - No Invoices Found</p>
		<?php
	}
}
?>
