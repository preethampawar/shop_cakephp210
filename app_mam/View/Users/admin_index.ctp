<?php echo $this->element('message');?>
<div class="floatLeft"><h1>Users List</h1></div>
<?php echo $this->Html->link('Create New User', '/admin/users/add', array('class'=>'button medium grey floatRight'));?>
<div class="clear"></div>
<br><br>
<?php
if(!empty($users)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='200'><?php echo $this->Paginator->sort('name', 'Name');?></th>
				<th width='200'><?php echo $this->Paginator->sort('email', 'Email Address');?></th>
				<th width='50'><?php echo $this->Paginator->sort('active', 'Status');?></th>
				<th width='70'><?php echo $this->Paginator->sort('registered', 'Registered');?></th>
				<th width='200'>Companies</th>
				<th width='100'><?php echo $this->Paginator->sort('created', 'Created on (d-m-Y)');?></th>
				<th width="80">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($users as $row) {
				$i++;				
			?>
			<tr>
				<td><?php echo $row['User']['name'];?></td>
				<td><?php echo $row['User']['email'];?></td>
				<td><?php echo ($row['User']['active']) ? 'Active' : 'InActive';?></td>				
				<td><?php echo ($row['User']['registered']) ? 'Yes' : 'No';?></td>				
				<td>
					
					<?php
					if(!empty($row['UserCompany'])) {
					?>
					<table cellpadding='0' cellspacing='0'>
						
					<?php
						foreach($row['UserCompany'] as $user_company) {														
						?>
						<tr>
							<td>
								<?php 
								echo $this->Html->link($user_company['Company']['title'], '/admin/companies/edit/'.$user_company['Company']['id'], array('class'=>'grey', 'title'=>$user_company['Company']['title']));																
								?>	
							</td>
							<td width='40'>
								<?php 
								if($user_company['Company']['user_id'] == $user_company['User']['id']) {
									echo 'Owner';
								}
								else {
									echo 'Shared';
								}
								?>
							</td>
						</tr>
						<?php								
						}
						?>
					</table>
					<?php
					}
					else
						echo '-';
					?>
					
				</td>
				<td><?php echo date('d-m-Y', strtotime($row['User']['created']));?></td>				
				<td>
					<?php
					echo $this->Html->link('Edit', array('controller'=>'users', 'action'=>'edit/'.$row['User']['id']), array('class'=>'button small grey', 'title'=>$row['User']['name']));
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
					echo $this->Html->link('Delete', array('controller'=>'users', 'action'=>'delete/'.$row['User']['id']), array('class'=>'button small grey'), 'Deleting a User Account will delete all user registered companies and all the information(Sales, Purchases, Cash records, Inventory, Groups, .. etc.) associated with the User Account. This action is irreversable. Are you sure you want to delete this record. "'.$row['User']['name'].'" ?');
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
	echo 'No User Records <br> <br>';
}
?>
