<div>

	<?php
	$this->set('enableTextEditor', true);
	echo $this->Form->create();
	$lineNo = 0;
	?>
	<div>
		<?php
		echo $this->Form->input('type', [
				'label' => 'Select Type: &nbsp; ',
				'type' => 'select',
				'value' => 'invoice',
				'options' => ['invoice' => 'Invoice', 'quotation' => 'Quotation'],
			]
		);
		?>
	</div>

	<div style="width: 800px" class="p-2">
		<!-- Quotation header -->
		<div class="corner setBackground">
			<table class="w-100">
				<tr>
					<td>
						<h4 class="text-center">Header</h4>
						<?php echo $this->Form->input('header', [
							'label' => false,
							'type' => 'textarea',
							'class' => 'texteditor',
							'default' => '<h1 style="text-align: center;">Invoice / Quotation</h1>',
						]); ?>
					</td>
				</tr>
			</table>
		</div>

		<hr class="mt-5">

		<!-- From Address -->
		<div class="mt-5">
			<table class="w-100">
				<tr>
					<td class="w-75">
						<h4 class="text-center">From Company Details</h4>
						<?php echo $this->Form->input('from_label', ['label' => false, 'class' => 'form-control input-sm mb-3', 'default' => 'From Company / Individual:']); ?>
						<?php echo $this->Form->input('from', [
							'label' => false,
							'type' => 'textarea',
							'class' => 'texteditor',
							'default' => '<b>From Company:</b><br><br><b>Address:</b>',
						]); ?>
					</td>
					<td class="align-top" style="vertical-align:top">
						<h4 class="text-center">Date</h4>
						<?php echo $this->Form->input('from_date_label', ['label' => false, 'class' => 'form-control input-sm mb-3', 'default' => 'Date:']); ?>
						<input
							type="date"
							name="data[InvoiceQuotation][from_date]"
							class="form-control input-sm"
							value="<?php echo $this->data['InvoiceQuotation']['from_date']; ?>"
						>
					</td>
				</tr>
			</table>
		</div>

		<hr class="mt-5">

		<div class="mt-5">
			<table class="w-100">
				<tr>
					<td class="w-75">
						<h4 class="text-center">To Company Details</h4>
						<?php echo $this->Form->input('for_label', ['label' => false, 'class' => 'form-control input-sm mb-3', 'default' => 'Quotation / Invoice For:']); ?>
						<?php echo $this->Form->input('for', [
							'label' => false,
							'type' => 'textarea',
							'class' => 'texteditor',
							'default' => '<b>To Company:</b><br><br><b>Address:</b>',
						]); ?>
					</td>
					<td class="align-top" style="vertical-align:top">
						<h4 class="text-center">Validity Date</h4>
						<?php echo $this->Form->input('for_date_label', ['label' => false, 'class' => 'form-control input-sm mb-3', 'default' => 'Validity:']); ?>
						<input
							type="date"
							name="data[InvoiceQuotation][for_date]"
							class="form-control input-sm"
							value="<?php echo $this->data['InvoiceQuotation']['for_date']; ?>"
						>
					</td>
				</tr>
			</table>
		</div>

		<hr class="mt-5">

		<!-- Items list -->
		<div class="mt-5">
			<h4 class="text-center">Items / Work done</h4>

			<table class="table table-bordered table-sm table-striped">
				<tbody id='itemsTbody'>
				<tr>
					<th class="text-center">Goods Description / Service</th>
					<th style="width:150px;">HSN/ACS</th>
					<th style="width:80px;">Quantity/Hrs.</th>
					<th style="width:80px;">Unit Price</th>
					<th style="width:130px;">Amount</th>
				</tr>
				<?php
				if (!empty($this->data['EntityItem'])) {
					foreach ($this->data['EntityItem'] as $index => $row) {
						$itemId = $row['id'];
						$item = $row['item'];
						$description = $row['description'];
						$quantity = $row['quantity'];
						$unitrate = $row['unitrate'];
						$amount = $row['amount'];
						?>
						<tr id="lineNo<?php echo $index; ?>">
							<td>
								<?php
								echo $this->Form->hidden('EntityItem.id.' . $index, ['value' => $itemId]);
								echo $this->Form->input('EntityItem.item.' . $index, ['default' => $item, 'type' => 'text', 'label' => false, 'div' => false, 'lineno' => 0, 'required' => true, 'class' => 'form-control input-sm']);
								?>
							</td>
							<td><?php echo $this->Form->input('EntityItem.description.' . $index, ['default' => $description, 'label' => false, 'type' => 'text', 'div' => false, 'lineno' => 0, 'required' => false, 'class' => 'form-control input-sm']); ?></td>
							<td><?php echo $this->Form->input('EntityItem.quantity.' . $index, ['default' => $quantity, 'type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '0', 'lineno' => 0, 'onchange' => 'calculateQuotationTotal()', 'required' => true, 'class' => 'form-control input-sm']); ?></td>
							<td><?php echo $this->Form->input('EntityItem.unitrate.' . $index, ['default' => $unitrate, 'type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '0.00', 'lineno' => 0, 'onchange' => 'calculateQuotationTotal()', 'required' => true, 'class' => 'form-control input-sm']); ?></td>
							<td>
								<?php echo $this->Form->input('EntityItem.amount.' . $index, ['default' => $amount, 'type' => 'text', 'readonly' => true, 'label' => false, 'class' => 'readonly lineamount', 'div' => false, 'style' => 'width:90px; float:left;', 'placeholder' => '0.00', 'lineno' => 0, 'class' => 'form-control input-sm']); ?>

								<?php if ($index > 0) : ?>
									<span onclick='removeLine(<?php echo $index; ?>)' title='Remove this row'
										  lineno='<?php echo $index; ?>'
										  style='float: left; font-size:25px; cursor: pointer; margin-left: 2px;'>&times;</span>
								<?php endif; ?>
							</td>
						</tr>
						<?php
						$lineNo = $index;
					}
				} else {
					?>
					<tr>
						<td><?php echo $this->Form->input('EntityItem.item.0', ['type' => 'text', 'label' => false, 'div' => false, 'lineno' => 0, 'required' => true, 'class' => 'form-control input-sm']); ?></td>
						<td><?php echo $this->Form->input('EntityItem.description.0', ['label' => false, 'type' => 'text', 'div' => false, 'lineno' => 0, 'required' => true, 'class' => 'form-control input-sm']); ?></td>
						<td><?php echo $this->Form->input('EntityItem.quantity.0', ['type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '0', 'lineno' => 0, 'onchange' => 'calculateQuotationTotal()', 'required' => true, 'class' => 'form-control input-sm']); ?></td>
						<td><?php echo $this->Form->input('EntityItem.unitrate.0', ['type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '0.00', 'lineno' => 0, 'onchange' => 'calculateQuotationTotal()', 'required' => true, 'class' => 'form-control input-sm']); ?></td>
						<td><?php echo $this->Form->input('EntityItem.amount.0', ['type' => 'text', 'readonly' => true, 'label' => false, 'class' => 'readonly lineamount', 'div' => false, 'style' => 'width:100px;', 'placeholder' => '0.00', 'lineno' => 0, 'class' => 'form-control input-sm']); ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan='5' class="text-center">
						<span class="btn btn-sm btn-info" onclick="addNewLine()"> + Add New Item </span>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>

		<hr class="mt-5">

		<!-- Subtotal -->
		<div class="mt-5">
			<table class="w-100">
				<tr>
					<td class="w-75">
						<!-- Quotation for info-->
						<div>
							<table class="w-100">
								<tr>
									<td>
										<h4 class="text-center">Instructions</h4>
										<?php echo $this->Form->input('instructions', [
											'label' => false,
											'type' => 'textarea',
											'class' => 'texteditor',
											'default' => '<b>Terms & Conditions / Instructions:</b><br>',
										]); ?>
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td class="text-right" style="vertical-align:top">
						<table class="w-100">
							<tbody>
							<tr>
								<td class="text-right font-weight-bold">Subtotal:</td>
								<td class="text-right font-weight-bold">
									<?php
									echo $this->Form->input('subtotal', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'readonly' => true, 'class' => 'readonly form-control input-sm', 'style' => 'width:100px;']);
									echo ' ' . $this->Session->read('Company.currency');
									?>
								</td>
							</tr>
							<tr>
								<td class="text-right font-weight-bold">Discount (%):</td>
								<td class="text-right font-weight-bold">
									<?php echo $this->Form->input('discount', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'style' => 'width:100px;', 'onchange' => 'calculateQuotationTotal()', 'class' => 'form-control input-sm']); ?>
								</td>
							</tr>
							<tr>
								<td class="text-right font-weight-bold">CGST (%):</td>
								<td class="text-right font-weight-bold">
									<?php echo $this->Form->input('cgst', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'style' => 'width:100px;', 'onchange' => 'calculateQuotationTotal()', 'class' => 'form-control input-sm']); ?>
								</td>
							</tr>
							<tr>
								<td class="text-right font-weight-bold">SGST (%):</td>
								<td class="text-right font-weight-bold">
									<?php echo $this->Form->input('sgst', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'style' => 'width:100px;', 'onchange' => 'calculateQuotationTotal()', 'class' => 'form-control input-sm']); ?>
								</td>
							</tr>
							<tr>
								<td class="text-right font-weight-bold">IGST (%):</td>
								<td class="text-right font-weight-bold">
									<?php echo $this->Form->input('igst', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'style' => 'width:100px;', 'onchange' => 'calculateQuotationTotal()', 'class' => 'form-control input-sm']); ?>
								</td>
							</tr>
							<tr>
								<td class="text-right font-weight-bold">Total:</td>
								<td class="text-right font-weight-bold">
									<?php
									echo $this->Form->input('total_amount', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'class' => 'readonly', 'style' => 'width:100px;', 'class' => 'form-control input-sm']);
									echo ' ' . $this->Session->read('Company.currency');
									?>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</div>

		<hr class="mt-5">

		<div class="mt-5">
			<h4 class="text-center">Footer</h4>
			<?php echo $this->Form->input('footer', [
				'label' => false,
				'type' => 'textarea',
				'class' => 'texteditor',
				'default' => '<h5 style="text-align: center; ">Template Footer</h5><div style="text-align: center; ">Thank you!</div>',
			]); ?>
		</div>

		<hr class="mt-5">

		<div class="text-center font-weight-bold mt-5 mb-5">
			<button class="btn btn-md btn-primary" type="submit">Save</button>
		</div>

	</div>

	<?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
	// showCategoryPrice('category');
	var lineNo = parseInt(<?php echo $lineNo;?>);

	function addNewLine() {
		lineNo = lineNo + 1;
		var lineRowStart = '<tr id="lineNo' + lineNo + '">';
		var lineItem = "<td><input type='text' name='data[EntityItem][item][" + lineNo + "]' id='EntityItemItem" + lineNo + "' class='form-control input-sm' lineno='" + lineNo + "' required='1' /></td>";
		var lineDescription = "<td><input type='text' name='data[EntityItem][description][" + lineNo + "]' id='EntityItemDescription" + lineNo + "' class='form-control input-sm' lineno='" + lineNo + "' /></td>";
		var lineQuantity = "<td><input type='text' name='data[EntityItem][quantity][" + lineNo + "]' id='EntityItemQuantity" + lineNo + "' placeholder='0' class='form-control input-sm' lineno='" + lineNo + "' onchange='calculateQuotationTotal()' required='1' /></td>";
		var lineUnitrate = "<td><input type='text' name='data[EntityItem][unitrate][" + lineNo + "]' id='EntityItemUnitrate" + lineNo + "' placeholder='0.00' class='form-control input-sm' lineno='" + lineNo + "' onchange='calculateQuotationTotal()' required='1' /></td>";
		var lineAmount = "<td><input type='text' name='data[EntityItem][amount][" + lineNo + "]' id='EntityItemAmount" + lineNo + "' readonly='readonly' class='readonly lineamount form-control input-sm' style='width:90px; float: left;' placeholder='0.00'/> <span onclick='removeLine(" + lineNo + ")' title='Remove this row' lineno='" + lineNo + "' style='float: left; font-size:25px; cursor: pointer;'>&times;</span></td>";
		var lineRowEnd = '</tr>';
		var row = lineRowStart + lineItem + lineDescription + lineQuantity + lineUnitrate + lineAmount + lineRowEnd;
		$('#itemsTbody').append(row);
	}

	function removeLine(lineno) {
		deleteLine(lineno);
	}

	function deleteLine(lineno) {
		$('#lineNo' + lineno).remove();
		calculateQuotationTotal();
	}

	function calculateQuotationTotal() {
		var subtotal = parseInt(0);
		var cgst = ($('#InvoiceQuotationCgst').val()) ? $('#InvoiceQuotationCgst').val() : 0;
		var sgst = ($('#InvoiceQuotationSgst').val()) ? $('#InvoiceQuotationSgst').val() : 0;
		var igst = ($('#InvoiceQuotationIgst').val()) ? $('#InvoiceQuotationIgst').val() : 0;
		var discount = ($('#InvoiceQuotationDiscount').val()) ? $('#InvoiceQuotationDiscount').val() : 0;
		for (i = 0; i <= lineNo; i++) {
			var qty = ($('#EntityItemQuantity' + i).val()) ? $('#EntityItemQuantity' + i).val() : 0;
			var unitrate = ($('#EntityItemUnitrate' + i).val()) ? $('#EntityItemUnitrate' + i).val() : 0;

			var amount = qty * unitrate;
			$('#EntityItemAmount' + i).val(amount.toFixed(2));

			var lineAmount = ($('#EntityItemAmount' + i).val()) ? $('#EntityItemAmount' + i).val() : 0;
			if (lineAmount > 0) {
				subtotal = subtotal + parseInt(lineAmount);
			}
		}

		total = 0;
		// calculate subtotal with discount.
		if (discount > 0) {
			total = subtotal - ((discount * subtotal) / 100);
		} else {
			total = subtotal;
		}

		var tax = parseFloat(cgst) + parseFloat(sgst) + parseFloat(igst);
		// calculate total including tax.
		if (tax > 0) {
			total = total + ((tax * total) / 100);
		}

		$('#InvoiceQuotationSubtotal').val(subtotal.toFixed(2));
		$('#InvoiceQuotationTotalAmount').val(total.toFixed(2));
	}

</script>
