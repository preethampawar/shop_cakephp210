<?php echo $this->element('transactions_menu');?>

<h1 class="">Add New Transaction</h1>
<br>

<?= $this->Form->create('Transaction', ['url' => '/transactions/add/']); ?>

<div class="mt-3">
	<label class="form-label">Category * (<a href="/TransactionCategories/add" class="small">+Add New</a>)</label>
	<?php
	if (!empty($categoriesList)) {
		echo $this->Form->select('transaction_category_id', $categoriesList, ['empty' => false, 'class' => 'form-control form-control-sm']);
		?>

		<?php
	} else {
		echo '<div class="text-muted small mt-3">Please create a new category to log transactions.</div>';
		return;
	}
	?>
</div>

<div class="mt-3">
	<label class="form-label">Payment Date *</label>
	<input name="data[Transaction][payment_date]" type="date" class="form-control form-control-sm" value="<?= $this->Session->check('paymentDate') ? $this->Session->read('paymentDate') : date('Y-m-d') ?>" required>
</div>

<div class="mt-3">
	<label class="form-label">Payment Type</label>
	<?= $this->Form->input('payment_type', ['type' => 'select', 'label' => false, 'required' => false, 'empty' => '-', 'options' => ['expense' => 'Expense', 'income' => 'Income'], 'class' => 'form-control form-control-sm']); ?>
</div>

<div class="mt-3">
	<label class="form-label">Amount</label>
	<?= $this->Form->input('payment_amount', ['type' => 'number', 'label' => false, 'required' => false, 'class' => 'form-control form-control-sm', 'default' => 0, 'min' => 1]); ?>
</div>

<div class="mt-3">
	<label class="form-label">Description</label>
	<?= $this->Form->input('description', ['type' => 'text', 'label' => false, 'class' => 'form-control form-control-sm', 'rows' => 2]); ?>
</div>

<div class="mt-4 text-center">
	<button type="submit" class="btn btn-primary btn-sm">Submit</button>
	&nbsp;&nbsp; <a href="/transactions/" class="btn btn-outline-warning btn-sm">Cancel</a>
</div>

<?= $this->Form->end() ?>

<br><br>
