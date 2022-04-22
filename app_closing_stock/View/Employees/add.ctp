<?php $this->start('employees_report_menu'); ?>
<?php echo $this->element('employees_menu'); ?>
<?php echo $this->element('income_expense_report_menu'); ?>
<?php $this->end(); ?>

<h1>Add New Employee</h1>
<br>
<div id="AddCategoryDiv" class="well">
	<?php
	echo $this->Form->create();
	echo $this->Form->input('name', ['placeholder' => 'Enter Employee Name', 'label' => 'Name', 'required' => true]);
	echo $this->Form->input('phone', ['placeholder' => 'Enter Phone No.', 'label' => 'Phone No.']);
	echo $this->Form->input('email', ['placeholder' => 'Enter Email Address', 'label' => 'Email Address']);
	echo $this->Form->input('address', ['placeholder' => 'Enter Address', 'label' => 'Address']);
	echo $this->Form->submit('Create Employee');
	echo $this->Form->end();
	?>
</div>
