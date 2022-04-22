<script>
	function updateSupplier(supplierId, orderProductId) {
		if (supplierId != "" && confirm("Are you sure you want to update the supplier?")) {
			document.getElementById("OrderProductAdminDetailsForm"+orderProductId).submit()
		}

		if (supplierId == "" && confirm("Are you sure you want to remove the supplier?")) {
			document.getElementById("OrderProductAdminDetailsForm"+orderProductId).submit()
		}
	}
</script>

<div class="text-end">
	<a href="/admin/orders/" type="button" class="btn btn-secondary btn-sm me-4">&laquo; BACK</a>
</div>

<h1>Order No. #<?= $order['Order']['id']; ?></h1>
<h6>&nbsp;Status - <?= $order['Order']['status'] ?></h6>

<?php
if($usersList) {
	?>
	<div class="bg-light p-3 border rounded mt-3">
		<table class="w-100">
			<tbody>
			<tr>
				<td>Delivery Boy - <span class="fw-bold"><?= $order['Order']['delivery_user_id'] ? $usersList[$order['Order']['delivery_user_id']] : ''; ?></span></td>
				<td class="text-end">
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#deliveryDropDownBackdrop">
						Assign Delivery Boy
					</button>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<?php
}
?>

<!-- Modal -->
<?php
echo $this->Form->create('Order', ['url' => '/admin/orders/assignDeliveryBoy/'.base64_encode($order['Order']['id'])]);
?>
<div class="modal fade" id="deliveryDropDownBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deliveryDropDownBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deliveryDropDownBackdropLabel">Assign Delivery Boy</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php
				echo $this->Form->select(
					'delivery_user_id',
					$usersList,
					[
						'class'=>'form-select',
						'empty' => 'Select',
						'default' => $order['Order']['delivery_user_id']
					]
				);
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>
<?php
echo $this->Form->end();
?>
<div class="mt-4">
	<?php
	$orderStatus = $order['Order']['status'];

	switch ($orderStatus) {
		case Order::ORDER_STATUS_NEW:
			$orderStatusOptions = [
					Order::ORDER_STATUS_CONFIRMED => Order::ORDER_STATUS_CONFIRMED,
					Order::ORDER_STATUS_CANCELLED => Order::ORDER_STATUS_CANCELLED,
			];
			break;
		case Order::ORDER_STATUS_CONFIRMED:
			$orderStatusOptions = [
					Order::ORDER_STATUS_SHIPPED => Order::ORDER_STATUS_SHIPPED,
					Order::ORDER_STATUS_CANCELLED => Order::ORDER_STATUS_CANCELLED,
			];
			break;
		case Order::ORDER_STATUS_SHIPPED:
			$orderStatusOptions = [
					Order::ORDER_STATUS_DELIVERED => Order::ORDER_STATUS_DELIVERED,
					Order::ORDER_STATUS_CANCELLED => Order::ORDER_STATUS_CANCELLED,
			];
			break;
		case Order::ORDER_STATUS_DELIVERED:
			$orderStatusOptions = [
					// Order::ORDER_STATUS_RETURNED => Order::ORDER_STATUS_RETURNED,
					Order::ORDER_STATUS_CANCELLED => Order::ORDER_STATUS_CANCELLED,
					Order::ORDER_STATUS_CLOSED => Order::ORDER_STATUS_CLOSED,
			];
			break;
		default:
			$orderStatusOptions = null;
			break;
	}
	?>

	<?php
	if ($orderStatusOptions) {
		?>
			<div class="alert alert-secondary bg-light shadow">
				<label for="selectedOrderStatus" class="form-label">Change Order Status</label>
				<select
						name="selectedOrderStatus"
						id="selectedOrderStatus"
						class="form-select"
						>
					<option value="0" class="small text-muted">Select</option>
					<?php
					foreach($orderStatusOptions as $index => $option) {
						?>
						<option value="<?= $index ?>"><?= $option ?></option>
						<?php
					}
					?>
				</select>

				<div class="mt-3">
					<label for="selectedOrderStatusMessage" class="form-label">Message</label>
					<textarea name="message" id="selectedOrderStatusMessage" class="form-control" placeholder="Enter your message" maxlength="100"></textarea>
				</div>

				<div class="mt-3">
					<label for="selectedOrderPaymentMethod" class="form-label">Payment Method</label>
					<select
							name="selectedOrderPaymentMethod"
							id="selectedOrderPaymentMethod"
							class="form-select"
					>
						<?php
						$defaultPaymentMethod = $order['Order']['payment_method'];

						foreach(Order::ORDER_PAYMENT_OPTIONS as $index => $option) {
							?>
							<option value="<?= $index ?>" <?= $defaultPaymentMethod === $index ? 'selected' : '' ?>><?= $option ?></option>
							<?php
						}
						?>
					</select>
				</div>

				<div class="form-check d-flex justify-content-start mt-3">
					<input name="sendEmailToCustomer" class="form-check-input" type="checkbox" value="" id="sendEmailToCustomer" checked>
					<label class="form-check-label ms-2 text-start" for="sendEmailToCustomer">
						Send notification message to customer
					</label>
				</div>

				<div class="mt-4 text-center">
					<button class="btn btn-primary" onclick="changeOrderStatus('<?= base64_encode($order['Order']['id']) ?>')">Update Order Status</button>
				</div>
			</div>

		<?php
	}
	?>
</div>

<?php
$modifiedDate = date('d-m-Y', strtotime($order['Order']['modified']));
$log = !empty($order['Order']['log']) ? json_decode($order['Order']['log'], true) : null;
if ($log) {
	foreach($log as $row2) {
		if ($row2['orderStatus'] == Order::ORDER_STATUS_NEW) {
			$createdDate = date('d-m-Y', $row2['date']);
			break;
		}
	}
}
$createdDate = $createdDate ?? $modifiedDate;
?>

<div class="p-3 shadow small mt-5">
	<p>Order Status: <u><?= $order['Order']['status'] ?></u></p>
	<p>Order Placed On: <u><?= $createdDate ?></u></p>

	Order History:
	<table class="table table-bordered table-hover mt-1">
		<thead>
		<tr>
			<th>Status</th>
			<th>Date</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if ($log) {
			foreach($log as $row2) {
				$message = $row2['message'] ?? '';
				$updatedOn = date('d-m-Y h:i A', $row2['date']);
				?>
					<tr>
						<td><?=$row2['orderStatus'] ?></td>
						<td><?=$updatedOn ?></td>
						<td><?= html_entity_decode($message) ?></td>
					</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
	<br>
</div>
<?php
if (isset($order['OrderProduct']) and !empty($order['OrderProduct'])) {
	$i = 0;
	$cartValue = $order['Order']['total_cart_value'];
	$payableAmount = $order['Order']['total_order_amount'];
	$totalDiscount = $order['Order']['total_discount'];
	$promoCodeDiscount = (float)$order['Order']['promo_code_discount'];

	$promoCodeDetails = !empty($order['Order']['promo_code_details']) ? json_decode($order['Order']['promo_code_details'], true) : [];
	$minPurchaseValue = (float)($promoCodeDetails['min_purchase_value'] ?? 0);
	$showPromoDiscount = false;
	if ($cartValue >= $minPurchaseValue) {
		$showPromoDiscount = true;
	}

	$cartMrpValue = 0;
	$totalItems = 0;
	?>

	<div class="p-3 shadow small mt-4">
		<h5>PRODUCTS</h5>
		<hr>
		<table class="table small">
			<thead>
			<tr>
				<th>Product</th>
				<th class="text-danger">Assign Supplier</th>
				<th class="text-center">Price</th>
				<th class="text-center">Qty</th>
				<th class="text-center">Amount</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($order['OrderProduct'] as $row) {
				$i++;
				$orderProductId = $row['id'];
				$orderProductSupplierId = $row['supplier_id'];
				$categoryName = ucwords($row['category_name']);
				$productName = ucwords($row['product_name']);
				$qty = $row['quantity'] ?: 0;
				$mrp = $row['mrp'];
				$discount = $row['discount'];
				$salePrice = $row['sale_price'];
				$showDiscount = $mrp != $salePrice;
				$totalProductPurchaseValue = $salePrice * $qty;

				$productCartValue = $qty * $salePrice;
				$productCartMRPValue = $qty * $mrp;
				$totalItems += $qty;
				$cartMrpValue += $productCartMRPValue;
				//$totalDiscount += $qty * $discount;
				?>

				<tr>
					<td><?= $productName ?></td>
					<td>
						<?php
						echo $this->Form->create('OrderProduct', ['url' => '/admin/orders/updateOrderProductSupplier', 'id' => 'OrderProductAdminDetailsForm'.$orderProductId]);
						echo $this->Form->hidden('id', ['value' => $orderProductId]);
						echo $this->Form->select('supplier_id', $suppliers, [
								'empty' => '- Select Supplier -',
							 	'class' => 'form-select form-select-sm text-danger border-danger',
								'onchange' => 'updateSupplier(this.value, "'.$orderProductId.'")',
								'default' => $orderProductSupplierId,
						]);
						echo $this->Form->end();
						?>
					</td>
					<td class="text-center">
						<?= $this->App->price($salePrice) ?>
						<br>
						<span class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
					</td>
					<td class="text-center">
						<?=  $qty ?>
					</td>
					<td class="text-center">
						<?= $this->App->price($productCartValue) ?>
					</td>
				</tr>

				<?php
			}
			?>

			</tbody>
			<tfoot>
			<tr class="text-muted">
				<td>Total Cart Value</td>
				<td class="text-decoration-line-through text-center">MRP <?= $this->App->price($cartMrpValue) ?></td>
				<td class="text-center"></td>
				<td class="text-center"></td>
				<td class="text-center"><?= $this->App->price($cartValue) ?></td>
			</tr>
			<?php
			if ($showPromoDiscount && $promoCodeDiscount > 0) {
				?>
				<tr class="text-muted">
					<td>Promo Code (<b><?= $order['Order']['promo_code'] ?></b>) </td>
					<td></td>
					<td></td>
					<td class="text-center"></td>
					<td class="text-center">-<?= $this->App->price($promoCodeDiscount) ?></td>
				</tr>
				<?php
			}
			?>
			<tr class="text-muted">
				<td>Shipping Charges</td>
				<td></td>
				<td></td>
				<td class="text-center"></td>
				<td class="text-center"><?= $this->App->price($order['Order']['shipping_amount']) ?></td>
			</tr>
			<tr class="fw-bold">
				<td>Total</td>
				<td></td>
				<td></td>
				<td class="text-center"><?= $totalItems ?></td>
				<td class="text-center"><?= $this->App->price($payableAmount) ?></td>
			</tr>

			</tfoot>

		</table>
		<div class="text-success text-center">
			Saved <?= $this->App->price($totalDiscount) ?> on this Order
			<?php
			if ($showPromoDiscount && $order['Order']['promo_code']) {
			?>
			<div class="alert alert-secondary bg-light">
				Promo Code<br>
				<b><?= $order['Order']['promo_code'] ?></b> :
				<b><?= $this->App->price($order['Order']['promo_code_discount']) ?> OFF</b>
			</div>
			<?php
			}
			?>
		</div>
		<br><br>
	</div>

	<div class="p-3 mt-4 shadow small">
		<h5>DELIVERY DETAILS</h5>
		<hr>
		<div class="">
			Contact Name:<br>
			<b><?= $order['Order']['customer_name'] ?></b>
		</div>
		<div class="mt-2">
			Contact Phone No:<br>
			<b><?= $order['Order']['customer_phone'] ?></b>
		</div>
		<div class="mt-2">
			Delivery Address:<br>
			<b><?= $order['Order']['customer_address'] ?></b>
		</div>
		<div class="mt-2">
			Special Instructions:<br>
			<b><?= h($order['Order']['customer_message']) ?></b>
		</div>
		<br><br>
	</div>

	<div class="p-3 mt-4 shadow small">
		<h5>PAYMENT DETAILS</h5>
		<hr>
		<div class="">
			Payment Method:
			<b><?= $order['Order']['payment_method'] ?></b>
		</div>
		<?php
		if(!empty($order['Order']['payment_reference_no'])) {
			?>
			<div class="mt-2">
				Payment Reference No:
				<b><?= $order['Order']['payment_reference_no'] ?></b>
			</div>
			<?php
		}
		?>
		<br><br>
	</div>

	<div class="my-5 text-center">
		<div class="d-flex justify-content-center">
			<a href="/admin/orders/" type="button" class="btn btn-secondary me-4">&laquo; BACK</a>
		</div>
	</div>

	<?php
} else {
	?>
	<div class="bg-white mt-3">
		No items found.
	</div>
	<?php
}
?>

