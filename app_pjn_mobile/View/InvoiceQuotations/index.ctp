<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>

	<div>
		<a href="/invoice_quotations/index/invoice" class="ml-2 mr-2">Show All Invoices</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="/invoice_quotations/index/quotation" class="ml-2">Show All Quotations</a>

	</div><br>

	<h1>
		<?php
		switch ($type) {
			case 'template' :
				echo 'Invoice / Quotation Templates';
				break;
			case 'invoice' :
				echo 'Invoices';
				break;
			case 'quotation' :
				echo 'Quotations';
				break;
			default:
				break;
		}
		?>
	</h1>
	<br>
<?php
if (!empty($quotations)) {
	?>
	<?php
	if ($type == 'template') {
		?>
		<table class="table table-small table-bordered table-striped">
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Type</th>
				<th>Template Name</th>
				<th>From Company Details</th>
				<th>Created On</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($quotations as $index => $row) {
				?>
				<tr>
					<td><?php echo $index + 1; ?></td>
					<td>Template</td>
					<td>
						<a href="/invoice_quotations/selectTemplate/<?php echo $row['InvoiceQuotation']['id']; ?>">
							<?php echo $row['InvoiceQuotation']['name']; ?>
						</a>
					</td>
					<td><?php echo $row['InvoiceQuotation']['from']; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['InvoiceQuotation']['created'])); ?></td>
					<td>
						<a href="/invoice_quotations/details/<?php echo $row['InvoiceQuotation']['id']; ?>">
							Details
						</a>
						&nbsp;&nbsp;|&nbsp;
						<a href="/invoice_quotations/editTemplate/<?php echo $row['InvoiceQuotation']['id']; ?>">
							Edit Template
						</a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}
	?>

	<!-- invoices -->
	<?php
	if ($type == 'invoice') {
		?>
		<table class="table table-small table-bordered table-striped">
			<thead>
			<tr>
				<th>Type</th>
				<th>Invoice No.</th>
				<th>Invoice Date</th>
				<th>To Company Details</th>
				<th>Total Amount</th>
				<th>Created On</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($quotations as $index => $row) {
				?>
				<tr>
					<td>Invoice</td>
					<td><?php echo $index + 1; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['InvoiceQuotation']['from_date'])); ?></td>
					<td><?php echo $row['InvoiceQuotation']['for']; ?></td>
					<td><?php echo $row['InvoiceQuotation']['total_amount']; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['InvoiceQuotation']['created'])); ?></td>
					<td>
						<a href="/invoice_quotations/details/<?php echo $row['InvoiceQuotation']['id']; ?>">
							Details
						</a>
						&nbsp;&nbsp;|&nbsp;
						<a href="/invoice_quotations/edit/<?php echo $row['InvoiceQuotation']['id']; ?>">
							Edit
						</a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}
	?>

	<!-- invoices -->
	<?php
	if ($type == 'quotation') {
		?>
		<table class="table table-small table-bordered table-striped">
			<thead>
			<tr>
				<th>Type</th>
				<th>Quotation No.</th>
				<th>Quotation Date</th>
				<th>To Company Details</th>
				<th>Total Amount</th>
				<th>Created On</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($quotations as $index => $row) {
				?>
				<tr>
					<td>Quotation</td>
					<td><?php echo $index + 1; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['InvoiceQuotation']['from_date'])); ?></td>
					<td><?php echo $row['InvoiceQuotation']['for']; ?></td>
					<td><?php echo $row['InvoiceQuotation']['total_amount']; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['InvoiceQuotation']['created'])); ?></td>
					<td>
						<a href="/invoice_quotations/details/<?php echo $row['InvoiceQuotation']['id']; ?>">
							Details
						</a>
						&nbsp;&nbsp;|&nbsp;
						<a href="/invoice_quotations/edit/<?php echo $row['InvoiceQuotation']['id']; ?>">
							Edit
						</a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}
	?>

	<?php
} else {
	echo 'No records found <br> <br>';
}
?>
