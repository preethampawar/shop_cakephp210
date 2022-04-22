<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>
<?php echo $this->element('dealers_top_nav_menu');?>

<h1>Dealer Brands</h1><br>

<div class="well well-xs">
<?php
echo $this->Form->create();
echo $this->Form->input('dealer_id', array('type'=>'select', 'label'=>'Select Dealer', 'required'=>true, 'options'=>$dealers, 'class'=>'autoSuggest form-control', 'multiple'=>'multiple'));
echo $this->Form->submit('Search'); 
echo $this->Form->end();
?>
</div>
<br>
<?php if($brands) { ?> 

<table class='table' style="width:100%">
	<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:150px;">Dealer</th>
			<th style="width:150px;">Brand</th>
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
			<td><?php echo $row['Dealer']['name'];?></td>			
			<td><?php echo $row['Brand']['name'];?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Brand']['created']));?></td>
			<td>				
				<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', array('controller'=>'brands', 'action'=>'removeDealer', $row['Brand']['id']), array('title'=>'Remove Dealer Brand - '.$row['Brand']['name'], 'escape'=>false, 'class'=>'btn btn-danger btn-xs'), 'Are you sure you want to delete this dealer brand?');?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php } else { ?>
<p>No records found.</p>
<?php } ?>