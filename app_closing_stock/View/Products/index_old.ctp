<h1>Manage Category Products</h1><br>

<style type="text/css">
	form {
		margin-right: 0px;
		width: 100%;
	}

	form div {
		padding: 0px;
	}
</style>
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
					<th style="text-align:center; width:200px;">Actions</th>
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
							<?php echo $this->Html->link('Add Product', ['controller' => 'products', 'action' => 'add', $row['ProductCategory']['id']], ['title' => $row['ProductCategory']['name'] . ' - Add Product', 'escape' => false, 'class' => 'btn btn-default btn-sm']); ?>
							&nbsp;
							<?php echo $this->Html->link('<span class="fa fa-pencil" aria-hidden="true"></span>', ['controller' => 'product_categories', 'action' => 'edit', $row['ProductCategory']['id']], ['title' => 'Edit Category - ' . $row['ProductCategory']['name'], 'escape' => false, 'class' => 'btn btn-warning btn-sm']); ?>

							<?php // echo $this->Html->link('Remove from list', array('controller'=>'product_categories', 'action'=>'remove', $row['ProductCategory']['id']), array('title'=>'Remove Category from list - '.$row['ProductCategory']['name']), 'Category - '.$row['ProductCategory']['name'].'\nProducts associated with the category, Sales and Purchase records will not be removed.\n\nAre you sure you want to remove this category from the list?');?>
							&nbsp;
							<?php echo $this->Html->link('<span class="fa fa-trash-can" aria-hidden="true"></span>', ['controller' => 'product_categories', 'action' => 'delete', $row['ProductCategory']['id']], ['title' => 'Delete Category - ' . $row['ProductCategory']['name'], 'escape' => false, 'class' => 'btn btn-danger btn-sm'], ' Category - ' . $row['ProductCategory']['name'] . "\n Deleting this category will remove all the products associated with it.\n All Sales & Purchase records will be deleted.\n\n Are you sure you want to delete this category?"); ?>
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
		<!-- <p><?php echo $this->Html->link('Show all categories', ['controller' => 'product_categories', 'action' => 'index'], ['title' => 'Show all categories']); ?></p> -->

		<h1>Product Category: <?php echo $productCategoryInfo['ProductCategory']['name']; ?></h1><br>
		<p><?php echo $this->Html->link('+ Add Product in ' . $productCategoryInfo['ProductCategory']['name'], ['controller' => 'products', 'action' => 'add', $productCategoryInfo['ProductCategory']['id']], ['title' => 'Add Products in ' . $productCategoryInfo['ProductCategory']['name'] . ' category', 'class' => 'btn btn-purple btn-sm']); ?></p>
		<br>
		<h2>Products list</h2>
		<?php if ($products) { ?>
			<table class='table'>
				<thead>
				<tr>
					<th>S.No</th>
					<th>Brand</th>
					<th>Product Name</th>
					<th>Units/Box</th>
					<th>Box Buying Price</th>
					<th>Unit Selling Price</th>
					<th>Spl. Margin</th>
					<th>Created on</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($products as $row) {
					$i++;
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $row['Brand']['name']; ?></td>
						<td><?php echo $row['Product']['name']; ?></td>
						<td><?php echo $row['Product']['box_qty']; ?></td>
						<td><?php echo $row['Product']['box_buying_price']; ?></td>
						<td><?php echo $row['Product']['unit_selling_price']; ?></td>
						<td><?php echo ($row['Product']['special_margin'] > 0) ? $row['Product']['special_margin'] : ''; ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['Product']['created'])); ?></td>
						<td><?php echo $this->Html->link('Edit', ['controller' => 'products', 'action' => 'edit', $productCategoryInfo['ProductCategory']['id'], $row['Product']['id']], ['title' => 'Edit Product - ' . $row['Product']['name'], 'class' => 'small button link yellow']); ?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		<?php } else { ?>
			<p>No products found.</p>
		<?php } ?>
	</div>
</div>
