<h1>Edit Product Category - <?php echo $pCatInfo['ProductCategory']['name']; ?></h1>
<br>
<div id="AddCategoryDiv" class="well">
	<?php echo $this->Form->create('ProductCategory'); ?>

	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => 'Category Name', 'value' => html_entity_decode($this->data['ProductCategory']['name']), 'required' => true]); ?>
	</div>
	<div style="float:left; clear:none;">
		<label>&nbsp;</label>
		<button type="submit" class="btn btn-sm btn-purple">Update Category</button>
	</div>
	<div style="clear: both;"></div>
	<?php
	echo $this->Form->end();
	?>

	<?php
	echo '&nbsp;' . $this->Html->link('Cancel', ['controller' => 'product_categories', 'action' => 'index'], ['class' => 'btn btn-sm btn-warning']);
	?>
</div>
