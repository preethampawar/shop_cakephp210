<h1>Invoice List</h1>

<?php
if (!empty($invoices)) {
	?>
	<table class='table small table-sm table-hover'>
		<thead class="table-light">
		<tr>
			<th>#</th>
			<th>Invoice No.</th>
			<th>Invoice Date</th>
			<th>Invoice Value</th>
			<!-- <th>MRP Rounding Up</th>
			<th>Net Invoice Value</th>
			<th>DD Amount</th> -->
		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		foreach ($invoices as $row) {
			$k++;
			$invoiceTax = $row['Invoice']['tax'];
			$invoice_amt = 0;
			if (isset($invoiceAmount[$row['Invoice']['id']])) {
				$invoice_amt = number_format(($invoiceAmount[$row['Invoice']['id']] + $invoiceTax), '2', '.', '');
			}
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
							<button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
									id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
								<?php echo $row['Invoice']['name']; ?>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
				<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
				<td><?php echo $row['Invoice']['invoice_value']; ?></td>
				<!-- <td><?php echo number_format($row['Invoice']['mrp_rounding_off'], '2', '.', ''); ?></td>
                <td><?php echo $row['Invoice']['invoice_value'] + $row['Invoice']['mrp_rounding_off']; ?></td>
                <td><?php echo $row['Invoice']['dd_amount']; ?></td> -->
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
} else {
	?>
	<p>No Invoices Found</p>
	<?php
}
?>
