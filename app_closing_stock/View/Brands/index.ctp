<?php $this->start('dealers_report_menu'); ?>
<?php echo $this->element('dealers_menu'); ?>
<?php $this->end(); ?>
<?php echo $this->element('dealers_top_nav_menu'); ?>

	<h1>Brands List</h1><br>
<?php if ($brands) { ?>
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

	<table class='table' style="width:100%">
		<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:150px;"><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
			<th style="width:80px;">Created on</th>
			<th style="width:120px;">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($brands as $row) {
			$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $this->Html->link($row['Brand']['name'], ['controller' => 'brands', 'action' => 'view', $row['Brand']['id']], ['title' => 'View Brand Details - ' . $row['Brand']['name']]); ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Brand']['created'])); ?></td>
				<td>
					<?php echo $this->Html->link('<span class="fa fa-pencil" aria-hidden="true"></span>', ['controller' => 'brands', 'action' => 'edit', $row['Brand']['id']], ['title' => 'Edit Brand - ' . $row['Brand']['name'], 'class' => 'btn btn-sm btn-warning', 'escape' => false]); ?>
					&nbsp;|&nbsp;
					<?php echo $this->Html->link('<span class="fa fa-trash-can" aria-hidden="true"></span>', ['controller' => 'brands', 'action' => 'remove', $row['Brand']['id']], ['title' => 'Remove Brand - ' . $row['Brand']['name'], 'escape' => false, 'class' => 'btn btn-danger btn-sm'], ' Brand - ' . $row['Brand']['name'] . ':  Deleting this brand will remove all the associated records. Are you sure you want to delete this brand?'); ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
<?php } else { ?>
	<p>No brand found.</p>
<?php } ?>
