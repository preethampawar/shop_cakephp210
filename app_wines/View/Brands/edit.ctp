<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>

<h1>Modify Brand Details: <?php echo $brandInfo['Brand']['name'];?></h1>

<div id="AddCategoryDiv">
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('name', array('placeholder'=>'Enter Brand Name', 'label'=>'Brand Name', 'required'=>true));
	echo $this->Form->submit('Modify Brand');
	echo $this->Form->end();
	?>
</div>