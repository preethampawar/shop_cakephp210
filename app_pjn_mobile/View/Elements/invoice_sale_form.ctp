<?php
echo $this->Form->create();
echo $this->Form->input('Invoice.invoice_type', ['type' => 'hidden', 'value' => $invoice_type]);
?>

<div class="mt-3">
	<label>Invoice Date</label>
	<input type="date" name="data[Invoice][invoice_date]" class="form-control form-control-sm" value="<?php echo $this->data['Invoice']['invoice_date'] ?? date('Y-m-d');?>" title="Select Date">
</div>

<div class="mt-3">
	<label>Invoice No.</label>
	<?php echo $this->Form->input('Invoice.name', ['label' => false, 'required' => true, 'type' => 'text', 'title' => 'Enter Invoice Name', 'class' => 'form-control input-sm']); ?>
</div>

<?php
if ($hasFranchise) {
	?>
	<div class="mt-3">
		<label>Franchise</label>
		<?php echo $this->Form->input('Invoice.franchise_id', ['label' => false, 'empty' => '-', 'title' => 'Select Franchise', 'options' => $franchiseList, 'type' => 'select', 'class' => 'form-control input-sm']); ?>
	</div>
	<?php
}
?>

<div class="mt-3">
	<label>Discount(%)</label>
	<?php echo $this->Form->input('Invoice.discount', ['label' => false, 'empty' => '-', 'title' => 'Discount in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default' => 0, 'min' => 0, 'max' => 100]); ?>
</div>

<div class="mt-3">
	<label>SGST(%)</label>
	<?php echo $this->Form->input('Invoice.sgst', ['label' => false, 'empty' => '-', 'title' => 'SGST in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default' => 0, 'min' => 0, 'max' => 100]); ?>
</div>

<div class="mt-3">
	<label>CGST(%)</label>
	<?php echo $this->Form->input('Invoice.cgst', ['label' => false, 'empty' => '-', 'title' => 'CGST in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default' => 0, 'min' => 0, 'max' => 100]); ?>
</div>

<div class="mt-3">
	<label>IGST(%)</label>
	<?php echo $this->Form->input('Invoice.igst', ['label' => false, 'empty' => '-', 'title' => 'IGST in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default' => 0, 'min' => 0, 'max' => 100]); ?>
</div>

<div class="mt-3">
	<label>Delivery Charges</label>
	<?php echo $this->Form->input('Invoice.delivery_amount', ['label' => false, 'empty' => '-', 'title' => 'Delivery Charges', 'type' => 'number', 'class' => 'form-control input-sm', 'default' => 0, 'min' => 0, 'max' => 100000]); ?>
</div>

<div class="mt-3">
	<label>Total Invoice Amount</label>
	<?php echo $this->Form->input('Invoice.static_invoice_value', ['label' => false, 'empty' => '-', 'title' => 'Total invoice value including transportation', 'type' => 'number', 'class' => 'form-control input-sm', 'default' => 0, 'min' => 0, 'max' => 10000000000]); ?>
</div>

<div class="mt-4 text-center">
	<button type="submit" class="btn btn-sm btn-purple">Save & Continue</button>
</div>

<?php
echo $this->Form->end();
?>

