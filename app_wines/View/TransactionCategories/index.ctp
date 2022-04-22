<?php $this->start('transactions_menu');?>
<?php echo $this->element('transactions_menu');?>
<?php $this->end();?>

<h1 class="mt-3">Manage Accounts</h1>
<br>
<div id="AddCategoryDiv" class="card bg-light mt-3">
	<div class="card-body">
		<?php echo $this->Form->create('TransactionCategory', ['url' => '/TransactionCategories/add/']); ?>

		<?php echo $this->Form->input('name', ['placeholder' => 'Enter Account Name', 'label' => 'New Account Name', 'required' => true, 'class' => 'form-control form-control-sm']); ?>

		<?php // echo $this->Form->input('expense', ['type' => 'checkbox', 'label' => 'Expense']); ?>
		<?php // echo $this->Form->input('income', ['type' => 'checkbox', 'label' => 'Income']); ?>

		<?php echo $this->Form->submit('Create Account', ['class' => 'btn btn-primary btn-sm mt-3']); ?>

		<?php echo $this->Form->end(); ?>
	</div>
</div>
<br>
<h2 class="mt-4">Categories</h2>
<?php if ($categories) { ?>
	<table class='table'>
		<thead>
		<tr>
			<th>#</th>
			<th>Account Name</th>
			<th>Show in Balance Sheet</th>
			<th>Priority</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($categories as $row) {
			$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>

					<i class="me-2 fa fa-circle <?= $row['TransactionCategory']['active'] == '1' ? 'text-success' : 'text-danger' ?>"></i>

					<?php
					echo $row['TransactionCategory']['name'];
					?>
				</td>
				<td>
					<?php
					echo $row['TransactionCategory']['show_in_balance_sheet'] ? 'Yes' : '';
					?>
				</td>
				<td>
					<?php
					echo $row['TransactionCategory']['priority'] > 0 ? $row['TransactionCategory']['priority'] : '';
					?>
				</td>
				<td class="text-end">
					<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit', ['controller' => 'TransactionCategories', 'action' => 'edit', $row['TransactionCategory']['id']], ['title' => 'Edit TransactionCategory - ' . $row['TransactionCategory']['name'], 'class' => 'btn btn-warning btn-sm', 'escape' => false]); ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
<?php } else { ?>
	<p>No category found.</p>
<?php } ?>
