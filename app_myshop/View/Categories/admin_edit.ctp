<div id="content">
	<section>
		<h2>Edit Category: <?php echo $categoryInfo['Category']['name']; ?></h2>
		<?php
		echo $this->Form->create();
		?>

		<div class="form-check form-switch my-3">
			<input type="hidden" name="data[Category][active]" value="0">
			<input
				type="checkbox"
				id="CategoryActive"
				name="data[Category][active]"
				value="1"
				class="form-check-input"
				<?php echo $this->data['Category']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="CategoryActive">Active</label>
		</div>
		<div class="mb-3">
			<label for="CategoryName" class="form-label">Category Name</label>
			<input
				type="text"
				id="CategoryName"
				name="data[Category][name]"
				value="<?php echo $this->data['Category']['name']; ?>"
				class="form-control form-control-sm"
				placeholder="Enter category name"
				minlength="2"
				required
			>
		</div>
		<div class="mt-4">
			<button class="btn btn-sm btn-primary" type="submit">Update</button>
		</div>

		<?php
		echo $this->Form->end();
		?>

		<div class='note mt-4'>Note*: Only alphanumeric characters are accepted. Special characters will be removed.</div>
	</section>
</div>
