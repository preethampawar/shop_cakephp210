<?php $this->start('invoices_report_menu');?>
<?php echo $this->element('invoices_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php $this->end();?>

<p class="text-left">
	<?php echo $this->Html->link('&laquo; Back to Invoice List', array('controller'=>'invoices', 'action'=>'index'), array('title'=>'Go back to Invoices list', 'class'=>'btn btn-warning btn-sm', 'escape'=>false));?>
</p>
<br>
<h1>Add or Remove Products in Invoice</h1>
<h2>Invoice: <?php echo $this->Session->read('Invoice.name');?></h2>

<?php
//debug($productsInfo);
if($productsInfo) {
$boxQuantity = 0;
?>
	<script type="text/javascript">
		var unitsInBox = new Array();
		var unitBoxPrice = new Array();
		var specialMargin = new Array();
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
			if(extra_units) {
				for(var i=0; i< extra_units; i++) {
					select_options = select_options+'<option value="'+i+'">'+i+'</option>';
				}
			}
			$('#PurchaseExtraUnits').html(select_options);

		}

		function setTotalPrice() {
			var productID = $('#PurchaseProductId').val();
			var productName = $('#PurchaseProductId option:selected').text();
			var iBoxQty = parseInt(($('#PurchaseBoxQty').val()>0)?$('#PurchaseBoxQty').val():0);
			//var iBoxQty = $('#PurchaseBoxQty').val();
			var extraUnits = $('#PurchaseExtraUnits').val();
			//alert(extraUnits);
			var iBoxQtyText = "";
			if(extraUnits!=0){
				iBoxQtyText = iBoxQty+'.'+extraUnits;
			}
			else{
				iBoxQtyText = iBoxQty;
			}
			var oBoxPrice = parseFloat((unitBoxPrice[productID]>0)?unitBoxPrice[productID]:0);
			var oUnitsInBox = parseInt((unitsInBox[productID]>0)?unitsInBox[productID]:0);
			var oSpecialMargin = parseFloat((specialMargin[productID]>0)?specialMargin[productID]:0);
			var unitPrice = 0;
			if(parseInt(oUnitsInBox)>0) {
				unitPrice = parseFloat((oBoxPrice/oUnitsInBox)).toFixed(2);
			}
			var oTotalPrice = ((iBoxQty*oBoxPrice)>0) ? (iBoxQty*oBoxPrice).toFixed(2) : 0;
			var oTotalUnits = parseInt(((iBoxQty*oUnitsInBox)>0)?(iBoxQty*oUnitsInBox):0);
			if(extraUnits!=0){
				oTotalUnits =parseInt(oTotalUnits)+parseInt(extraUnits);
				var pricePerUnit =parseFloat(oBoxPrice/oUnitsInBox);
				var extraUnitsPrice = parseFloat(pricePerUnit*extraUnits);
				oTotalPrice = parseFloat(oTotalPrice)+parseFloat(extraUnitsPrice);
				oTotalPrice = oTotalPrice.toFixed(2);

			}
			var oTotalUnitsString = ' ['+oTotalUnits+' units] ';
			var oTotalSpecialMargin = ((oTotalUnits*oSpecialMargin)>0) ? (oTotalUnits*oSpecialMargin).toFixed(2) : 0;

			// set hidden variables

			$('#PurchaseBoxBuyingPrice').val(oBoxPrice);
			$('#PurchaseUnitsInBox').val(oUnitsInBox);
			$('#PurchaseUnitPrice').val(unitPrice);
			$('#PurchaseSpecialMargin').val(oSpecialMargin);
			$('#PurchaseTotalUnits').val(oTotalUnits);
			$('#PurchaseTotalAmount').val(oTotalPrice);
			$('#PurchaseTotalSpecialMargin').val(oTotalSpecialMargin);


			if(oTotalPrice <= 0) {
				$('#SubmitForm').attr('title', 'Total amount should be greater than 0');
			}
			else {
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
			if(parseInt($('#SubmitForm').attr('title').length) > 0) {
				alert($('#SubmitForm').attr('title'));
				return false;
			}
			return true;
		}
	</script>
	<br>
	<div id="AddInvoiceProductDiv" class="well">
		<?php
		echo $this->Form->create();
		?>
		<div id="paramsDiv">
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('product_id', array('empty'=>false, 'label'=>'Select Product', 'required'=>true, 'type'=>'select', 'options'=>$productsList, 'onchange'=>'setExtraUnits(); setTotalPrice()', 'autofocus'=>true, 'class'=>'autoSuggest'));?>
			</div>

			<div style="float:left; clear:none;">
				<?php
				echo $this->Form->input('box_qty', array('type'=>'number', 'value'=>1, 'min'=>'0', 'max'=>'99999', 'label'=>'No. of Boxes', 'required'=>true, 'oninput'=>'setTotalPrice()', 'title'=>'Values should be between 1 to 99999'));
				echo $this->Form->input('box_buying_price', array('type'=>'hidden'));
				echo $this->Form->input('units_in_box', array('type'=>'hidden'));
				echo $this->Form->input('unit_price', array('type'=>'hidden'));
				echo $this->Form->input('total_units', array('type'=>'hidden'));
				echo $this->Form->input('total_amount', array('type'=>'hidden'));
				echo $this->Form->input('special_margin', array('type'=>'hidden', 'value' => 0));
				echo $this->Form->input('total_special_margin', array('type'=>'hidden', 'value' => 0));
				?>
			</div>
			<?php
			//debug($boxQuantity);
			$extraUnitArray=[];
			for($i=1;$i<=$boxQuantity;$i++){
				$extraUnitArray[$i-1] = $i-1;
			}
			?>
			<div style="float:left; clear:none;" id="ExtraUnitsDiv">
				<?php echo $this->Form->input('extra_units', array('empty'=>false, 'label'=>'No.of Units', 'type'=>'select', 'options'=>$extraUnitArray, 'onchange'=>'setTotalPrice()', 'autofocus'=>true));?>
			</div>
			<div style="float:left; clear:none; padding-top: 25px;">
				<?php echo $this->Form->submit('Add Product', array('id'=>'SubmitForm', 'title'=>'', 'type'=>'submit', 'onclick'=>'return submitButtonMsg()', 'div'=>false, 'class' => 'btn btn-primary btn-sm'));?>
			</div>

			<div style="float:left; clear:both; padding-top:10px;">
				<table class="table">
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
		<div style="clear:both;"></div>
		<?php
		echo $this->Form->end();
		?>
	</div>

	<script type="text/javascript">
		setExtraUnits();
		setTotalPrice();
	</script>

	<h2>Invoice Products</h2>
	<?php
	//debug($invoiceProducts);
	if($invoiceProducts) {
	?>
	<table class="table" style="width:100%;">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Category Name</th>
				<?php echo $this->Session->read('Store.show_brands_in_products') ? "<th>Brand</th>" : ""; ?>
				<th>Product Name</th>
				<th>No. of Boxes</th>

				<th>Unit Box Price</th>
				<th>Total Amount</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			$totalBoxes = 0;
			$totalAmount = 0;
			$totalSpecialMargin = 0;
			$totalNoOfUnits = 0;
			$tax = $this->Session->read('Invoice.tax');
			foreach($invoiceProducts as $row) {
				$i++;
				$totalBoxes+=$row['Purchase']['box_qty'];
				$totalAmount+=$row['Purchase']['total_amount'];
				$totalUnits = $row['Purchase']['total_units'];
				$noOfBoxes = floor($row['Purchase']['total_units']/$row['Purchase']['units_in_box']);
				$unitInBox = $row['Purchase']['units_in_box'];
				$noOfUnits = ($totalUnits)-($noOfBoxes*$unitInBox);
				$totalNoOfUnits+=$noOfUnits;
				//debug($row['Product']);
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Purchase']['category_name'];?></td>
				<?php
				if($this->Session->read('Store.show_brands_in_products')) {
				?>
					<td><?php echo isset($row['Product']['Brand']['name']) ? $row['Product']['Brand']['name'] : '';?></td>
				<?php
				}
				?>
				<td><?php echo $row['Purchase']['product_name'];?></td>
				<td style="text-align:center;"><?php echo $row['Purchase']['box_qty'];
				if($noOfUnits){
					echo "&nbsp;($noOfUnits)";
				}
				?></td>

				<td style="text-align:center;"><?php echo $row['Purchase']['box_buying_price'];?></td>
				<td style="text-align:right;"><?php echo $row['Purchase']['total_amount'];?></td>
				<td>
					<form method="post" style="" name="invoice_remove_product_<?php echo $row['Purchase']['id'];?>" id="invoice_remove_product_<?php echo $row['Purchase']['id'];?>" action="<?php echo $this->Html->url("/purchases/removeProduct/".$row['Purchase']['id']);?>">
						<a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Purchase']['product_name'];?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Purchase']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</a>
					</form>
					<?php
					//echo $this->Form->postLink('Remove', array('controller'=>'purchases', 'action'=>'removeProduct', $row['Purchase']['id']), array('title'=>'Remove product from invoice - '.$row['Purchase']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Purchase']['product_name'].'" from the list?');
					?>
				</td>
			</tr>
			<?php
			}
			?>
			<tfoot style="font-weight:bold;">
				<tr>
					<td colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 4 : 3;?>'></td>
					<td style="text-align:center;"><?php echo $totalBoxes;
					if($totalNoOfUnits){
						echo "&nbsp;($totalNoOfUnits)";
					}
					?> Boxes</td>
					<td style="text-align:right;" colspan='2'><?php echo number_format($totalAmount, '2', '.', '');?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right; color:red;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 8 : 7;?>'>&nbsp;</td>
				</tr>

				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>
						Invoice Value: <br>

						MRP Rounding Off: <br>
						Net Invoice Value: <br>
					</td>
					<td style="text-align:right;">
						<?php echo number_format($totalAmount, '2', '.', '');?> <br>
						<?php echo $invoiceInfo['Invoice']['mrp_rounding_off']; ?> <br>
						<?php echo $invoiceInfo['Invoice']['invoice_value']+$invoiceInfo['Invoice']['special_margin']+$invoiceInfo['Invoice']['mrp_rounding_off'];?> <br>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 8 : 7;?>'>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>e-challan / DD Amount:</td>
					<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['dd_amount'];?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Previous Credit:</td>
					<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['prev_credit'];?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Sub Total:</td>
					<td style="text-align:right;"><?php echo number_format($invoiceInfo['Invoice']['dd_amount']+$invoiceInfo['Invoice']['prev_credit'], '2', '.', '');?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>(-) Less this Invoice Value:</td>
					<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['invoice_value']+$invoiceInfo['Invoice']['special_margin']+$invoiceInfo['Invoice']['mrp_rounding_off'];?></td>
					<td>&nbsp;</td>
				</tr>

<!---->
<!--                <tr>-->
<!--                    <td style="text-align:right;" colspan='--><?php //echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?><!--'>Retail Shop Excise Turnover Tax:</td>-->
<!--                    <td style="text-align:right;">--><?php //echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax'];?><!--</td>-->
<!--                    <td>&nbsp;</td>-->
<!--                </tr>-->
                <tr>
                    <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Special Excise Cess:</td>
                    <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['special_excise_cess'];?></td>
                    <td>&nbsp;</td>
                </tr>


                <tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>TCS:</td>
					<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['tcs_value'];?></td>
					<td>&nbsp;</td>
				</tr>

                <tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>New Retailer Professional Tax:</td>
					<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['new_retailer_prof_tax'];?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Retailer Credit Balance:</td>
					<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['credit_balance'];?></td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>
		</tbody>
	</table>

	<?php } else { ?>
	<p>No products found in Invoice "<?php echo $this->Session->read('Invoice.name');?>".</p>
	<?php } ?>

<?php
}
else {
	echo 'No products found. You need to add products to continue.';
}
?>
<br><br>
