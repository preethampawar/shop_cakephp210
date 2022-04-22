<div class="menu-item">
	<h4>Purchases</h4>
	<ul>
		<li><?php echo $this->Html->link('Add New Purchase', array('controller'=>'purchases', 'action'=>'add'));?></li>
		<li><?php echo $this->Html->link('Import Purchases', array('controller'=>'purchases', 'action'=>'uploadCsv'));?></li>
		<li><?php echo $this->Html->link('Show Recent Purchases', array('controller'=>'purchases', 'action'=>'index'));?></li>
	</ul>	
</div>