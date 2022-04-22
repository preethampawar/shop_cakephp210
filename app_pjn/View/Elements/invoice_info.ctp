<p style="text-align:left;"><?php echo $this->Html->link('&laquo; Back to Invoice List', ['controller' => 'invoices', 'action' => 'index'], ['title' => 'Go back to Invoices list', 'style' => 'font-weight:bold;', 'escape' => false]); ?></p>
<h1><?php echo ucwords($invoiceInfo['Invoice']['invoice_type']) ?>
	Invoice: <?php echo $invoiceInfo['Invoice']['name']; ?></h1>
<br>
<?php
$hasFranchise = $this->Session->read('StoreSetting.hasFranchise');
if ($invoiceInfo['Invoice']['invoice_type'] == 'sale') {
	?>
	<table class="table table-stripped">
		<thead>

		<tr>
			<th>Invoice No</th>
			<?php echo $hasFranchise ? '<th>Franchise</th>' : null; ?>
			<th>Invoice Date</th>
			<th>Total Invoice Amount</th>
			<th>Total Sale Value</th>
			<th>Discount (%)</th>
			<th>SGST (%)</th>
			<th>CGST (%)</th>
			<th>IGST (%)</th>
			<th>Delivery Charges</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><?php echo $invoiceInfo['Invoice']['name']; ?></td>
			<?php echo $hasFranchise ? ('<td>' . $invoiceInfo['Invoice']['franchise_name'] . '</td>') : '-'; ?>
			<td><?php echo date('d-m-Y', strtotime($invoiceInfo['Invoice']['invoice_date'])); ?></td>
			<td><?php echo $invoiceInfo['Invoice']['static_invoice_value']; ?></td>
			<td><?php echo $invoiceInfo['Invoice']['invoice_value']; ?> </td>
			<td><?php echo $invoiceInfo['Invoice']['discount']; ?></td>
			<td><?php echo $invoiceInfo['Invoice']['sgst']; ?></td>
			<td><?php echo $invoiceInfo['Invoice']['cgst']; ?></td>
			<td><?php echo $invoiceInfo['Invoice']['igst']; ?></td>
			<td><?php echo $invoiceInfo['Invoice']['delivery_amount']; ?></td>
		</tr>
		</tbody>
	</table>
	<?php
} else {
	?>
	<table class="table table-stripped">
		<thead>

		<tr>
			<th>Invoice No</th>
			<th>Invoice Date</th>
			<th>Total Invoice Amount</th>
			<th>Supplier</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><?php echo $invoiceInfo['Invoice']['name']; ?></td>
			<td><?php echo date('d-m-Y', strtotime($invoiceInfo['Invoice']['invoice_date'])); ?></td>
			<td><?php echo $invoiceInfo['Invoice']['static_invoice_value']; ?></td>
			<td><?php echo $invoiceInfo['Invoice']['supplier_name']; ?></td>
		</tr>
		</tbody>
	</table>
	<?php
}
?>
