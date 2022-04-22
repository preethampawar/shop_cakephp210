<?php
$this->start('invoices_report_menu');
echo $this->element('invoices_menu');
echo $this->element('sales_purchases_report_menu');
$this->end();

$hasFranchise = $this->Session->read('StoreSetting.hasFranchise');
?>

<h1>Edit Invoice</h1><br>
<div class="well">
	<div class="row">
		<div class="col-xs-3">
			<?php
			echo $this->Form->input('Invoice.invoice_type', [
					'type' => 'select',
					'title' => 'Invoice Type',
					'label' => 'Invoice Type',
					'empty' => '-',
					'options' => $invoiceTypes,
					'class' => 'form-control input-sm',
					'readonly' => true,
					'disabled' => true,
				]
			);
			?>
		</div>
	</div>

	<?php
	if (isset($this->data['Invoice']) && $this->data['Invoice']['invoice_type'] == 'purchase') {
		echo $this->element('invoice_purchase_form', ['invoice_type' => 'purchase', 'franchiseList' => $franchiseList, 'suppliersList' => $suppliersList, 'hasFranchise' => $hasFranchise]);
	} else if (isset($this->data['Invoice']) && $this->data['Invoice']['invoice_type'] == 'sale') {
		echo $this->element('invoice_sale_form', ['invoice_type' => 'sale', 'franchiseList' => $franchiseList, 'suppliersList' => $suppliersList, 'hasFranchise' => $hasFranchise]);
	}

	echo '<br>';
	echo $this->Html->link('Cancel', ['controller' => 'invoices', 'action' => 'index'], ['class' => 'btn btn-danger btn-xs']);
	?>
</div>
