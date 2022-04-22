<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>

<h1>Modify Dealer Details: <?php echo $dealerInfo['Dealer']['name'];?></h1>

<div id="AddCategoryDiv">
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('name', array('placeholder'=>'Enter Dealer Name', 'label'=>'Dealer Name', 'required'=>true));
	echo $this->Form->submit('Modify Dealer');
	echo $this->Form->end();
	?>
</div>