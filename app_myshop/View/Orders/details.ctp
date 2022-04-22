<div class="text-end">
	<a href="/orders/" type="button" class="btn btn-secondary btn-sm me-4">&laquo; BACK</a>
</div>

<h1>Order No. #<?= $order['Order']['id']; ?></h1>
<h6> - <?= $order['Order']['status'] ?></h6>

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
$createdDate = $createdDate ?: $modifiedDate;
?>

<div class="p-3 shadow small mt-4">
	<p>Order Status: <u><?= $order['Order']['status'] ?></u></p>
	<p>Order Placed On: <u><?= $createdDate ?></u></p>

	Order History:
	<table class="table table-bordered table-hover mt-1">
		<thead>
		<tr>
			<th>Status</th>
			<th>Date</th>
			<th>Message</th>
		</tr>
		</thead>
		<tbody>
		<?php
		if ($log) {
			foreach($log as $row2) {
				$updatedOn = date('d-m-Y h:i A', $row2['date']);
				$message = $row2['message'] ?? '';

				if ($row2['orderStatus'] === Order::ORDER_STATUS_DRAFT) {
					continue;
				}
				?>
					<tr>
						<td><?=$row2['orderStatus'] ?></td>
						<td><?=$updatedOn ?></td>
						<td><?=html_entity_decode($message) ?></td>
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
	?>

	<div class="p-3 shadow small mt-4">
		<h5>PRODUCTS</h5>
		<hr>
		<table class="table small">
			<thead>
			<tr>
				<th>Product</th>
				<th class="text-center">Price</th>
				<th class="text-center">Qty</th>
				<th class="text-center">Amount</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			$cartValue = $order['Order']['total_cart_value'];
			$payableAmount = $order['Order']['total_order_amount'];
			$totalDiscount = $order['Order']['total_discount'];
			$promoCodeDiscount = (float)$order['Order']['promo_code_discount'];

			$cartMrpValue = 0;
			$totalItems = 0;
			foreach ($order['OrderProduct'] as $row) {
				$i++;
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
				<td class="text-center"><?= $this->App->price($cartValue) ?></td>
			</tr>
			<?php
			if ($promoCodeDiscount > 0) {
				?>
				<tr class="text-muted">
					<td>Promo Code (<b><?= $order['Order']['promo_code'] ?></b>) </td>
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
				<td class="text-center"></td>
				<td class="text-center"><?= $this->App->price($order['Order']['shipping_amount']) ?></td>
			</tr>
			<tr class="fw-bold">
				<td>Total</td>
				<td></td>
				<td class="text-center"><?= $totalItems ?></td>
				<td class="text-center"><?= $this->App->price($payableAmount) ?></td>
			</tr>

			</tfoot>

		</table>
		<div class="text-success text-center">You have saved <?= $this->App->price($totalDiscount) ?> on this Order</div>
		<br>
		<br>
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
			<a href="/orders/" type="button" class="btn btn-secondary me-4">&laquo; BACK</a>
		</div>
	</div>

	<?php
} else {
	?>
	<div class="bg-white">
		No items in your order.
	</div>
	<?php
}
?>

