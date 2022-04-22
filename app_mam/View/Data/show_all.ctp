<div class="floatLeft adminLeft">
	<div class="floatLeft"><h3>All Articles <?php echo (isset($categoryInfo)) ? ' - '.$categoryInfo['Category']['name'] : '';?></h3></div>
	<div class="floatRight">
		<?php echo $this->Html->link('Add New Article &nbsp;&raquo;', array('controller'=>'posts', 'action'=>'addPage'), array('class'=>'button grey large', 'escape'=>false));?>&nbsp;
	</div>
	<div class="clear"></div>
	<hr>

	<div class="clear"></div>
	<?php
	if(!empty($posts)) {
	?>
		<?php
		echo $this->Html->css('jquerysorter/themes/blue/style');
		echo $this->Html->css('jquerysorter/jquery.tablesorter.pager');

		echo $this->Html->script('jquery.tablesorter.min');
		echo $this->Html->script('jquery.tablesorter.pager');
		?>
		<script type="text/javascript">	
		$(document).ready(function() { 
			$("table") 
			.tablesorter({widthFixed: true, widgets: ['zebra']}) 
			; 
		});
		</script>
		<table cellspacing="1" class="tablesorter">
			<thead>
				<tr>
					<th>No.</th>
					<th>ARTICLE</th>
					<th>STATUS</th>
					<th>CATEGORY</th>
					<th>CREATED</th>
					<th>MANAGE</th>
				</tr>
			</thead>		
			<tbody>
				<?php
				$i=0;
				foreach($posts as $row) {
					$i++;
				?>
				<tr>
					<td width='35' align="center"><?php echo $i;?></td>
					<td>
						<?php 
							echo '<b>'.$row['Post']['title'].'</b><br>';
							echo '<span style="color:green;">'.$this->Html->url('/posts/'.$row['Post']['keyword'], true).'</span>';
						?>
					</td>
					<td width='70' align="center">
						<?php
							if($row['Post']['active']) {
								$link = $this->Html->link('Active', array('controller'=>'posts', 'action'=>'changeStatus', base64_encode($row['Post']['id']), base64_encode(($row['Post']['active']) ? 'inactive' : 'active')));
							}
							else{
								$link = $this->Html->link('InActive', array('controller'=>'posts', 'action'=>'changeStatus', base64_encode($row['Post']['id']), base64_encode(($row['Post']['active']) ? 'inactive' : 'active')));
							}
							echo $link;
						?>
					</td>
					<td width='100' align="center"><?php echo $row['Category']['name'];?></td>
					<td width='100' align="center"><?php echo date('Y-m-d', strtotime($row['Post']['created']));?></td>
					<td width='80' align="center">
						<?php 
							echo $this->Html->link('Edit', array('controller'=>'posts', 'action'=>'editPage/'.base64_encode($row['Post']['id'])));
							echo ' &nbsp;|&nbsp; ';
							echo $this->Html->link('Delete', array('controller'=>'posts', 'action'=>'deletePage/'.base64_encode($row['Post']['id'])), array(), 'Are you sure you want to delete page - '.$i.'?');
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
		echo 'No Articles Found';
	}
	?>
</div>
<?php //echo $this->element('admin_categories_menu');?>

