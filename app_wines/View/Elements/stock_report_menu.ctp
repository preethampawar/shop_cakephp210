<div class="menu-item">
    <b>Stock Report</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Day wise Stock Report', ['controller' => 'reports', 'action' => 'dayWiseStockReport']); ?></li>
        <li><?php echo $this->Html->link('Month wise Stock Report', ['controller' => 'reports', 'action' => 'monthWiseStockReport']); ?></li>
        <li><?php echo $this->Html->link('Complete Stock Report', ['controller' => 'reports', 'action' => 'completeStockReport']); ?></li>
        <li><?php echo $this->Html->link('My Store Performance - Visual Report', ['controller' => 'reports', 'action' => 'completeStockReportChart']); ?></li>
    </ul>
</div>