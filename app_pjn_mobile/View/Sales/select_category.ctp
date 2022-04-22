
<a href="/invoices/edit/<?php echo $invoiceInfo['Invoice']['id']; ?>" class="btn btn-sm btn-purple"> &laquo; Edit Invoice</a>
<a href="/invoices/" class="btn btn-sm btn-secondary ml-3">Go to Invoice List</a>

<div class="mt-3">
	<h1>
		<?php
		echo $invoiceInfo['Invoice']['invoice_type'] == 'purchase' ? 'Purchase Invoice' : 'Sale Invoice';
		echo ' - '. $invoiceInfo['Invoice']['name'];
		?>
	</h1>
</div>
<hr>
<h4>Add Product - Step1</h4>

<script>
	function getCategoryProducts() {
		let categoryId = $('#SaleCategoryId').val();
		window.location = '/sales/addProduct/<?= $invoiceInfo['Invoice']['id'] ?>/' + categoryId;
	}
</script>

<div>
		<div class="mt-3">
		<?php
			echo $this->Form->create();
			echo $this->Form->input('category_id', [
				'id' => 'SaleCategoryId',
				'empty' => false,
				'label' => 'Select Category',
				'required' => true,
				'type' => 'select',
				'options' => $categoriesList,
				'class' => 'autoSuggest form-control form-control-md p-3',
			]);
			echo $this->Form->end();
		?>
		</div>


	<div class="text-center mt-3">
		<button class="btn btn-purple btn-sm mt-3" onclick="getCategoryProducts()">Next</button>

		<a href="/invoices/" class="btn btn-sm btn-danger ml-3 mt-3" role="button">Cancel</a>
	</div>

</div>


