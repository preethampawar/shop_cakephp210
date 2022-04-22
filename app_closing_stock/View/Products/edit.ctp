<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="/product_categories/index/<?= $productCategoryInfo['ProductCategory']['id'] ?>">
				<?= $productCategoryInfo['ProductCategory']['name']; ?>
			</a>
		</li>
		<li class="breadcrumb-item active" aria-current="page"><?= $productCategoryInfo['Product']['name'] ?></li>
	</ol>
</nav>

<h1><?= $productCategoryInfo['Product']['name'] ?></h1><br>


<div id="EditProductDiv" class="well">
	<?php
	echo $this->Form->create();
	?>
	<div id="paramsDiv">
		<div class="mb-3">
			<label for="ProductName" class="form-label">Product Name</label>
			<?php
				echo $this->Form->input('name', [
						'placeholder' => 'Enter Product Name',
						'label' => false,
						'required' => true,
						'pattern' => '[a-zA-Z0-9\s\-\/\&]{1,100}',
						'title' => 'eg: Royal Challenge - Q, Kingfisher - P, etc.',
						'class' => 'form-control'
					]
				);
			?>
		</div>

		<div class="mb-3">
			<label for="ProductBrandId" class="form-label">Brand</label>
			<?php
			echo $this->Form->input('brand_id', [
				'label' => false,
				'type' => 'select',
				'options' => $brands,
				'escape' => false,
				'class' => 'form-control',
				'empty' => ' -- Select Brand -- '
			]);
			?>
		</div>


		<div class="mb-3">
			<label for="ProductBoxQty" class="form-label">Quantity Per Box</label>
			<?php
			echo $this->Form->input('box_qty', [
				'placeholder' => 'Enter units in box',
				'label' => false,
				'list' => 'boxqty',
				'pattern' => '[1-9][0-9]{0,3}',
				'title' => 'Values 1 to 999 are allowed',
				'class' => 'form-control',
			]);
			?>
			<datalist id='boxqty'>
				<option value='12'>
				<option value='24'>
				<option value='48'>
				<option value='96'>
			</datalist>
		</div>

		<div class="mb-3">
			<label for="ProductBoxBuyingPrice" class="form-label">Box Purchase Price</label>
			<?php
			echo $this->Form->input('box_buying_price', [
				'placeholder' => 'Box Price',
				'label' => false,
				'required' => false,
				'pattern' => '[0-9]+(\.[0-9][0-9]?)?',
				'title' => 'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc',
				'class' => 'form-control',
			]);
			?>
		</div>


		<div class="mb-3">
			<label for="ProductUnitSellingPrice" class="form-label">Unit Selling Price</label>
			<?php
			echo $this->Form->input('unit_selling_price', [
				'placeholder' => 'Unit Price',
				'label' => false,
				'required' => false,
				'pattern' => '[0-9]+(\.[0-9][0-9]?)?',
				'title' => 'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc',
				'class' => 'form-control',
			]);
			?>
		</div>

		<div class="mb-3">
			<button type="submit" class="btn btn-purple btn-md form-control mt-3">Update</button>
		</div>
		<div class="text-center mt-4">
			<a href="/product_categories/index/<?php echo $productCategoryInfo['ProductCategory']['id']; ?>"
			   class="btn btn-sm btn-outline-danger">Cancel</a>
		</div>
	</div>
	<div style="clear:both;"></div>

	<?php
	echo $this->Form->end();
	?>
</div>
