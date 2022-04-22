<?php $this->start('reports_menu');?>
<?php echo $this->element('reports_menu');?>
<?php $this->end();?>

<h1>Transactions Report</h1>

<?php echo $this->Form->create('Report', ['url' => '/reports/generateTransactionsReport/', 'target'=> '_blank', 'id' => 'IncomeExpensesReport']); ?>

	<div class="mt-3">
		<?php
		$options = ['income' => 'Credit', 'expense' => 'Debit'];
		echo $this->Form->input('payment_type', ['empty' => 'Show All', 'label' => 'Report Type (Credit/Debit)', 'type' => 'select', 'options' => $options, 'escape' => false, 'class' => 'form-control form-control-sm', 'onchange' => '$("#IncomeExpensesReport").removeAttr("target"); $("#IncomeExpensesReport").removeAttr("action"); $("#IncomeExpensesReport").submit();']);
		?>
	</div>
	<div class="mt-3">
		<?php echo $this->Form->input('category_id', ['empty' => 'All', 'label' => 'Select Account', 'type' => 'select', 'options' => $categoriesList, 'escape' => false, 'class' => 'form-control form-control-sm']); ?>
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
