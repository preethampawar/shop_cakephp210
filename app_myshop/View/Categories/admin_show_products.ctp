<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/admin/categories/">Categories</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?php echo $categoryInfo['Category']['name']; ?></li>
	</ol>
</nav>

<div class="mt-4">
	<?= $this->element('products_quota_widget') ?>
</div>

<div class="mt-4 mb-3 d-flex justify-content-between align-items-center">
	<h5><?php echo $categoryInfo['Category']['name']; ?></h5>

	<?php if (!$productsLimitExceeded): ?>
	<a href='/admin/products/add/<?php echo $categoryInfo['Category']['id'];?>' class="btn btn-info btn-sm">+ Add Product</a>
	<?php endif; ?>
</div>

<p>Below is the list of products in this category. </p>
<?php
if (!empty($categoryProducts)) {

	$i = 0;
	$categoryID = $categoryInfo['Category']['id'];

	foreach ($productsList as $productID => $productName) {
		if (!$productID) {
			continue;
		}
		$i++;
		$productActive = $categoryProducts[$productID]['Product']['active'];
		$productFeatured = $categoryProducts[$productID]['Product']['featured'];
		?>
		<li class="list-group-item d-flex justify-content-between align-items-center px-1">
			<div>
				<?php
				if ($productActive) {
					$title = "Set Inactive - $productName";
					$url = '/admin/products/setInactive/' . $productID;
					$confirmMessage = 'Are you sure you want to deactivate this product? Deactivating will hide this product from public.';
					?>
					<span class="small fa fa-circle text-success"></span>
					<!-- <span
						class="small fa fa-circle text-success"
						onclick="showConfirmPopup('<?php echo $url;?>//', '<?php echo $title;?>//', '<?php echo $confirmMessage;?>//')"></span> -->
					<?php
					// echo $this->Html->link("<span class='fa fa-toggle-on'></span>", '/admin/products/setInactive/' . $productID, ['escape' => false], 'Are you sure you want to deactivate this product? Deactivating will hide this product from public.');
				} else {
					$title = "Set Active - $productName";
					$url = '/admin/products/setActive/' . $productID;
					$confirmMessage = 'Are you sure you want to activate this product? Activating will make this product available to public.';
					?>
					<span class="small fa fa-circle text-danger"></span>
					<!-- <span
						class="small fa fa-circle text-danger"
						onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')"></span> -->
					<?php
					// echo $this->Html->link($this->Html->image('red_button.png', ['alt' => 'active', 'title' => 'Click to activate', 'height' => '12', 'width' => '12']), '/admin/products/setActive/' . $productID, ['escape' => false, 'style' => 'color:red; margin:2px;'], 'Are you sure you want to activate this product? Activating will make this product available to public.');
				}
				?>

				<?php echo $this->Html->link($productName, '/admin/products/edit/' . $productID . '/' . $categoryID, ['title' => $productName]); ?>
			</div>

			<div>
				<?php echo $this->App->getLinkButton('<span class="far fa-edit"></span>', '/admin/products/edit/' . $productID . '/' . $categoryID, 'edit'); ?>

				<?php
				$confirmMessage = 'Are you sure you want to delete this product?';
				$url = '/admin/products/deleteProduct/' . $productID . '/' . $categoryID;
				$title = 'Delete - '. $productName;
				?>
				<span
					class="far fa-trash-alt ml-2 text-danger"
					onclick="showConfirmPopup('<?php echo $url;?>', '<?php echo $title;?>', '<?php echo $confirmMessage;?>')"></span>
			</div>
		</li>



		<?php
	}
}
?>
<br><br>
