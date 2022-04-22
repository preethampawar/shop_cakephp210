<aside id="admin_left_column">
	<?php echo $this->element('admin_categories_menu'); ?>
</aside>
<div id="content" class='content'>
	<section>
		<h2>Add New Category</h2>
		<?php
		echo $this->Form->create('', array('url' => '/admin/categories/add'));
		echo $this->Form->input('Category.name', array('label' => false, 'title' => 'Add new category', 'style' => 'width:300px; float:left; margin-right:20px;'));
		echo $this->Form->submit('+ Add', array('class' => 'floatLeft'));
		echo $this->Form->end();
		?>
		<div class='clear'>&nbsp;</div>
		<div class='note'>Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>
	</section>
</div>
