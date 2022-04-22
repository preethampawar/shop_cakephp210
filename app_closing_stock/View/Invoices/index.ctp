<h1>Invoice List</h1>
<div class="my-2 text-end">
	<a href="/invoices/refresh" class="btn btn-sm btn-outline-secondary">Refresh Invoices</a>
</div>

<?php
if (!empty($invoices)) {
	?>
	<div class="table-responsive small">
	<table class='table small table-sm table-hover mt-2'>
		<thead class="table-light">
		<tr>
			<th>#</th>
			<th>Invoice No.</th>
			<th>Invoice Date</th>
			<th>Invoice Value</th>
			<th>MRP Rounding Up</th>
			<th class="text-muted">Net Invoice Value</th>
			<th>DD Amount</th>
			<th>Prev Credit</th>
			<th>Special Excise Cess</th>
			<th>TCS</th>
			<th>New Retailer Professional Tax</th>
			<th class="text-muted">(DD Amount + Prev Credit)</th>
			<th class="text-muted">Total Purchase Value</th>
			<th>Retailer Credit Balance</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		foreach ($invoices as $row) {
			$k++;
			$invoiceValue = (float)$row['Invoice']['invoice_value'];
			$tcs_value = (float)$row['Invoice']['tcs_value'];
			$retail_shop_excise_turnover_tax = (float)$row['Invoice']['retail_shop_excise_turnover_tax'];
			$special_excise_cess = (float)$row['Invoice']['special_excise_cess'];
			$newRetailerProfessionalTax = (float)$row['Invoice']['new_retailer_prof_tax'];
			$mrpRoundingOff = (float)$row['Invoice']['mrp_rounding_off'];
			$ddAmount = (float)$row['Invoice']['dd_amount'];
			$prevCredit = (float)$row['Invoice']['prev_credit'];
			$ddPurchase = (float)$row['Invoice']['dd_purchase'];
			$creditBalance = (float)$row['Invoice']['credit_balance'];

			?>
			<tr>
				<td><?php echo $k; ?></td>
				<td>
					<?php
					// echo $this->Html->link($row['Invoice']['name'], array('controller'=>'invoices', 'action'=>'selectInvoice', $row['Invoice']['id']), array('title'=>'Add/Remove products in this invoice - '.$row['Invoice']['name']));

					?>
					<form
						method="post"
						style=""
						name="invoice_remove_product_<?php echo $row['Invoice']['id']; ?>"
						id="invoice_remove_product_<?php echo $row['Invoice']['id']; ?>"
						action="<?php echo $this->Html->url("/invoices/Delete/" . $row['Invoice']['id']); ?>"
					>
						<div class="dropdown">
							<a class="dropdown-toggle" role="button" id="dropdownMenuButton<?= $row['Invoice']['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
								<?php echo $row['Invoice']['name']; ?>
							</a>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $row['Invoice']['id'] ?>">
								<li>
									<a class="dropdown-item"
									   href="/invoices/details/<?php echo $row['Invoice']['id']; ?>">Details</a>
								</li>
								<li>
									<a class="dropdown-item" href="/invoices/edit/<?php echo $row['Invoice']['id']; ?>">Edit</a>
								</li>
								<li>
									<hr class="dropdown-divider">
								</li>
								<li>
									<a
										href="javascript:return false;"
										onclick="if (confirm('Deleting this invoice will remove all the products associated with it.\n\nAre you sure you want to delete this invoice <?php echo $row['Invoice']['name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Invoice']['id']; ?>').submit(); } event.returnValue = false; return false;"
										class="dropdown-item small">
										Delete
									</a>
								</li>
							</ul>
						</div>
					</form>
				</td>
				<td class="text-nowrap"><?= date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
				<td><?= $invoiceValue ?></td>
				<td><?= $mrpRoundingOff ?></td>
				<td class="text-muted"><?= $invoiceValue + $mrpRoundingOff; ?></td>
				<td><?= $ddAmount; ?></td>
				<td><?= $prevCredit; ?></td>
				<td><?= $special_excise_cess; ?></td>
				<td><?= $tcs_value; ?></td>
				<td><?= $newRetailerProfessionalTax; ?></td>
				<td class="text-muted"><?= $ddAmount + $prevCredit; ?></td>
				<td class="text-muted"><?= $ddPurchase; ?></td>
				<td><?= $creditBalance; ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	</div>
	<?php
} else {
	?>
	<p>No Invoices Found</p>
	<?php
}
?>
