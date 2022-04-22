<?php $this->start('employees_report_menu');?>
<?php echo $this->element('employees_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<h2>Salary Records</h2>
	<?php 
	if($salaries) { 
	?>
	<?php
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page '.$this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		// Shows the next and previous links
		echo '&laquo;'.$this->Paginator->prev('Prev', null, null, array('class' => 'disabled'));
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();
		
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, array('class' => 'disabled')).'&raquo;';
	?>
	<table class='table' style="width:100%">
		<thead>
			<tr>
				<th>S.No</th>
				<th><?php echo $this->Paginator->sort('employee_name', 'Employee'); ?></th>
				<th><?php echo $this->Paginator->sort('payment_amount', 'Payment Amount'); ?></th>
				<th><?php echo $this->Paginator->sort('payment_date', 'Date'); ?></th>
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
					<?php //echo $this->Form->postLink('Remove', array('controller'=>'salaries', 'action'=>'remove', $row['Salary']['id']), array('title'=>'Remove this record for employee '.$row['Salary']['employee_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this record for employee "'.$row['Salary']['employee_name'].'"?');?>				
				</td>
			</tr>
			<?php
			}
			?>			
		</tbody>
	</table>
	<?php
	if(count($salaries) > 10) {
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page '.$this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		// Shows the next and previous links
		echo '&laquo;'.$this->Paginator->prev('Prev', null, null, array('class' => 'disabled'));
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();
		
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, array('class' => 'disabled')).'&raquo;';
	}
	?>
	<?php } else { ?>
	<p>No records found.</p>
	<?php } ?>