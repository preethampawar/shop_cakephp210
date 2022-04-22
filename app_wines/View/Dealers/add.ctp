<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>
<?php echo $this->element('dealers_top_nav_menu');?>

<h1>Add New Dealer</h1>
<br>
<div id="AddCategoryDiv" class="well">
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('name', array('placeholder'=>'Enter Dealer Name', 'label'=>'Dealer Name', 'required'=>true));
	echo $this->Form->submit('Create Dealer');
	echo $this->Form->end();
	?>
</div>

<br>
<h1>Recently Added Dealers</h1>

<?php if($result) { ?> 
<table class='table search-table' style="width:100%">
	<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:150px;">Dealer Name</th>
			<th style="width:150px;">Brand Name</th>
			<th style="width:150px;">Product Name</th>
			<th style="width:80px;">Dealer Created Date</th>
			<th style="width:120px;">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		foreach($result as $row) {
			$i++;
			
			$dealer_id = $row['d']['id'];
			$dealer_name = $row['d']['name'];
			$dealer_created_date = date('d-m-Y', strtotime($row['d']['created']));
			
			$brand_id = $row['b']['id'];
			$brand_name = $row['b']['name'];
			
			$product_id = $row['p']['id'];
			$product_name = $row['p']['name'];
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $dealer_name;?></td>			
			<td><?php echo $brand_name;?></td>			
			<td><?php echo $product_name;?></td>			
			<td><?php echo $dealer_created_date;?></td>
			<td>
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', array('controller'=>'dealers', 'action'=>'edit', $dealer_id), array('title'=>'Edit Dealer - '.$dealer_name, 'class'=>'btn btn-xs btn-warning', 'escape'=>false));?>
				 &nbsp;|&nbsp;
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', array('controller'=>'dealers', 'action'=>'remove', $dealer_id), array('title'=>'Remove Dealer - '.$dealer_name, 'escape'=>false, 'class'=>'btn btn-danger btn-xs'), ' Dealer - '.$dealer_name.':  Deleting this dealer will remove all the associated records. Are you sure you want to delete this dealer?');?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php } else { ?>
<p>No dealers found.</p>
<?php } ?>