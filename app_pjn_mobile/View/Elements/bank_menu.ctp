<div class="menu-item">
	<h4>Bank Book</h4>
	<ul>
		<li><?php echo $this->Html->link('+ Add New Bank Record', ['controller' => 'banks', 'action' => 'index']); ?></li>
		<li><?php echo $this->Html->link('Show All Bank Records', ['controller' => 'banks', 'action' => 'index']); ?></li>
	</ul>

	<h4>Bank Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Bank Report', ['controller' => 'reports', 'action' => 'bankReport']); ?></li>
	</ul>
</div>
