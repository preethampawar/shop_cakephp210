<p><?php echo $this->Html->link('Show all categories', ['controller' => 'product_categories', 'action' => 'index'], ['title' => 'Change to a different category']); ?></p>
<h1>Edit
	Product: <?php echo $this->Html->link($productCategoryInfo['ProductCategory']['name'], ['controller' => 'products', 'action' => 'index', $productCategoryInfo['ProductCategory']['id']], ['title' => 'Show all products in ' . $productCategoryInfo['ProductCategory']['name'] . ' category']); ?>
	&raquo; <?php echo $productCategoryInfo['Product']['name']; ?></h1>

<div id="EditProductDiv">
	<?php
	echo $this->Form->create();
	?>
	<div id="paramsDiv">
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('name', ['placeholder' => 'Enter Product Name', 'label' => 'Product Name', 'required' => true, 'pattern' => '[a-zA-Z0-9\s\-]{1,55}', 'title' => 'eg: Royal Challenge - Q, Kingfisher - P, etc.']); ?>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('product_code', ['placeholder' => 'Enter Product Code', 'label' => 'Product Code', 'required' => false, 'pattern' => '[a-zA-Z0-9]{0,55}', 'title' => 'Should be Alphanumeric values. eg: PR000098, C02345, BER006, etc.']); ?>
		</div>
		<div style="float:left;">
			<?php echo $this->Form->input('box_qty', ['placeholder' => 'Units in Box', 'label' => 'No. of units per Box', 'list' => 'boxqty', 'pattern' => '[1-9][0-9]{0,3}', 'title' => 'Values 1 to 999 are allowed']); ?>
			<datalist id='boxqty'>
				<option value='12'>
				<option value='24'>
				<option value='48'>
				<option value='96'>
			</datalist>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('box_buying_price', ['placeholder' => 'Box Price', 'label' => 'Box Buying Price', 'required' => false, 'pattern' => '[0-9]+(\.[0-9][0-9]?)?', 'title' => 'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc']); ?>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('unit_selling_price', ['placeholder' => 'Unit Price', 'label' => 'Unit Selling Price', 'required' => false, 'pattern' => '[0-9]+(\.[0-9][0-9]?)?', 'title' => 'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc']); ?>
		</div>
		<div style="clear:both; clear:none;">

		</div>
	</div>
	<?php
	echo $this->Form->submit('Create Product');
	echo $this->Form->end();
	?>
</div>
