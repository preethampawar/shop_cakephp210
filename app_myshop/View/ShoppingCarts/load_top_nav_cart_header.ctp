<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts($this->Session->read('ShoppingCart.id'));

$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$totalItems = 0;
if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
		$totalItems += $row['quantity'];
	}
}
?>
<a href="#" class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
	<div class="<?= (int)$totalItems > 0 ? ' text-orange' : ''; ?>"><i class="bi <?= (int)$totalItems > 0 ? ' bi-cart-check-fill' : ' bi-cart'; ?> fs-5"></i> My Cart <span class="badge bg-orange rounded-pill"><?php echo (int)$totalItems; ?></span></div>
</a>

