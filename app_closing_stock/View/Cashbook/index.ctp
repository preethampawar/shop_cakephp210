<h1>Cash Book</h1>

<div class="text-end mt-3">
	<a href="/cashbook/add" class="btn btn-primary btn-sm">+ Add New Record</a>
</div>

<div class="mt-3">



	<?php
	if (!empty($categoriesList)) {
	?>
	<div class="card bg-light mt-4">
		<div class="card-body">
			<div class="d-flex justify-content-between">
				<label class="form-label">Filter</label>
				<?php echo $this->Form->select('Category.id', $categoriesList, ['empty' => '- Select Category -', 'class' => 'form-select form-select-sm ms-2', 'onchange' => 'selectCategory(this)', 'default' => $categoryID]); ?>


				<script type="text/javascript">
					function selectCategory(ele) {
						var catId = ele.value ?? '';
						window.location = '/cashbook/index/' + catId;
					}
				</script>

			</div>

		</div>
	</div>
	<?php
	}
	?>

	<h6 class="mt-3">
		<?php
		if ($categoryInfo) {
			echo 'Category "' . $categoryInfo['Category']['name'] . '"';
			?>
			<span
					style="font-size:11px; font-style:italic;">[<?php echo $this->Html->link('Show all records', ['controller' => 'cashbook', 'action' => 'index'], ['title' => 'Show all category records']); ?>]</span>
			<?php
		} else {
			echo 'All Records';
		}
		?>
	</h6>

	<?php
	if ($cashbook) {
		?>

		<div class="table-responsive mt-3 small">
			<table class='table table-sm'>
			<thead>
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Amount</th>
				<th>Type</th>
				<th>Category</th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($cashbook as $row) {
				$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td class="text-nowrap"><?php echo date('d-m-Y', strtotime($row['Cashbook']['payment_date'])); ?></td>
					<td><?php echo $row['Cashbook']['payment_amount']; ?></td>
					<td><?php echo ucwords($row['Cashbook']['payment_type']); ?></td>
					<td>
						<?php echo $row['Cashbook']['category_name']; ?>
						<span class="text-muted small"><?php echo $row['Cashbook']['description']; ?></span>
					</td>
					<td>
						<form method="post" style=""
							  name="invoice_cashbook_product_<?php echo $row['Cashbook']['id']; ?>"
							  id="invoice_cashbook_product_<?php echo $row['Cashbook']['id']; ?>"
							  action="<?php echo $this->Html->url("/cashbook/remove/" . $row['Cashbook']['id']); ?>">
							<a href="#" name="Remove"
							   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#invoice_cashbook_product_<?php echo $row['Cashbook']['id']; ?>').submit(); } event.returnValue = false; return false;"
							   class="btn btn-danger btn-sm">
								<span class="fa fa-trash-can" aria-hidden="true"></span>
							</a>
						</form>

						<?php
						//echo $this->Form->postLink('Remove', array('controller'=>'cashbook', 'action'=>'remove', $row['Cashbook']['id']), array('title'=>'Remove this record', 'class'=>'small button link red'), 'Are you sure you want to delete this record?');
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		</div>
		<?php
		if (count($cashbook) > 10) {
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
		<p class="text-muted small">No records found.</p>
	<?php
	}
	?>

</div>
