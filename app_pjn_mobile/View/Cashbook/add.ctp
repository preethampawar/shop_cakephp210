<?php $this->start('cashbook_menu'); ?>
<?php echo $this->element('cashbook_menu'); ?>
<?php $this->end(); ?>

<div class="row">
	<div class="col-xs-7 col-sm-7 col-lg-9">
		<h1>Cashbook</h1>
		<div>
			<br>
			<p><label for="categoryList">Select category to add records (<a href="/categories/">+ Add New Category</a>)</label>
			</p>

			<select
				id="categoryList"
				name="category"
				style="width:200px;"
				onchange="selectCategory()"
				class="autoSuggest form-control input-sm">
				<option value="0"> -</option>
				<?php
				if (!empty($categoriesList)) {
					foreach ($categoriesList as $cat_id => $cat_name) {
						?>
						<option value="<?php echo $cat_id; ?>"
							<?php
							echo (isset($categoryInfo['Category']['id']) and ($categoryInfo['Category']['id'] == $cat_id)) ? 'selected' : null;
							?>>
							<?php echo $cat_name; ?>
						</option>
						<?php
					}
				}
				?>
			</select>

			<script type="text/javascript">
				function selectCategory() {
					var catId = $('#categoryList').val();
					window.location = '/cashbook/add/' + catId;
				}
			</script>

			<?php
			if (empty($categoriesList)) {
				echo 'Create new category to add records';
			}
			?>

			<br><br>
		</div>


		<?php
		if ($categoryInfo) {
			$expense = $categoryInfo['Category']['expense'];
			$income = $categoryInfo['Category']['income'];
			$showType = false;
			if (($income and $expense) or (!$income and !$expense)) {
				$showType = true;
				$type = 'Income/Expense';
			} else {
				$type = ($income) ? 'income' : 'expense';
			}
			?>

			<div id="AddCashRecordDiv" class="well">
				<?php echo $this->Form->create('Cashbook', ['url' => '/cashbook/add/' . $categoryInfo['Category']['id']]); ?>
				<div style="float:left; clear:none;">
					<?php
					if ($showType) {
						$options = ['expense' => 'Expense', 'income' => 'Income'];
					} else {
						$type = ($income) ? 'income' : 'expense';
						if ($type == 'income') {
							$options = ['income' => 'Income'];
						} else {
							$options = ['expense' => 'Expense'];
						}
					}

					echo $this->Form->input('payment_type', ['type' => 'select', 'label' => 'Payment Type', 'required' => true, 'title' => 'Payment Type', 'options' => $options, 'style' => 'width:110px;']);
					?>
				</div>

				<div style="float:left; clear:none; margin-left:10px;">
					<?php echo $this->Form->input('payment_date', ['label' => 'Date', 'required' => true, 'type' => 'date']); ?>
				</div>
				<div style="float:left; clear:both;">
					<?php
					echo $this->Form->input('payment_amount', ['type' => 'text', 'label' => 'Amount', 'required' => true, 'title' => 'Amount', 'style' => 'width:100px;']);
					?>
				</div>

				<div style="float:left; clear:none; margin-left:10px;">
					<?php echo $this->Form->input('description', ['label' => 'Description', 'type' => 'text', 'style' => 'width:250px;']); ?>
				</div>
				<div style="float:left; clear:none; margin-left:10px; margin-top: 20px;">
					<button type="submit" class="btn btn-purple btn-sm">Add Record</button>
				</div>
				<div style="clear:both; padding:0px;"></div>
				<?php echo $this->Form->end(); ?>
			</div>
			<?php
		}
		?>


		<h2>
			Recently added records (5)
		</h2>
		<?php
		if ($cashbook) {
			?>

			<table class='table' style="width:100%;">
				<thead>
				<tr>
					<th style="width:20px;">#</th>
					<th style="width:150px;">Category</th>
					<th>Description</th>
					<th style="width:150px;">Payment Amount</th>
					<th style="width:120px;">Payment Type</th>
					<th style="width:120px;">Payment Date</th>
					<th style="width:50px;">Actions</th>
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
						<td><?php echo $row['Cashbook']['category_name']; ?></td>
						<td><?php echo $row['Cashbook']['description']; ?></td>
						<td><?php echo $row['Cashbook']['payment_amount']; ?></td>
						<td><?php echo ucwords($row['Cashbook']['payment_type']); ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['Cashbook']['payment_date'])); ?></td>
						<td>
							<form method="post" style=""
								  name="invoice_cashbook_product_<?php echo $row['Cashbook']['id']; ?>"
								  id="invoice_cashbook_product_<?php echo $row['Cashbook']['id']; ?>"
								  action="<?php echo $this->Html->url("/cashbook/remove/" . $row['Cashbook']['id']); ?>">
								<a href="#" name="Remove"
								   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#invoice_cashbook_product_<?php echo $row['Cashbook']['id']; ?>').submit(); } event.returnValue = false; return false;"
								   class="btn btn-danger btn-xs">
									<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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
			<p>No records found.</p>
		<?php } ?>
	</div>
</div>
