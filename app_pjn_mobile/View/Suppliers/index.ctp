<?php $this->start('invoices_report_menu'); ?>
<?php echo $this->element('invoices_menu'); ?>
<?php echo $this->element('sales_purchases_report_menu'); ?>
<?php $this->end(); ?>

<article>
	<header><h1>Suppliers</h1></header>
	<br>
	<p><?php echo $this->Html->link('+ Add New Supplier', ['controller' => 'suppliers', 'action' => 'add'], ['class' => 'small button link green']); ?></p>
	<?php
	if (!empty($suppliers)) {
		?>
		<table class='table'>
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Supplier</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Address</th>
				<th>Created on</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			foreach ($suppliers as $row) {
				$k++;
				?>
				<tr>
					<td><?php echo $k; ?></td>
					<td>
						<?php echo $this->Html->link($row['Supplier']['name'], ['controller' => 'suppliers', 'action' => 'details', $row['Supplier']['id']], ['title' => 'Supplier details']); ?>
					</td>
					<td><?php echo $row['Supplier']['phone']; ?></td>
					<td><?php echo $row['Supplier']['email']; ?></td>
					<td><?php echo $row['Supplier']['address']; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Supplier']['created'])); ?></td>
					<td>
						<form method="post" style="" name="supplier_<?php echo $row['Supplier']['id']; ?>"
							  id="supplier_<?php echo $row['Supplier']['id']; ?>"
							  action="<?php echo $this->Html->url("/suppliers/remove/" . $row['Supplier']['id']); ?>">
							<?php echo $this->Html->link('Edit', ['controller' => 'suppliers', 'action' => 'edit', $row['Supplier']['id']], ['title' => 'Edit ' . $row['Supplier']['name'], 'class' => 'small button link yellow']); ?>
							|
							<input type="submit" value="Remove" name="Remove"
								   onclick="if (confirm('Are you sure you want to delete this supplier - <?php echo $row['Supplier']['name']; ?>?')) { $('#supplier_<?php echo $row['Supplier']['id']; ?>').submit(); } event.returnValue = false; return false;">

							<?php //echo $this->Form->postLink('Remove', array('controller'=>'suppliers', 'action'=>'remove', $row['Supplier']['id']), array('title'=>'Remove Supplier - '.$row['Supplier']['name'], 'class'=>'small button link red'), 'Are you sure you want to delete this Supplier - "'.$row['Supplier']['name'].'" ?');	?>
						</form>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<p>No Suppliers Found</p>
		<?php
	}
	?>

</article>
