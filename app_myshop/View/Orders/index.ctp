<h1>My Orders</h1>

<div class="mt-3">
	<?php
	if (!empty($orders)) {
	?>

		<table class="table text-center">
			<thead>
			<tr>
				<th>Order No.</th>
				<th>Status</th>
				<th>Total Amount</th>
				<th>Created On</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ($orders as $row) {
					$i++;
					$orderId = $row['Order']['id'];
					$status = $row['Order']['status'];
					$totalAmount = $row['Order']['total_order_amount'];
					$modifiedDate = date('d-m-Y', strtotime($row['Order']['modified']));
					$createdDate = null;
					$log = !empty($row['Order']['log']) ? json_decode($row['Order']['log'], true) : null;

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
					<tr>
						<td><a href="/orders/details/<?= base64_encode($orderId)?>"><?= $orderId ?></a></td>
						<td><?= $status ?></td>
						<td><?= $totalAmount ? $this->App->price($totalAmount) : '-' ?></td>
						<td><?= $createdDate ?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>

		<br>
		<?php
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page ' . $this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		// Shows the next and previous links
		echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
		echo '&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();

		echo '&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	} else {
		?>
		No orders found.
		<?php
	}
	?>
</div>

