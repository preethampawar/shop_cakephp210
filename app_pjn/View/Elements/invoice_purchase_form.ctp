<!-- Purchase Invoice Form -->
<?php
echo $this->Form->create();
echo $this->Form->input('Invoice.invoice_type', ['type' => 'hidden', 'value' => $invoice_type]);
?>
<br>
<div class="row">
	<div class="col-xs-6">
		<?php echo $this->Form->input('Invoice.invoice_date', ['label' => 'Invoice Date', 'required' => true, 'type' => 'date', 'title' => 'Select date']); ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-3">
		<?php echo $this->Form->input('Invoice.name', ['label' => 'Invoice No.', 'required' => true, 'type' => 'text', 'title' => 'Enter Invoice Name', 'class' => 'form-control input-sm']); ?>
	</div>
	<div class="col-xs-3">
		<?php echo $this->Form->input('Invoice.supplier_id', ['label' => 'Supplier', 'empty' => '-', 'title' => 'Select Supplier', 'options' => $suppliersList, 'type' => 'select', 'class' => 'form-control input-sm']); ?>
	</div>
	<div class="col-xs-3">
		<?php echo $this->Form->input('Invoice.static_invoice_value', ['label' => 'Total Invoice Amount', 'title' => 'Total Invoice Amount', 'required' => true, 'class' => 'form-control input-sm']); ?>
	</div>
</div>

<div class="row">
	<div class="col-xs-4">
		<button type="submit" class="btn btn-sm btn-primary">Save Invoice</button>
	</div>
</div>
<?php
echo $this->Form->end();
?>
