<a href="/invoices/edit/<?php echo $invoiceInfo['Invoice']['id']; ?>" class="btn btn-sm btn-purple"> &laquo; Back to
	Step-1</a>
<a href="/invoices/" class="btn btn-sm btn-secondary ml-3">Go to Invoice List</a>

<h1 class="mt-3">Add Invoice Products</h1>
<h4>Step-2: Invoice No. <?php echo $this->Session->read('Invoice.name'); ?></h4>
<hr>

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

	<div id="AddInvoiceProductDiv" class="mt-3">
		<?php
		echo $this->Form->create();
		?>
		<div id="paramsDiv">
			<div class="mb-3">
				<?php echo $this->Form->input('product_id', [
						'empty' => false,
						'label' => 'Select Product',
						'required' => true,
						'type' => 'select',
						'options' => $productsList,
						'onchange' => 'setExtraUnits(); setTotalPrice()',
						'autofocus' => true,
						'class' => 'autoSuggest form-control form-control-sm',
					]
				); ?>
			</div>

			<div class="mb-3">
				<?php echo $this->Form->input('box_qty', [
						'type' => 'number',
						'value' => 1,
						'min' => '0',
						'max' => '99999',
						'label' => 'No. of Boxes',
						'required' => true,
						'oninput' => 'setTotalPrice()',
						'title' => 'Values should be between 1 to 99999',
						'class' => 'form-control form-control-sm',
					]
				);
				echo $this->Form->input('box_buying_price', ['type' => 'hidden']);
				echo $this->Form->input('units_in_box', ['type' => 'hidden']);
				echo $this->Form->input('unit_price', ['type' => 'hidden']);
				echo $this->Form->input('total_units', ['type' => 'hidden']);
				echo $this->Form->input('total_amount', ['type' => 'hidden']);
				echo $this->Form->input('special_margin', ['type' => 'hidden', 'value' => 0]);
				echo $this->Form->input('total_special_margin', ['type' => 'hidden', 'value' => 0]);
				?>
			</div>

			<?php
			//debug($boxQuantity);
			$extraUnitArray = [];
			for ($i = 1; $i <= $boxQuantity; $i++) {
				$extraUnitArray[$i - 1] = $i - 1;
			}
			?>
			<div class="mb-3">
				<?php
				echo $this->Form->input('extra_units', [
					'empty' => false,
					'label' => 'No.of Units',
					'type' => 'select',
					'options' => $extraUnitArray,
					'onchange' => 'setTotalPrice()',
					'autofocus' => true,
					'class' => 'form-control form-control-sm',
				]);
				?>
			</div>


		</div>
		<table class="table table-sm small table-striped">
			<tbody>
			<tr>
				<td>Product</td>
				<td><span id="oProductName"></span></td>
			</tr>
			<tr>
				<td>Box Price</td>
				<td><span id="oBoxPrice">0</span></td>
			</tr>
			<tr>
				<td>Unit Price</td>
				<td><span id="oUnitPrice">0</span></td>
			</tr>
			<tr>
				<td>No. of Boxes</td>
				<td><span id="oTotalBoxQty">0</span> &nbsp;-&nbsp; <span id="oTotalUnits"></span></td>
			</tr>
			<tr>
				<td>Purchase Amount</td>
				<td><span id="oTotalPrice">0</span></td>
			</tr>
			</tbody>
		</table>

		<div class="mb-3">
			<button id="SubmitForm" title='' type="submit" class="btn btn-purple btn-md form-control"
					onclick="return submitButtonMsg()">Add Product
			</button>
		</div>

		<div class="text-center mt-4">
			<a href="/invoices/" class="btn btn-sm btn-outline-danger">Cancel</a>
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
	//debug($invoiceProducts);
	if ($invoiceProducts) {
		?>
		<br>
		<h5>Invoice Products</h5>
		<div class="table-responsive">
			<table class="table table-sm table-hover small table-bordered">
				<thead class="table-light">
				<tr>
					<th>#</th>
					<th>Product Name</th>
					<th>No. of Boxes</th>
					<th>Unit Box Price</th>
					<th>Total Amount</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				$totalBoxes = 0;
				$totalAmount = 0;
				$totalSpecialMargin = 0;
				$totalNoOfUnits = 0;
				$totalNoOfUnits = 0;
				$tax = $this->Session->read('Invoice.tax');
				foreach ($invoiceProducts as $row) {
					$i++;
					$totalBoxes += $row['Purchase']['box_qty'];
					$totalAmount += $row['Purchase']['total_amount'];
					// $totalSpecialMargin += $row['Purchase']['total_special_margin'];
					$totalUnits = $row['Purchase']['total_units'];
					$noOfBoxes = floor($row['Purchase']['total_units'] / $row['Purchase']['units_in_box']);
					$unitInBox = $row['Purchase']['units_in_box'];
					$noOfUnits = ($totalUnits) - ($noOfBoxes * $unitInBox);
					$totalNoOfUnits += $noOfUnits;
					//debug($row['Product']);
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td>
							<a class="dropdown-toggle" role="button" id="dropdownMenuButton<?= $i ?>" data-bs-toggle="dropdown" aria-expanded="false">
								<?php echo $row['Purchase']['product_name']; ?>
							</a>
							<ul class="dropdown-menu small" aria-labelledby="dropdownMenuButton<?= $i ?>">
								<?php
								if ($this->Session->read('Store.show_brands_in_products')) {
									if (isset($row['Product']['Brand']['name'])) {
										?>
										<li>
											<a class="dropdown-item small disabled"><?php echo $row['Product']['Brand']['name']; ?>
												></a>
										</li>
										<li>
											<hr class="dropdown-divider">
										</li>
										<?php
									}
									?>
									<?php
								}
								?>

								<li>
									<form method="post"
										  name="invoice_remove_product_<?php echo $row['Purchase']['id']; ?>"
										  id="invoice_remove_product_<?php echo $row['Purchase']['id']; ?>"
										  action="<?php echo $this->Html->url("/purchases/removeProduct/" . $row['Purchase']['id']); ?>">
										<a href="#"
										   name="Remove"
										   onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Purchase']['product_name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Purchase']['id']; ?>').submit(); } event.returnValue = false; return false;"
										   class="dropdown-item small">
											Delete
										</a>
									</form>
								</li>
							</ul>
						</td>
						<td><?php echo $row['Purchase']['box_qty'];
							if ($noOfUnits) {
								echo "&nbsp;($noOfUnits)";
							}
							?></td>

						<td><?php echo $row['Purchase']['box_buying_price']; ?></td>
						<td><?php echo $row['Purchase']['total_amount']; ?></td>
					</tr>
					<?php
				}
				?>
				<tfoot style="font-weight:bold;">
				<tr>
					<td colspan='2'></td>
					<td>
						<?php echo $totalBoxes;
						if ($totalNoOfUnits) {
							echo "&nbsp;($totalNoOfUnits)";
						}
						?> Boxes
					</td>
					<td></td>
					<td colspan='1'><?php echo number_format($totalAmount, '2', '.', ''); ?></td>
				</tr>
				<tr>
					<td colspan='5'>&nbsp;</td>
				</tr>

				<tr>
					<td style="text-align:right;" colspan='4'>
						Invoice Value: <br>

						MRP Rounding Off: <br>
						Net Invoice Value: <br>
					</td>
					<td>
						<?php echo number_format($totalAmount, '2', '.', ''); ?> <br>
						<?php echo $invoiceInfo['Invoice']['mrp_rounding_off']; ?> <br>
						<?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['special_margin'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?>
						<br>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='5'>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='4'>e-challan / DD Amount:</td>
					<td><?php echo $invoiceInfo['Invoice']['dd_amount']; ?></td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='4'>Previous Credit:</td>
					<td><?php echo $invoiceInfo['Invoice']['prev_credit']; ?></td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='4'>Sub Total:</td>
					<td><?php echo number_format($invoiceInfo['Invoice']['dd_amount'] + $invoiceInfo['Invoice']['prev_credit'], '2', '.', ''); ?></td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='4'>(-) Less this Invoice Value:</td>
					<td><?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['special_margin'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
				</tr>

<!---->
<!--				<tr>-->
<!--					<td style="text-align:right;" colspan='4'>Retail Shop Excise Turnover Tax:</td>-->
<!--					<td>--><?php //echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax']; ?><!--</td>-->
<!--				</tr>-->
				<tr>
					<td style="text-align:right;" colspan='4'>Special Excise Cess:</td>
					<td><?php echo $invoiceInfo['Invoice']['special_excise_cess']; ?></td>
				</tr>


				<tr>
					<td style="text-align:right;" colspan='4'>TCS:</td>
					<td><?php echo $invoiceInfo['Invoice']['tcs_value']; ?></td>
				</tr>

				<tr>
					<td style="text-align:right;" colspan='4'>New Retailer Professional Tax:</td>
					<td><?php echo $invoiceInfo['Invoice']['new_retailer_prof_tax'];?></td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='4'>Retailer Credit Balance:</td>
					<td><?php echo $invoiceInfo['Invoice']['credit_balance']; ?></td>
				</tr>
				</tfoot>
				</tbody>
			</table>
		</div>
	<?php } else { ?>
		<!-- <p>No products found in Invoice "<?php echo $this->Session->read('Invoice.name'); ?>".</p> -->
	<?php } ?>

	<?php
} else {
	echo '<p>There are no products in this store. Please create a product first.</p>';
}
?>
<br><br>
