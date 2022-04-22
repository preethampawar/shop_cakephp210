<?php echo $this->element('message');?>

<?php echo $this->Html->link('+ Create New Transaction', array('controller'=>'transactions', 'action'=>'add'), array('class'=>'button medium grey floatRight'));?>
<h1>Transactions</h1>
<div class="clear"></div>

<br>
<?php
if(!empty($transactions)) {
?>	
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('category_name', 'Category');?></th>
				<th><?php echo $this->Paginator->sort('particular', 'Transaction Details');?></th>
				<th width='130'>Expense</th>
				<th width='150'>Income</th>
				<th width='100'><?php echo $this->Paginator->sort('date', 'Date (d-m-Y)');?></th>
				<th width="100">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;			
			foreach($transactions as $row) {
				$i++;
				$title = $row['Transaction']['category_name'].': '.$row['Transaction']['particular'];				
			?>
			<tr>				
				<td><?php echo $row['Transaction']['category_name'];?></td>
				<td><?php echo $row['Transaction']['particular'];?></td>				
				<td class="debit"><?php echo ($row['Transaction']['transaction_type'] == 'debit') ? $this->Number->currency($row['Transaction']['payment_amount'], $CUR) : '-';?></td>
				<td class="credit"><?php echo ($row['Transaction']['transaction_type'] == 'credit') ? $this->Number->currency($row['Transaction']['payment_amount'], $CUR) : '-';?></td>				
				<td><?php echo date('d-m-Y', strtotime($row['Transaction']['date']));?></td>
				<td>
					<?php
					echo $this->Html->link('Edit', array('controller'=>'transactions', 'action'=>'edit/'.$row['Transaction']['id']), array('class'=>'button small grey', 'title'=>$title));
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
					echo $this->Html->link('Delete', array('controller'=>'transactions', 'action'=>'delete/'.$row['Transaction']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this record. "'.$title.'" ?');
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
}
?>