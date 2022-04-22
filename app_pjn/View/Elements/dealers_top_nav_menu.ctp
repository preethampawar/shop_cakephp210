<div class="btn-group" role="group" aria-label="Manage Dealers">
	<div class="btn-group" aria-label="Add Dealer">
		<?php echo $this->Html->link('+ Add New Dealer', ['controller' => 'dealers', 'action' => 'add'], ['class' => 'btn btn-default']); ?>
	</div>
	<div class="btn-group" aria-label="Add Brand">
		<?php echo $this->Html->link('+ Add New Brand', ['controller' => 'brands', 'action' => 'add'], ['class' => 'btn btn-default']); ?>
	</div>
	<div class="btn-group" aria-label="Add Dealer Brand">
		<?php echo $this->Html->link('+ Add Dealer Brands', ['controller' => 'brands', 'action' => 'addDealerBrands'], ['class' => 'btn btn-default']); ?>
	</div>
	<div class="btn-group" aria-label="Add Brand Product">
		<?php echo $this->Html->link('+ Add Brand Products', ['controller' => 'brands', 'action' => 'addBrandProducts'], ['class' => 'btn btn-default']); ?>
	</div>
</div>
<br><br>
