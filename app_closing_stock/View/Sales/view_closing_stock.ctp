<!--
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/stores/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Show Closing Stock Report</li>
    </ol>
</nav>
-->

<h2 class="mb-3"><i class="fa fa-list-alt"></i> Closing Stock Report</h2>

<?php
echo $this->Form->create();
?>
<div>
	<label for="fromDate">From Date:</label><br>
	<input type="date" id="fromDate" name="fromDate" class="form-control form-control-sm"
	   value="<?php echo $fromDate; ?>" required>
</div>
<div class="mt-3">
	<label for="toDate">To Date:</label><br>
	<input type="date" name="toDate" class="form-control form-control-sm" value="<?php echo $toDate; ?>"
		   required>
</div>

<div class="row mb-3 mt-3">
	<div class="col-xs-12 text-center">
		<button type="submit" class="btn btn-purple btn-sm">Generate Report</button>
	</div>
</div>
<?php
echo $this->Form->end();
?>
<hr>
<p class="small text-center">Result from "<?php echo Date('d-m-Y', strtotime($fromDate)); ?>" to
	"<?php echo Date('d-m-Y', strtotime($toDate)); ?>"</p>
<?php
if ($sales) {
	$totalAmount = 0;
	foreach ($sales as $row) {
		$totalAmount += $row['Sale']['total_amount'];
	}
	?>
	<hr>
	<div class="text-center"><strong>Total Amount - <?php echo $totalAmount; ?></strong></div>
	<hr>

	<div class="table-responsive">
		<table class='table table-sm small table-hover'>
			<thead class="table-light">
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Product</th>
				<th>Closing Qty</th>
				<th>Sale Qty</th>
				<th>Unit Price</th>
				<th>Sale Amount</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			$totalAmount = 0;
			foreach ($sales as $row) {
				$i++;
				$totalAmount += $row['Sale']['total_amount'];
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date'])); ?></td>
					<td><?php echo $row['Sale']['product_name']; ?></td>
					<td><?php echo $row['Sale']['closing_stock_qty']; ?></td>
					<td><?php echo $row['Sale']['total_units']; ?></td>
					<td><?php echo $row['Sale']['unit_price']; ?></td>
					<td><?php echo $row['Sale']['total_amount']; ?></td>
					<td>
						<form method="post" style="" name="sales_<?php echo $row['Sale']['id']; ?>"
							  id="sales_<?php echo $row['Sale']['id']; ?>"
							  action="<?php echo $this->Html->url("/sales/removeProduct/" . $row['Sale']['id']); ?>">
							<button
								type="button"
								class="btn-close"
								aria-label="Close"
								onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name']; ?> from the list?')) { $('#sales_<?php echo $row['Sale']['id']; ?>').submit(); } event.returnValue = false; return false;"
							></button>
						</form>
						<?php //echo $this->Form->postLink('Remove', array('controller'=>'sales', 'action'=>'removeProduct', $row['Sale']['id']), array('title'=>'Remove product from invoice - '.$row['Sale']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Sale']['product_name'].'"?');?>
					</td>
				</tr>

				<?php
			}
			?>
			<tr>
				<td colspan="6" class="text-right font-weight-bold">Total Amount -</td>
				<td class="font-weight-bold"><?php echo $totalAmount; ?></td>
				<td>&nbsp;</td>
			</tr>
			</tbody>
		</table>
	</div>
	<br>

<?php } else { ?>
	<p class="small text-center">No products found.</p>
<?php } ?>
