<div class="menu-item">
    <b>Bank Book</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('+ Add New Bank Record', ['controller' => 'banks', 'action' => 'index']); ?></li>
        <li><?php echo $this->Html->link('Show All Bank Records', ['controller' => 'banks', 'action' => 'index']); ?></li>
    </ul>

    <b>Bank Report</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Bank Report', ['controller' => 'reports', 'action' => 'bankReport']); ?></li>
    </ul>
</div>