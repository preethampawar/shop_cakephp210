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

<div class="text-end">
	<a href="/bills/edit/<?= $bill['Bill']['id'] ?>/<?= $autoSubmitVal ?>" class="btn btn-sm <?= $autoSubmitBtnColor ?>"><?= $autoSubmitText ?></a>
	<a href="/bills/printBill/<?= $bill['Bill']['id'] ?>" class="btn btn-light btn-sm ms-3">&#x1F5B6; Print Bill</a>
</div>

<h1>Bill no. <?= $bill['Bill']['id'] ?></h1>
<script>
	var billProductsList = JSON.parse('<?= json_encode($productsList) ?>');
	var billProductsInfo = JSON.parse('<?= json_encode($productsInfo) ?>');

	console.log(billProductsList)
	console.log(billProductsInfo)
</script>

<?php
echo $this->Form->create(null, ['id'=>'BillEditForm']);
?>

<div class="card bg-light">
	<div class="card-body">
		<div class="row">
			<div class="col-9">
				<label for="" class="form-label">Products [available stock]</label>
				<?php
				echo $this->Form->input('product_id', [
						'type'=>'select',
						'name' => "data[Sale][product_id]",
						'class'=>'autoSuggestAutoOpen form-select',
						'id' => 'billProductId',
						'label'=> false,
						'empty'=>'Select Product',
						'required'=>true,
						'options'=>$productsList,
						'onblur'=>'setBillDefaults()',
						'onchange'=>'setBillDefaults()',
						'escape'=>false,
						'autofocus' => true,
				]);
				?>
			</div>

			<div class="col-3">
				<label for="" class="form-label">Date</label>
				<input
						name = "data[Bill][date]"
						type="hidden"
						value="<?= date('Y-m-d', strtotime($bill['Bill']['created'])) ?>"
				>
				<input
						name = "date"
						type="text"
						value="<?= date('d-m-Y', strtotime($bill['Bill']['created'])) ?>"
						id="billDate"
						class="form-control form-control-sm"
						tabindex="1"
						disabled
				>
			</div>
			<!--
			<div class="col">
				<label for="" class="form-label">Name</label>
				<input type="text" class="form-control" placeholder="Name" aria-label="Name">
			</div>
			<div class="col">
				<label for="" class="form-label">Mobile No.</label>
				<input type="text" class="form-control" placeholder="Mobile no." aria-label="Mobie no.">
			</div>
			-->
		</div>
		<div class="row mt-3">

			<div class="col-3">
				<label for="" class="form-label">Quantity</label>
				<input
						name = "data[Sale][total_units]"
						id = "billQty"
						type="number"
						class="form-control"
						placeholder="Quantity"
						aria-label="Quantity"
						value="1"
						step="1"
						min="1"
						onchange="calculateBillAmount()"
				>
			</div>
			<div class="col-3">
				<label for="" class="form-label">Unit Price</label>
				<input
						name = "data[Sale][unit_price]"
						id = "billProductUnitPrice"
						type="number"
						class="form-control"
						placeholder="Unit Price"
						aria-label="Unit Price"
						value="0"
						min="0"
						onchange="calculateBillAmount()"
				>
			</div>
			<div class="col-3">
				<label for="" class="form-label">Amount</label>
				<input
						id = "billProductAmount"
						type="text"
						class="form-control"
						placeholder="0"
						aria-label=""
						disabled
				>
			</div>
			<div class="col-3">
				<label for="" class="form-label">&nbsp;</label>
				<button type="button" class="btn btn-primary form-control" onclick="saveBill()">Submit</button>
			</div>
		</div>
	</div>
</div>

<?php
echo $this->Form->end();

//debug($bill);
?>

<?php
	if ($bill['Sale']) {
?>
<table class="table table-sm mt-4">
	<thead>
	<tr>
		<th>S.No.</th>
		<th>Item</th>
		<th>Qty</th>
		<th>Unit Price</th>
		<th>Amount</th>
		<th></th>
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
			<td>
				<?= $amount ?>
			</td>
			<td class="text-end">
				<a href="/bills/deleteProduct/<?= $billId ?>/<?= $productId ?>"></a>
				<button
						type="button"
						data-url="/bills/deleteProduct/<?= $billId ?>/<?= $productId ?>"
						class="btn btn-danger btn-sm"
						onclick="if(confirm('Are you sure you want to remove this product?')) { window.location = '/bills/deleteProduct/<?= $billId ?>/<?= $productId ?>'}"
				>
					Remove
				</button>
			</td>
		</tr>
		<?php

	}
	?>
	</tbody>
	<tfoot>
	<tr class="fw-bold">
		<td colspan="4" class="text-end">Total Amount: </td>
		<td><?= $totalSaleAmount ?></td>
		<td></td>
	</tr>
	</tfoot>
</table>
		<div class="mt-4 text-center">
			<a href="/bills/printBill/<?= $bill['Bill']['id'] ?>" class="btn btn-light btn-sm">&#x1F5B6; Print Bill</a>
		</div>
<?php
	}
?>
