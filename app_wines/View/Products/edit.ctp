<h1>Edit Product</h1><br>
<?php echo $this->Html->link($productCategoryInfo['ProductCategory']['name'], array('controller'=>'product_categories', 'action'=>'index', $productCategoryInfo['ProductCategory']['id']), array('title'=>'Show all products in '.$productCategoryInfo['ProductCategory']['name'].' category'));?> &raquo; <?php echo $productCategoryInfo['Product']['name'];?>
<br>
<div id="EditProductDiv" class="well">
	<?php
	echo $this->Form->create();
	?>
	<div id="paramsDiv">
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('name', array('placeholder'=>'Enter Product Name', 'label'=>'Product Name', 'required'=>true, 'pattern'=>'[a-zA-Z0-9\s\-\/\&]{1,100}', 'title'=>'eg: Royal Challenge - Q, Kingfisher - P, etc.'));?>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('product_code', array('placeholder'=>'Enter Product Code', 'label'=>'Product Code', 'required'=>false, 'pattern'=>'[a-zA-Z0-9]{0,55}', 'title'=>'Should be Alphanumeric values. eg: PR000098, C02345, BER006, etc.'));?>
		</div>
		<div style="float:left; clear:none;">
			<?php //echo $this->Form->input('product_code', array('placeholder'=>'Enter Product Code', 'label'=>'Product Code', 'required'=>false, 'pattern'=>'[a-zA-Z0-9]{0,55}', 'title'=>'Should be Alphanumeric values. eg: PR000098, C02345, BER006, etc.'));?>
			<?php echo $this->Form->input('brand_id', array('label'=>'Brand', 'type'=>'select', 'options'=>$brands, 'escape'=>false, 'style'=>'width:200px;', 'class'=>'autoSuggest', 'empty'=>' -- Select Brand -- '));?>
		</div>
		<div style="float:left;">
			<?php echo $this->Form->input('box_qty', array('placeholder'=>'Units in Box', 'label'=>'No. of units per Box', 'list'=>'boxqty', 'pattern'=>'[1-9][0-9]{0,3}', 'title'=>'Values 1 to 999 are allowed'));?>
			<datalist id='boxqty'>
				<option value='12'>
				<option value='24'>
				<option value='48'>
				<option value='96'>
			</datalist>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('box_buying_price', array('placeholder'=>'Box Price', 'label'=>'Box Buying Price', 'required'=>false, 'pattern'=>'[0-9]+(\.[0-9][0-9]?)?', 'title'=>'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc'));?>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('unit_selling_price', array('placeholder'=>'Unit Price', 'label'=>'Unit Selling Price', 'required'=>false, 'pattern'=>'[0-9]+(\.[0-9][0-9]?)?', 'title'=>'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc'));?>
		</div>
		<div style="float:left; clear:none;" class="hidden">
			<?php echo $this->Form->input('special_margin', array('placeholder'=>'Special Margin', 'label'=>'Special Margin', 'required'=>false, 'pattern'=>'[0-9]+(\.[0-9][0-9]?)?', 'title'=>'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc'));?>
		</div>
		<div style="clear:both; float:left; margin-right:20px; margin-left:5px;">
			<button
				type="button"
				class="btn btn-sm btn-primary btn-block"
				onclick = "document.getElementById('ProductEditForm').submit()"
			>Update Product</button>
		</div>
		<div style="clear:none; float:left;">
			<a href="/product_categories/index/<?php echo $productCategoryInfo['ProductCategory']['id'];?>" class="btn btn-sm btn-warning">Cancel</a>
		</div>
	</div>
	<div style="clear:both;"></div>

	<?php
	echo $this->Form->end();
	?>
	<br>
	<div class="" aria-label="Add Brand">
		&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('+ Add New Brand', array('controller'=>'brands', 'action'=>'add'), array('class'=>'btn btn-default btn-sm'));?>
	</div>
</div>
