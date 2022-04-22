<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>

<h1>Step 1 - Select Template</h1>

<table style="width:500px; font-size: 100%;" class="table table-striped">
	<thead>
	<tr>
		<th width='50'>Sl.No.</th>
		<th>Template</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	foreach ($quotations as $index => $row) {
		$i++;
		?>
		<tr>
			<td><?php echo $i; ?></td>

			<td>
				<?php echo $this->Html->link($row['InvoiceQuotation']['name'], ['controller' => 'invoice_quotations', 'action' => 'create', $row['InvoiceQuotation']['id']], ['class' => 'btn btn-xs btn-info', 'title' => 'Select template: ' . $row['InvoiceQuotation']['name'], 'escape' => false]); ?>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
