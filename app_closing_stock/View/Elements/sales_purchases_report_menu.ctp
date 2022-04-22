<div class="menu-item">
	<h4>Sales/Purchases Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Invoice Report', ['controller' => 'reports', 'action' => 'invoiceReport']); ?></li>
		<li><?php echo $this->Html->link('Invoice - DD Report', ['controller' => 'reports', 'action' => 'invoiceDdReport']); ?></li>
		<li><?php echo $this->Html->link('Purchase Report', ['controller' => 'reports', 'action' => 'purchaseReport']); ?></li>
		<li><?php echo $this->Html->link('Sales Report', ['controller' => 'reports', 'action' => 'salesReport']); ?></li>
	</ul>
</div>
