<h1>Cashbook Report</h1>

<?php echo $this->Form->create('Report', ['url' => '/reports/generateCashbookReport/', 'id' => 'IncomeExpensesReport']); ?>

	<div class="mt-3">
		<?php
		$options = ['income' => 'Income', 'expense' => 'Expense'];
		echo $this->Form->input('payment_type', ['empty' => 'Income & Expenses', 'label' => 'Report Type', 'type' => 'select', 'options' => $options, 'escape' => false, 'class' => 'form-select form-select-sm', 'onchange' => '$("#IncomeExpensesReport").removeAttr("target"); $("#IncomeExpensesReport").removeAttr("action"); $("#IncomeExpensesReport").submit();']);
		?>
	</div>
	<div class="mt-3">
		<?php echo $this->Form->input('category_id', ['empty' => 'All', 'label' => 'Select Category', 'type' => 'select', 'options' => $categoriesList, 'escape' => false, 'class' => 'form-select form-select-sm']); ?>
	</div>

	<div class="mt-3">
		<label class="form-label">From Date *</label>
		<input name="data[Report][from_date]" type="date" class="form-control form-control-sm" value="<?= date('Y-m') ?>-01" required>
	</div>
	<div class="mt-3">
		<label class="form-label">To Date *</label>
		<input name="data[Report][to_date]" type="date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
	</div>


	<div class="mt-4">
		<?php echo $this->Form->submit('Generate Report', ['id' => 'SubmitForm', 'title' => '', 'type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return submitButtonMsg()']); ?>
	</div>

<?php
echo $this->Form->end();
?>
