<div class="menu-item">
	<h4>Stock Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Day wise Stock Report', array('controller'=>'reports', 'action'=>'dayWiseStockReport'));?></li>
		<li><?php echo $this->Html->link('Month wise Stock Report', array('controller'=>'reports', 'action'=>'monthWiseStockReport'));?></li>
		<li><?php echo $this->Html->link('Complete Stock Report', array('controller'=>'reports', 'action'=>'completeStockReport'));?></li>
		<li><?php echo $this->Html->link('My Store Performance - Visual Report', array('controller'=>'reports', 'action'=>'completeStockReportChart'));?></li>
	</ul>
</div>