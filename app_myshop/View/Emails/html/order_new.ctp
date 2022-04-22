<p>Dear <?= $order['Order']['customer_name']?>,</p>
<p>We have received your order. You will be notified once the order is confirmed.</p>
<p>Below are your order details:</p>
<p>
	<h2>Order No. #<?= $order['Order']['id']; ?></h2>
</p>

<?php
if (isset($order['OrderProduct']) and !empty($order['OrderProduct'])) {
	?>

	<div class="p-3 shadow small mt-4">
		<h5>PRODUCTS</h5>

		<table class="table small" style="width: 100%" cellpadding="5" cellspacing="0" border="1">
			<thead>
			<tr>
				<th style="text-align: left;">Product</th>
				<th style="text-align: left;" class="text-center">Price</th>
				<th style="text-align: left;" class="text-center">Qty</th>
				<th style="text-align: left;" class="text-center">Amount</th>
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
				$categoryName = $row['category_name'];
				$productName = $row['product_name'];
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
			<?php
			/*
			 ?>
			<tr class="text-muted">
				<td>Total Cart Value</td>
				<td class="text-decoration-line-through text-center"></td>
				<td class="text-center"></td>
				<td class="text-center"><?= $this->App->price($cartValue) ?></td>
			</tr>
			<?php
			*/
			?>
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
		<?php
		if ($totalDiscount > 0) {
		?>
			<br>
			<div class="text-success text-center">You have saved <?= $this->App->price($totalDiscount) ?> on this Order</div>
		<?php
		}
		?>
	</div>
	<br>
	<div class="p-3 mt-4 shadow small">
		<h5>DELIVERY DETAILS</h5>
		<hr>
		<table class="table small" cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td>Contact Name:</td>
				<td><b><?= $order['Order']['customer_name'] ?></b></td>
			</tr>
			<tr>
				<td>Contact Phone No:</td>
				<td><b><?= $order['Order']['customer_phone'] ?></b></td>
			</tr>
			<tr>
				<td>Contact Email:</td>
				<td><b><?= $order['Order']['customer_email'] ?></b></td>
			</tr>
			<tr>
				<td>Contact Address:</td>
				<td><b><?= $order['Order']['customer_address'] ?></b></td>
			</tr>
			<tr>
				<td>Special Instructions:</td>
				<td><b><?= $order['Order']['customer_message'] ?></b></td>
			</tr>
		</table>
	</div>
	<br>
	<div class="p-3 mt-4 shadow small">
		<h5>PAYMENT DETAILS</h5>
		<hr>
		<table class="table small" cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td>Payment Method:</td>
				<td><b><?= $order['Order']['payment_method'] ?></b></td>
			</tr>
			<?php
			if(!empty($order['Order']['payment_reference_no'])) {
			?>
			<tr>
				<td>Payment Reference No:</td>
				<td><b><?= $order['Order']['payment_reference_no'] ?></b></td>
			</tr>
			<?php
			}
			?>
		</table>
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

<br>
<p>Thank you for shopping with us.</p>

<p>
	-<br>
	<?= $this->Session->read('Site.title') ?><br>
	<?= $this->Html->url('/', true) ?>
</p>

<p>This is an auto generated email. Please do not respond.</p>
