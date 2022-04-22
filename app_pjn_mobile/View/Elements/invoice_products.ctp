<h2>Invoice Products</h2>
<?php
$invoiceType = $invoiceInfo['Invoice']['invoice_type'];

if ($invoiceType == 'purchase') {
	if ($invoiceProducts) {
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
			$i = 0;
			$totalBoxes = 0;
			$totalAmount = 0;
			$totalSpecialMargin = 0;
			$totalNoOfUnits = 0;
			$tax = $this->Session->read('Invoice.tax');
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
				//debug($row['Product']);
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row['Purchase']['category_name']; ?></td>
					<?php
					if ($this->Session->read('Store.show_brands_in_products')) {
						?>
						<td><?php echo isset($row['Product']['Brand']['name']) ? $row['Product']['Brand']['name'] : ''; ?></td>
						<?php
					}
					?>
					<td><?php echo $row['Purchase']['product_name']; ?></td>
					<td style="text-align:center;"><?php echo $row['Purchase']['box_qty'];
						if ($noOfUnits) {
							echo "&nbsp;($noOfUnits)";
						}
						?></td>

					<td style="text-align:center;"><?php echo $row['Purchase']['box_buying_price']; ?></td>
					<td style="text-align:right;"><?php echo $row['Purchase']['total_amount']; ?></td>
					<td>
						<form method="post" style="" name="invoice_remove_product_<?php echo $row['Purchase']['id']; ?>"
							  id="invoice_remove_product_<?php echo $row['Purchase']['id']; ?>"
							  action="<?php echo $this->Html->url("/purchases/removeProduct/" . $row['Purchase']['id']); ?>">
							<a href="#" name="Remove"
							   onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Purchase']['product_name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Purchase']['id']; ?>').submit(); } event.returnValue = false; return false;"
							   class="btn btn-danger btn-xs">
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
				<td colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 4 : 3; ?>'></td>
				<td style="text-align:center;"><?php echo $totalBoxes;
					if ($totalNoOfUnits) {
						echo "&nbsp;($totalNoOfUnits)";
					}
					?> Boxes
				</td>
				<td style="text-align:right;" colspan='2'><?php echo number_format($totalAmount, '2', '.', ''); ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style="text-align:right; color:red;"
					colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 8 : 7; ?>'>&nbsp;
				</td>
			</tr>

			<tr>
				<td style="text-align:right;"
					colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>
					Total Purchase Value:
				</td>
				<td style="text-align:right;">
					<?php echo number_format($totalAmount, '2', '.', ''); ?>
				</td>
				<td>&nbsp;</td>
			</tr>

			</tfoot>
			</tbody>
		</table>

		<?php
	}
} else if ($invoiceType == 'sale') {
	?>
	<table class="table" style="width:100%;">
		<thead>
		<tr>
			<th>S.No</th>
			<th>Category Name</th>
			<?php echo $this->Session->read('Store.show_brands_in_products') ? "<th>Brand</th>" : ""; ?>
			<th>Product Name</th>
			<th>No. of Units</th>
			<th>Unit Price</th>
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
				<td><?php echo $row['Sale']['category_name']; ?></td>
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
				<td style="text-align:right;"><?php echo $row['Sale']['total_amount']; ?></td>
				<td>
					<form method="post" style="" name="invoice_remove_product_<?php echo $row['Sale']['id']; ?>"
						  id="invoice_remove_product_<?php echo $row['Sale']['id']; ?>"
						  action="<?php echo $this->Html->url("/purchases/removeProduct/" . $row['Sale']['id']); ?>">
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
			<td colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'></td>
			<td style="text-align:right;"><?php echo number_format($totalAmount, '2', '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:right; color:red;"
				colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 8 : 7; ?>'>&nbsp;
			</td>
		</tr>

		<tr>
			<td style="text-align:right;"
				colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5; ?>'>
				Invoice Value: <br>
				Net Invoice Value: <br>
			</td>
			<td style="text-align:right;">
				<?php echo number_format($totalAmount, '2', '.', ''); ?> <br>
				<?php echo $invoiceInfo['Invoice']['invoice_value']; ?> <br>
			</td>
			<td>&nbsp;</td>
		</tr>
		</tfoot>
		</tbody>
	</table>
	<?php
} else { ?>
	<p>No products found in Invoice "<?php echo $this->Session->read('Invoice.name'); ?>".</p>
<?php } ?>
