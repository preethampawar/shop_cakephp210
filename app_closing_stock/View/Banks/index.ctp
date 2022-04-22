<?php $this->start('bank_menu'); ?>
<?php echo $this->element('bank_menu'); ?>
<?php //echo $this->element('bank_report_menu');?>
<?php $this->end(); ?>

<div class="row">
	<div class="col-lg-9">
		<h1>Bank Book</h1>


		<div id="AddBankRecordDiv" class="well">
			<?php echo $this->Form->create('Bank', ['url' => '/banks/add/']); ?>
			<div style="float:left; clear:none;">
				<?php
				$options = ['credit' => 'Deposit', 'debit' => 'Withdrawal'];
				echo $this->Form->input('payment_type', ['type' => 'select', 'label' => 'Payment Type', 'required' => true, 'title' => 'Payment Type', 'options' => $options, 'style' => 'width:110px;']);
				?>
			</div>

			<div style="float:left; clear:none; margin-left:10px;">
				<?php echo $this->Form->input('payment_date', ['label' => 'Date', 'required' => true, 'type' => 'date']); ?>
			</div>
			<div style="float:left; clear:both;">
				<?php
				echo $this->Form->input('amount', ['type' => 'number', 'label' => 'Amount', 'required' => true, 'title' => 'Amount', 'style' => 'width:100px;']);
				?>
			</div>

			<div style="float:left; clear:none; margin-left:10px;">
				<?php echo $this->Form->input('title', ['label' => 'Description', 'type' => 'text', 'style' => 'width:250px;']); ?>
			</div>
			<div style="float:left; clear:none; margin-left:10px;">
				<br>
				<?php echo $this->Form->submit('Add Record'); ?>
			</div>
			<div style="clear:both; padding:0px;"></div>
			<?php echo $this->Form->end(); ?>
		</div>

		<br>
		<h2>Bank Records</h2>
		<?php
		if ($records) {
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
					<th style="width:20px;">#</th>
					<th style="width:150px;"><?php echo $this->Paginator->sort('payment_date', 'Date'); ?></th>
					<th>Description</th>
					<th style="width:150px;">
						<?php //echo $this->Paginator->sort('payment_type', 'Payment Type'); ?>
						Deposit
					</th>
					<th style="width:150px;">Withdrawal</th>
					<th style="width:100px;">Actions</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($records as $row) {
					$i++;
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['Bank']['payment_date'])); ?></td>
						<td><?php echo $row['Bank']['title']; ?></td>
						<td>
							<?php //echo ucwords(($row['Bank']['payment_type'] == 'credit') ? 'Deposit' : 'Withdrawal');?>
							<?php
							if ($row['Bank']['payment_type'] == 'credit') {
								echo $row['Bank']['amount'];
							}
							?>
						</td>
						<td>
							<?php
							if ($row['Bank']['payment_type'] == 'debit') {
								echo $row['Bank']['amount'];
							}
							?>
						</td>
						<td>
							<form method="post" style="" name="invoice_bank_product_<?php echo $row['Bank']['id']; ?>"
								  id="invoice_bank_product_<?php echo $row['Bank']['id']; ?>"
								  action="<?php echo $this->Html->url("/banks/remove/" . $row['Bank']['id']); ?>">
								<a href="#" name="Remove"
								   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#invoice_bank_product_<?php echo $row['Bank']['id']; ?>').submit(); } event.returnValue = false; return false;"
								   class="btn btn-danger btn-sm">
									<span class="fa fa-trash-can" aria-hidden="true"></span>
								</a>
							</form>

							<?php
							//echo $this->Form->postLink('Remove', array('controller'=>'bank', 'action'=>'remove', $row['Bank']['id']), array('title'=>'Remove this record', 'class'=>'small button link red'), 'Are you sure you want to delete this record?');
							?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
			if (count($records) > 10) {
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
