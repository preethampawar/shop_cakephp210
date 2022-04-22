<div>
	<h2 class="floatLeft">Create New Quotation</h2>
	<?php echo '&nbsp;' . $this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/quotations/', ['class' => 'button small red floatRight', 'escape' => false]); ?>
	<br/><br/><br/><br/>
	<?php echo $this->Form->create(); ?>

	<div style="margin:auto; width:800px; border:1px solid #efefef; background-color:#fff; padding:5px;">
		<!-- Quotation header -->
		<div class="corner setBackground">
			<?php echo $this->Form->create(); ?>
			<table style="width:100%" class="noBorderTable">
				<tr>
					<td style="text-align:center; font-size:20px;"><b>Quotation</b></td>
				</tr>
			</table>
		</div>

		<!-- From Address -->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="noBorderTable">
				<tr>
					<td style="width:400px;">
						<b>From Company/Individual:</b>
						<?php
						$default = $this->Session->read('Company.display_name');
						echo $this->Form->input('from_name', ['label' => 'Name', 'type' => 'text', 'placeholder' => 'From Name', 'required' => true, 'default' => $default, 'label' => 'Name']);

						echo $this->Form->input('from_address', ['label' => 'Address', 'type' => 'textarea', 'rows' => '2', 'cols' => '10', 'default' => $this->Session->read('Company.address'), 'required' => true, 'placeholder' => 'From Address']);
						?>
					</td>
					<td>&nbsp;</td>
					<td style="width:300px;">
						<?php
						$img = $this->Html->image('calendar.gif', ['onclick' => "$('#datepicker').focus()"]);
						echo $this->Form->input('date', ['label' => 'Date (Y-m-d)*', 'id' => 'datepicker', 'type' => 'text', 'required' => true, 'after' => '&nbsp;' . $img . '<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly' => true, 'placeholder' => 'Click to open calendar', 'style' => 'width:85%']);
						?>
					</td>
				</tr>
			</table>
		</div>

		<!-- Quotation for info-->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="noBorderTable">
				<tr>
					<td colspan='100%'>
						<b>Quotation For:</b>
					</td>
				</tr>
				<tr>
					<td style="width:400px;">
						<?php
						echo $this->Form->input('to_name', ['label' => 'Name', 'type' => 'text', 'placeholder' => 'To Name', 'required' => true]);
						echo $this->Form->input('to_address', ['label' => 'Address', 'type' => 'textarea', 'rows' => '2', 'cols' => '10', 'placeholder' => 'To Address', 'required' => true]);
						?>
					</td>
					<td>&nbsp;</td>
					<td style="width:300px;">
						<?php
						$img = $this->Html->image('calendar.gif', ['onclick' => "$('#datepicker').focus()"]);
						echo $this->Form->input('validity', ['label' => 'Validity (Y-m-d)*', 'id' => 'datepicker2', 'type' => 'text', 'required' => true, 'after' => '&nbsp;' . $img . '<input type="text" id="alternate2" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly' => true, 'placeholder' => 'Click to open calendar', 'style' => 'width:85%']);
						?>
					</td>
				</tr>
			</table>
		</div>

		<!-- Quotation for info-->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="noBorderTable">
				<tr>
					<td>
						<?php
						echo $this->Form->input('comments', ['label' => '<b>Comments or Special instructions:</b>', 'type' => 'textarea', 'rows' => '1', 'cols' => '10', 'placeholder' => 'Comments']);
						?>
					</td>
				</tr>
			</table>
		</div>

		<!-- Items list -->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="noBorderTable" cellpadding='1' cellspacing='1'>
				<tbody id='itemsTbody'>
				<tr>
					<th>Item/Type</th>
					<th>Description</th>
					<th style="width:50px;">Size</th>
					<th style="width:50px;">Age</th>
					<th style="width:50px;">Qty/Hrs</th>
					<th style="width:60px;">Rate</th>
					<th style="width:130px;">Amount</th>
				</tr>
				<tr>
					<td><?php echo $this->Form->input('EntityItem.item.0', ['type' => 'text', 'label' => false, 'div' => false, 'lineno' => 0, 'required' => true]); ?></td>
					<td><?php echo $this->Form->input('EntityItem.description.0', ['label' => false, 'type' => 'textarea', 'rows' => 1, 'style' => 'height:17px;', 'div' => false, 'lineno' => 0, 'required' => true]); ?></td>
					<td><?php echo $this->Form->input('EntityItem.size.0', ['type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '', 'lineno' => 0]); ?></td>
					<td><?php echo $this->Form->input('EntityItem.age.0', ['type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '', 'lineno' => 0]); ?></td>
					<td><?php echo $this->Form->input('EntityItem.quantity.0', ['type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '0', 'lineno' => 0, 'onchange' => 'calculateQuotationTotal()', 'required' => true]); ?></td>
					<td><?php echo $this->Form->input('EntityItem.unitrate.0', ['type' => 'text', 'label' => false, 'div' => false, 'placeholder' => '0.00', 'lineno' => 0, 'onchange' => 'calculateQuotationTotal()', 'required' => true]); ?></td>
					<td><?php echo $this->Form->input('EntityItem.amount.0', ['type' => 'text', 'readonly' => true, 'label' => false, 'class' => 'readonly lineamount', 'div' => false, 'style' => 'width:100px;', 'placeholder' => '0.00', 'lineno' => 0]); ?></td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan='5' style="text-align:left">

						<span class="button small grey" onclick="addNewLine()"> + New Line </span>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
		<!-- Subtotal -->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="noBorderTable">
				<tbody>
				<tr>
					<td style="text-align:right; font-weight:bold;">Subtotal:</td>
					<td style="width:130px; font-weight:bold;">
						<?php
						echo $this->Form->input('Quotation.subtotal', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'readonly' => true, 'class' => 'readonly', 'style' => 'width:100px;']);
						echo ' ' . $this->Session->read('Company.currency');
						?>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; font-weight:bold;">Discount:</td>
					<td style="font-weight:bold;">
						<?php echo $this->Form->input('Quotation.discount', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'style' => 'width:100px;', 'onchange' => 'calculateQuotationTotal()']); ?>
						%
					</td>
				</tr>
				<tr>
					<td style="text-align:right; font-weight:bold;">Tax Rate:</td>
					<td style="font-weight:bold;">
						<?php echo $this->Form->input('Quotation.tax_rate', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'style' => 'width:100px;', 'onchange' => 'calculateQuotationTotal()']); ?>
						%
					</td>
				</tr>
				<tr>
					<td style="text-align:right; font-weight:bold;">Total:</td>
					<td style="font-weight:bold;">
						<?php
						echo $this->Form->input('Quotation.total_amount', ['label' => false, 'div' => false, 'placeholder' => '0.00', 'readonly' => true, 'class' => 'readonly', 'style' => 'width:100px;']);
						echo ' ' . $this->Session->read('Company.currency');
						?>
					</td>
				</tr>
				</tbody>
			</table>
			<br/><br/>

			<div style="text-align:center; font-weight:bold;"><?php echo $this->Form->submit('Save Quotation'); ?></div>
		</div>

		<div class='clear'></div>
	</div>

	<?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
	// showCategoryPrice('category');
	var lineNo = 0;

	function addNewLine() {
		lineNo = lineNo + 1;
		var lineRowStart = '<tr id="lineNo' + lineNo + '">';
		var lineItem = "<td><input type='text' name='data[EntityItem][item][" + lineNo + "]' id='EntityItemItem" + lineNo + " lineno='" + lineNo + "' required='1' /></td>";
		var lineDescription = "<td><input type='textarea' name='data[EntityItem][description][" + lineNo + "]' id='EntityItemDescription" + lineNo + "' lineno='" + lineNo + "' cols='30' style='height:17px;' required='1' /></td>";
		var lineSize = "<td><input type='text' name='data[EntityItem][size][" + lineNo + "]' id='EntityItemSize" + lineNo + "' lineno='" + lineNo + "' /></td>";
		var lineAge = "<td><input type='text' name='data[EntityItem][age][" + lineNo + "]' id='EntityItemAge" + lineNo + "' lineno='" + lineNo + "' /></td>";
		var lineQuantity = "<td><input type='text' name='data[EntityItem][quantity][" + lineNo + "]' id='EntityItemQuantity" + lineNo + "' placeholder='0' lineno='" + lineNo + "' onchange='calculateQuotationTotal()' required='1' /></td>";
		var lineUnitrate = "<td><input type='text' name='data[EntityItem][unitrate][" + lineNo + "]' id='EntityItemUnitrate" + lineNo + "' placeholder='0.00' lineno='" + lineNo + "' onchange='calculateQuotationTotal()' required='1' /></td>";
		var lineAmount = "<td><input type='text' name='data[EntityItem][amount][" + lineNo + "]' id='EntityItemAmount" + lineNo + "' readonly='readonly' class='readonly lineamount' style='width:100px;' placeholder='0.00'/>  <img src='../img/error.png' alt='x' onclick='removeLine(" + lineNo + ")' title='Remove this row' lineno='" + lineNo + "' /></td>";
		var lineRowEnd = '</tr>';
		var row = lineRowStart + lineItem + lineDescription + lineSize + lineAge + lineQuantity + lineUnitrate + lineAmount + lineRowEnd;
		$('#itemsTbody').append(row);
	}

	function removeLine(lineno) {
		$('#lineNo' + lineno).fadeOut();
		setTimeout("deleteLine(" + lineno + ")", 1000);
	}

	function deleteLine(lineno) {
		$('#lineNo' + lineno).remove();
		calculateQuotationTotal();
	}

	function calculateQuotationTotal() {
		var subtotal = parseInt(0);
		var taxrate = ($('#QuotationTaxRate').val()) ? $('#QuotationTaxRate').val() : 0;
		// var salestax = ($('#QuotationSalesTax').val()) ? $('#QuotationSalesTax').val() : 0;
		var discount = ($('#QuotationDiscount').val()) ? $('#QuotationDiscount').val() : 0;
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

		// var tax = parseFloat(taxrate)+parseFloat(salestax);
		var tax = parseFloat(taxrate);
		// calculate total including tax.
		if (tax > 0) {
			total = total + ((tax * total) / 100);
		}

		$('#QuotationSubtotal').val(subtotal.toFixed(2));
		$('#QuotationTotalAmount').val(total.toFixed(2));
	}

	$(function () {
		$("#datepicker").datepicker({altFormat: "yy-mm-dd"});
		$("#datepicker").datepicker("option", "dateFormat", "yy-mm-dd");
		$("#datepicker").datepicker("option", "altField", "#alternate");
		$("#datepicker").datepicker("option", "altFormat", "DD, d MM, yy");
		$("#datepicker").datepicker("option", "defaultDate", '');
		<?php
		if(isset($this->data['Quotation']['date'])) {
		?>
		$("#datepicker").attr("value", "<?php echo $this->data['Quotation']['date'];?>");
		<?php
		}
		else{
		?>
		$("#datepicker").attr("value", "<?php echo date('Y-m-d');?>");
		<?php
		}
		?>

		$("#datepicker2").datepicker({altFormat: "yy-mm-dd"});
		$("#datepicker2").datepicker("option", "dateFormat", "yy-mm-dd");
		$("#datepicker2").datepicker("option", "altField", "#alternate2");
		$("#datepicker2").datepicker("option", "altFormat", "DD, d MM, yy");
		$("#datepicker2").datepicker("option", "defaultDate", '');
		<?php
		if(isset($this->data['Quotation']['date'])) {
		?>
		$("#datepicker2").attr("value", "<?php echo $this->data['Quotation']['date'];?>");
		<?php
		}
		else{
		?>
		$("#datepicker2").attr("value", "<?php echo date('Y-m-d');?>");
		<?php
		}
		?>
	});

</script>
