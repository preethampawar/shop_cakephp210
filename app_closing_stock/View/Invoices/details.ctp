<a href="/invoices/" class="btn btn-sm btn-secondary small">&laquo; Back to invoice list</a>
<div class="mb-3 mt-3">
	<a href="/invoices/edit/<?php echo $invoiceInfo['Invoice']['id']; ?>" class="btn btn-sm btn-purple small">Edit
		Ivoice Details</a>
	<a href="/invoices/selectInvoice/<?php echo $invoiceInfo['Invoice']['id']; ?>"
	   class="btn btn-sm btn-purple ml-3 small">Add/Remove Products</a>
</div>

<h1>
	Invoice Details - <?php echo $invoiceInfo['Invoice']['name']; ?>
</h1>
<table class="table table-sm small table-striped">
	<tbody>
	<tr>
		<td width="80">Invoice No:</td>
		<td width="120"><?php echo $invoiceInfo['Invoice']['name']; ?></td>
	</tr>
	<tr>
		<td width="110">Invoice Date:</td>
		<td width="120"><?php echo date('d-m-Y', strtotime($invoiceInfo['Invoice']['invoice_date'])); ?></td>
	</tr>
	<tr>
		<td width="110">DD Amount:</td>
		<td width="120"><?php echo $invoiceInfo['Invoice']['dd_amount']; ?></td>
	</tr>

	<tr>
		<td>Prev Credit:</td>
		<td><?php echo $invoiceInfo['Invoice']['prev_credit']; ?></td>
	</tr>
	<tr>
		<td>Invoice Value:</td>
		<td><?php echo $invoiceInfo['Invoice']['invoice_value']; ?></td>
	</tr>
	<tr>
		<td>MRP Rounding Up:</td>
		<td><?php echo $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
	</tr>
	<tr>
		<td>Net Invoice Value:</td>
		<td><?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
	</tr>
<!--	<tr>-->
<!--		<td>Retail Shop Excise Turnover Tax:</td>-->
<!--		<td>--><?php //echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax']; ?><!--</td>-->
<!--	</tr>-->
	<tr>
		<td>Special Excise Cess:</td>
		<td><?php echo $invoiceInfo['Invoice']['special_excise_cess']; ?></td>
	</tr>
	<tr>
		<td>TCS Value:</td>
		<td><?php echo $invoiceInfo['Invoice']['tcs_value']; ?></td>
	</tr>
	<tr>
		<td>New Retailer Professional Tax:</td>
		<td><?php echo $invoiceInfo['Invoice']['new_retailer_prof_tax']; ?></td>
	</tr>
	<tr>
		<td>Credit Balance:</td>
		<td><?php echo $invoiceInfo['Invoice']['credit_balance']; ?></td>
	</tr>
	<tr></tr>
	</tbody>
</table>
<br>
<h4>Invoice Products </h4>
<?php
if ($invoiceProducts) {
	?>
	<table class='table table-sm table-bordered small'>
		<thead class="table-light text-left">
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
		$totalMrpRoundingUp = $invoiceInfo['Invoice']['mrp_rounding_off'];
		$totalNoOfUnits = 0;
		$tax = $invoiceInfo['Invoice']['tax'];
		foreach ($invoiceProducts as $row) {
			$i++;
			$totalBoxes += $row['Purchase']['box_qty'];
			$totalAmount += $row['Purchase']['total_amount'];
			$totalSpecialMargin += $row['Purchase']['total_special_margin'];
			$totalUnits = $row['Purchase']['total_units'];
			$noOfBoxes = floor($row['Purchase']['total_units'] / $row['Purchase']['units_in_box']);
			$unitInBox = $row['Purchase']['units_in_box'];
			$noOfUnits = ($totalUnits) - ($noOfBoxes * $unitInBox);
			$totalNoOfUnits += $noOfUnits;

			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $row['Purchase']['product_name']; ?></td>
				<td class="text-left"><?php echo $row['Purchase']['box_qty'];
					if ($noOfUnits) {
						echo "&nbsp;($noOfUnits)";
					}
					?></td>
				<td class="text-left"><?php echo $row['Purchase']['box_buying_price']; ?></td>
				<td class="text-left"><?php echo $row['Purchase']['total_amount']; ?></td>
				</td>
			</tr>
			<?php
		}
		?>
		<tfoot>
		<tr class="font-weight-bold">
			<td colspan='2'></td>
			<td class="text-left"><?php echo $totalBoxes;
				if ($totalNoOfUnits) {
					echo "&nbsp;($totalNoOfUnits)";
				}
				?> Boxes
			</td>

			<td>&nbsp;</td>
			<td class="text-left"><?php echo number_format($totalAmount, '2', '.', ''); ?></td>
		</tr>
		<tr>
			<td colspan='5'>&nbsp;</td>
		</tr>

		<tr>
			<td class="text-right" colspan='4'>
				Invoice Value: <br>
				MRP Rounding Up: <br>
				Net Invoice Value: <br>
			</td>
			<td class="text-left">
				<?php echo number_format($totalAmount, '2', '.', ''); ?> <br>
				<?php echo number_format($totalMrpRoundingUp, '2', '.', ''); ?> <br>
				<?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['special_margin'] + $totalMrpRoundingUp; ?>
				<br>
			</td>
		</tr>
		<tr>
			<td colspan='5'>&nbsp;</td>
		</tr>
		<tr>
			<td class="text-right" colspan='4'>e-challan / DD Amount:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['dd_amount']; ?></td>
		</tr>
		<tr>
			<td class="text-right" colspan='4'>Previous Credit:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['prev_credit']; ?></td>
		</tr>
		<tr>
			<td class="text-right" colspan='4'>Sub Total:</td>
			<td class="text-left"><?php echo number_format($invoiceInfo['Invoice']['dd_amount'] + $invoiceInfo['Invoice']['prev_credit'], '2', '.', ''); ?></td>
		</tr>
		<tr>
			<td class="text-right" colspan='4'>(-) Less this Invoice Value:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['invoice_value'] + $invoiceInfo['Invoice']['special_margin'] + $invoiceInfo['Invoice']['mrp_rounding_off']; ?></td>
		</tr>

<!--		<tr>-->
<!--			<td class="text-right" colspan='4'>Retail Shop Excise Turnover Tax:</td>-->
<!--			<td class="text-left">--><?php //echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax']; ?><!--</td>-->
<!--		</tr>-->
		<tr>
			<td class="text-right" colspan='4'>Special Excise Cess:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['special_excise_cess']; ?></td>
		</tr>


		<tr>
			<td class="text-right" colspan='4'>TCS:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['tcs_value']; ?></td>
		</tr>
		<tr>
			<td class="text-right" colspan='4'>New Retailer Professional Tax:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['new_retailer_prof_tax']; ?></td>
		</tr>
		<tr>
			<td class="text-right" colspan='4'>Retailer Credit Balance:</td>
			<td class="text-left"><?php echo $invoiceInfo['Invoice']['credit_balance']; ?></td>
		</tr>
		</tfoot>

		</tbody>
	</table>

<?php } else { ?>
	<p>No products found in Invoice "<?php echo $invoiceInfo['Invoice']['name']; ?>".</p>
<?php } ?>
<br><br>
