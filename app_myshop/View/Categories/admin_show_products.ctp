<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/admin/categories/">Categories</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?php echo $categoryInfo['Category']['name']; ?></li>
	</ol>
</nav>

<h1>Products List - <?php echo trim($categoryInfo['Category']['name']); ?></h1>

<div class="mt-4">
	<?= $this->element('products_quota_widget') ?>
</div>


<div class="text-end mt-3">
	<a href="/admin/products/sort/<?php echo $categoryInfo['Category']['id'];?>" class="btn btn-sm btn-outline-secondary">Sort Products</a>

	<?php if (!$productsLimitExceeded): ?>
	<a href='/admin/products/add/<?php echo $categoryInfo['Category']['id'];?>' class="btn btn-primary btn-sm ms-2">+ Add Product</a>
	<?php endif; ?>
</div>


<div class="table-responsive mt-3">
	<?php
	if (!empty($categoryProducts)) {

		$i = 0;
		$categoryID = $categoryInfo['Category']['id'];
		?>
		<table class="table small">
			<thead>
			<tr>
				<th>#</th>
				<th>Product Name</th>
				<th></th>
			</tr>
			</thead>
			<tbody>

				<?php
				$k = 0;
				foreach ($productsList as $productID => $productName) {
					$k++;
					if (!$productID) {
						continue;
					}
					$i++;
					$productActive = $categoryProducts[$productID]['Product']['active'];
					$productFeatured = $categoryProducts[$productID]['Product']['featured'];
					?>
						<tr>
							<td><?= $k ?>.</td>
							<td>
								<span class="me-1">
									<?php
									if ($productActive) {
										$title = "Set Inactive - $productName";
										$url = '/admin/products/setInactive/' . $productID;
										$confirmMessage = 'Are you sure you want to deactivate this product? Deactivating will hide this product from public.';
										?>
										<i class="fa fa-circle text-success"></i>
										<!-- <span
										class="small fa fa-circle text-success"
										onclick="showConfirmPopup('<?php echo $url;?>//', '<?php echo $title;?>//', '<?php echo $confirmMessage;?>//')"></span> -->
										<?php
									} else {
										$title = "Set Active - $productName";
										$url = '/admin/products/setActive/' . $productID;
										$confirmMessage = 'Are you sure you want to activate this product? Activating will make this product available to public.';
										?>
										<i class="fa fa-circle text-danger"></i>
										<!-- <span
										class="small fa fa-circle text-danger"
										onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')"></span> -->
										<?php
									}
									?>
								</span>

								<?php echo $this->Html->link($productName, '/admin/products/edit/' . $productID . '/' . $categoryID, ['title' => $productName]); ?>
							</td>
							<td class="text-end text-nowrap">
								<a href="/admin/products/edit/<?= $productID ?>/<?= $categoryID ?>" class="btn btn-primary btn-sm">Edit</a>

								<?php
								$confirmMessage = 'Are you sure you want to delete this product?';
								$url = '/admin/products/deleteProduct/' . $productID . '/' . $categoryID;
								$title = 'Delete - '. $productName;
								?>
								<span
									class="ms-2 btn btn-outline-danger btn-sm"
									onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')">Delete</span>
							</td>
						</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php
	} else {
		echo "No products found.";
	}
	?>
</div>
<br><br>
