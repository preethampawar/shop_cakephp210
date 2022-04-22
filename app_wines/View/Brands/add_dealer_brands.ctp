<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>
<?php echo $this->element('dealers_top_nav_menu');?>

<h1>Add Dealer Brands</h1>
<br>
<div id="AddCategoryDiv" class="well">
	<?php 
	echo $this->Form->create();
	echo $this->Form->hidden('submit', array('value'=>0));
	echo $this->Form->input('dealer_id', array('type'=>'select', 'label'=>'Select Dealer', 'required'=>true, 'options'=>$dealers, 'empty'=>'-- Select Dealer --', 'class'=>'form-control', 'onchange'=>'$("#BrandAddDealerBrandsForm").submit()'));
	echo $this->Form->input('id', array('type'=>'select', 'label'=>'Select Brands', 'required'=>false, 'multiple'=>'multiple', 'options'=>$brands, 'class'=>'autoSuggest form-control', 'selected'=>$dealer_brands));
	echo $this->Form->submit('Update', array('onclick'=>'$("#BrandSubmit").val(1); return true;')); 
	echo $this->Form->end(); 
	?>
</div>