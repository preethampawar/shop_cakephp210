<div class="menu-item">
	<h4>Cashbook Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Cashbook Report', array('controller'=>'reports', 'action'=>'cashbookReport'));?></li>
	</ul>
	<h4>Income/Expenses Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Income/Expenses Report', array('controller'=>'reports', 'action'=>'incomeAndExpensesReport'));?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link('Business Snapshot Report', array('controller'=>'reports', 'action'=>'snapshot'));?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link('P & L Report', array('controller'=>'reports', 'action'=>'profitLossReport'));?></li>
	</ul>
</div>
