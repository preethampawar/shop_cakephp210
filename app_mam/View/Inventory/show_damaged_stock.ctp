
<?php echo $this->Html->link('+ Add Damaged Stock', array('controller'=>'inventory', 'action'=>'addDamagedStock'), array('class'=>'button medium grey floatRight'));?>
<h1>Damaged Stock</h1>
<div class="clear"></div>
<br>
<?php
if(!empty($inventory)) {
?>
	<table cellspacing='1' cellpadding='1' style="width:900px;">
		<thead>
			<tr>
				<!-- <th>Sl.No.</th> -->
				<th>Category / Product Name</th>						
				<th width='100'>Stock Damaged</th>				
				<th width='150'><?php echo $this->Paginator->sort('date', 'Date(d-m-Y)');?></th>
				<th width="100">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($inventory as $row) {
				$i++;				
				$stockDamaged = $row['Inventory']['quantity'];
			?>
			<tr>
				<!-- <td width='50'><?php echo $i;?></td> -->
				<td><?php echo $row['Category']['name'];?></td>			
				<td><?php echo $stockDamaged;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Inventory']['date']));?></td>
				
				<td>
					<?php
					if(!empty($row['Data']['id'])) {						
						//$link = '/inventory/editDamagedStock/'.$row['Inventory']['id'].'/'.$row['Data']['id'];
						//echo $this->Html->link('Edit', $link, array('class'=>'button small grey'));
						//echo '&nbsp;&nbsp;&nbsp;'; 
						echo $this->Html->link('Delete', array('controller'=>'inventory', 'action'=>'deleteDamagedStock/'.$row['Inventory']['id'].'/'.$row['Data']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this entry?');
					}
					// else {
						// echo $this->Html->link('Edit', array('controller'=>'inventory', 'action'=>'edit/'.$row['Inventory']['id']), array('class'=>'button small grey'));
						// echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						// echo $this->Html->link('Delete', array('controller'=>'inventory', 'action'=>'delete/'.$row['Inventory']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this entry?');
					// }
					?>
				</td>
				
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<br>
	<?php echo $this->Paginator->prev(' << ' . __('previous'), array(), null, array('class' => 'prev disabled'));?>
	&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->numbers();?>&nbsp;&nbsp;&nbsp;
	<?php echo $this->Paginator->next(__('next').' >>' , array(), null, array('class' => 'next disabled'));?>
<?php	
}
else {
	echo '&nbsp;Data Not Available.<br> <br>';
	// echo ($showAddButton) ?  $this->Html->link('Click here to add new sales record', array('controller'=>'sales', 'action'=>'add')) : '';
}
?>

