<h2>Categories</h2>
<?php
App::uses('Category', 'Model');
$categoryModel = new Category;
$categories = $categoryModel->admin_getCategories();

if (!empty($categories)) {
	?>
	<div class="floatRight"><?php echo $this->Html->Link('+ Add Products', '/admin/products/add'); ?></div>
	<div class='clear'></div>
	<br>
	<div id='adminCategoryNavigation'>
		<ul>
			<?php
			foreach ($categories as $row) {
				$categoryID = $row['Category']['id'];
				$categoryName = $row['Category']['name'];
				$tmp = substr($categoryName, 0, 25);
				$categoryDisplayName = (strlen($categoryName) > 28) ? $tmp . '...' : $categoryName;
				$categoryNameSlug = Inflector::slug($categoryName, '-');
				?>
				<li>
					<div style="color:orange; float:left; margin-top:5px;">&raquo;
					</div><?php echo $this->Html->link($categoryDisplayName, '/admin/categories/showProducts/' . $categoryID . '/' . $categoryNameSlug, ['title' => $categoryName, 'class' => 'floatLeft', 'style' => 'width:180px;']); ?>
					<div class='floatRight' style='margin-top:5px;'>
						<?php echo $this->Html->link('Edit', '/admin/categories/edit/' . $categoryID, ['style' => 'color:orange;']); ?>
						|
						<?php echo $this->Html->link($this->Html->image('error.png', ['alt' => 'active', 'title' => 'Click to remove this category']), '/admin/categories/delete/' . $categoryID, ['escape' => false, 'style' => 'color:red;'], 'Deleting this category will delete all the category information and products associated with it. This action is irreversable. Are you sure you want to delete this category?'); ?>
					</div>
					<div class='clear'></div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
} else {
	echo '<p>No Category Found</p>';
}
?>
