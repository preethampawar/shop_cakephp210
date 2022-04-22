<?php echo $this->element('message'); ?>
<aside id="admin_left_column">
	<?php echo $this->element('admin_categories_menu'); ?>
</aside>
<div id="content">
	<section>
		<h2>Add New Category</h2>
		<?php
		echo $this->Form->create();
		echo $this->Form->input('Category.name', ['label' => false, 'title' => 'Add new category', 'style' => 'width:300px; float:left; margin-right:20px;']);
		echo $this->Form->submit('+ Add', ['class' => 'floatLeft']);
		echo $this->Form->end();
		?>
		<div class='clear'>&nbsp;</div>
		<div class='note'>Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>
	</section>
</div>
