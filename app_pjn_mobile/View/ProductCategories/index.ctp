<style type="text/css">
	form {
		margin-right: 0px;
		width: 100%;
	}

	form div {
		padding: 0px;
	}
</style>

<div class="containter row">
	<div class="col-xs-5 col-sm-5 col-lg-3">
		<h1>Manage Products</h1>
	</div>
</div>
<br>
<div class="row">
	<div class="col-xs-5 col-sm-5 col-lg-3">
		<div id="AddCategoryDiv" class="well">
			<?php echo $this->Form->create('ProductCategory', ['url' => '/product_categories/add/']); ?>
			<div class="input-group">
				<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => false, 'required' => true, 'class' => 'form-control']); ?>
				<span class="input-group-btn">
				    <button type="submit" class="btn btn-purple btn-block">Create Category</button>
					<?php // echo $this->Form->submit('Create Category', array('div'=>false, 'type'=>'submit', 'class'=>'btn btn-default')); ?>
				</span>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>

		<?php if ($categories) { ?>
			<table class='table'>
				<thead>
				<tr>
					<th style="width:20px">#</th>
					<th>Category</th>
					<th style="text-align:center; width:100px;">Actions</th>
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
						<td style="text-align:center;">
							<?php // echo $this->Html->link('+ Add Product', array('controller'=>'products', 'action'=>'add', $row['ProductCategory']['id']), array('title'=>$row['ProductCategory']['name'].' - Add Product', 'escape'=>false, 'class'=>'btn btn-purple btn-xs'));?>

							<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', ['controller' => 'product_categories', 'action' => 'edit', $row['ProductCategory']['id']], ['title' => 'Edit Category - ' . $row['ProductCategory']['name'], 'escape' => false, 'class' => 'btn btn-warning btn-xs']); ?>

							<?php // echo $this->Html->link('Remove from list', array('controller'=>'product_categories', 'action'=>'remove', $row['ProductCategory']['id']), array('title'=>'Remove Category from list - '.$row['ProductCategory']['name']), 'Category - '.$row['ProductCategory']['name'].'\nProducts associated with the category, Sales and Purchase records will not be removed.\n\nAre you sure you want to remove this category from the list?');?>
							&nbsp;
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', ['controller' => 'product_categories', 'action' => 'delete', $row['ProductCategory']['id']], ['title' => 'Delete Category - ' . $row['ProductCategory']['name'], 'escape' => false, 'class' => 'btn btn-danger btn-xs'], ' Category - ' . $row['ProductCategory']['name'] . "\n Deleting this category will remove all the products associated with it.\n All Sales & Purchase records will be deleted.\n\n Are you sure you want to delete this category?"); ?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}
		?>
	</div>
	<div class="col-xs-7 col-sm-7 col-lg-9">
		<p>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-upload"></span> Upload products from CSV file', ['controller' => 'product_categories', 'action' => 'uploadCsv'], ['class' => 'btn btn-info btn-sm', 'escape' => false]); ?>

			<?php echo $this->Html->link('<span class="glyphicon glyphicon-download"></span> Download Products', ['controller' => 'product_categories', 'action' => 'downloadCsv'], ['class' => 'btn btn-info btn-sm', 'escape' => false]); ?></p>

		<h2>
			<?php
			echo $categoryID ? 'Category "' . $category['ProductCategory']['name'] . '"' : '';
			?>
		</h2>

		<h3>Total Products = <?php echo $productsCount; ?></h3>

		<?php if ($products) { ?>

			<p>
				<?php echo $categoryID ? '<br>' . $this->Html->link('+ Add Product', ['controller' => 'products', 'action' => 'add', $category['ProductCategory']['id']], ['title' => $category['ProductCategory']['name'] . ' - Add Product', 'escape' => false, 'class' => 'btn btn-purple btn-xs']) : ''; ?>
			</p>
			<table class='table search-table'>
				<thead>
				<tr>
					<th style="width:20px">#</th>
					<th>Category</th>
					<th>Brand</th>
					<th>Product</th>
					<th>Units/Box</th>
					<th>Box Price</th>
					<th>Unit Price</th>

					<th>Created on</th>
					<th style="text-align:center;">Actions</th>
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
								<td><?php echo $this->Html->link($row['ProductCategory']['name'], ['controller' => 'product_categories', 'action' => 'index', $row['ProductCategory']['id']], ['title' => 'Show all products in ' . $row['ProductCategory']['name'] . ' category']); ?> </td>
								<td><?php echo ($product['brand_id']) ? $brands[$product['brand_id']] : null; ?></td>
								<td><?php echo $this->Html->link($product['name'], ['controller' => 'products', 'action' => 'edit', $row['ProductCategory']['id'], $product['id']], ['title' => 'Edit Product - ' . $product['name']]); ?></td>
								<td><?php echo $product['box_qty']; ?></td>
								<td><?php echo $product['box_buying_price']; ?></td>
								<td><?php echo $product['unit_selling_price']; ?></td>

								<td><?php echo date('d-m-Y', strtotime($product['created'])); ?></td>
								<td style="text-align: center">
									<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', ['controller' => 'products', 'action' => 'edit', $row['ProductCategory']['id'], $product['id']], ['title' => 'Edit Product - ' . $product['name'], 'escape' => false, 'class' => 'btn btn-warning btn-xs']); ?>
									&nbsp;
									<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', ['controller' => 'products', 'action' => 'delete', $product['id']], ['title' => 'Delete Product - ' . $product['name'], 'escape' => false, 'class' => 'btn btn-danger btn-xs'], 'Product - ' . $product['name'] . "\n\nDeleting this product will remove all Sales & Purchase records associated with it.\n\nAre you sure you want to delete this product?"); ?>
								</td>
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
	</div>
</div>


<?php
if (!$categories) {
	?>

	<p>No category found.</p>
	<p>First create a "Category" and add products in it.</p>

	<?php
}
?>
