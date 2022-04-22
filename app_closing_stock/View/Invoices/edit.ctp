<a href="/invoices/" class="btn btn-sm btn-secondary mb-3">Go to Invoice List</a>
<h1>Edit Invoice - <?php echo $this->data['Invoice']['name']; ?></h1>
<h4>Step-1</h4>
<hr>
<?php
echo $this->Form->create();
?>
<div class="small">
	<div class="mb-3">
		<label for="invoiceDate" class="form-label">Invoice Date</label>
		<input type="date" name="data[Invoice][invoice_date]"
			   value="<?php echo $this->data['Invoice']['invoice_date']; ?>" class="form-control" id="invoiceDate"
			   required>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.name', ['label' => 'Invoice No.', 'required' => true, 'type' => 'text', 'title' => 'Enter Invoice Name', 'class' => "form-control"]); ?>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.mrp_rounding_off', ['label' => 'MRP Rounding Off', 'title' => 'Enter MRP Rounding Off Value', 'default' => 0, 'class' => "form-control"]); ?>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.dd_amount', ['label' => 'DD Amount', 'title' => 'Enter DD Amount', 'required' => true, 'class' => "form-control"]); ?>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.prev_credit', array('label' => 'Previous Credit', 'title' => 'Enter Previous Credit', 'default' => 0, 'class' => 'form-control')); ?>
	</div>
<!--	<div class="mb-3">-->
<!--		--><?php //echo $this->Form->input('Invoice.retail_shop_excise_turnover_tax', ['label' => 'Retail Shop Excise Turnover Tax', 'title' => 'Retail Shop Excise Turnover Tax', 'class' => "form-control"]); ?>
<!--	</div>-->
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.special_excise_cess', ['label' => 'Special Excise Cess', 'title' => 'Special Excise Cess', 'class' => "form-control"]); ?>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.tcs_value', ['label' => 'TCS Value', 'title' => 'Enter TCS Value', 'default' => 0, 'class' => "form-control"]); ?>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.new_retailer_prof_tax', array('label' => 'New Retailer Professional Tax', 'title' => 'Enter New Retailer Professional Tax', 'default' => 0, 'class' => 'form-control')); ?>
	</div>
	<div class="mb-3">
		<?php echo $this->Form->input('Invoice.supplier_id', ['label' => 'Supplier', 'empty' => '-', 'type' => 'text', 'title' => 'Select Supplier', 'options' => $suppliersList, 'type' => 'select', 'class' => "form-select"]); ?>
	</div>
	<button type="submit" class="btn btn-purple btn-md form-control mt-3">Update & Continue</button>

	<div class="text-center mt-4">
		<a href="/invoices/" class="btn btn-sm btn-outline-danger">Cancel</a>
	</div>
</div>
<?php
echo $this->Form->end();
?>
<br><br>

