<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>

<article>
	<header><h1><?php echo ucwords($type); ?> Invoices</h1></header>
	<br>
	<?php
	if (!empty($invoices)) {
		?>
		<table class='table' style="width:100%;">
			<thead>
			<tr>
				<th>#</th>
				<th>Type</th>
				<th>Invoice No.</th>
				<th>Invoice Date</th>
				<th>Total Invoice Amount</th>
				<th>Purchase / Sale Amount</th>
				<th>Actions</th>
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
					<td><?php echo ucwords($row['Invoice']['invoice_type']); ?></td>
					<td style="width:150px;">
						<?php
						echo $this->Html->link($row['Invoice']['name'], ['controller' => 'invoices', 'action' => 'selectInvoice', $row['Invoice']['id']], ['title' => 'Add/Remove products in this invoice - ' . $row['Invoice']['name']]);
						?>
					</td>
					<!-- <td><?php echo $row['Invoice']['invoice_value']; ?></td>
				<td><?php echo number_format($row['Invoice']['mrp_rounding_off'], '2', '.', ''); ?></td>
				<td><?php echo $row['Invoice']['invoice_value'] + $row['Invoice']['mrp_rounding_off']; ?></td>
				-->
					<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
					<td><?php echo $row['Invoice']['static_invoice_value']; ?></td>
					<td><?php echo $row['Invoice']['invoice_value']; ?></td>

					<td style="width:220px; text-align:center;">
						<form method="post" style="" name="invoice_remove_product_<?php echo $row['Invoice']['id']; ?>"
							  id="invoice_remove_product_<?php echo $row['Invoice']['id']; ?>"
							  action="<?php echo $this->Html->url("/invoices/Delete/" . $row['Invoice']['id']); ?>">
							<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">

								<?php
								echo $this->Html->link('Details', ['controller' => 'invoices', 'action' => 'selectInvoice', $row['Invoice']['id']], ['title' => 'Invoice Details - ' . $row['Invoice']['name'], 'class' => 'btn btn-default btn-xs', 'role' => 'button']);
								?>
								<?php
								echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit', ['controller' => 'invoices', 'action' => 'edit', $row['Invoice']['id']], ['title' => 'Edit ' . $row['Invoice']['name'], 'class' => 'btn btn-default btn-xs', 'role' => 'button', 'escape' => false]);
								?>
								<a href="javascript:return false;"
								   onclick="if (confirm('Deleting this invoice will remove all the products associated with it.\n\nAre you sure you want to delete this invoice <?php echo $row['Invoice']['name']; ?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Invoice']['id']; ?>').submit(); } event.returnValue = false; return false;"
								   class="btn btn-default btn-xs" role="button">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
								</a>

							</div>

							<?php //echo $this->Form->postLink('Delete', array('controller'=>'invoices', 'action'=>'Delete', $row['Invoice']['id']), array('title'=>'Remove Invoice - '.$row['Invoice']['name']), 'Deleting this invoice will remove all the products associated with it.\nAre you sure you want to delete this Invoice - "'.$row['Invoice']['name'].'" ?');	?>
						</form>
					</td>
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
