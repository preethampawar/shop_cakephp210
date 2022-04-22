<!--
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/stores/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Closing Stock</li>
    </ol>
</nav>
-->

<h2 class="mb-3"><i class="fa fa-plus-circle"></i> Add Closing Stock</h2>

<?php
if ($productsInfo) {
	?>
	<script type="text/javascript">
		var unitSellingPrice = [];
		var availableQty = [];
		<?php
		foreach($productsInfo as $productId => $row) {
		?>
		unitSellingPrice['<?php echo $productId;?>'] = '<?php echo $row['unit_selling_price'];?>';
		availableQty['<?php echo $productId;?>'] = '<?php echo $row['balance_qty'];?>';
		<?php
		}
		?>

		function setMaxClosingStockValue() {
			$('#SaleClosingStockQty').attr('max', 999999);

			var productID = $('#SaleProductId').val();
			var productBalanceQty = availableQty[productID];
			var productName = $('#SaleProductId option:selected').text();
			if (productBalanceQty >= 1) {
				$('#SaleClosingStockQty').attr('max', (productBalanceQty - 1));
			} else {
				alert(productName + ' - is out of stock');
				$('#SaleClosingStockQty').attr('max', 0);
			}
		}

		function setDefaultProductPrice() {
			var productID = $('#SaleProductId').val();
			var unitPrice = parseFloat((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
			$('#SaleUnitPrice').val(unitPrice);
		}

		function setTotalPrice() {
			var productID = $('#SaleProductId').val();
			var productName = $('#SaleProductId option:selected').text();
			var iClosingQty = parseInt(($('#SaleClosingStockQty').val() > 0) ? $('#SaleClosingStockQty').val() : 0);
			var iAvailableQty = parseInt((availableQty[productID] > 0) ? availableQty[productID] : 0);
			var oUnitPrice = parseInt((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
			var oTotalPrice = 0;
			var iTotalUnits = parseInt(iAvailableQty - iClosingQty);

			if (iTotalUnits <= 0) {
				//alert('Product is out of stock');
			} else {
				var oTotalPrice = ((iTotalUnits * oUnitPrice) > 0) ? (iTotalUnits * oUnitPrice).toFixed(2) : 0;
			}


			if (iAvailableQty <= 0) {
				$('#SubmitForm').attr('title', 'Product is out of stock');
			} else {
				if (iTotalUnits <= 0) {
					$('#SubmitForm').attr('title', 'Closing Quantity should be less than ' + iAvailableQty);
				} else {
					if (oTotalPrice <= 0) {
						$('#SubmitForm').attr('title', 'Total amount should be greater than 0');
					} else {
						$('#SubmitForm').attr('title', '');
					}
				}
			}

			// set hidden variables
			$('#SaleTotalUnits').val(iTotalUnits);
			$('#SaleUnitPrice').val(oUnitPrice);
			$('#SaleTotalAmount').val(oTotalPrice);

			// set output
			$('#productName').text(productName);
			$('#oAvailableQty').text(iAvailableQty);
			$('#oSaleQty').text(iTotalUnits);
			$('#oClosingQty').text(iClosingQty);
			$('#oUnitPrice').text(oUnitPrice);
			$('#oTotalPrice').text(oTotalPrice);
		}

		function submitButtonMsg() {
			setTotalPrice();
			if (parseInt($('#SubmitForm').attr('title').length) > 0) {
				alert($('#SubmitForm').attr('title'));
				return false;
			}
			return true;
		}
	</script>


	<div id="AddSaleProductDiv" class="well">
		<?php
		echo $this->Form->create();
		?>
		<div class="row">
			<div class="col-xl-12">
				<div class="mb-3">
					<label for="SaleSaleDate" class="form-label">Select Date:</label>
					<input type="date" name="data[Sale][sale_date]" id="SaleSaleDate" value="<?php echo $saleDate; ?>"
						   class="form-control form-control-sm" required>
					<?php
					echo $this->Form->hidden('date_change', ['type' => 'hidden', 'value' => 0]);
					?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-12">
				<div class="form-group input-group-sm mb-3">
					<?php
					echo $this->Form->input('product_id', [
						'empty' => false,
						'label' => 'Product *[available qty]',
						'required' => true,
						'type' => 'select',
						'options' => $productsList,
						'onchange' => 'setDefaultProductPrice(); setTotalPrice(); setMaxClosingStockValue();',
						'autofocus' => true,
						'escape' => false,
						'class' => 'autoSuggest form-control form-control-sm',
					]); ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-12">
				<div class="form-group input-group-sm mb-3">
					<?php
					echo $this->Form->input('closing_stock_qty', [
						'type' => 'number',
						'min' => '0',
						'max' => '999999',
						'label' => 'Closing Quantity',
						'required' => true,
						'oninput' => 'setTotalPrice()',
						'title' => 'Values should be between 0 to 99999',
						'class' => 'form-control form-control-sm',
						'div' => false,
					]);
					?>

					<?php
					echo $this->Form->input('total_units', ['type' => 'hidden']);
					echo $this->Form->input('unit_price', ['type' => 'hidden']);
					echo $this->Form->input('total_amount', ['type' => 'hidden']);
					echo $this->Form->input('reference', ['type' => 'hidden', 'value' => '#ClosingStock']);
					?>
				</div>
			</div>
		</div>
		<table class="table table-sm table-striped small">
			<tbody>
			<tr>
				<td>Product</td>
				<td><span id="productName"></span></td>
			</tr>
			<tr>
				<td>Closing Quantity</td>
				<td><span id="oClosingQty">0</span></td>
			</tr>
			<tr>
				<td>Sale Quantity</td>
				<td><span id="oSaleQty">0</span></td>
			</tr>
			<tr>
				<td>Unit Selling Price (MRP)</td>
				<td><span id="oUnitPrice"></span></td>
			</tr>
			<tr>
				<td>Sale Amount</td>
				<td><span id="oTotalPrice">0</span></td>
			</tr>
			</tbody>
		</table>

		<div class="row">
			<div class="col-xl-12 text-center">
				<button type="submit" id='SubmitForm' title='' class="btn btn-purple btn-md form-control"
						onclick="return submitButtonMsg()">Add Closing Stock
				</button>
			</div>
		</div>

		<?php
		echo $this->Form->end();
		?>
	</div>

	<script type="text/javascript">
		<?php
		if(!(isset($this->data)) or ($this->Session->check('selectedProductID')))
		{
		?>
		setDefaultProductPrice();
		<?php
		}
		?>
		setTotalPrice();
		setMaxClosingStockValue();
	</script>

	<br><br>
	<h6>Recently Added Products (5)</h6>
	<?php
	if ($saleProducts) {
		?>
		<div class="table-responsive">
			<table class='table table-sm small table-hover'>
				<thead class="table-light">
				<tr>
					<th>Date</th>
					<th>Product</th>
					<th>Closing Qty</th>
					<th>Sale Qty</th>
					<th>Unit Price</th>
					<th>Sale Amount</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($saleProducts as $row) {
					$i++;
					?>
					<tr>
						<td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date'])); ?></td>
						<td><?php echo $row['Sale']['product_name']; ?></td>
						<td><?php echo $row['Sale']['closing_stock_qty']; ?></td>
						<td><?php echo $row['Sale']['total_units']; ?></td>
						<td><?php echo $row['Sale']['unit_price']; ?></td>
						<td><?php echo $row['Sale']['total_amount']; ?></td>
						<td>
							<form method="post" style="" name="sales_<?php echo $row['Sale']['id']; ?>"
								  id="sales_<?php echo $row['Sale']['id']; ?>"
								  action="<?php echo $this->Html->url("/sales/removeProduct/" . $row['Sale']['id']); ?>">
								<button
									type="button"
									class="btn-close"
									aria-label="Close"
									onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name']; ?> from the list?')) { $('#sales_<?php echo $row['Sale']['id']; ?>').submit(); } event.returnValue = false; return false;"
								></button>
							</form>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
		<br>

	<?php } else { ?>
		<p>No sales found</p>
	<?php } ?>

	<?php
} else {
	echo 'No products found. You need to add products to continue.';
}
?>
