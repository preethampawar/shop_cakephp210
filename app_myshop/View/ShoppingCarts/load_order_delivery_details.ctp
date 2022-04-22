<?php
App::uses('Order', 'Model');

if (isset($shoppingCartProducts['ShoppingCartProduct']) and !empty($shoppingCartProducts['ShoppingCartProduct'])) {
	$cartValue = 0;
	$totalItems = 0;

	foreach ($shoppingCartProducts['ShoppingCartProduct'] as $row) {
		$qty = $row['quantity'] ?: 0;
		$mrp = $row['mrp'];
		$discount = $row['discount'];
		$salePrice = $mrp - $discount;
		$totalProductPurchaseValue = $salePrice * $qty;
		$cartValue += $totalProductPurchaseValue;
		$totalItems += $qty;
	}

	$payableAmount = $cartValue + $this->Session->read('Site.shipping_charges');
	?>

	<?php echo $this->Form->create('ShoppingCart', ['url' => '/ShoppingCarts/placeOrder', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) ?>

	<div id="deliveryErrorAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
		<div class="content"></div>
		<button type="button" class="btn-close" aria-label="Close" onclick="hideDeliveryAlertError()"></button>
	</div>

	<div class="">

		<div class="">
			<label for="orderCustomerName">Name <span class="text-danger small">(required)</span></label>
			<input
				type="text"
				name="data[customer_name]"
				id="orderCustomerName"
				minlength="2"
				maxlength="55"
				class="form-control form-control-sm"
				value="<?= $orderDetails['Order']['customer_name'] ?>"
				placeholder="Enter Full Name"
				required>
		</div>

		<?php
		if (!$this->Session->check('User')) {
		?>
		<div class="mt-3">
			<label for="orderCustomerPhone">Phone No. (10 digits)  <span class="text-danger small">(required)</span></label>
			<input
				type="number"
				name="data[customer_phone]"
				id="orderCustomerPhone"
				class="form-control form-control-sm"
				value="<?= $orderDetails['Order']['customer_phone'] ?>"
				min="6000000000"
				max="9999999999"
				placeholder="Enter 10 digit mobile no."
				required>
		</div>
		<?php
		}
 		?>

		<?php
		if (!$this->Session->check('User')) {
			$required = (bool)$this->Session->read('Site.sms_notifications') === true ? null : 'required';

			$email = $orderDetails['Order']['customer_email'];
			if ((bool)$this->Session->read('Site.sms_notifications') === true) {
				$email = $email === $this->Session->read('Site.default_customer_notification_email') ? '' : $email;
			}
		?>
			<div class="mt-3">
				<label for="orderCustomerEmail">Email <?php if($required) { ?><span class="text-danger small">(required)</span><?php } ?></label>
				<input
					type="email"
					name="data[customer_email]"
					id="orderCustomerEmail"
					class="form-control form-control-sm"
					value="<?= $email ?>"
					placeholder="Enter Email Address"
					<?= $required ?>>
			</div>
		<?php
		}
		?>

		<div class="mt-3">
			<label for="orderCustomerAddress">Delivery Address <span class="text-danger small">(required)</span></label>
			<textarea
				name="data[customer_address]"
				id="orderCustomerAddress"
				rows="3"
				class="form-control form-control-sm"
				placeholder="Enter Delivery Address"
				required><?= $orderDetails['Order']['customer_address'] ?></textarea>
		</div>

		<div class="mt-3">
			<label for="orderCustomerMessage">Special Instructions <span class="text-muted small">(optional)</span></label>
			<textarea
				name="data[customer_message]"
				id="orderCustomerMessage"
				rows="3"
				placeholder="Enter your message here"
				class="form-control form-control-sm"><?= $orderDetails['Order']['customer_message'] ?></textarea>
		</div>
	</div>

	<?php
	if ($prefilledDeliveryDetails) {
		?>
			<div class="alert alert-warning mt-3">The above delivery details are pre-filled from your last order. Please cross check and confirm the details before placing a new order.</div>
		<?php
	}
	?>

	<div class="mt-4 text-center">
		<div class="d-flex justify-content-center">
			<button type="button" class="btn btn-secondary me-4" onclick="orderDeliveryDetails.hide(); myShoppingCart.show()">Back</button>
			<button type="button" id="saveOrderDeliveryDetailsButton" class="btn btn-orange" onclick="saveOrderDeliveryDetails()">Next &raquo;</button>
		</div>
	</div>

	<?php echo $this->Form->end() ?>

	<?php
} else {
	?>
	<div class="bg-white">
		No items in your cart.
	</div>
	<?php
}
?>
<div id="orderDeliveryDetailsSpinner" class="mt-4"></div>

<br><br><br><br>

