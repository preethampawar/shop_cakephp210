<?php echo $this->element('transactions_menu');?>

<h1 class="mt-3">Manage Transaction Categories</h1>
<br>
<div id="AddCategoryDiv" class="card bg-light mt-3">
	<div class="card-body">
		<?php echo $this->Form->create('TransactionCategory', ['url' => '/TransactionCategories/add/']); ?>

		<?php echo $this->Form->input('name', ['placeholder' => 'Enter Transaction Category Name', 'label' => 'Category Name', 'required' => true, 'class' => 'form-control form-control-sm']); ?>

		<?php // echo $this->Form->input('expense', ['type' => 'checkbox', 'label' => 'Expense']); ?>
		<?php // echo $this->Form->input('income', ['type' => 'checkbox', 'label' => 'Income']); ?>

		<?php echo $this->Form->submit('Create Transaction Category', ['class' => 'btn btn-primary btn-sm mt-3']); ?>

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
			<th>Category Name</th>
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
					<span class=" me-1 <?= $row['TransactionCategory']['active'] == '1' ? 'text-success' : 'text-danger' ?>">&#9679;</span>
					<?php
					echo $row['TransactionCategory']['name'];
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
