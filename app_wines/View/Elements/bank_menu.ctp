<div class="menu-item">
	<h4>Bank Book</h4>
	<ul>
		<li><?php echo $this->Html->link('+ Add New Bank Record', array('controller'=>'banks', 'action'=>'index'));?></li>
		<li><?php echo $this->Html->link('Show All Bank Records', array('controller'=>'banks', 'action'=>'index'));?></li>
	</ul>
	
	<h4>Bank Report</h4>
	<ul>		
		<li><?php echo $this->Html->link('Bank Report', array('controller'=>'reports', 'action'=>'bankReport'));?></li>
	</ul>	
</div>