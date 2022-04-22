<?php
$hasFranchise = $this->Session->read('StoreSetting.hasFranchise');
?>

<h1>Add New Invoice</h1>

<div class="mt-3">
	<?php
	echo $this->Form->input('Invoice.invoice_type', [
			'type' => 'select',
			'title' => 'Select Invoice Type',
			'label' => 'Invoice Type',
			'empty' => '--Select--',
			'options' => $invoiceTypes,
			'class' => 'form-control input-sm',
			'onchange' => 'setInvoiceType(this.value)',
			'value' => $invoiceType,
			'disabled' => true
		]
	);
	?>
</div>

<?php
if (isset($invoiceType) && $invoiceType == 'purchase') {
	echo $this->element('invoice_purchase_form', ['invoice_type' => 'purchase', 'franchiseList' => $franchiseList, 'suppliersList' => $suppliersList, 'hasFranchise' => $hasFranchise]);
} elseif (isset($invoiceType) && $invoiceType == 'sale') {
	echo $this->element('invoice_sale_form', ['invoice_type' => 'sale', 'franchiseList' => $franchiseList, 'suppliersList' => $suppliersList, 'hasFranchise' => $hasFranchise]);
}
?>

<br><br>

<script>
	let hasFranchise = '<?php echo $hasFranchise;?>';

	function setInvoiceType(invoiceType) {
		window.location = '/invoices/add/' + invoiceType;
	}
</script>
