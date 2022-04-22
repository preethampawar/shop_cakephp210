<?php // echo $this->element('/sites/admin_menu');
// debug($sites);
?>


<div>
	<h3>List of sites</h3>
	<?php
	if(!empty($sites))
	{
	?>
	<table class='table'>
		<thead>
			<tr>
				<th style='width:15px;'>ID</th>
				<th style='width:150px;'>Name</th>			
				<th>Title</th>			
				<th style='width:250px;'>Site Domains</th>
				<th style='width:100px;'>Created on</th>
				<th style='width:60px;'>Actions</th>
			</tr>
		</thead>
		<tbody>	
			<?php
			foreach($sites as $row)
			{		
			?>
			<tr>
				<td><?php echo $row['Site']['id'];?></td>
				<td><?php echo $row['Site']['name'];?></td>
				<td>
					<?php echo $row['Site']['title'];?>
					<br/>
					<span class='note'><?php echo $row['Site']['caption'];?></span>
				</td>
				
				
				<td>
					<?php 
					if(!empty($row['Domain'])) {
						foreach ($row['Domain'] as $domain) {
						?>
						<div><?php echo ($domain['default']) ? '<strong>'.$domain['name'].'</strong>' : $domain['name'];?></div>	
						<?php
						}
					}
					else {
						echo '-';
					}
					?>
				</td>
				<td><?php echo date('d-m-Y', strtotime($row['Site']['created']));?></td>
				<td>
					<?php 
						echo $this->Html->link( __('Edit', true), array('controller'=>'sites', 'action'=>'edit', $row['Site']['id']));
					
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
	?>
</div>	