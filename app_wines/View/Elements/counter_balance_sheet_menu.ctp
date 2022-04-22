<div class="menu-item">
    <b>Counter Balance Sheet</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Calculate Counter Balance', ['controller' => 'CounterBalanceSheets', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Show All Counter Bal. Sheets', ['controller' => 'CounterBalanceSheets', 'action' => 'index']); ?></li>
    </ul>
    <b>Transaction Logs</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('+ Add Transaction Log', ['controller' => 'TransactionLogs', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Show All Transaction Logs', ['controller' => 'TransactionLogs', 'action' => 'index']); ?></li>
    </ul>
</div>