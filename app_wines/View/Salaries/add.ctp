<?php $this->start('employees_report_menu');?>
<?php echo $this->element('employees_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<p><?php echo $this->Html->link('Cancel', array('controller'=>'salaries', 'action'=>'index'), array('title'=>'Go back to Salary list'));?></p>
<h1>Make Salary Payment</h1>

<?php
if($employeesList) {
?>
	<div id="AddSalaryDiv">
		<?php 
		echo $this->Form->create();
		?>
		<div id="paramsDiv">			
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('employee_id', array('empty'=>false, 'label'=>'Select Employee', 'required'=>true, 'type'=>'select', 'options'=>$employeesList, 'autofocus'=>true, 'escape'=>false));?>
			</div>
			<div style="float:left; clear:none;">
				<?php 				
				echo $this->Form->input('payment_amount', array('type'=>'text', 'label'=>'Payment Amount', 'required'=>true, 'title'=>'Payment Amount'));
				?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('payment_date', array('label'=>'Salary Date', 'required'=>true, 'type'=>'date'));?>
			</div>
			<div style="float:left; clear:none; padding-top:5px;">
				<br>
				&nbsp;&nbsp;<?php echo $this->Form->submit('Add Salary', array('id'=>'SubmitForm', 'type'=>'submit', 'div'=>false));?>
			</div>				
			<div style="clear:both; margin:0; padding:0;"></div>
		</div>
		<?php		
		echo $this->Form->end();
		?>				
	</div>
	
	<h2>Recent records</h2>
	<?php 
	if($salaries) { 
	?>
	<table class='table' style="width:100%">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Employee</th>
				<th>Payment Amount</th>
				<th>Salary Date</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($salaries as $row) {
				$i++;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $this->Html->link($row['Salary']['employee_name'], array('controller'=>'employees', 'action'=>'view', $row['Salary']['employee_id']), array('title'=>'Click to get Employee details'));?></td>
				<td><?php echo $row['Salary']['payment_amount'];?></td>				
				<td><?php echo date('d-m-Y', strtotime($row['Salary']['payment_date']));?></td>
				<td>
					<form method="post" style="" name="salary_<?php echo $row['Salary']['id'];?>" id="salary_<?php echo $row['Salary']['id'];?>" action="<?php echo $this->Html->url("/salaries/remove/".$row['Salary']['id']);?>">
						<input type="submit" value="Remove" name="Remove" onclick="if (confirm('Are you sure you want to delete this record of employee - <?php echo $row['Salary']['employee_name'];?> from the list?')) { $('#salary_<?php echo $row['Salary']['id'];?>').submit(); } event.returnValue = false; return false;"> 
					</form>
					<?php //echo $this->Form->postLink('Remove', array('controller'=>'salaries', 'action'=>'remove', $row['Salary']['id']), array('title'=>'Remove this record of - '.$row['Salary']['employee_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this record of employee "'.$row['Salary']['employee_name'].'"?');?>				
				</td>
			</tr>
			<?php
			}
			?>			
		</tbody>
	</table>
	
	<?php } else { ?>
	<p>No salaries found</p>
	<?php } ?>
	
<?php
}
else {
	echo 'No employees found. You need to add employees to continue.';
}
?>