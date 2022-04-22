

<h1>Cash Book - Add New Category</h1> <br>

<div id="AddCategoryDiv" class="well">
	<?php echo $this->Form->create('Category', array('url'=>'/categories/add/'));?>
		
			<?php echo $this->Form->input('name', array('placeholder'=>'Enter Category Name', 'label'=>'Category Name', 'required'=>true)); ?>
		
			<?php echo $this->Form->input('expense', array('type'=>'checkbox', 'label'=>'Expense')); ?>
			<?php echo $this->Form->input('income', array('type'=>'checkbox', 'label'=>'Income')); ?>
		
			<?php echo $this->Form->submit('Create Category');?>
		
	<?php echo $this->Form->end();?>
</div>
<br>
<h2>Categories List</h2>
<?php if($categories) { ?>
<table class='table'>
	<thead>
		<tr>
			<th>S.No</th>
			<th>Category Name</th>
			<th>Type</th>
			<th>Created on</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		foreach($categories as $row) {
			$i++;
			$tmp = array();
			if($row['Category']['income']) {
				$tmp[] = 'Income';
			}
			if($row['Category']['expense']) {
				$tmp[] = 'Expense';
			}
			$type = implode(', ',$tmp);
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td>
				<?php 
				//echo $row['Category']['name'];
				echo $this->Html->link($row['Category']['name'], array('controller'=>'cashbook', 'action'=>'index', $row['Category']['id']), array('title'=>'Add records in "'.$row['Category']['name'].'" category'));
				?> 			
			</td>			
			<td><?php echo $type;?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Category']['created']));?></td>
			<td>
				<form method="post" style="" name="categories_form_<?php echo $row['Category']['id'];?>" id="categories_form_<?php echo $row['Category']['id'];?>" action="<?php echo $this->Html->url("/categories/delete/".$row['Category']['id']);?>">
					<a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this category - <?php echo $row['Category']['name'];?>?')) { $('#categories_form_<?php echo $row['Category']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-danger btn-xs">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</a>  
					&nbsp;&nbsp;
					<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', array('controller'=>'categories', 'action'=>'edit', $row['Category']['id']), array('title'=>'Edit Category - '.$row['Category']['name'], 'class'=>'btn btn-warning btn-xs', 'escape'=>false));?>
										
						
					<?php //echo $this->Form->postLink('Remove', array('controller'=>'categories', 'action'=>'delete', $row['Category']['id']), array('title'=>'Remove Category - '.$row['Category']['name'], 'class'=>'small button link red'), 'Are you sure you want to delete this category "'.$row['Category']['name'].'"');?>			
				</form>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php } else { ?>
<p>No category found.</p>
<?php } ?>