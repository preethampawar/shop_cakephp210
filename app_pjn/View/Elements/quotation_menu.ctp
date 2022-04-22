<div class="menu-item">
	<h4>Invoices & Quotations</h4>
	<ul>
		<li><?php echo $this->Html->link('+ Add New Invoice / Quotation', ['controller' => 'invoice_quotations', 'action' => 'selectTemplate']); ?></li>
		<li><?php echo $this->Html->link('Show All Invoices', ['controller' => 'invoice_quotations', 'action' => 'index', 'invoice']); ?></li>
		<li><?php echo $this->Html->link('Show All Quotations', ['controller' => 'invoice_quotations', 'action' => 'index', 'quotation']); ?></li>
	</ul>

	<h4>Templates</h4>
	<ul>
		<li><?php echo $this->Html->link('+ Create Template', ['controller' => 'invoice_quotations', 'action' => 'createTemplate']); ?></li>
		<li><?php echo $this->Html->link('Show All Templates', ['controller' => 'invoice_quotations', 'action' => 'index', 'template']); ?></li>
	</ul>
	<!--
	<h4>Bank Report</h4>
	<ul>
		<li><?php echo $this->Html->link('Bank Report', ['controller' => 'reports', 'action' => 'bankReport']); ?></li>
	</ul>
	-->
</div>
