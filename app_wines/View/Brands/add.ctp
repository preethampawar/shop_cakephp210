<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>
<?php echo $this->element('dealers_top_nav_menu');?>

<h1>Add New Brand</h1>
<br>
<div id="AddCategoryDiv" class="well">
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('name', array('placeholder'=>'Enter Brand Name', 'label'=>'Brand Name', 'required'=>true));
	echo $this->Form->submit('Create Brand');
	echo $this->Form->end();
	?>
</div>


<br>
<h1>Recently Added Brands</h1>
<?php if($brands) { ?> 
<table class='table' style="width:100%">
	<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:150px;">Brand Name</th>
			<th style="width:80px;">Created on</th>
			<th style="width:120px;">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		foreach($brands as $row) {
			$i++;
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $this->Html->link($row['Brand']['name'], array('controller'=>'brands', 'action'=>'view', $row['Brand']['id']), array('title'=>'View Brand Details - '.$row['Brand']['name']));?></td>			
			<td><?php echo date('d-m-Y', strtotime($row['Brand']['created']));?></td>
			<td>
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', array('controller'=>'brands', 'action'=>'edit', $row['Brand']['id']), array('title'=>'Edit Brand - '.$row['Brand']['name'], 'class'=>'btn btn-xs btn-warning', 'escape'=>false));?>
				 &nbsp;|&nbsp;
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', array('controller'=>'brands', 'action'=>'remove', $row['Brand']['id']), array('title'=>'Remove Brand - '.$row['Brand']['name'], 'escape'=>false, 'class'=>'btn btn-danger btn-xs'), ' Brand - '.$row['Brand']['name'].':  Deleting this brand will remove all the associated records. Are you sure you want to delete this brand?');?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php } else { ?>
<p>No brands found.</p>
<?php } ?>