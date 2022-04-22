<h1>Select Category</h1><br>

<?php if ($categories) { ?>
	<table class='table'>
		<thead>
		<tr>
			<th style="width:20px">#</th>
			<th>Category</th>
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
					<?php echo $this->Html->link($row['ProductCategory']['name'], ['controller' => 'product_categories', 'action' => 'index', $row['ProductCategory']['id']], ['title' => 'Show all products in ' . $row['ProductCategory']['name'] . ' category']); ?>

				</td>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
}
?>
