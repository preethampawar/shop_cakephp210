<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>
	<br>
<?php echo $this->element('invoice_info', ['invoiceInfo' => $invoiceInfo]); ?>

	<br>
	<h2>Add Product</h2>

<?php
if ($productsInfo) {
	?>
	<script type="text/javascript">
		var unitSellingPrice = [];
		var availableQty = [];
		<?php
		foreach($productsInfo as $row) {
		?>
		unitSellingPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['unit_selling_price'];?>';
		availableQty['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['ProductStockReport']['balance_qty'];?>';
		<?php
		}
		?>
		let discount = parseFloat("<?php echo ($invoiceInfo['Invoice']['discount'] > 0) ? $invoiceInfo['Invoice']['discount'] : 0;?>");
		let sgst = parseFloat("<?php echo ($invoiceInfo['Invoice']['sgst'] > 0) ? $invoiceInfo['Invoice']['sgst'] : 0;?>");
		let cgst = parseFloat("<?php echo ($invoiceInfo['Invoice']['cgst'] > 0) ? $invoiceInfo['Invoice']['cgst'] : 0;?>");
		let totalAmountAfterTaxes = 0;

		function setDefaultProductPrice() {
			var productID = $('#SaleProductId').val();
			var unitPrice = parseFloat((unitSellingPrice[productID] > 0) ? unitSellingPrice[productID] : 0);
			$('#SaleUnitPrice').val(unitPrice);
		}

		function setTotalPrice() {
			var productID = $('#SaleProductId').val();
			var iTotalUnits = parseInt(($('#SaleTotalUnits').val() > 0) ? $('#SaleTotalUnits').val() : 0);
			var unitPrice = parseInt(($('#SaleUnitPrice').val() > 0) ? $('#SaleUnitPrice').val() : 0);
			var iAvailableQty = parseInt((availableQty[productID] > 0) ? availableQty[productID] : 0);
			let iDiscount = parseFloat(($('#SaleDiscount').val() > 0) ? $('#SaleDiscount').val() : 0);
			let iSgst = parseFloat(($('#SaleSgst').val() > 0) ? $('#SaleSgst').val() : 0);
			let iCgst = parseFloat(($('#SaleCgst').val() > 0) ? $('#SaleCgst').val() : 0);
			let iIgst = parseFloat(($('#SaleIgst').val() > 0) ? $('#SaleIgst').val() : 0);

			var oTotalPrice = ((iTotalUnits * unitPrice) > 0) ? (iTotalUnits * unitPrice).toFixed(2) : 0;
			let oTotalSaleAmount = 0;
			let discountedSaleAmount = oTotalPrice * (100 - iDiscount) / 100;
			oTotalSaleAmount = discountedSaleAmount + (discountedSaleAmount * (iSgst + iCgst + iIgst) / 100);

			var oTotalUnits = iTotalUnits;

			if (iAvailableQty <= 0) {
				$('#SubmitForm').attr('title', 'Product is out of stock');
			} else {
				if (iAvailableQty < iTotalUnits) {
					$('#SubmitForm').attr('title', 'No. of Units cannot be greater than ' + iAvailableQty);
				} else {
					if (iTotalUnits <= 0) {
						$('#SubmitForm').attr('title', 'No. of Units should be greater than 0');
					} else {
						if (oTotalPrice <= 0) {
							$('#SubmitForm').attr('title', 'Total amount should be greater than 0');
						} else {
							$('#SubmitForm').attr('title', '');
						}
					}
				}
			}

			$('#SaleSaleAmountWithoutTaxes').val(oTotalPrice);

			// calculate discount and taxes

			$('#SaleTotalAmount').val(oTotalSaleAmount);

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


	<div id="AddSaleProductDiv" class="">
		<?php
		echo $this->Form->create();
		?>

		<table class="table table-striped" style="width:600px;">
			<tbody>
			<tr>
				<td style="width:200px;">Product [available stock]</td>
				<td>
					<?php echo $this->Form->input('product_id', ['empty' => false, 'label' => false, 'required' => true, 'type' => 'select', 'options' => $productsList, 'onchange' => 'setDefaultProductPrice(); setTotalPrice();', 'autofocus' => true, 'escape' => false, 'class' => 'autoSuggest form-control input-sm', 'style' => 'width: 100%;']); ?>
				</td>
			</tr>
			<tr>
				<td>Unit Price</td>
				<td>
					<?php
					echo $this->Form->input('unit_price', ['type' => 'text', 'label' => false, 'required' => true, 'oninput' => 'setTotalPrice()', 'title' => 'Unit Price', 'class' => "form-control input-sm"]);
					?>
				</td>
			</tr>
			<tr>
				<td>No. of Units</td>
				<td>
					<?php
					echo $this->Form->input('total_units', ['type' => 'number', 'min' => '1', 'max' => '99999', 'label' => false, 'required' => true, 'oninput' => 'setTotalPrice()', 'title' => 'Values should be between 1 to 99999', 'class' => "form-control input-sm"]);
					?>
				</td>
			</tr>
			<tr>
				<td><b>Sale Value</b></td>
				<td>
					<?php echo $this->Form->input('sale_amount_without_taxes', ['label' => false, 'title' => 'Sale Value', 'type' => 'number', 'class' => 'form-control input-sm', 'readonly' => true, 'default' => 0, 'min' => 0, 'max' => 10000000000, 'step' => '0.01']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Discount (%)
				</td>
				<td>
					<?php echo $this->Form->input('discount', ['label' => false, 'title' => 'Discount in percentage', 'type' => 'number', 'oninput' => 'setTotalPrice()', 'class' => 'form-control input-sm', 'default' => $invoiceInfo['Invoice']['discount'], 'min' => 0, 'max' => 100, 'step' => '0.1']); ?>
				</td>
			</tr>
			<tr>
				<td>
					SGST (%)
				</td>
				<td>
					<?php echo $this->Form->input('sgst', ['label' => false, 'title' => 'SGST in percentage', 'type' => 'number', 'oninput' => 'setTotalPrice()', 'class' => 'form-control input-sm', 'default' => $invoiceInfo['Invoice']['sgst'], 'min' => 0, 'max' => 100, 'step' => '0.1']); ?>
				</td>
			</tr>
			<tr>
				<td>
					CGST (%)
				</td>
				<td>
					<?php echo $this->Form->input('cgst', ['label' => false, 'title' => 'CGST in percentage', 'type' => 'number', 'oninput' => 'setTotalPrice()', 'class' => 'form-control input-sm', 'default' => $invoiceInfo['Invoice']['cgst'], 'min' => 0, 'max' => 100, 'step' => '0.1']); ?>
				</td>
			</tr>
			<tr>
				<td>
					IGST (%)
				</td>
				<td>
					<?php echo $this->Form->input('igst', ['label' => false, 'title' => 'IGST in percentage', 'type' => 'number', 'oninput' => 'setTotalPrice()', 'class' => 'form-control input-sm', 'default' => $invoiceInfo['Invoice']['igst'], 'min' => 0, 'max' => 100, 'step' => '0.1']); ?>
				</td>
			</tr>
			<tr>
				<td><b>Total Sale Amount</b></td>
				<td style="font-weight: bold">
					<?php echo $this->Form->input('total_amount', ['label' => false, 'title' => 'Total Sale Amount', 'type' => 'number', 'class' => 'form-control input-sm', 'readonly' => true, 'default' => 0, 'min' => 0, 'max' => 10000000000, 'step' => '0.01']); ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<button type="submit" class="btn btn-sm btn-primary" onclick='return submitButtonMsg()'>Add
						Product
					</button>
				</td>
			</tr>
			</tbody>
		</table>

		<?php
		echo $this->Form->end();
		?>
		<script type="text/javascript">
			$(document).ready(function () {
				<?php
				$setDefaultPrice = (!(isset($this->data)) or !($this->data)) ? true : (($this->Session->check('selectedProductID')) ? true : false);
				echo ($setDefaultPrice) ? 'setDefaultProductPrice();' : null;
				echo 'setTotalPrice();';
				?>
			});
		</script>

	</div>
	<br>

	<?php echo $this->element('sale_invoice_products', ['invoiceProducts' => $invoiceProducts, 'invoiceInfo' => $invoiceInfo]); ?>


	<?php
} else {
	echo 'No products found. You need to add products to continue.';
}
?>
