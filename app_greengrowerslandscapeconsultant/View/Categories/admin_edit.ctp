<?php echo $this->element('message');?>
<aside id="admin_left_column">	
	<?php echo $this->element('admin_categories_menu');?>		
</aside>
<div id="content">	
	<section>
		<h2>Edit Category: <?php echo $categoryInfo['Category']['name'];?></h2>
		<?php
		echo $this->Form->create();
		echo $this->Form->input('Category.active', array('label'=>'Active', 'title'=>'Status Active', 'style'=>'width:10px; margin-right:5px;', 'before'=>'Status: ', 'after'=>'<br><br>'));
		echo $this->Form->input('Category.name', array('label'=>false, 'title'=>'Add new category', 'style'=>'width:300px; float:left; margin-right:20px;'));
		echo $this->Form->submit('Update', array('class'=>'floatLeft'));
		echo $this->Form->end();
		?>
		<div class='clear'>&nbsp;</div>
		<div class='note'>Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>
	</section>
</div>