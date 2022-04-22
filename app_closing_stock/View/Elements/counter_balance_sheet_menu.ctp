<div class="menu-item">
	<h4>Counter Balance Sheet</h4>
	<ul>
		<li><?php echo $this->Html->link('Calculate Counter Balance', ['controller' => 'CounterBalanceSheets', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Show All Counter Bal. Sheets', ['controller' => 'CounterBalanceSheets', 'action' => 'index']); ?></li>
	</ul>
	<h4>Transaction Logs</h4>
	<ul>
		<li><?php echo $this->Html->link('+ Add Transaction Log', ['controller' => 'TransactionLogs', 'action' => 'add']); ?></li>
		<li><?php echo $this->Html->link('Show All Transaction Logs', ['controller' => 'TransactionLogs', 'action' => 'index']); ?></li>
	</ul>
</div>
