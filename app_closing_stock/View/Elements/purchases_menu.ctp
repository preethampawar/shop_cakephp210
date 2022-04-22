<div class="menu-item">
	<h4>Purchases</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Purchase', ['controller' => 'purchases', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Import Purchases', ['controller' => 'purchases', 'action' => 'uploadCsv']); ?></li>
		<li><?php echo $this->Html->link('Show Recent Purchases', ['controller' => 'purchases', 'action' => 'index']); ?></li>
	</ul>
</div>
