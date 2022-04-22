<div class="menu-item">
	<h4>Product Sales</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Sale', array('controller'=>'sales', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Add All Products - Sale', array('controller'=>'sales', 'action'=>'addAllProducts'));?></li>
		<li><?php echo $this->Html->link('Show Recent Sales', array('controller'=>'sales', 'action'=>'index'));?></li>
	</ul>	
</div>