<div class="menu-item">
    <b>Product Sales</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Add New Sale', ['controller' => 'sales', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Add All Products - Sale', ['controller' => 'sales', 'action' => 'addAllProducts']); ?></li>
        <li><?php echo $this->Html->link('Show Recent Sales', ['controller' => 'sales', 'action' => 'index']); ?></li>
    </ul>
</div>