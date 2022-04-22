<style type="text/css">
	.checkbox label {
		padding-left: 5px;
	}
</style>


<div id="adminAddContent">
	<section>
		<h2 class='floatLeft'>Add New Product</h2>
		<div
			class="floatRight"><?php echo $this->Html->Link('+ Add New Category', '/admin/categories/add', ['title' => 'Add new category']); ?></div>
		<div class='clear'></div>
		<br>
		<?php
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->admin_getCategoryList();
		if (!empty($categories)) {
			$categoryOptions = [];
			foreach ($categories as $categoryID => $categoryName) {
				$categoryOptions[$categoryID] = ucwords($categoryName);
			}
			asort($categoryOptions);

			echo $this->Form->create();
			echo '<br><strong>Product Name</strong>';
			echo $this->Form->input('Product.name', ['label' => false, 'title' => 'Add new product', 'style' => 'width:300px; margin-right:20px;']);
			echo "<div class='note'>Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>";
			echo '<br><strong>Select Category</strong>';
			echo $this->Form->input('Category.id', ['label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $categoryOptions, 'title' => 'Select Category', 'style' => '', 'div' => false]);
			echo '<br>';
			echo $this->Form->submit('Submit &raquo;', ['class' => 'floatLeft', 'escape' => false]);
			echo $this->Form->end();
			?>
			<div class='clear'>&nbsp;</div>

			<?php
		} else {
			?>
			You need to create a category before you add any product. click <?php echo $this->Html->Link('here', '/admin/categories/add', ['title' => 'Add new category']); ?> to create a <?php echo $this->Html->Link('new category', '/admin/categories/add', ['title' => 'Add new category']); ?>.
			<?php
		}
		?>

	</section>
</div>
