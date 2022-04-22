<?php
App::uses('Order', 'Model');

if (isset($shoppingCartProducts['ShoppingCartProduct']) and !empty($shoppingCartProducts['ShoppingCartProduct'])) {
	$cartValue = 0;
	$totalItems = 0;
	$cartMrpValue = 0;
	$totalDiscount = 0;
	$deliveryCharges = $this->Session->read('Site.shipping_charges');

	foreach ($shoppingCartProducts['ShoppingCartProduct'] as $row) {
		$qty = $row['quantity'] ?: 0;
		$mrp = $row['mrp'];
		$discount = $row['discount'];
		$salePrice = $mrp - $discount;
		$totalProductPurchaseValue = $salePrice * $qty;
		$cartValue += $totalProductPurchaseValue;
		$totalItems += $qty;

		$productCartMRPValue = $qty * $mrp;
		$totalDiscount += $qty * $discount;
		$cartMrpValue += $productCartMRPValue;
	}

	$payableAmount = $cartValue + $deliveryCharges;
	?>

	<div class="p-3 shadow rounded d-none">

		<h5>PRICE DETAILS</h5>
		<hr>

		<div class="d-flex justify-content-between mt-3">
			<span>Price (<?= $totalItems ?> items)</span>
			<span><?= $this->App->price($cartMrpValue) ?></span>
		</div>

		<div class="d-flex justify-content-between mt-3">
			<span>Discount</span>
			<span class="text-success">- <?= $this->App->price($totalDiscount) ?></span>
		</div>

		<div class="d-flex justify-content-between mt-3">
			<span>Delivery Charges</span>
			<span><?= $deliveryCharges > 0 ? $this->App->price($deliveryCharges) : '<span class="text-success">FREE</span>' ?></span>
		</div>

		<hr class="my-2">
		<div class="d-flex justify-content-between mt-2 fw-bold fs-5">
			<span>Total Amount</span>
			<span><?= $this->App->price($payableAmount) ?></span>
		</div>

	</div>

	<?php echo $this->Form->create('ShoppingCart', ['url' => '/ShoppingCarts/placeOrder', 'method' => 'post', 'class' => 'needs-validation', 'novalidate']) ?>

	<div id="paymentErrorAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
		<div class="content"></div>
		<button type="button" class="btn-close" aria-label="Close" onclick="hidePaymentAlertError()"></button>
	</div>


	<div class="p-3 shadow rounded">

		<div class="fw-bold">
			<h5>PAYMENT METHOD</h5>
		</div>
		<hr>

		<div class="form-check mt-2 small">
			<input class="form-check-input" type="radio" name="data[payment_method]" id="paymentOption1" value="<?= Order::PAYMENT_METHOD_COD ?>" onclick="checkPaymentMethod(this)" checked>
			<label class="form-check-label" for="paymentOption1">
				Cash on Delivery (COD)
			</label>
		</div>

		<?php
		/*
		?>
		<div class="form-check mt-2 small">
			<input class="form-check-input" type="radio" name="data[payment_method]" id="paymentOption2" value="<?= Order::PAYMENT_METHOD_GPAY ?>" onclick="checkPaymentMethod(this)">
			<label class="form-check-label" for="paymentOption2">
				Google Pay (GPay)
			</label>
		</div>

		<div class="form-check mt-2 small">
			<input class="form-check-input" type="radio" name="data[payment_method]" id="paymentOption3" value="<?= Order::PAYMENT_METHOD_PHONE_PE ?>" onclick="checkPaymentMethod(this)">
			<label class="form-check-label" for="paymentOption3">
				Phone Pe
			</label>
		</div>

		<div class="form-check mt-2 small">
			<input class="form-check-input" type="radio" name="data[payment_method]" id="paymentOption4" value="<?= Order::PAYMENT_METHOD_PAYTM ?>" onclick="checkPaymentMethod(this)">
			<label class="form-check-label" for="paymentOption4">
				Paytm
			</label>
		</div>

		<div class="mt-2 small disabledElement" id="paymentReferenceNoDiv">
			<label for="paymentReferenceNo">Payment Reference No. <span class="text-danger small">(required)</span></label>
			<input class="form-control form-control-sm" type="text" name="data[payment_reference_no]" id="paymentReferenceNo">
		</div>
		<?php
		*/
		?>
		<input class="form-control form-control-sm" type="hidden" name="data[payment_reference_no]" id="paymentReferenceNo">
	</div>


	<div class="mt-5 text-center">
		<div class="d-flex justify-content-center">
			<button type="button" class="btn btn-secondary me-4" onclick="orderPaymentDetails.hide(); orderDeliveryDetails.show()">Back</button>
			<button type="button" id="saveOrderDeliveryDetailsButton" class="btn btn-orange" onclick="saveOrderPaymentDetails()">Next &raquo;</button>
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
<div id="orderPaymentDetailsSpinner" class="mt-4"></div>

<br><br><br><br>


