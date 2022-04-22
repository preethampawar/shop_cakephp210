<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>

<?php echo $this->element('invoice_info', ['invoiceInfo' => $invoiceInfo]); ?>

<?php
//debug($productsInfo);
if ($productsInfo) {
	$boxQuantity = 0;
	?>
	<script type="text/javascript">
		var unitsInBox = [];
		var unitBoxPrice = [];
		var specialMargin = [];
		<?php
		foreach($productsInfo as $row) {
		?>
		unitsInBox['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_qty'];?>';
		unitBoxPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['box_buying_price'];?>';
		specialMargin['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['special_margin'];?>';
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
			var oSpecialMargin = parseFloat((specialMargin[productID] > 0) ? specialMargin[productID] : 0);
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
			var oTotalUnitsString = ' [' + oTotalUnits + ' units] ';
			var oTotalSpecialMargin = ((oTotalUnits * oSpecialMargin) > 0) ? (oTotalUnits * oSpecialMargin).toFixed(2) : 0;

			// set hidden variables

			$('#PurchaseBoxBuyingPrice').val(oBoxPrice);
			$('#PurchaseUnitsInBox').val(oUnitsInBox);
			$('#PurchaseUnitPrice').val(unitPrice);
			$('#PurchaseSpecialMargin').val(oSpecialMargin);
			$('#PurchaseTotalUnits').val(oTotalUnits);
			$('#PurchaseTotalAmount').val(oTotalPrice);
			$('#PurchaseTotalSpecialMargin').val(oTotalSpecialMargin);


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
			$('#oSpecialMargin').text(oSpecialMargin);
			$('#oTotalSpecialMargin').text(oTotalSpecialMargin);
			$('#oProductName').text(productName);
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
	<br>
	<h2>Add Products</h2>
	<div id="AddInvoiceProductDiv" class="well">
		<?php
		echo $this->Form->create();
		echo $this->Form->input('box_buying_price', ['type' => 'hidden']);
		echo $this->Form->input('units_in_box', ['type' => 'hidden']);
		echo $this->Form->input('unit_price', ['type' => 'hidden']);
		echo $this->Form->input('total_units', ['type' => 'hidden']);
		echo $this->Form->input('total_amount', ['type' => 'hidden']);
		echo $this->Form->input('special_margin', ['type' => 'hidden']);
		echo $this->Form->input('total_special_margin', ['type' => 'hidden']);
		?>
		<div class="row">
			<div class="col-xs-6 col-md-5 col-lg-4 ">
				<?php echo $this->Form->input('product_id', ['empty' => false, 'label' => 'Select Product', 'required' => true, 'type' => 'select', 'options' => $productsList, 'onchange' => 'setExtraUnits(); setTotalPrice()', 'autofocus' => true, 'class' => 'autoSuggest form-control input-sm', 'style' => 'width: 100%;']); ?>
			</div>
			<div class="col-xs-2">
				<?php echo $this->Form->input('box_qty', ['type' => 'number', 'value' => 1, 'min' => '0', 'max' => '99999', 'label' => 'No. of Boxes', 'required' => true, 'oninput' => 'setTotalPrice()', 'title' => 'Values should be between 1 to 99999', 'class' => "form-control input-sm"]); ?>
			</div>
			<div class="col-xs-2">
				<?php
				//debug($boxQuantity);
				$extraUnitArray = [];
				for ($i = 1; $i <= $boxQuantity; $i++) {
					$extraUnitArray[$i - 1] = $i - 1;
				}
				?>
				<?php echo $this->Form->input('extra_units', ['empty' => false, 'label' => 'No.of Units', 'type' => 'select', 'options' => $extraUnitArray, 'onchange' => 'setTotalPrice()', 'autofocus' => true, 'class' => "form-control input-sm"]); ?>
			</div>
			<div class="col-xs-2">
				<button type="submit" class="btn btn-xs btn-primary" style="margin-top: 25px;"
						onclick='return submitButtonMsg()'>Add Product
				</button>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<table class="table" style="width: 80%">
					<thead>
					<tr>
						<th>Product</th>
						<th>Box Price</th>
						<th>Unit Price</th>
						<th>No. of Boxes</th>
						<th class="hidden">Special Margin Per Unit</th>
						<th class="hidden">Total Special Margin</th>
						<th>Total Amount</th>
					</tr>
					</thead>
					<tr>
						<td><span id="oProductName"></span></td>
						<td><span id="oBoxPrice">0</span></td>
						<td><span id="oUnitPrice">0</span></td>
						<td><span id="oTotalBoxQty">0</span> &nbsp;-&nbsp; <span id="oTotalUnits"></span></td>
						<td class="hidden"><span id="oSpecialMargin">0</span></td>
						<td class="hidden"><span id="oTotalSpecialMargin">0</span></td>
						<td><span id="oTotalPrice">0</span></td>
					</tr>
				</table>
			</div>
		</div>
		<?php
		echo $this->Form->end();
		?>
	</div>

	<script type="text/javascript">
		setExtraUnits();
		setTotalPrice();
	</script>


	<?php echo $this->element('invoice_products', ['invoiceProducts' => $invoiceProducts, 'invoiceInfo' => $invoiceInfo]); ?>

	<?php
} else {
	echo 'No products found. You need to add products to continue.';
}
?>
