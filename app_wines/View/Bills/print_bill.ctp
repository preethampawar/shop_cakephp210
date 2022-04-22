<?php
$autoSubmitEnabled = false;
if ($this->Session->check('Bills.autoSubmitEnabled') && $this->Session->read('Bills.autoSubmitEnabled') === true) {
	$autoSubmitEnabled = true;
}

$autoSubmitVal = $autoSubmitEnabled ? 0 : 1;
$autoSubmitText = $autoSubmitEnabled ? 'Auto Submit [Enabled]' : 'Auto Submit [Disabled]';
$autoSubmitBtnColor = $autoSubmitEnabled ? ' btn-success ' : ' btn-light ';
?>

<script>
	var billsAutoSubmitEnabled = parseInt('<?= $autoSubmitEnabled ? 1 : 0 ?>');
</script>
<div class="d-flex justify-content-between small">
	<h1>Bill no. <?= $bill['Bill']['id'] ?></h1>
	<div>Date:<br> <?= date('d-m-Y', strtotime($bill['Bill']['bill_date'])) ?></div>
</div>


<?php
	if ($bill['Sale']) {
?>
<table class="table table-sm mt-4">
	<thead>
	<tr>
		<th>S.No.</th>
		<th>Item</th>
		<th>Qty</th>
		<th>Rate</th>
		<th class="text-center">Amount</th>
	</tr>
	</thead>
	<tbody>
	<?php

	$billId = $bill['Bill']['id'];
	$totalSaleAmount = 0;
	$i = 0;

	foreach ($bill['Sale'] as $sales) {
		$saleId = $sales['id'];
		$productId = $sales['product_id'];
		$productName = $sales['product_name'];
		$quantity = (int)$sales['total_units'];
		$unitPrice = (float)$sales['unit_price'];
		$amount = (float)$sales['total_amount'];
		$totalSaleAmount += $amount;
		$i++;
		?>
		<tr>
			<td>
				<?= $i ?>
			</td>
			<td>
				<?= $productName ?>
			</td>
			<td>
				<?= $quantity ?>
			</td>
			<td>
				<?= $unitPrice ?>
			</td>
			<td class="text-center">
				<?= $amount ?>
			</td>
		</tr>
		<?php

	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<th colspan="4" class="text-end">Total Amount: </th>
		<th class="text-center"><?= $totalSaleAmount ?></th>
	</tr>
	</tfoot>
</table>
<?php
	}
?>

<script>
	// window.print();
</script>


