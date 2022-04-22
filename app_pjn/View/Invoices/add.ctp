<?php
$this->start('invoices_report_menu');
echo $this->element('invoices_menu');
echo $this->element('sales_purchases_report_menu');
$this->end();
$hasFranchise = $this->Session->read('StoreSetting.hasFranchise');
?>

<h1>Create New Invoice</h1><br>
<div class="well well-sm">
	<?php
	echo $this->Form->create('InvoiceType', ['url' => '/invoices/add']);
	?>
	<div class="row">
		<div class="col-xs-3">
			<?php
			echo $this->Form->input('Invoice.invoice_type', [
					'type' => 'select',
					'title' => 'Select Invoice Type',
					'label' => 'Select Invoice Type',
					'empty' => '-',
					'options' => $invoiceTypes,
					'class' => 'form-control input-sm',
					'onchange' => 'setInvoiceType(this.value)',
				]
			);
			?>
		</div>
	</div>
	<?php
	echo $this->Form->end();
	?>

	<?php
	if (isset($this->data['Invoice']) && $this->data['Invoice']['invoice_type'] == 'purchase') {
		echo $this->element('invoice_purchase_form', ['invoice_type' => 'purchase', 'franchiseList' => $franchiseList, 'suppliersList' => $suppliersList, 'hasFranchise' => $hasFranchise]);
	} else if (isset($this->data['Invoice']) && $this->data['Invoice']['invoice_type'] == 'sale') {
		echo $this->element('invoice_sale_form', ['invoice_type' => 'sale', 'franchiseList' => $franchiseList, 'suppliersList' => $suppliersList, 'hasFranchise' => $hasFranchise]);
	}
	?>
</div>
<br>

<script>
	let hasFranchise = '<?php echo $hasFranchise;?>';

	function setInvoiceType(invoiceType) {
		$('#InvoiceTypeAddForm').submit()
	}
</script>
