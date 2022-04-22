<div class="btn-group" role="group" aria-label="Manage Dealers">
	<div class="btn-group" aria-label="Add Dealer">
		<?php echo $this->Html->link('+ Add New Dealer', array('controller'=>'dealers', 'action'=>'add'), array('class'=>'btn btn-default'));?>
	</div>
	<div class="btn-group" aria-label="Add Brand">
		<?php echo $this->Html->link('+ Add New Brand', array('controller'=>'brands', 'action'=>'add'), array('class'=>'btn btn-default'));?>
	</div>
	<div class="btn-group" aria-label="Add Dealer Brand">
		<?php echo $this->Html->link('+ Add Dealer Brands', array('controller'=>'brands', 'action'=>'addDealerBrands'), array('class'=>'btn btn-default'));?>
	</div>
	<div class="btn-group" aria-label="Add Brand Product">
		<?php echo $this->Html->link('+ Add Brand Products', array('controller'=>'brands', 'action'=>'addBrandProducts'), array('class'=>'btn btn-default'));?>
	</div>	
</div> 
<br><br>