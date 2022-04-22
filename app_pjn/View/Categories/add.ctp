<?php $this->start('cashbook_menu'); ?>
<?php echo $this->element('cashbook_menu'); ?>
<?php $this->end(); ?>


<h1>Cashbook - Add New Category</h1> <br>

<div id="AddCategoryDiv" class="well">
	<?php echo $this->Form->create('Category', ['url' => '/categories/add/']); ?>

	<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => 'Category Name', 'required' => true]); ?>
	<?php echo $this->Form->input('expense', ['type' => 'checkbox', 'label' => 'Expense']); ?>
	<?php echo $this->Form->input('income', ['type' => 'checkbox', 'label' => 'Income']); ?>

	<button class="btn btn-primary btn-sm" type="submit">Create Category</button>
	<br><br><br>
	<?php echo $this->Html->link("Cancel", ['controller' => 'categories', 'action' => 'index'], ['escape' => false, 'class' => 'btn btn-warning btn-sm']); ?>
	<?php echo $this->Form->end(); ?>
</div>
