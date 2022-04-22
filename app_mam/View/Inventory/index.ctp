
<?php echo $this->Html->link('+ Add Stock', array('controller'=>'inventory', 'action'=>'add'), array('class'=>'button medium grey floatRight'));?>
<h1>Stock Updates</h1>
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
				<th width='100'>Stock In</th>				
				<th width='100'>Stock Out</th>				
				<th width='100'>Stock Damaged</th>				
				<th width='150'><?php echo $this->Paginator->sort('date', 'Date(d-m-Y)');?></th>
				<th width="150">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($inventory as $row) {
				$i++;
				$stockIn = null;
				$stockOut = null;
				$stockDamaged = null;
				if($row['Inventory']['type'] == 'out')
				{
					if(!empty($row['Data']['id'])) {
						$stockOut = $row['Inventory']['quantity'];
					}
					else {
						$stockOut = $row['Inventory']['quantity'];
					}
				}
				if($row['Inventory']['type'] == 'in') {
					if(!empty($row['Data']['id'])) {
						$stockIn = $row['Inventory']['quantity'];
					}
					else {
						$stockIn = $row['Inventory']['quantity'];
					}
				}
				if($row['Inventory']['type'] == 'damaged') {
					if(!empty($row['Data']['id'])) {
						$stockDamaged = $row['Inventory']['quantity'];
					}
					else {
						$stockDamaged = $row['Inventory']['quantity'];
					}
				}				
			?>
			<tr>
				<!-- <td width='50'><?php echo $i;?></td> -->
				<td><?php echo $row['Category']['name'];?></td>
				<td><?php echo $stockIn;?></td>
				<td><?php echo $stockOut;?></td>
				<td><?php echo $stockDamaged;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Inventory']['date']));?></td>
				
				<td>
					<?php
					
					$damagedStock = false;
					if(isset($row['Inventory']['id']) and !empty($row['Inventory']['id'])) {
						if($row['Inventory']['type'] == 'damaged') {
							$damagedStock = true;
						}
					}
					if($damagedStock) {
						echo $this->Html->link('Edit', array('controller'=>'inventory', 'action'=>'editDamagedStock/'.$row['Inventory']['id'].'/'.$row['Inventory']['data_id']), array('class'=>'button small grey'));
						echo '&nbsp;&nbsp;&nbsp;';						
						echo $this->Html->link('Delete', array('controller'=>'inventory', 'action'=>'deleteDamagedStock/'.$row['Inventory']['id'].'/'.$row['Inventory']['data_id']), array('class'=>'button small grey'), 'Are you sure you want to delete this record?');
					}				
					else {
						if(!empty($row['Data']['id'])) {
							if($row['Data']['business_type'] == 'sale') {
								$link = '/sales/edit/'.$row['Data']['id'];
								$type = 'By Sales';
							}
							elseif($row['Data']['business_type'] == 'purchase') {
								$link = '/purchases/edit/'.$row['Data']['id'];
								$type = 'By Purchase';
							}
							else {
								$link = '/cash/edit/'.$row['Data']['id'];
								$type = 'By Cash';
							}
							echo $this->Html->link('Edit', $link, array('class'=>'button small grey'));
							echo '&nbsp;&nbsp;&nbsp; ['.$type.']';
						}
						else {
							echo $this->Html->link('Edit', array('controller'=>'inventory', 'action'=>'edit/'.$row['Inventory']['id']), array('class'=>'button small grey'));
							echo '&nbsp;&nbsp;&nbsp;&nbsp;';
							echo $this->Html->link('Delete', array('controller'=>'inventory', 'action'=>'delete/'.$row['Inventory']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this entry?');
						}
					}
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

