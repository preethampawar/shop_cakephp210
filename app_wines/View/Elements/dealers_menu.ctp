<div class="menu-item">
    <b>Dealers</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('+ Add New Dealer', ['controller' => 'dealers', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Show All Dealers', ['controller' => 'dealers', 'action' => 'index']); ?></li>
        <li><?php echo $this->Html->link('Show Dealer Brand Products', ['controller' => 'dealers', 'action' => 'showDealerBrandProducts']); ?></li>
    </ul>

    <b>Brands</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('+ Add New Brand', ['controller' => 'brands', 'action' => 'add']); ?></li>
        <li><?php echo $this->Html->link('Show All Brands', ['controller' => 'brands', 'action' => 'index']); ?></li>
    </ul>

    <b>Dealer Brands</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('+ Add Dealer Brands', ['controller' => 'brands', 'action' => 'addDealerBrands']); ?></li>
        <li><?php echo $this->Html->link('Show All Dealer Brands', ['controller' => 'brands', 'action' => 'showDealerBrands']); ?></li>
    </ul>

    <b>Brand Products</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('+ Add Brand Products', ['controller' => 'brands', 'action' => 'addBrandProducts']); ?></li>
        <li><?php echo $this->Html->link('Show All Brand Products', ['controller' => 'brands', 'action' => 'showAllBrandProducts']); ?></li>
    </ul>

    <b>Dealer Reports</b>
    <ul class="list-unstyled">
        <li><?php echo $this->Html->link('Dealer Brand Purchases Report', ['controller' => 'reports', 'action' => 'dealerBrandPurchases']); ?></li>
    </ul>
</div>