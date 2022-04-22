<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/products/">All Categories</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?php echo $category['ProductCategory']['name']; ?></li>
	</ol>
</nav>


<?php
if ($categories) {
	?>


	<h1>
		<?php echo 'Products in ' . $category['ProductCategory']['name']; ?>
	</h1>

	<?php if ($products) { ?>
		<table class='table small table-sm table-hover'>
			<thead>
			<tr>
				<th style="width:20px">#</th>
				<th>Product</th>
				<th>Qty Per Box</th>
				<th>Box Price</th>
				<th>Unit SP</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($products as $row) {
				if (!empty($row['Product'])) {
					foreach ($row['Product'] as $product) {
						$i++;
						?>
						<tr>
							<td><?php echo $i; ?></td>

							<td><?php echo $this->Html->link($product['name'], ['controller' => 'products', 'action' => 'edit', $row['ProductCategory']['id'], $product['id']], ['title' => 'Edit Product - ' . $product['name']]); ?></td>
							<td><?php echo $product['box_qty']; ?></td>
							<td><?php echo $product['box_buying_price']; ?></td>
							<td><?php echo $product['unit_selling_price']; ?></td>
						</tr>
						<?php
					}
				}
			}
			?>
			</tbody>
		</table>
		<?php
	}
	?>


	<?php
} else {
	?>

	<p>No category found.</p>
	<p>First create a "Category" and add products in it.</p>
	<?php
}
?>
