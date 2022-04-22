<div class="menu-item">
    <b>Invoice</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Add New Invoice', ['controller' => 'invoices', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Invoices List', ['controller' => 'invoices', 'action' => 'index']); ?></li>
    </ul>

    <b>Suppliers</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Add New Supplier', ['controller' => 'suppliers', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Suppliers List', ['controller' => 'suppliers', 'action' => 'index']); ?></li>
    </ul>
</div>