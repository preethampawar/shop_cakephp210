<!-- Purchase Invoice Form -->
<?php
echo $this->Form->create();
echo $this->Form->input('Invoice.invoice_type', ['type' => 'hidden', 'value' => $invoice_type]);
?>


<div class="mt-3">
	<label for="InvoiceInvoiceDate">Invoice Date</label>
	<input type="date" id="InvoiceInvoiceDate" name="data[Invoice][invoice_date]" class="form-control form-control-sm" value="<?php echo $this->data['Invoice']['invoice_date'] ?? date('Y-m-d');?>" title="Select Date">
</div>

<div class="mt-3">
	<?php echo $this->Form->input('Invoice.name', ['label' => 'Invoice No.', 'required' => true, 'type' => 'text', 'title' => 'Enter Invoice Name', 'class' => 'form-control input-sm']); ?>
</div>

<div class="mt-3">
	<?php echo $this->Form->input('Invoice.supplier_id', ['label' => 'Supplier', 'empty' => '-', 'title' => 'Select Supplier', 'options' => $suppliersList, 'type' => 'select', 'class' => 'form-control input-sm']); ?>
</div>

<div class="mt-3">
	<?php echo $this->Form->input('Invoice.static_invoice_value', ['label' => 'Total Invoice Amount', 'title' => 'Total Invoice Amount', 'required' => true, 'class' => 'form-control input-sm']); ?>
</div>

<div class="mt-4 text-center">
	<button type="submit" class="btn btn-sm btn-purple">Save & Continue</button>
</div>
<?php
echo $this->Form->end();
?>
