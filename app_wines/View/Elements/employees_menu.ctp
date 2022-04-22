<div class="menu-item">
	<h4>Employees</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Employee', array('controller'=>'employees', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Employees List', array('controller'=>'employees', 'action'=>'index'));?></li>
	</ul>
	
	<h4>Salaries</h4>
	<ul>
		<li><?php echo $this->Html->link('Make Salary Payment', array('controller'=>'salaries', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Show Salary Records', array('controller'=>'salaries', 'action'=>'index'));?></li>
	</ul>	
</div>