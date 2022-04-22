<?php $this->start('dealers_report_menu'); ?>
<?php echo $this->element('dealers_menu'); ?>
<?php $this->end(); ?>


<?php echo $this->element('dealers_top_nav_menu'); ?>

	<h1>Dealers List</h1><br>

<?php if ($result) { ?>
	<table class='table search-table' style="width:100%">
		<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:150px;">Dealer Name</th>
			<th style="width:80px;">Dealer Created Date</th>
			<th style="width:120px;">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($result as $row) {
			$i++;

			$dealer_id = $row['d']['id'];
			$dealer_name = $row['d']['name'];
			$dealer_created_date = date('d-m-Y', strtotime($row['d']['created']));
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $dealer_name; ?></td>
				<td><?php echo $dealer_created_date; ?></td>
				<td>
					<?php echo $this->Html->link('<span class="fa fa-edit" aria-hidden="true"></span>', ['controller' => 'dealers', 'action' => 'edit', $dealer_id], ['title' => 'Edit Dealer - ' . $dealer_name, 'class' => 'btn btn-sm btn-warning', 'escape' => false]); ?>
					&nbsp;|&nbsp;
					<?php echo $this->Html->link('<span class="fa fa-remove" aria-hidden="true"></span>', ['controller' => 'dealers', 'action' => 'remove', $dealer_id], ['title' => 'Remove Dealer - ' . $dealer_name, 'escape' => false, 'class' => 'btn btn-danger btn-sm'], ' Dealer - ' . $dealer_name . ':  Deleting this dealer will remove all the associated records. Are you sure you want to delete this dealer?'); ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
<?php } else { ?>
	<p>No dealer found.</p>
<?php } ?>
