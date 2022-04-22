<?php
function array_to_csv_download($array, $filename = "orders.csv", $delimiter = ";")
{
	header('Content-Type: application/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '";');

	// open the "output" stream
	// see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
	$f = fopen('php://output', 'w');

	foreach ($array as $line) {
		fputcsv($f, $line, $delimiter);
	}
	exit;
}

$q_start_date = isset($this->request->query['start_date']) ? 'start_date=' . $this->request->query['start_date'] : '';
$q_end_date = isset($this->request->query['end_date']) ? 'end_date=' . $this->request->query['end_date'] : '';

$q = '';
if ($q_start_date && $q_end_date) {
	$q = '?' . $q_start_date . '&' . $q_end_date;
}
if ($selectedSupplier > 0) {
	$q .= '&data[supplier_id]=' . $selectedSupplier;
}

$ordersData = [];

$orders_total_amount = 0;
$orders_online_amount = 0;
$orders_cash_amount = 0;
$orders_supplier_amount = 0;

if (!empty($orders)) {
	$i = 1;
	$k = 1;
	foreach ($orders as $row) {
		$orderId = (string)$row['Order']['id'];
		$customerName = (string)$row['Order']['customer_name'];
		$customerEmail = (string)$row['Order']['customer_email'];
		$customerPhone = (string)$row['Order']['customer_phone'];
		$customerAddress = htmlentities($row['Order']['customer_address'], ENT_QUOTES);
		$customerMessage = htmlentities($row['Order']['customer_message'], ENT_QUOTES);
		$orderTotalCartValue = (float)$row['Order']['total_cart_value'];
		$orderTotalDiscount = (float)$row['Order']['total_discount'];
		$orderShippingAmount = (float)$row['Order']['shipping_amount'];
		$orderTotalAmount = (float)$row['Order']['total_order_amount'];
		$status = (string)$row['Order']['status'];
		$notes = htmlentities($row['Order']['notes'], ENT_QUOTES);
		$paymentMethod = (string)$row['Order']['payment_method'];
		$partialPaymentAmount = (float)$row['Order']['partial_payment_amount'];
		$onlinePaymentAmount = $orderTotalAmount - $partialPaymentAmount;

		if ($paymentMethod == Order::PAYMENT_METHOD_COD) {
			$partialPaymentAmount = $orderTotalAmount;
			$onlinePaymentAmount = 0;
		}

		$orders_total_amount += $orderTotalAmount;
		$orders_online_amount += $onlinePaymentAmount;
		$orders_cash_amount += $partialPaymentAmount;

		$onlineOrOfflineOrder = (string)$row['Order']['is_offline_order'] ? 'Offline' : 'Online';
		$promoCode = (string)$row['Order']['promo_code'] ?: '-';
		$promoCodeDiscount = (float)$row['Order']['promo_code_discount'] ?: '-';
		$deliveryUserId = $row['Order']['delivery_user_id'];
		$deliveryUserName = (string)($usersList && $deliveryUserId ? ($usersList[$deliveryUserId] ?? '') : '-');
		$modifiedDate = date('d-m-Y h:i A', strtotime($row['Order']['modified']));
		$modifiedRawDate = date('Y-m-d', strtotime($row['Order']['modified']));
		$createdDate = null;
		$createdRawDate = null;

		$log = !empty($row['Order']['log']) ? json_decode($row['Order']['log'], true) : null;

		if ($log) {
			foreach ($log as $row2) {
				if ($row2['orderStatus'] == Order::ORDER_STATUS_NEW) {
					$createdDate = date('d-m-Y h:i A', $row2['date']);
					$createdRawDate = date('Y-m-d', $row2['date']);
					break;
				}
			}
		}
		$createdDate = (string)($createdDate ?: $modifiedDate);
		$createdRawDate = (string)($createdRawDate ?: $modifiedRawDate);

		if (!empty($row['OrderProduct'])) {
			foreach ($row['OrderProduct'] as $orderProduct) {
				$productId = $orderProduct['product_id'];
				$productName = $orderProduct['product_name'];
				$categoryName = $orderProduct['category_name'];
				$quantity = (int)$orderProduct['quantity'];
				$mrp = (float)$orderProduct['mrp'];
				$discount = (float)$orderProduct['discount'];
				$salePrice = (float)$orderProduct['sale_price'];
				$supplierName = $suppliers && $orderProduct['supplier_id'] ? ($suppliers[$orderProduct['supplier_id']] ?? '') : '-';

				$paperRate = (float)($supplierProductsPaperRates[$orderProduct['supplier_id']][$productId][$createdRawDate]['paperRate'] ?? 0);
				$paperRateDate = (string)($supplierProductsPaperRates[$orderProduct['supplier_id']][$productId][$createdRawDate]['paperRateDate'] ?? '');
				$supplierRate = (float)($supplierProductsPaperRates[$orderProduct['supplier_id']][$productId][$createdRawDate]['supplierRate'] ?? 0);
				$supplierAmount = $supplierRate * $quantity;

				if (empty($paperRateDate)) {
					$paperRate = (float)($supplierProductsPaperRates[$orderProduct['supplier_id']][$productId]['default']['paperRate'] ?? 0);
					$paperRateDate = (string)($supplierProductsPaperRates[$orderProduct['supplier_id']][$productId]['default']['paperRateDate'] ?? '');
					$supplierRate = (float)($supplierProductsPaperRates[$orderProduct['supplier_id']][$productId]['default']['supplierRate'] ?? 0);
					$supplierAmount = $supplierRate * $quantity;
				}

				$orders_supplier_amount += $supplierAmount;

				$ordersData[$k]['SlNo'] = $i;
				$ordersData[$k]['orderNo'] = $orderId;
				$ordersData[$k]['customerName'] = $customerName;
				$ordersData[$k]['customerEmail'] = $customerEmail;
				$ordersData[$k]['customerPhone'] = $customerPhone;
				$ordersData[$k]['customerAddress'] = $customerAddress;
				$ordersData[$k]['customerMessage'] = $customerMessage;
				$ordersData[$k]['orderTotalCartValue'] = $orderTotalCartValue;
				$ordersData[$k]['orderTotalDiscount'] = $orderTotalDiscount;
				$ordersData[$k]['orderShippingAmount'] = $orderShippingAmount;
				$ordersData[$k]['orderTotalAmount'] = $orderTotalAmount;
				$ordersData[$k]['orderOnlineAmount'] = $onlinePaymentAmount;
				$ordersData[$k]['orderPartialAmount'] = $partialPaymentAmount;
				$ordersData[$k]['status'] = $status;
				$ordersData[$k]['notes'] = $notes;
				$ordersData[$k]['paymentMethod'] = $paymentMethod;
				$ordersData[$k]['onlineOrOfflineOrder'] = $onlineOrOfflineOrder;
				$ordersData[$k]['promoCode'] = $promoCode;
				$ordersData[$k]['promoCodeDiscount'] = $promoCodeDiscount;
				$ordersData[$k]['deliveryUserName'] = $deliveryUserName;
				$ordersData[$k]['createdDate'] = $createdDate;
				$ordersData[$k]['productName'] = $productName;
				$ordersData[$k]['categoryName'] = $categoryName;
				$ordersData[$k]['quantity'] = $quantity;
				$ordersData[$k]['mrp'] = $mrp;
				$ordersData[$k]['discount'] = $discount;
				$ordersData[$k]['salePrice'] = $salePrice;
				$ordersData[$k]['supplierName'] = $supplierName;

				$ordersData[$k]['paperRateDate'] = $paperRateDate;
				$ordersData[$k]['paperRate'] = $paperRate ? $paperRate : '';
				$ordersData[$k]['supplierRate'] = $supplierRate ? $supplierRate : '';
				$ordersData[$k]['supplierAmount'] = $supplierAmount ? $supplierAmount : '';

				$k++;
			}
			$i++;
		}
	}
}

if ($download) {
	$csvHeader = [
		'Sl No.',
		'Order No.',
		'Date',
		'Status',
		'Amount',
		'Shipping',
		'Total Amount',
		'Payment Method',
		'Online Payment Amount',
		'Cash Payment Amount',
		'Saved Amount',
		'Online/Offline',
		'Customer',
		'Email',
		'Mobile',
		'Address',
		'Special Instructions',
		'Promo Code',
		'Promo Code Discount',
		'Product',
		'Category',
		'MRP',
		'Discount',
		'Sale Price',
		'Quantity',
		'Supplier',
		'Date',
		'Paper Rate',
		'Supplier Rate',
		'Supplier Amount',
	];

	$csvData[] = $csvHeader;

	foreach ($ordersData as $row) {
		$csvData[] = [
			$row['SlNo'],
			$row['orderNo'],
			$row['createdDate'],
			$row['status'],
			$row['orderTotalCartValue'],
			$row['orderShippingAmount'],
			$row['orderTotalAmount'],
			$row['paymentMethod'],
			$row['orderOnlineAmount'],
			$row['orderPartialAmount'],
			$row['orderTotalDiscount'],
			$row['onlineOrOfflineOrder'],
			$row['customerName'],
			$row['customerEmail'],
			$row['customerPhone'],
			$row['customerAddress'],
			$row['customerMessage'],
			$row['promoCode'],
			$row['promoCodeDiscount'],
			$row['productName'],
			$row['categoryName'],
			$row['mrp'],
			$row['discount'],
			$row['salePrice'],
			$row['quantity'],
			$row['supplierName'],
			$row['paperRateDate'],
			$row['paperRate'],
			$row['supplierRate'],
			$row['supplierAmount'],
		];
	}

	$csvData[] = [
		null,
		null,
		null,
		null,
		null,
		null,
		$orders_total_amount,
		null,
		$orders_online_amount,
		$orders_cash_amount,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		$orders_supplier_amount,
	];

	$fileName = 'Orders_' . ($start_date ?? date('Y-m-d')) . ' to ' . ($end_date ?? date('Y-m-d')) . '.csv';

	array_to_csv_download($csvData, $fileName, ',');
}

?>

<h1>Orders Report</h1>

<form method="get">
	<div class="row small mt-3">
		<div class="col-md-3 mb-3">
			<label for="StartDate">From <span class="text-danger small">(required)</span></label>
			<input type="date" id="StartDate" name="start_date" value="<?= $start_date ?? date('Y-m-d') ?>" class="form-control form-control-sm">
		</div>

		<div class="col-md-3 mb-3">
			<label for="EndDate">To <span class="text-danger small">(required)</span></label>
			<input type="date" id="EndDate" name="end_date" value="<?= $end_date ?? date('Y-m-d') ?>" class="form-control form-control-sm">
		</div>

		<div class="col-md-3 mb-3">
			<label for="Suppliers">Supplier</label>
			<?= $this->Form->select('supplier_id', $suppliers, ['class' => 'form-select form-select-sm', 'default' => $selectedSupplier ?? null, 'empty' => '- All -']) ?>
		</div>

		<div class="col-md-3">
			<label for="Suppliers" class="d-none d-md-block">&nbsp;</label>
			<button class="btn btn-sm btn-outline-primary w-100 d-block" type="submit">Search</button>
		</div>
	</div>
</form>
<hr>

<div class="mt-3 d-flex justify-content-end">
	<div class="btn-group">
		<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
			Filter By Status - <?= $orderType ?: 'All' ?>
		</button>
		<ul class="dropdown-menu">
			<li><a class="dropdown-item" href="/admin/orders/reports/<?= Order::ORDER_STATUS_NEW . $q ?>"><?= Order::ORDER_STATUS_NEW ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/reports/<?= Order::ORDER_STATUS_CONFIRMED . $q ?>"><?= Order::ORDER_STATUS_CONFIRMED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/reports/<?= Order::ORDER_STATUS_SHIPPED . $q ?>"><?= Order::ORDER_STATUS_SHIPPED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/reports/<?= Order::ORDER_STATUS_DELIVERED . $q ?>"><?= Order::ORDER_STATUS_DELIVERED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/reports/<?= Order::ORDER_STATUS_CLOSED . $q ?>"><?= Order::ORDER_STATUS_CLOSED ?></a></li>
			<li><a class="dropdown-item" href="/admin/orders/reports/<?= Order::ORDER_STATUS_CANCELLED . $q ?>"><?= Order::ORDER_STATUS_CANCELLED ?></a></li>
		</ul>
	</div>

	<?php
	if (!empty($orders)) {
	?>
		<div>
			<a class="btn btn-primary ms-3" href="/admin/orders/reports/<?= $orderType ?: 0 ?>/1<?= $q ?>"><i class="bi bi-download"></i> Download</a>
		</div>
	<?php
	}
	?>
</div>


<?php
$orderType = !empty($orderType) ? $orderType : 'All';
?>
<div class="bg-light p-2 mt-3 border-bottom">
	<span class="badge bg-orange rounded-pill"><?= count($orders) ?></span>
	<span class="text-orange fw-bold"><?= $orderType ?></span> orders.

	<div class="small mt-3 text-muted">From "<?= date('d-m-Y', strtotime($start_date)) ?>" to "<?= date('d-m-Y', strtotime($end_date)) ?>"</div>
</div>

<div class="">
	<?php
	if (!empty($orders)) {
	?>
		<div class="table-responsive mt-4">
			<table class="table table-sm small mt-4" style="min-height:200px;">
				<thead>
					<tr>
						<th>Sl No.</th>
						<th>Order No.</th>
						<th>Date</th>
						<th>Status</th>
						<th>Amount</th>
						<th>Shipping</th>
						<th>Total Amount</th>
						<th>Payment Method</th>
						<th>Online Payment Amount</th>
						<th>Cash Payment Amount</th>
						<th>Saved Amount</th>
						<th>Online/Offline</th>
						<th>Customer</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Address</th>
						<th>Special Instructions</th>
						<th>Promo Code</th>
						<th>Promo Code Discount</th>
						<th>Product</th>
						<th>Category</th>
						<th>MRP</th>
						<th>Discount</th>
						<th>Sale Price</th>
						<th>Quantity</th>
						<th>Supplier</th>
						<!-- <th>Date</th> -->
						<th>Paper Rate</th>
						<th>Supplier Rate</th>
						<th>Supplier Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($ordersData as $row) {
					?>
						<tr>
							<td><?= $row['SlNo'] ?></td>
							<td><?= $row['orderNo'] ?></td>
							<td><?= $row['createdDate'] ?></td>
							<td><?= $row['status'] ?></td>
							<td><?= $row['orderTotalCartValue'] ?></td>
							<td><?= $row['orderShippingAmount'] ?></td>
							<td><?= $row['orderTotalAmount'] ?></td>
							<td><?= $row['paymentMethod'] ?></td>
							<td><?= $row['orderOnlineAmount'] ?></td>
							<td><?= $row['orderPartialAmount'] ?></td>
							<td><?= $row['orderTotalDiscount'] ?></td>
							<td><?= $row['onlineOrOfflineOrder'] ?></td>
							<td><?= $row['customerName'] ?></td>
							<td><?= $row['customerEmail'] ?></td>
							<td><?= $row['customerPhone'] ?></td>
							<td><?= $row['customerAddress'] ?></td>
							<td><?= $row['customerMessage'] ?></td>
							<td><?= $row['promoCode'] ?></td>
							<td><?= $row['promoCodeDiscount'] ?></td>
							<td><?= $row['productName'] ?></td>
							<td><?= $row['categoryName'] ?></td>
							<td><?= $row['mrp'] ?></td>
							<td><?= $row['discount'] ?></td>
							<td><?= $row['salePrice'] ?></td>
							<td><?= $row['quantity'] ?></td>
							<td><?= $row['supplierName'] ?></td>

							<!-- <td><?= $row['paperRateDate'] ?></td> -->
							<td><?= $row['paperRate'] ?></td>
							<td><?= $row['supplierRate'] ?></td>
							<td><?= $row['supplierAmount'] ?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th><?= $orders_total_amount ?></th>
						<th>&nbsp;</th>
						<th><?= $orders_online_amount ?></th>
						<th><?= $orders_cash_amount ?></th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>

						<!-- <th>&nbsp;</th> -->
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th><?= $orders_supplier_amount ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php
	} else {
	?>
		No orders found.
	<?php
	}
	?>
</div>
<br><br><br>