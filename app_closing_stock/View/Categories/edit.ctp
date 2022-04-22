<p class="text-end"><a href="/categories/" class="btn btn-warning btn-sm">Cancel</a></p>
<h1>Edit Category</h1>

<div id="AddCategoryDiv">
	<?php echo $this->Form->create(); ?>
	<?php echo $this->Form->input('active', ['type' => 'checkbox', 'label' => 'Active', 'class' => 'my-3']); ?>
	<?php echo $this->Form->input('name', ['placeholder' => 'Enter Category Name', 'label' => 'Category Name', 'required' => true, 'class' => 'form-control form-control-sm']); ?>
	<?php echo $this->Form->submit('Update', ['class' => 'btn btn-primary btn-sm mt-3']); ?>
	<?php echo $this->Form->end(); ?>
</div>
