<?php
//debug($orderInfo);
$orderId = $orderInfo['Order']['id'];
$encodedOrderId = base64_encode($orderId);
?>

<h1>Order #<?= $orderId ?></h1>
<h6 class="mt-3 mb-4">Modify Order Details</h6>

<div class="mt-4 text-end">
	<a href="/admin/orders/" class="btn btn-outline-warning btn-sm">CANCEL</a>
</div>


<!-- add product form -->
<form action="/admin/orders/addOfflineProduct/<?= $encodedOrderId ?>" method="post">
	<div class="mt-3 alert bg-light border rounded">

		<lablel for="categoryIdProductId">Add New Product</lablel>
		<select name="data[categoryIdProductId]" id="categoryIdProductId" class="form-select form-select-sm">
			<?php
			foreach($categoryProducts as $index => $row) {
				?>
					<option value="<?= $index ?>"><?= $row ?></option>
				<?php
			}
			?>
		</select>
		<div class="mt-3">
			<lablel for="productQty">Quantity</lablel>
			<input type="number" min="1" max="1000" name="data[quantity]" id="productQty" class="form-control form-control-sm" value="1">
		</div>

		<button class="btn btn-primary btn-sm mt-3" type="submit">Add Product</button>
	</div>
</form>
<div class="mt-5 p-3 shadow rounded">
	<div class="fw-bold">
		<h5>PRODUCTS</h5>
	</div>
	<hr>
	<div class="mt-2">
		<?php
		if($orderInfo['OrderProduct']) {
			?>
			<form method="post" action="/admin/orders/updateOfflineProducts/<?= $encodedOrderId ?>">

				<table class="table">
					<tbody>
					<tr>
						<th>#</th>
						<th>Product</th>
						<th>MRP</th>
						<th>Discount</th>
						<th class="text-center">SalePrice</th>
						<th>Qty</th>
						<th class="text-center">Total</th>
						<th class="text-center"></th>
					</tr>

					<?php
					$i=0;
					foreach($orderInfo['OrderProduct'] as $offlineOrderProduct) {
						$i++;

						$orderProductId = $offlineOrderProduct['id'];
						$encodedOrderProductId = base64_encode($orderProductId);
						$removeOrderProductUrl = '/admin/orders/deleteOrderProduct/' . $encodedOrderProductId;
						$removeOrderProductContent = 'Are you sure you want to delete this Product - '. htmlentities($offlineOrderProduct['product_name']) .'?';
						$qty = (int)$offlineOrderProduct['quantity'];
						$mrp = (float)$offlineOrderProduct['mrp'];
						$discount = (float)$offlineOrderProduct['discount'];
						$salePrice = $mrp - $discount;
						$amount = $qty * $salePrice;
					?>
					<tr>
						<td><?= $i ?></td>
						<td><?= $offlineOrderProduct['product_name'] ?></td>

						<td>
							<input type="number" name="data[<?= $offlineOrderProduct['id'] ?>][mrp]" min="1" max="1000" value="<?= $mrp ?>" required>
						</td>
						<td>
							<input type="number" name="data[<?= $offlineOrderProduct['id'] ?>][discount]" min="0" max="1000" value="<?= $discount ?>" required>
						</td>
						<td class="text-center">
							<?= $this->App->price($salePrice) ?>
						</td>
						<td>
							<input type="number" name="data[<?= $offlineOrderProduct['id'] ?>][quantity]" min="1" max="1000" value="<?= $qty ?>" required>
						</td>
						<td class="text-center">
							<?= $this->App->price($amount) ?>
						</td>
						<td>
							<div class="dropdown">
								<a class="" href="#" id="dropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="bi bi-three-dots-vertical p-2"></i>
								</a>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
									<li><a class="dropdown-item" href="#" onclick="showConfirmPopup('<?= $removeOrderProductUrl ?>', 'Remove Product', '<?= $removeOrderProductContent ?>'); return false;">Remove</a></li>
								</ul>
							</div>
						</td>
					</tr>
					<?php
					}
					?>
					</tbody>
				</table>

				<div class="mt-3 text-warning">*Note: Any changes to the above details will not be saved until "UPDATE" button is clicked.</div>

				<div class="mt-3 text-center">
					<button type="submit" class="btn btn-primary btn-sm">UPDATE</button>
				</div>
			</form>

			<?php
		} else {
			?>
			<div class="text-warning text-center">*There are no products in this Order</div>
			<?php
		}
		?>
	</div>
</div>

<hr class="mt-5">
<form method="post" action="/admin/orders/saveOrder/<?=$encodedOrderId?>">

	<div class="mt-5 p-3 shadow rounded">
		<div class="fw-bold">
			<h5>CUSTOMER DETAILS</h5>
		</div>
		<hr>

		<div class="mt-2">
			<label for="orderCustomerName">Contact Name <span class="text-danger small">(required)</span></label>
			<input
					type="text"
					name="data[customer_name]"
					id="orderCustomerName"
					minlength="2"
					maxlength="55"
					class="form-control form-control-sm"
					value="<?= $orderInfo['Order']['customer_name'] ?? '' ?>"
					placeholder="Enter Full Name"
					required>
		</div>

		<div class="mt-3">
			<label for="orderCustomerPhone">Contact Phone No. (10 digits)  <span class="text-danger small">(required)</span></label>
			<input
					type="number"
					name="data[customer_phone]"
					id="orderCustomerPhone"
					class="form-control form-control-sm"
					value="<?= $orderInfo['Order']['customer_phone'] ?? '' ?>"
					min="6000000000"
					max="9999999999"
					placeholder="Enter 10 digit mobile no."
					required>
		</div>

		<div class="mt-3">
			<label for="orderCustomerEmail">Contact Email <span class="text-danger small">(required)</span></label>
			<input
					type="email"
					name="data[customer_email]"
					id="orderCustomerEmail"
					class="form-control form-control-sm"
					value="<?= $orderInfo['Order']['customer_email'] ?? '' ?>"
					placeholder="Enter Email Address"
					required>
		</div>

		<div class="mt-3">
			<label for="orderCustomerAddress">Delivery Address <span class="text-danger small">(required)</span></label>
			<textarea
					name="data[customer_address]"
					id="orderCustomerAddress"
					rows="3"
					class="form-control form-control-sm"
					placeholder="Enter Delivery Address"
					required><?= $orderInfo['Order']['customer_address'] ?? '' ?></textarea>
		</div>

		<div class="mt-3">
			<label for="orderCustomerMessage">Special Instructions <span class="text-muted small">(optional)</span></label>
			<textarea
					name="data[customer_message]"
					id="orderCustomerMessage"
					rows="3"
					placeholder="Enter your message here"
					class="form-control form-control-sm"><?= $orderInfo['Order']['customer_message'] ?? '' ?></textarea>
		</div>
	</div>

	<div class="mt-3 p-3 shadow rounded">
		<div class="fw-bold"><h5>SHIPPING</h5></div>
		<hr>

		<div class="mt-2 small">
			<label for="shippingCharges">Shipping Charges</label>
			<input type="number" id="shippingCharges" class="form-control form-control-sm" name="data[shipping_amount]" min="0" max="1000" value="<?= $this->Session->read('Site.shipping_charges') ?>" required>
		</div>
	</div>

	<div class="mt-3 p-3 shadow rounded">
		<div class="fw-bold"><h5>PAYMENT METHOD</h5></div>
		<hr>

		<div class="form-check mt-2 small">
			<input class="form-check-input" type="radio" name="data[payment_method]" id="paymentOption1" value="<?= Order::PAYMENT_METHOD_COD ?>" checked>
			<label class="form-check-label" for="paymentOption1">
				Cash on Delivery (COD)
			</label>
		</div>
	</div>



	<?php if($orderInfo['OrderProduct']) { ?>
	<div class="mt-5 text-center">
		<button type="submit" class="btn btn-primary">SAVE AS NEW ORDER</button>
	</div>
	<?php } else { ?>
		<div class="mt-5 text-center">
			<button type="button" class="btn btn-primary disabled">SAVE AS NEW ORDER</button>
		</div>
		<div class="text-warning text-center">*There are no products in this Order</div>
	<?php } ?>

	<div class="mt-4 text-center">
		<a href="/admin/orders/" class="btn btn-outline-warning ms-3">CANCEL</a>
	</div>
</form>

<br><br><br>





<link href="/vendor/select2-4.1/select2.min.css" rel="stylesheet">
<script src="/vendor/select2-4.1/select2.min.js"></script>
<script>
	$(document).ready(function () {
		$('#categoryIdProductId').select2({
		});
	})

	$(document).on('select2:open', () => {
		let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
		allFound[allFound.length - 1].focus();
	});
</script>


