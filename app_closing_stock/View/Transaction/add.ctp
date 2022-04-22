<h1 class="">New Transaction</h1>
<div class="text-end mt-3">
	<a href="/transactions/" class="btn btn-warning btn-sm">Cancel</a>
</div>



<?= $this->Form->create('Transaction', ['url' => '/transactions/add/']); ?>

<div class="mt-3">
	<label class="form-label">Category * (<a href="/TransactionCategories/add" class="small">+Add New</a>)</label>
	<?php
	if (!empty($categoriesList)) {
		echo $this->Form->select('transaction_category_id', $categoriesList, ['empty' => false, 'class' => 'form-select form-select-sm']);
		?>

		<?php
	} else {
		echo '<div class="text-muted small mt-3">No category found. Please create a new category to add expenses/income.</div>';
		return;
	}
	?>
</div>

<div class="mt-3">
	<label class="form-label">Payment Date *</label>
	<input name="data[Transaction][payment_date]" type="date" class="form-control form-control-sm" value="<?= $this->Session->check('paymentDate') ? $this->Session->read('paymentDate') : date('Y-m-d') ?>" required>
</div>

<div class="mt-3">
	<label class="form-label">Payment Type *</label>
	<?= $this->Form->input('payment_type', ['type' => 'select', 'label' => false, 'required' => true, 'options' => ['expense' => 'Debit', 'income' => 'Credit'], 'class' => 'form-select form-select-sm']); ?>
</div>

<div class="mt-3">
	<label class="form-label">Amount *</label>
	<?= $this->Form->input('payment_amount', ['type' => 'number', 'label' => false, 'required' => true, 'class' => 'form-control form-control-sm', 'default' => 0, 'min' => 1]); ?>
</div>

<div class="mt-3">
	<label class="form-label">Description</label>
	<?= $this->Form->input('description', ['type' => 'text', 'label' => false, 'class' => 'form-control form-control-sm']); ?>
</div>

<div class="mt-4 text-center">
	<button type="submit" class="btn btn-primary btn-sm">Submit</button>
</div>

<?= $this->Form->end() ?>
