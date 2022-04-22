<h1>Companies</h1>
<?php echo $this->Html->link('+ Create New Company', array('controller'=>'companies', 'action'=>'add'), array('class'=>'button grey large floatRight'));?>
<div class="clear"></div>
<br><br>
<?php
if(!empty($companies)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>			
				<th>Sl.No.</th>
				<th><?php echo $this->Paginator->sort('title', 'Company Name');?></th>				
				<th><?php echo $this->Paginator->sort('business_type', 'Account Type');?></th>
				<th><?php echo $this->Paginator->sort('active', 'Status');?></th>
				<th><?php echo $this->Paginator->sort('created', 'Date');?></th>
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
				<td><?php echo ($row['Company']['business_type']) ? Configure::read('BusinessAccounts.'.$row['Company']['business_type']) : '';?></td>
				<td><?php echo ($row['Company']['active']) ? 'Active' : 'InActive';?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Company']['created']));?></td>
				<td>
					<?php
					echo $this->Html->link('Edit', array('controller'=>'companies', 'action'=>'edit/'.$row['Company']['id']));
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
					if($row['Company']['id'] != $this->Session->read('Company.id')) {
						echo $this->Html->link('Delete', array('controller'=>'companies', 'action'=>'delete/'.$row['Company']['id']), array(), 'This action will delete all information regarding Company, Data and Users associated with this account. This action is irreversable, data once deleted cannot be recovered. Are you sure you want to perform this action?');
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
	echo 'No Company Found <br> <br>';
	echo $this->Html->link('Click here to add new company', array('controller'=>'companies', 'action'=>'add'));
}
?>