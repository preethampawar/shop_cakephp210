<?php $this->start('stock_reports_menu'); ?>
<?php echo $this->element('stock_menu'); ?>
<?php echo $this->element('stock_report_menu'); ?>
<?php $this->end(); ?>


	<h1>Add All Products Closing Stock</h1><br>

<?php
if ($productsList) {
	?>
	<div id="AddSaleProductDiv" class="row">
		<div class="col-lg-8 col-md-10">
			<?php
			echo $this->Form->create(null, ['validate' => 'validate']);
			?>

			<?php echo $this->Form->input('sale_date', ['label' => 'Sale Date', 'required' => true, 'type' => 'date']); ?>
			<br>
			<table class="table table-striped table-bordered table-condensed search-table">
				<thead>
				<tr>
					<th>Sl.No.</th>
					<th>Product Name [Available Qty]</th>
					<th>Closing Qty</th>
					<th>Sale Qty</th>
					<th>MRP</th>
					<th>Total Amount</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($productsList as $product_id => $product_name) {
					$i++;
					$unit_selling_price = $productsSellingPrice[$product_id];
					$stock = $products_stock[$product_id];
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $product_name; ?> &nbsp; &nbsp;&nbsp; &nbsp; [<?php echo $stock; ?>]</td>
						<td>
							<div class="input-group-sm">
								<?php
								echo $this->Form->input('closing_stock_qty.' . $product_id, ['type' => 'number', 'min' => '0', 'max' => $stock, 'label' => false, 'required' => false, 'title' => 'Values should be between 0 to ' . $stock, 'class' => 'form-control input-sm', 'div' => false,
									'onkeyup' => "validateForm(this); calculateAmount(this, $product_id, $stock, $unit_selling_price)"]);
								?>
							</div>
						</td>
						<td>
							<span id="productSaleQty<?php echo $product_id; ?>"></span>
						</td>
						<td><?php echo $unit_selling_price; ?></td>
						<td>
							<span id="productSaleAmount<?php echo $product_id; ?>"></span>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>

			<div class="submitLinkButton">
				<input type="button" class="submitLinkButton btn btn-warning btn-lg" value="Update Closing Stock"
					   onclick='submitForm()'>
			</div>

			<input type="button" id="formSubmitButton" style="display:none;">
			<?php
			echo $this->Form->end();
			?>
			<br><br><br>
		</div>
	</div>
	<style type="text/css">
		.submitLinkButton {
			position: fixed;
			bottom: 10px;
			right: 10px;
		}
	</style>

	<script>
		function validateForm() {
			var $myForm = $('#SaleAddAllClosingStockForm');

			if (!$myForm[0].checkValidity()) {
				// If the form is invalid, submit it. The form won't actually submit;
				// this will just cause the browser to display the native HTML5 error messages.
				$('#formSubmitButton').attr('type', 'submit');
				$myForm.find(':submit').click();
				$('#formSubmitButton').attr('type', 'button');

			}
			return false;
		}

		function calculateAmount(selectedElement, product_id, available_qty, unit_selling_price) {
			var selectedQty = selectedElement.value;
			if (selectedQty && available_qty && unit_selling_price) {
				var saleQty = available_qty - selectedQty;
				var saleAmt = (unit_selling_price * saleQty).toFixed(2);
				if (saleQty >= 0) {
					$('#productSaleQty' + product_id).text(saleQty);
					$('#productSaleAmount' + product_id).text(saleAmt);
				}
			} else {
				$('#productSaleQty' + product_id).text('');
				$('#productSaleAmount' + product_id).text('');
			}
		}

		function submitForm() {
			var $myForm = $('#SaleAddAllClosingStockForm');
			if (!$myForm[0].checkValidity()) {
				$('#formSubmitButton').attr('type', 'submit');
				$myForm.find(':submit').click();
				$('#formSubmitButton').attr('type', 'button');
			} else {
				$('#formSubmitButton').attr('type', 'submit');
				if (confirm('Are you sure you want to update closing stock?')) {
					$myForm.find(':submit').click();
					return true;
				}
			}
			return false;
		}
	</script>

	<?php
} else {
	echo 'No products found. You need to add products to continue.';
}
?>
