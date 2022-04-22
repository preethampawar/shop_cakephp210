<?php $this->start('employees_report_menu');?>
<?php echo $this->element('employees_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<h1>Modify Employee Details: <?php echo $employeeInfo['Employee']['name'];?></h1>

<div id="AddCategoryDiv">
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('name', array('placeholder'=>'Enter Employee Name', 'label'=>'Name', 'required'=>true));
	echo $this->Form->input('phone', array('placeholder'=>'Enter Phone No.', 'label'=>'Phone No.'));
	echo $this->Form->input('email', array('placeholder'=>'Enter Email Address', 'label'=>'Email Address'));
	echo $this->Form->input('address', array('placeholder'=>'Enter Address', 'label'=>'Address'));
	echo $this->Form->submit('Modify Employee');
	echo $this->Form->end();
	?>
</div>