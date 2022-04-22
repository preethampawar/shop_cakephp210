<?php $this->start('employees_report_menu');?>
<?php echo $this->element('employees_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<h1>Employees List</h1>
<?php if($employees) { ?>
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
			<th style="width:10px;">S.No</th>
			<th style="width:150px;"><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
			<th style="width:80px;">Phone</th>
			
			<th style="width:150px;">Address</th>
			<th style="width:80px;">Created on</th>
			<th style="width:120px;">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		foreach($employees as $row) {
			$i++;
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $this->Html->link($row['Employee']['name'], array('controller'=>'employees', 'action'=>'view', $row['Employee']['id']), array('title'=>'View Employee Details - '.$row['Employee']['name']));?></td>			
			<td><?php echo $row['Employee']['phone'];?></td>			
					
			<td><?php echo $row['Employee']['address'];?></td>			
			<td><?php echo date('d-m-Y', strtotime($row['Employee']['created']));?></td>
			<td>
				<form method="post" style="" name="remove_employee_<?php echo $row['Employee']['id'];?>" id="remove_employee_<?php echo $row['Employee']['id'];?>" action="<?php echo $this->Html->url("/employees/remove/".$row['Employee']['id']);?>">
				<?php echo $this->Html->link('Edit', array('controller'=>'employees', 'action'=>'edit', $row['Employee']['id']), array('title'=>'Edit Employee - '.$row['Employee']['name'], 'class'=>'small button link yellow'));?>
				 | 
					<input type="submit" value="Remove" name="Remove" onclick="if (confirm('Deleting this Employee will also delete his/her salary information.\n\nAre you sure you want to delete this employee - <?php echo $row['Employee']['name'];?> from the list?')) { $('#remove_employee_<?php echo $row['Employee']['id'];?>').submit(); } event.returnValue = false; return false;"class='small button link red'> 
				<?php //echo $this->Form->postLink('Remove', array('controller'=>'employees', 'action'=>'remove', $row['Employee']['id']), array('title'=>'Remove this employee '.$row['Employee']['name'], 'class'=>'small button link red'), 'Deleting this Employee will also delete his/her salary information.\nAre you sure you want to delete this employee "'.$row['Employee']['name'].'"?');?>
				</form> 
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php } else { ?>
<p>No employee found.</p>
<?php } ?>