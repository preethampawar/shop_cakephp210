<?php $this->start('employees_report_menu');?>
<?php echo $this->element('employees_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<?php
if($employeeInfo) {
?>
	<h1>Employee Details (<?php echo $this->Html->link('Edit', array('controller'=>'employees', 'action'=>'edit', $employeeInfo['Employee']['id']), array('title'=>'Edit Employee - '.$employeeInfo['Employee']['name']));?>)</h1>
	<br>
	<p>
	Name: <?php echo $employeeInfo['Employee']['name'];?><br>
	Phone No: <?php echo $employeeInfo['Employee']['phone'];?><br>
	Email: <?php echo $employeeInfo['Employee']['email'];?><br>
	Address: <?php echo $employeeInfo['Employee']['address'];?><br>
	Created on: <?php echo date('d-m-Y', strtotime($employeeInfo['Employee']['created']));?><br>
	</p>
	
	<h2>Salary Information</h2>
	<?php if($employeeInfo['Salary']) { ?>
		<table class='table' style="width:500px;">
			<tr><th>Payment Date</th><th>Payment Amount</th></tr>
			<?php foreach($employeeInfo['Salary'] as $row) {?>
			<tr>
				<td><?php echo date('d-m-Y',  strtotime($row['payment_date']));?></td>
				<td><?php echo $row['payment_amount'];?></td>
			</tr>
			<?php } ?>
		</table>
	
	<?php } else { echo 'No records found'; }  ?>
<?php } else { echo 'Employee not found'; }  ?>