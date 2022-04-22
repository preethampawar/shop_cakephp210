<?php
App::uses('Category', 'Model');
$categoryModel = new Category;
$categories = $categoryModel->getCategories($this->Session->read('Site.id'));
?>
<li class="nav-item navbar-side-border-bottom px-0">
	<h5 class="pr-2 mt-3">Select Category</h5>
</li>

<?php
if (!empty($categories)) {
	foreach ($categories as $row) {
		$categoryID = $row['Category']['id'];
		$categoryName = Inflector::humanize($row['Category']['name']);
		$categoryNameSlug = Inflector::slug($row['Category']['name'], '-');
		?>
		<li class="nav-item">
			<a
				class="nav-link"
				href="/products/show/<?php echo $categoryID; ?>/<?php echo $categoryNameSlug; ?>"
				title="<?php echo $categoryName; ?>">
				<?php echo $categoryName; ?>
			</a>
		</li>
		<?php
	}
	?>
	<li class="nav-item navbar-side-border-top"><a class="nav-link" href="/products/showAll" title="Show all products">
			Show All Products</a></li>
	<?php
} else {
	?>
	<li class="nav-item navbar-side-border-top">
		No categories found
	</li>
	<?php
}
?>
