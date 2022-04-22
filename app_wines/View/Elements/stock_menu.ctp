<div class="menu-item">
    <b>Closing Stock</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Add Closing Stock', ['controller' => 'sales', 'action' => 'addClosingStock']); ?></li>
        <li><?php echo $this->Html->link('Add Closing Stock for all Products', ['controller' => 'sales', 'action' => 'addAllClosingStock']); ?></li>
        <li><?php echo $this->Html->link('Import Closing Stock', ['controller' => 'sales', 'action' => 'uploadCsv']); ?></li>
        <li><?php echo $this->Html->link('Show Recent Closed Stock', ['controller' => 'sales', 'action' => 'viewClosingStock']); ?></li>
    </ul>
</div>
