<p><?php echo $this->Html->link('Show all categories', array('controller'=>'product_categories', 'action'=>'index'), array('title'=>'Change to a different category'));?></p>
<h1>Category: <?php echo $this->Html->link($productCategoryInfo['ProductCategory']['name'], array('controller'=>'product_categories', 'action'=>'index', $productCategoryInfo['ProductCategory']['id']), array('title'=>'Show all products in '.$productCategoryInfo['ProductCategory']['name']));?></h1>

<div id="AddProductDiv" class="well">
	<?php
	echo $this->Form->create();
	?>
	<div id="paramsDiv">
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('name', array('placeholder'=>'Enter Product Name', 'label'=>'Product Name', 'required'=>true, 'style'=>'width:200px;', 'pattern'=>'[a-zA-Z0-9\s\-\/\&]{1,100}', 'title'=>'eg: Royal Challenge - Q, Kingfisher - P, etc.'));?>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('product_code', array('placeholder'=>'Enter Product Code or Barcode', 'label'=>'Product Code / Barcode', 'required'=>false, 'style'=>'width:200px;', 'pattern'=>'[a-zA-Z0-9]{0,55}', 'title'=>'Should be Alphanumeric values. eg: PR000098, C02345, BER006, etc.'));?>
		</div>
		<div style="float:left; clear:none;">
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
			<?php echo $this->Form->input('unit_selling_price', array('placeholder'=>'Unit Price', 'label'=>'Unit Selling Price (MRP)', 'required'=>false, 'pattern'=>'[0-9]+(\.[0-9][0-9]?)?', 'title'=>'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc'));?>
		</div>
		<div style="float:left; clear:none;" class="hidden">
			<?php echo $this->Form->input('special_margin', array('placeholder'=>'Special Margin Per Unit', 'label'=>'Special Margin Per Unit', 'required'=>false, 'pattern'=>'[0-9]+(\.[0-9][0-9]?)?', 'title'=>'Should be a whole number or a decimal number. eg: 100, 1000.00, 1000.50 etc'));?>
		</div>

		<div style="clear:both; float:left; margin-right:20px; margin-left:5px;">
			<button
					type="button"
					class="btn btn-sm btn-primary btn-block"
					onclick = "document.getElementById('ProductAddForm').submit()"
			>Create Product</button>
		</div>
	</div>
	<div style="clear: both; padding:0; margin:0;"></div>
	<?php
	echo $this->Form->end();
	?>
	<br><br>
	<div class="" aria-label="Add Brand">
		&nbsp;&nbsp;<?php echo $this->Html->link('+ Add New Brand', array('controller'=>'brands', 'action'=>'add'), array('class'=>'btn btn-default btn-sm'));?>
	</div>
</div>

<br>
<h2>Recently added products in '<?php echo $productCategoryInfo['ProductCategory']['name'];?>' category</h2>
<?php if($products) { ?>
<table class="table">
	<thead>
		<tr>
			<th>S.No</th>
			<th>Brand</th>
			<th>Product Name</th>
			<th>Units/Box</th>
			<th>Box Buying Price</th>
			<th>Unit Selling Price</th>
			<th>Created on</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		foreach($products as $row) {
			$i++;
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $row['Brand']['name'];?></td>
			<td><?php echo $row['Product']['name'] . ($row['Product']['product_code'] ? ' ['.$row['Product']['product_code'].']' : '');?></td>
			<td><?php echo $row['Product']['box_qty'];?></td>
			<td><?php echo $row['Product']['box_buying_price'];?></td>
			<td><?php echo $row['Product']['unit_selling_price'];?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Product']['created']));?></td>
			<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', array('controller'=>'products', 'action'=>'edit', $productCategoryInfo['ProductCategory']['id'], $row['Product']['id']), array('title'=>'Edit Product - '.$row['Product']['name'], 'escape'=>false, 'class'=>'btn btn-warning btn-xs'));?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php } else { ?>
<p>No products found.</p>
<?php } ?>
