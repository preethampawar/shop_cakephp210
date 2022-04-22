<div class="menu-item">
	<h4>Closing Stock</h4>
	<ul>
		<li><?php echo $this->Html->link('Add Closing Stock', ['controller' => 'sales', 'action' => 'addClosingStock']); ?></li>
		<li><?php echo $this->Html->link('Add All Products Closing Stock', ['controller' => 'sales', 'action' => 'addAllClosingStock']); ?></li>
		<li><?php echo $this->Html->link('Import Closing Stock', ['controller' => 'sales', 'action' => 'uploadCsv']); ?></li>
		<li><?php echo $this->Html->link('Show Recent Closed Stock', ['controller' => 'sales', 'action' => 'viewClosingStock']); ?></li>
	</ul>
</div>
