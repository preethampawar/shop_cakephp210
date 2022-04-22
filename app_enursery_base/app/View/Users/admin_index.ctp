<div>
	<h3>List of Users</h3>
	<?php //echo $this->Html->link('Create New Account &raquo;', array('controller'=>'users', 'action'=>'add'), array('escape'=>false));?>
	
	<?php
	if(!empty($users))
	{
	?>
	<table class='table'>
		<thead>
			<tr>
				<th style='width:20px;'>ID</th>
				<th>Name</th>			
				<th style='width:250px;'>Email</th>			
				<th style='width:150px;'>Site Name</th>			
				<th style='width:70px;'>Status</th>
				<th style='width:100px;'>Created on</th>
				<th style='width:120px;'>Actions</th>
			</tr>
		</thead>
		<tbody>	
			<?php
			foreach($users as $row)
			{		
			?>
			<tr>
				<td><?php echo $row['User']['id'];?></td>
				<td><?php echo $row['User']['name'];?></td>
				<td><?php echo $row['User']['email'];?></td>				
				<td><?php echo $row['Site']['name'];?></td>				
				<td><?php echo ($row['User']['active']) ? 'Active' : 'Inactive';?></td>				
				
				<td><?php echo date('d-m-Y', strtotime($row['User']['created']));?></td>
				<td>
					<?php echo $this->Html->link( __('Details', true), array('controller'=>'users', 'action'=>'userInfo', $row['User']['id']));?>
					&nbsp;|&nbsp;	
					<?php echo $this->Html->link( __('Edit', true), array('controller'=>'users', 'action'=>'edit', $row['User']['id']));?>	
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
	?>
</div>	