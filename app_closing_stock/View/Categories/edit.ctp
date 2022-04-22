<p><?php echo $this->Html->link('Cancel', ['controller' => 'cashbook', 'action' => 'index']); ?></p>
<h1>Edit Category: <?php echo $pCatInfo['Category']['name']; ?></h1>

<div id="AddCategoryDiv">
	<?php echo $this->Form->create(); ?>
	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => 'Category Name', 'required' => true]); ?>
	</div>
	<div style="float:left; clear:none; padding-top:10px;">
		<br><?php echo $this->Form->input('expense', ['type' => 'checkbox', 'label' => 'Expense']); ?>
	</div>
	<div style="float:left; clear:none; padding-top:10px;">
		<br><?php echo $this->Form->input('income', ['type' => 'checkbox', 'label' => 'Income']); ?>
	</div>
	<div style="float:left; clear:none; padding-top:10px;">
		<?php echo $this->Form->submit('Update Category'); ?>
	</div>
	<div style="clear:both;"></div>
	<?php echo $this->Form->end(); ?>
</div>
