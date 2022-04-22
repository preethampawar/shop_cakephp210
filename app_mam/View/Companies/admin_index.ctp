<?php echo $this->element('message');?>
<h1>Companies List</h1>
<br><br>
<?php
if(!empty($companies)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>			
				<th>Sl.No.</th>
				<th><?php echo $this->Paginator->sort('title', 'Company');?></th>				
				<th>Owner</th>
				<th><?php echo $this->Paginator->sort('business_type', 'Account Type');?></th>
				<th><?php echo $this->Paginator->sort('active', 'Status');?></th>
				<th><?php echo $this->Paginator->sort('subscription_end_date', 'Expiry Date');?></th>
				<th><?php echo $this->Paginator->sort('created', 'Created On');?></th>
				<th>Action</th>								
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($companies as $row) {
			$i++;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Company']['title'];?></td>
				<td><?php echo $this->Html->link($row['User']['email'], '/admin/users/edit/'.$row['User']['id']);?></td>
				<td><?php echo Configure::read('BusinessAccounts.'.$row['Company']['business_type']);?></td>
				<td><?php echo ($row['Company']['active']) ? 'Active' : 'InActive';?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Company']['subscription_end_date']));?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Company']['created']));?></td>
				<td>
					<?php
					echo $this->Html->link('Edit', '/admin/companies/edit/'.$row['Company']['id']);
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
					if($row['Company']['id'] != $this->Session->read('Company.id')) {
						echo $this->Html->link('Delete', '/admin/companies/delete/'.$row['Company']['id'], array(), 'This action will delete all information regarding Company, Data and Users associated with this account. This action is irreversable, data once deleted cannot be recovered. Are you sure you want to perform this action?');
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
	echo '&nbsp; No Company Found <br> <br>';	
}
?>