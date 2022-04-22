<div class="text-end">
	<a href="/cashbook/" class="btn btn-warning btn-sm">Go to CashBook</a>
</div>

<h1 class="mt-3">Manage Categories</h1>

<div id="AddCategoryDiv" class="card bg-light mt-3">
	<div class="card-body">
		<?php echo $this->Form->create('Category', ['url' => '/categories/add/']); ?>

		<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => 'Category Name', 'required' => true, 'class' => 'form-control form-control-sm']); ?>

		<?php // echo $this->Form->input('expense', ['type' => 'checkbox', 'label' => 'Expense']); ?>
		<?php // echo $this->Form->input('income', ['type' => 'checkbox', 'label' => 'Income']); ?>

		<?php echo $this->Form->submit('Create Category', ['class' => 'btn btn-primary btn-sm mt-3']); ?>

		<?php echo $this->Form->end(); ?>
	</div>
</div>

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
			$tmp = [];
			if ($row['Category']['income']) {
				$tmp[] = 'Income';
			}
			if ($row['Category']['expense']) {
				$tmp[] = 'Expense';
			}
			$type = implode(', ', $tmp);
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>
					<i class="fa fa-circle me-1 <?= $row['Category']['active'] == '1' ? 'text-success' : 'text-danger' ?>"></i>
					<?php

					echo $row['Category']['name'];
					//echo $this->Html->link($row['Category']['name'], ['controller' => 'cashbook', 'action' => 'index', $row['Category']['id']], ['title' => 'Add records in "' . $row['Category']['name'] . '" category']);
					?>
				</td>
				<td class="text-end">
					<!--
					<form method="post" style="" name="categories_form_<?php echo $row['Category']['id']; ?>"
						  id="categories_form_<?php echo $row['Category']['id']; ?>"
						  action="<?php echo $this->Html->url("/categories/delete/" . $row['Category']['id']); ?>">
						<a href="#" name="Remove"
						   onclick="if (confirm('Are you sure you want to delete this category - <?php echo $row['Category']['name']; ?>?')) { $('#categories_form_<?php echo $row['Category']['id']; ?>').submit(); } event.returnValue = false; return false;"
						   class="btn btn-danger btn-sm">
							<span class="fa fa-trash-can" aria-hidden="true"></span>
						</a>
						&nbsp;&nbsp;



						<?php //echo $this->Form->postLink('Remove', array('controller'=>'categories', 'action'=>'delete', $row['Category']['id']), array('title'=>'Remove Category - '.$row['Category']['name'], 'class'=>'small button link red'), 'Are you sure you want to delete this category "'.$row['Category']['name'].'"');?>
					</form>
					-->
					<?php echo $this->Html->link('<span class="fa fa-pencil" aria-hidden="true"></span>', ['controller' => 'categories', 'action' => 'edit', $row['Category']['id']], ['title' => 'Edit Category - ' . $row['Category']['name'], 'class' => 'btn btn-warning btn-sm', 'escape' => false]); ?>
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
