<?php
$inventory = false;
if($this->Session->check('UserCompany')) {	
	switch($this->Session->read('Company.business_type')) {
		case 'personal':			
			break;			
		case 'general':
			break;			
		case 'inventory':
			$inventory = true;
			break;			
		case 'wineshop':
			$inventory = true;
			break;			
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>

<?php echo $this->element('message');?>

<?php echo $this->Html->link('+ Add New Cash Record', array('controller'=>'cash', 'action'=>'add'), array('class'=>'button medium grey floatRight'));?>
<h1>Cash Book</h1>
<div class="clear"></div>

<br>
<?php
if(!empty($cash)) {
?>	
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<!-- <th width='30'>Sl.No.</th> -->
				<th><?php echo $this->Paginator->sort('category_name', 'Category');?></th>
				<th><?php echo $this->Paginator->sort('particular', 'Particular');?></th>
				<?php echo ($inventory) ? "<th width='60'>Quantity</th>" : null;?>
				<?php echo ($inventory) ? "<th width='80'>Unit Rate</th>" : null;?>
				<th width='130'><?php echo $this->Paginator->sort('total_amount', 'Total Amount');?></th>
				<th width='130'>Amount Paid (Dr.)</th>
				<th width='150'>Amount Received (Cr.)</th>
				<th width='130'><?php echo $this->Paginator->sort('pending_amount', 'Pending Amount');?></th>
				<th width='100'><?php echo $this->Paginator->sort('date', 'Date');?></th>
				<th width="100">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;			
			foreach($cash as $row) {
				$i++;
				$title = $row['Cash']['category_name'].': '.$row['Cash']['particular'];		
				$qty = 	($row['Cash']['quantity']) ? $row['Cash']['quantity'] : '-';
				$unitrate = ($row['Cash']['unitrate'] > 0) ? $this->Number->currency($row['Cash']['unitrate'], $CUR) : '-';
			?>
			<tr>
				<!-- <td><?php echo $i;?></td> -->
				<td><?php echo $row['Cash']['category_name'];?></td>
				<td>
					<?php 
						echo $row['Cash']['particular'];
						$tmp = array();
						if(!empty($row['Group'])) {
							foreach($row['Group'] as $group) {
								$tmp[] = $group['name'];
							}
							$tmp = implode(', ', $tmp);
							//echo ($tmp) ? '<br>('.$tmp.')' : '';
						}
						if($tmp) {
							echo '<br><span style="color:#ff0000; font-size:12px;">';
							echo $tmp;
							echo '</span>';
						}
					?>
				</td>				
				<?php echo ($inventory) ? "<td>$qty</td>" : null?>
				<?php echo ($inventory) ? "<td>$unitrate</td>" : null?>
				<td><?php echo ($row['Cash']['total_amount'] > 0) ? $this->Number->currency($row['Cash']['total_amount'], $CUR) : '-'; ?></td>			
				
				<td class="debit"><?php echo ($row['Cash']['transaction_type'] == 'debit') ? $this->Number->currency($row['Cash']['payment_amount'], $CUR) : '-';?></td>
				<td class="credit"><?php echo ($row['Cash']['transaction_type'] == 'credit') ? $this->Number->currency($row['Cash']['payment_amount'], $CUR) : '-';?></td>				
				
				<td><?php echo ($row['Cash']['pending_amount'] > 0) ? $this->Number->currency($row['Cash']['pending_amount'], $CUR) : '-';?></td>	
				
				<td><?php echo date('d-m-Y', strtotime($row['Cash']['date']));?></td>
				
					
				<td>
					<?php
					$damagedStock = false;
					if(isset($row['Inventory']['id']) and !empty($row['Inventory']['id'])) {
						if($row['Inventory']['type'] == 'damaged') {
							$damagedStock = true;
						}
					}
					if(!$damagedStock) {
						echo $this->Html->link('Edit', array('controller'=>'cash', 'action'=>'edit/'.$row['Cash']['id']), array('class'=>'button small grey', 'title'=>$title));
						echo '&nbsp;&nbsp;&nbsp;';						
					}
					else {
						echo $this->Html->link('Edit', '/inventory/editDamagedStock/'.$row['Inventory']['id'].'/'.$row['Inventory']['data_id'], array('class'=>'button small grey'));
						echo '&nbsp;&nbsp;&nbsp;'; 
					}
					echo $this->Html->link('Delete', array('controller'=>'cash', 'action'=>'delete/'.$row['Cash']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this record. "'.$title.'" ?');
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
	//echo ($showAddButton) ? $this->Html->link('Click here to add new cash record', array('controller'=>'cash', 'action'=>'add')) : '';
}
?>