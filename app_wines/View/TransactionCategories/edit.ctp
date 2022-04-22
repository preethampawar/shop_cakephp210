<?php $this->start('transactions_menu');?>
<?php echo $this->element('transactions_menu');?>
<?php $this->end();?>

<p class="text-end"></p>
<h1>Edit Account</h1>


<div id="AddCategoryDiv">
	<?php echo $this->Form->create(); ?>
	<?php echo $this->Form->input('active', ['type' => 'checkbox', 'label' => 'Active', 'class' => 'my-3']); ?>
	<?php echo $this->Form->input('show_in_balance_sheet', ['type' => 'checkbox', 'label' => 'Show in Balance Sheet', 'class' => 'my-3']); ?>
	<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => 'Account Name', 'required' => true, 'class' => 'form-control form-control-sm']); ?>
	<?php echo $this->Form->input('priority', ['type' => 'number', 'placeholder' => 'Enter Priority Number', 'label' => 'Priority (1, 2, 3, ..etc. )', 'class' => 'form-control form-control-sm']); ?>
	<?php // echo $this->Form->submit('Update', ['class' => 'btn btn-primary btn-sm mt-3']); ?>
	<br>
	<button type="submit" class="btn btn-sm btn-primary">Update</button>
	&nbsp; <a href="/TransactionCategories/" class="btn btn-warning btn-sm">Cancel</a>
	<?php echo $this->Form->end(); ?>
</div>
