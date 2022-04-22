<?php $this->start('transactions_menu');?>
<?php echo $this->element('transactions_menu');?>
<?php $this->end();?>

<h1>Transactions</h1>
<br>

<div class="mt-3">
	<?php
	if (!empty($categoriesList)) {
	?>
	<div class="card bg-light mt-4">
		<div class="card-body">
			<div class="d-flex justify-content-between">
				<label class="form-label">Filter</label>
				<?php echo $this->Form->select('TransactionCategory.id', $categoriesList, ['empty' => '- Select Account -', 'class' => 'form-control form-control-sm ms-2', 'onchange' => 'selectCategory(this)', 'default' => $transactionCategoryId]); ?>


				<script type="text/javascript">
					function selectCategory(ele) {
						var catId = ele.value ?? '';
						window.location = '/transactions/index/' + catId;
					}
				</script>

			</div>

		</div>
	</div>
		<br>
	<?php
	}
	?>

	<h6 class="mt-3">
		<?php
		if ($categoryInfo) {
			echo 'Account "' . $categoryInfo['TransactionCategory']['name'] . '"';
			?>
			<span
					style="font-size:11px; font-style:italic;">[<?php echo $this->Html->link('Show all records', ['controller' => 'transactions', 'action' => 'index'], ['title' => 'Show all category records']); ?>]</span>
			<?php
		} else {
			echo 'All Records';
		}
		?>
	</h6>

	<?php
	if ($cashbook) {
		?>

		<div class="table-responsive mt-3">
			<table class='table table-sm'>
			<thead>
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Amount</th>
				<th>Type</th>
				<th>Account</th>
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
					<td class="text-nowrap"><?php echo date('d-m-Y', strtotime($row['Transaction']['payment_date'])); ?></td>
					<td class="<?= $row['Transaction']['payment_type'] === 'expense' ? 'text-danger' : 'text-success' ?>"><?php echo $row['Transaction']['payment_amount']; ?></td>
					<td><?php echo ucwords($row['Transaction']['payment_type'] === 'expense' ? 'Debit' : 'Credit'); ?></td>
					<td>
						<?php echo $row['Transaction']['transaction_category_name']; ?>
						<div class="text-muted small"><?php echo $row['Transaction']['description']; ?></div>
					</td>
					<td class="text-end" style="width: 100px;">
						<form method="post" style=""
							  name="invoice_cashbook_product_<?php echo $row['Transaction']['id']; ?>"
							  id="invoice_cashbook_product_<?php echo $row['Transaction']['id']; ?>"
							  action="<?php echo $this->Html->url("/transactions/remove/" . $row['Transaction']['id']); ?>">
							<a href="#" name="Remove"
							   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#invoice_cashbook_product_<?php echo $row['Transaction']['id']; ?>').submit(); } event.returnValue = false; return false;"
							   class="btn btn-danger btn-sm">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
							</a>
						</form>

						<?php
						//echo $this->Form->postLink('Remove', array('controller'=>'transactions', 'action'=>'remove', $row['Transaction']['id']), array('title'=>'Remove this record', 'class'=>'small button link red'), 'Are you sure you want to delete this record?');
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
