<div class="menu-item">
	<h4>Employees</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Employee', ['controller' => 'employees', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Employees List', ['controller' => 'employees', 'action' => 'index']); ?></li>
	</ul>

	<h4>Salaries</h4>
	<ul>
		<li><?php echo $this->Html->link('Make Salary Payment', ['controller' => 'salaries', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Show Salary Records', ['controller' => 'salaries', 'action' => 'index']); ?></li>
	</ul>
</div>
