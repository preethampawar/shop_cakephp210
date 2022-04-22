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
				<th>BestDeal</th>
				<th>ShowInCart</th>
				<th>Group</th>
				<th>Base Price</th>
				<th>Relative Price</th>
				<th>MRP</th>
				<th>Discount</th>
				<th>SalePrice</th>
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
					$productShowInCart = $categoryProducts[$productID]['Product']['show_in_cart'];
					$mrp = (float)$categoryProducts[$productID]['Product']['mrp'];
					$discount = (float)$categoryProducts[$productID]['Product']['discount'];
					$salePrice = $mrp - $discount;
					$productRelativePrice = $categoryProducts[$productID]['Product']['relative_base_price'];
					$allowRelativePriceUpdate = (float)$categoryProducts[$productID]['Product']['allow_relative_price_update'];
					$productRelativePriceRelation = $categoryProducts[$productID]['Product']['relative_price_relation'];
					$productGroupId = $categoryProducts[$productID]['Product']['group_id'];
					$baseRate = (float)($groupRates[$productGroupId] ?? 0);
					$groupName = $groups[$productGroupId] ?? '';
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

										<span
										class="bi bi-circle-fill text-success"
										onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')"></span>
										<?php
									} else {
										$title = "Set Active - $productName";
										$url = '/admin/products/setActive/' . $productID;
										$confirmMessage = 'Are you sure you want to activate this product? Activating will make this product available to public.';
										?>

										<span
										class="bi bi-circle-fill text-danger"
										onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')"></span>
										<?php
									}
									?>
								</span>

								<?php echo $this->Html->link($productName, '/admin/products/edit/' . $productID . '/' . $categoryID, ['title' => $productName]); ?>
							</td>
							<td>
								<span class="me-1">
									<?php
									if ($productFeatured) {
										$title = "Click to remove from Best deal - $productName";
										$url = '/admin/products/unsetFeatured/' . $productID;
										?>
										<a href="<?php echo $url;?>" class="bi bi-circle-fill text-success" title="<?= $title ?>"></a>
										<?php
									} else {
										$title = "Set as Best deal - $productName";
										$url = '/admin/products/setFeatured/' . $productID;
										?>
										<a href="<?php echo $url;?>" class="bi bi-circle-fill text-danger" title="<?= $title ?>"></a>
										<?php
									}
									?>
								</span>
							</td>
							<td>
								<span class="me-1">
									<?php
									if ($productShowInCart) {
										$title = "Remove from Best deal - $productName";
										$url = '/admin/products/toggleShowInCart/' . $productID . '/0';
										?>
										<a href="<?php echo $url;?>" class="bi bi-circle-fill text-success" title="<?= $title ?>"></a>
										<?php
									} else {
										$title = "Show in Best deal - $productName";
										$url = '/admin/products/toggleShowInCart/' . $productID . '/1';
										?>
										<a href="<?php echo $url;?>" class="bi bi-circle-fill text-danger" title="<?= $title ?>"></a>
										<?php
									}
									?>
								</span>
							</td>
							<td class="text-muted">
								<?php
								if ($productGroupId) {
								?>
									<a href="/admin/groups/products/<?= $productGroupId ?>"><?= $groupName ?></a>
								<?php
								} else {
									?>
									<a href="/admin/groups/products/">+Assign</a>
									<?php
								}
								?>
							</td>
							<td class="text-muted"><?= $baseRate ?: '' ?></td>
							<td class="text-muted"><?= $allowRelativePriceUpdate && $productGroupId ? $productRelativePriceRelation.$productRelativePrice : '' ?></td>
							<td class="text-muted"><?= $mrp ?></td>
							<td class="text-muted"><?= $discount ?></td>
							<td class="text-dark"><?= $salePrice ?></td>
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
