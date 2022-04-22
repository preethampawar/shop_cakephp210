<article>
	<header><h1><?php echo ucwords($type); ?> Invoices</h1></header>
	<br>
	<?php
	if (!empty($invoices)) {
		?>
		<table class='table small table-sm table-hover'>
			<thead>
			<tr>
				<th>#</th>
				<th>Invoice No.</th>
				<th>Invoice Date</th>
				<th>Invoice Amount</th>
				<th><?php echo ucwords($type); ?> Amount</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			foreach ($invoices as $row) {
				$k++;


				?>
				<tr>
					<td><?php echo $k; ?></td>
					<td>
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

						<?php
						//echo $this->Html->link($row['Invoice']['name'], ['controller' => 'invoices', 'action' => 'selectInvoice', $row['Invoice']['id']], ['title' => 'Add/Remove products in this invoice - ' . $row['Invoice']['name']]);
						?>
					</td>

					<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
					<td><?php echo $row['Invoice']['static_invoice_value']; ?></td>
					<td><?php echo $row['Invoice']['invoice_value']; ?></td>


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

</article>
