<h2>Invoice Products</h2>
<?php
$invoiceType = $invoiceInfo['Invoice']['invoice_type'];

if ($invoiceProducts) {
	?>
	<table class="table" style="width:100%;">
		<thead>
		<tr>
			<th>S.No</th>
			<?php echo $this->Session->read('Store.show_brands_in_products') ? "<th>Brand</th>" : ""; ?>
			<th>Product Name</th>
			<th>No. of Units</th>
			<th>Unit Price</th>
			<th>Discount(%)</th>
			<th>SGST(%)</th>
			<th>CGST(%)</th>
			<th>IGST(%)</th>
			<th>Total Amount</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		$totalAmount = 0;
		$totalNoOfUnits = 0;
		foreach ($invoiceProducts as $row) {
			$i++;
			$totalAmount += $row['Sale']['total_amount'];
			$totalUnits = $row['Sale']['total_units'];
			$totalNoOfUnits += $totalUnits;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<?php
				if ($this->Session->read('Store.show_brands_in_products')) {
					?>
					<td><?php echo isset($row['Product']['Brand']['name']) ? $row['Product']['Brand']['name'] : ''; ?></td>
					<?php
				}
				?>
				<td><?php echo $row['Sale']['product_name']; ?></td>
				<td style="text-align:center;"><?php echo $row['Sale']['total_units']; ?></td>
				<td style="text-align:center;"><?php echo $row['Sale']['unit_price']; ?></td>
				<td style="text-align:center;"><?php echo $row['Sale']['discount']; ?></td>
				<td style="text-align:center;"><?php echo $row['Sale']['sgst']; ?></td>
				<td style="text-align:center;"><?php echo $row['Sale']['cgst']; ?></td>
				<td style="text-align:center;"><?php echo $row['Sale']['igst']; ?></td>
				<td style="text-align:right;"><?php echo $row['Sale']['total_amount']; ?></td>
				<td>
					<form method="post" style="" name="invoice_remove_product_<?php echo $row['Sale']['id']; ?>"
						  id="invoice_remove_product_<?php echo $row['Sale']['id']; ?>"
						  action="<?php echo $this->Html->url("/sales/removeProduct/" . $row['Sale']['id']); ?>">
						<a href="#" name="Remove"
						   onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Sale']['id']; ?>').submit(); } event.returnValue = false; return false;"
						   class="btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</a>
					</form>
				</td>
			</tr>
			<?php
		}
		?>
		<tfoot style="font-weight:bold;">
		<tr>
			<td colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 9 : 8; ?>'></td>
			<td style="text-align:right;"><?php echo number_format($totalAmount, '2', '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:right; color:red;"
				colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 11 : 9; ?>'>&nbsp;
			</td>
		</tr>

		<tr>
			<td style="text-align:right;"
				colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 9 : 8; ?>'>
				Total Sale Value:
			</td>
			<td style="text-align:right;">
				<?php echo $invoiceInfo['Invoice']['invoice_value']; ?> <br>
			</td>
			<td>&nbsp;</td>
		</tr>
		</tfoot>
		</tbody>
	</table>
	<?php
} else {
	?>
	<p>No products found in Invoice "<?php echo $this->Session->read('Invoice.name'); ?>".</p>
	<?php
}
?>
