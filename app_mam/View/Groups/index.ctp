<div style="width:800px;">
	<div class="floatLeft">
		<h1>Groups</h1>
	</div>
	<?php echo $this->Html->link('+ Create New Group', array('controller'=>'groups', 'action'=>'add'), array('class'=>'button grey medium floatRight'));?>
	<div class="clear"></div>
	<br>
	<?php	
	if(!empty($groups)) {
		$i=0;
	?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='50'>Sl.No.</th>				
				<th>Group Name</th>				
							
				<th width="150">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($groups as $row)
			{	
				$i++;
			?>		
				<tr>
					<td><?php echo $i;?></td>
					<td style="padding:2px 5px;">
						<?php echo $row['Group']['name'];?>
					</td>
					
					<td>
						<?php
						echo $this->Html->link('Edit', array('controller'=>'groups', 'action'=>'edit/'.$row['Group']['id']), array('title'=>'Edit Group - '.$row['Group']['name']));
						echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo $this->Html->link('Delete', array('controller'=>'groups', 'action'=>'delete/'.$row['Group']['id']), array('title'=>'Delete Group - '.$row['Group']['name']), "Are you sure you want to delete this group - ".$row['Group']['name']."?");
						?>
					</td>					
				</tr>			
			<?php	
			}			
			?>
		</tbody>
	</table>		
	<?php		
	}
	else {			
		echo '&nbsp;No Groups Found.';	
	}
	?>	
</div>
<br><br>
