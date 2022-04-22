<div class="menu-item">
	<h4>Cashbook</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Record', ['controller' => 'cashbook', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Show All Records', ['controller' => 'cashbook', 'action' => 'index']); ?></li>
	</ul>

	<h4>Cashbook Categories</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Category', ['controller' => 'categories', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Show All Categories', ['controller' => 'categories', 'action' => 'index']); ?></li>
	</ul>
</div>
