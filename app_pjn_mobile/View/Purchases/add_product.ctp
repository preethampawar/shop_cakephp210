<a href="/purchases/selectCategory/<?= $invoiceInfo['Invoice']['id'] ?>" class="btn btn-sm btn-purple"> &laquo; Back</a>
<a href="/invoices/index/purchase" class="btn btn-sm btn-secondary ml-3">Go to Invoice List</a>

<div class="mt-3">
	<h1>
		<?php echo $invoiceInfo['Invoice']['invoice_type'] == 'purchase' ? 'Purchase Invoice' : 'Sale Invoice'; ?>
		- <?php echo $invoiceInfo['Invoice']['name']; ?>
	</h1>
</div>
<hr>
<h4 class="mt-3">Add Product - Step2</h4>

<?php

if ($productsInfo) {
	$boxQuantity = 0;
	?>
	<script type="text/javascript">
		var unitsInBox = [];
		var unitBoxPrice = [];
		<?php
		foreach($productsInfo as $row) {
		?>
		unitsInBox['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_qty'];?>';
		unitBoxPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_buying_price'];?>';
		<?php
		$boxQuantity = $row['Product']['box_qty'];
		}
		?>

		function setExtraUnits() {
			var productID = $('#PurchaseProductId').val();
			var extra_units = parseInt(unitsInBox[productID]);
			var select_options = '';
			if (extra_units) {
				for (var i = 0; i < extra_units; i++) {
					select_options = select_options + '<option value="' + i + '">' + i + '</option>';
				}
			}
			$('#PurchaseExtraUnits').html(select_options);

		}

		function setTotalPrice() {
			var productID = $('#PurchaseProductId').val();
			var productName = $('#PurchaseProductId option:selected').text();
			var iBoxQty = parseInt(($('#PurchaseBoxQty').val() > 0) ? $('#PurchaseBoxQty').val() : 0);
			//var iBoxQty = $('#PurchaseBoxQty').val();
			var extraUnits = $('#PurchaseExtraUnits').val();
			//alert(extraUnits);
			var iBoxQtyText = "";
			if (extraUnits != 0) {
				iBoxQtyText = iBoxQty + '.' + extraUnits;
			} else {
				iBoxQtyText = iBoxQty;
			}
			var oBoxPrice = parseFloat((unitBoxPrice[productID] > 0) ? unitBoxPrice[productID] : 0);
			var oUnitsInBox = parseInt((unitsInBox[productID] > 0) ? unitsInBox[productID] : 0);

			var unitPrice = 0;
			if (parseInt(oUnitsInBox) > 0) {
				unitPrice = parseFloat((oBoxPrice / oUnitsInBox)).toFixed(2);
			}
			var oTotalPrice = ((iBoxQty * oBoxPrice) > 0) ? (iBoxQty * oBoxPrice).toFixed(2) : 0;
			var oTotalUnits = parseInt(((iBoxQty * oUnitsInBox) > 0) ? (iBoxQty * oUnitsInBox) : 0);
			if (extraUnits != 0) {
				oTotalUnits = parseInt(oTotalUnits) + parseInt(extraUnits);
				var pricePerUnit = parseFloat(oBoxPrice / oUnitsInBox);
				var extraUnitsPrice = parseFloat(pricePerUnit * extraUnits);
				oTotalPrice = parseFloat(oTotalPrice) + parseFloat(extraUnitsPrice);
				oTotalPrice = oTotalPrice.toFixed(2);

			}
			var oTotalUnitsString = oTotalUnits + ' unit(s)';

			// set hidden variables
			$('#PurchaseBoxBuyingPrice').val(oBoxPrice);
			$('#PurchaseUnitsInBox').val(oUnitsInBox);
			$('#PurchaseUnitPrice').val(unitPrice);
			$('#PurchaseTotalUnits').val(oTotalUnits);
			$('#PurchaseTotalAmount').val(oTotalPrice);


			if (oTotalPrice <= 0) {
				$('#SubmitForm').attr('title', 'Total amount should be greater than 0');
			} else {
				$('#SubmitForm').attr('title', '');
			}

			// set output
			$('#oTotalBoxQty').text(iBoxQtyText);
			$('#oOneBoxQty').text(oUnitsInBox);
			$('#oBoxPrice').text(oBoxPrice);
			$('#oUnitPrice').text(unitPrice);
			$('#oTotalUnits').text(oTotalUnitsString);
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

	<div id="AddInvoiceProductDiv" class="well">
		<?php
		echo $this->Form->create();
		echo $this->Form->input('box_buying_price', ['type' => 'hidden']);
		echo $this->Form->input('units_in_box', ['type' => 'hidden']);
		echo $this->Form->input('unit_price', ['type' => 'hidden']);
		echo $this->Form->input('total_units', ['type' => 'hidden']);
		echo $this->Form->input('total_amount', ['type' => 'hidden']);
		?>

		<div class="mt-3">
			<lable>Category</lable>
			<input type="text" class="form-control form-control-sm"
				   value="<?php echo $categoryInfo['ProductCategory']['name']; ?>" disabled>
		</div>
		<div class="mt-3">
			<?php echo $this->Form->input('product_id', ['empty' => false, 'label' => 'Select Product', 'required' => true, 'type' => 'select', 'options' => $productsList, 'onchange' => 'setExtraUnits(); setTotalPrice()', 'autofocus' => true, 'class' => 'autoSuggest form-control input-sm', 'style' => 'width: 100%;']); ?>
		</div>
		<div class="mt-3">
			<?php echo $this->Form->input('box_qty', ['type' => 'number', 'value' => 1, 'min' => '0', 'max' => '99999', 'label' => 'No. of Boxes', 'required' => true, 'oninput' => 'setTotalPrice()', 'title' => 'Values should be between 1 to 99999', 'class' => "form-control input-sm"]); ?>
		</div>
		<div class="mt-3">
			<?php
			//debug($boxQuantity);
			$extraUnitArray = [];
			for ($i = 1; $i <= $boxQuantity; $i++) {
				$extraUnitArray[$i - 1] = $i - 1;
			}
			?>
			<?php echo $this->Form->input('extra_units', ['empty' => false, 'label' => 'No.of Units', 'type' => 'select', 'options' => $extraUnitArray, 'onchange' => 'setTotalPrice()', 'autofocus' => true, 'class' => "form-control input-sm"]); ?>
		</div>

		<div class="mt-3">
			<table class="table table-condensed table-striped small">
				<thead>
				<tr>
					<th class="text-center">Box Price</th>
					<th class="text-center">Unit Price</th>
					<th class="text-center">No. of Boxes</th>
					<th class="text-center">Total Amount</th>
				</tr>
				</thead>
				<tr>
					<td class="text-center"><span id="oBoxPrice">0</span></td>
					<td class="text-center"><span id="oUnitPrice">0</span></td>
					<td class="text-center"><span id="oTotalBoxQty">0</span><br><span id="oTotalUnits"
																					  class="text-primary font-italic"></span>
					</td>
					<td class="text-center"><span id="oTotalPrice">0</span></td>
				</tr>
			</table>
		</div>

		<div class="text-center">
			<button id="SubmitForm" title="" type="submit" class="btn btn-sm btn-purple mt-3"
					onclick='return submitButtonMsg()'>Submit
			</button>

			<a href="/invoices/index/purchase" class="btn btn-sm btn-danger ml-3 mt-3" role="button">Cancel</a>
		</div>
		<?php
		echo $this->Form->end();
		?>
	</div>

	<script type="text/javascript">
		setExtraUnits();
		setTotalPrice();
	</script>

	<?php
} else {
	echo 'No products found. You need to add products to continue.';
}
?>
