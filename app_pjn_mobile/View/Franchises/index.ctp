<?php $this->start('franchise_menu'); ?>
<?php echo $this->element('franchise_menu'); ?>
<?php $this->end(); ?>

	<h1>Franchise List</h1><br>
<?php if ($franchises) { ?>

	<table class='table table-condensed' style="width:100%">
		<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:80px;">Status</th>
			<th style="width:80px;">Code</th>
			<th>Name</th>
			<th>Email</th>
			<th style="width:100px;">Mobile</th>
			<th style="width:100px;">District</th>
			<th style="width:100px;">State</th>
			<th style="width:250px;">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($franchises as $row) {
			$i++;
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>
					<?php

					echo $row['Franchise']['is_active'] ? '<span class="text-success"><i class="glyphicon glyphicon-user"></i> Active</span>' : '<span class="text-danger"><i class="glyphicon glyphicon-user"></i> Inactive</span>';
					?>
				</td>
				<td><?php echo $row['Franchise']['code']; ?></td>
				<td><?php echo $this->Html->link($row['Franchise']['name'], ['controller' => 'franchises', 'action' => 'view', $row['Franchise']['id']], ['title' => 'View Franchise Details - ' . $row['Franchise']['name']]); ?></td>
				<td><?php echo $row['Franchise']['email']; ?></td>
				<td><?php echo $row['Franchise']['mobile']; ?></td>
				<td><?php echo $row['Franchise']['district']; ?></td>
				<td><?php echo $row['Franchise']['state']; ?></td>
				<td style="width:250px;">
					<form method="post" style="" name="remove_franchise<?php echo $row['Franchise']['id']; ?>"
						  id="remove_franchise<?php echo $row['Franchise']['id']; ?>"
						  action="<?php echo $this->Html->url("/franchises/remove/" . $row['Franchise']['id']); ?>">
						<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group">

							<?php
							echo $this->Html->link('Details', ['controller' => 'franchises', 'action' => 'view', $row['Franchise']['id']], ['title' => 'Franchise Details - ' . $row['Franchise']['name'], 'class' => 'btn btn-default btn-xs', 'role' => 'button']);
							?>
							<?php
							echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit', ['controller' => 'franchises', 'action' => 'edit', $row['Franchise']['id']], ['title' => 'Edit ' . $row['Franchise']['name'], 'class' => 'btn btn-default btn-xs', 'role' => 'button', 'escape' => false]);
							?>
							<a href="javascript:return false;"
							   onclick="if (confirm('Deleting this franchise will remove all the products associated with it.\n\nAre you sure you want to delete this franchise <?php echo $row['Franchise']['name']; ?> from the list?')) { $('#franchise_remove_product_<?php echo $row['Franchise']['id']; ?>').submit(); } event.returnValue = false; return false;"
							   class="btn btn-default btn-xs" role="button">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
							</a>

						</div>

					</form>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
<?php } else { ?>
	<p>No franchise found.</p>
<?php } ?>
