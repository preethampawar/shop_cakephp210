<?php
//debug($deliveredOrders);
//debug($shippedOrders);
?>


<h1>Delivery Report</h1>
<div class="container">
	<form method="get">
		<div class="row small mt-3 alert alert-info">
			<div class="col-md-3 mb-3">
				<label for="StartDate">From <span class="text-danger small">(required)</span></label>
				<input type="date" id="StartDate" name="start_date" value="<?= $start_date ?? date('Y-m-d') ?>" class="form-control form-control-sm">
			</div>

			<div class="col-md-3 mb-3">
				<label for="EndDate">To <span class="text-danger small">(required)</span></label>
				<input type="date" id="EndDate" name="end_date" value="<?= $end_date ?? date('Y-m-d') ?>" class="form-control form-control-sm">
			</div>
			<div class="col-md-3">
				<label for="Suppliers" class="d-none d-md-block">&nbsp;</label>
				<button class="btn btn-sm btn-primary w-100 d-block" type="submit">Search</button>
			</div>
		</div>
	</form>
</div>



<div class="alert alert-secondary bg-light mt-4">
	<span class="badge bg-orange rounded-pill"><?= count($deliveredOrders) ?> - Orders</span>
	

	<span class="small mt-3 text-muted">From "<?= date('d-m-Y', strtotime($start_date)) ?>" to "<?= date('d-m-Y', strtotime($end_date)) ?>"</span>
</div>

<div class="table-responsive mt-3">
	<?php
	if ($deliveredOrders) {
		$totalOrdersAmount = 0;
		$totalOnlineAmount = 0;
		$totalCashAmount = 0;
	?>
		<table class="table table-sm">
			<thead>
				<tr>
					<th>#</th>
					<th>Order No.</th>
					<th>Date</th>
					<th>Total Amount</th>
					<th>Payment Method</th>
					<th>Online Amount</th>
					<th>Cash Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ($deliveredOrders as $row) {
					$i++;

					$orderId = $row['Order']['id'];
					$orderDate = date('d-m-Y', strtotime($row['Order']['created']));
					$paymentMethod = !empty($row['Order']['payment_method']) ? $row['Order']['payment_method'] : '';
					$totalAmount = $row['Order']['total_order_amount'];
					$partialPaymentAmount = $row['Order']['partial_payment_amount'];
					$onlineAmount = $totalAmount - $partialPaymentAmount;

					if ($paymentMethod === Order::PAYMENT_METHOD_COD) {
						$onlineAmount = 0;
						$partialPaymentAmount = $totalAmount;
					}

					$totalOrdersAmount += (float)$totalAmount;
					$totalOnlineAmount += (float)$onlineAmount;
					$totalCashAmount += (float)$partialPaymentAmount;
				?>
					<tr>
						<td class=""><?= $i ?></td>
						<td class="">#<?= (string)$orderId ?></td>
						<td class=""><?= (string)$orderDate ?></td>
						<td class=""><?= (float)$totalAmount ?></td>
						<td class=""><?= (string)$paymentMethod ?></td>
						<td class=""><?= (float)$onlineAmount ?></td>
						<td class=""><?= (float)$partialPaymentAmount ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th><?= $totalOrdersAmount ?></th>
					<th></th>
					<th><?= $totalOnlineAmount ?></th>
					<th><?= $totalCashAmount ?></th>
				</tr>
			</tfoot>
		</table>
	<?php
	} else {
		echo '<span class="text-muted small">No Orders found.</span>';
	}
	?>
</div>