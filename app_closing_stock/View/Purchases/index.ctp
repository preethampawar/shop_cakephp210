<?php $this->start('purchases_report_menu'); ?>
<?php echo $this->element('purchases_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php echo $this->element('stock_report_menu'); ?>
<?php $this->end(); ?>

	<h1>Import Purchases from CSV file</h1>
	<p><?php echo $this->Html->link('Import Purchases', ['controller' => 'purchases', 'action' => 'uploadCsv']); ?></p>
	<br>

	<h2>Recent Purchases</h2>
<?php
if ($purchases) {
	?>
	<?php
	// prints X of Y, where X is current page and Y is number of pages
	echo 'Page ' . $this->Paginator->counter();
	echo '&nbsp;&nbsp;&nbsp;&nbsp;';

	// Shows the next and previous links
	echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	// Shows the page numbers
	echo $this->Paginator->numbers();

	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	?>
	<table class='table' style="width:100%;">
		<thead>
		<tr>
			<th>S.No</th>
			<th>Date</th>
			<th>Product</th>
			<th>No. of Boxes</th>
			<th>Box Price</th>
			<th>Quantity</th>
			<th>Unit Price</th>
			<th>Total Amount</th>
			<th>Invoice</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($purchases as $row) {
			$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Purchase']['purchase_date'])); ?></td>
				<td><?php echo $row['Purchase']['product_name']; ?></td>
				<td style="text-align:center;"><?php echo ($row['Purchase']['invoice_id']) ? $row['Purchase']['box_qty'] : '-'; ?></td>
				<td><?php echo ($row['Purchase']['invoice_id']) ? $row['Purchase']['box_buying_price'] : '-'; ?></td>
				<td><?php echo (!$row['Purchase']['invoice_id']) ? $row['Purchase']['total_units'] : '-'; ?></td>
				<td><?php echo (!$row['Purchase']['invoice_id']) ? $row['Purchase']['unit_price'] : '-'; ?></td>
				<td><?php echo $row['Purchase']['total_amount']; ?></td>
				<td><?php echo ($row['Purchase']['invoice_id']) ? $this->Html->link($row['Purchase']['invoice_name'], ['controller' => 'invoices', 'action' => 'details', $row['Purchase']['invoice_id']], ['title' => 'Invoice Details']) : '-'; ?></td>
				<td>
					<form method="post" style="" name="purchase_product_<?php echo $row['Purchase']['id']; ?>"
						  id="purchase_product_<?php echo $row['Purchase']['id']; ?>"
						  action="<?php echo $this->Html->url("/purchases/removeProduct/" . $row['Purchase']['id']); ?>">
						<a href="#" name="Remove"
						   onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Purchase']['product_name']; ?> from the list?')) { $('#purchase_product_<?php echo $row['Purchase']['id']; ?>').submit(); } event.returnValue = false; return false;"
						   class="btn btn-danger btn-sm">
							<span class="fa fa-trash-can" aria-hidden="true"></span>
						</a>
					</form>

					<?php //echo $this->Form->postLink('Remove', array('controller'=>'purchases', 'action'=>'removeProduct', $row['Purchase']['id']), array('title'=>'Remove product - '.$row['Purchase']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Purchase']['product_name'].'"?');?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
	if (count($purchases) > 10) {
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page ' . $this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		// Shows the next and previous links
		echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();

		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	}
	?>
<?php } else { ?>
	<p>No products found in Invoice "<?php echo $this->Session->read('Invoice.name'); ?>".</p>
<?php } ?>
