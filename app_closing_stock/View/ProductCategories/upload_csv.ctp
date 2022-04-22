<p><?php echo $this->Html->link('Back', ['controller' => 'product_categories', 'action' => 'index'], ['title' => 'Show all Categories']); ?></p>

<?php if (isset($response)) { ?>
	<div class="">
		<h2>File Upload Summary</h2>
		<p>
			Total Categories Added/Updated = <?php echo $categoriesCount; ?><br>
			New Categories Added = <?php echo $categoriesAdded; ?><br>
			Old Categories Updated = <?php echo $categoriesUpdated; ?><br>
		</p>
		<p>
			Total Products Added/Updated = <?php echo $productsCount; ?><br>
			New Products Added = <?php echo $productsAdded; ?><br>
			Old Products Updated = <?php echo $productsUpdated; ?><br>
		</p>

	</div>
<?php } ?>


<h1>Upload CSV File</h1>

<div id="AddProductDiv">
	<?php
	echo $this->Form->create(null, ['type' => 'file']);
	echo $this->Form->input('csv', ['type' => 'file', 'label' => 'Select CSV file']);
	echo $this->Form->submit('Submit');
	echo $this->Form->end();
	?>
</div>
<br><br>
Note*
<div class="notice">
	<h3>CSV File Format</h3>
	<p>Category Name, Product Name, Box Buying Price, Quantity in Box, Unit Selling Price, Special Margin</p>
	<table class='table'>
		<thead>
		<tr>
			<th>Column</th>
			<th>Data Type</th>
			<th>Example</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>CategoryName</td>
			<td>Only Alpha Numeric Characters</td>
			<td>Beer, Whisky, Rum,.. etc.</td>
		</tr>
		<tr>
			<td>ProductName</td>
			<td>Only Alpha Numeric Characters</td>
			<td>Kingfisher, Royal Stag, Kinlay 500ml,.. etc.</td>
		</tr>
		<tr>
			<td>BoxPrice</td>
			<td>Only Numeric/Decimal values</td>
			<td>550, 550.00, 550.50,.. etc.</td>
		</tr>
		<tr>
			<td>BoxQuantity</td>
			<td>Only Numeric values</td>
			<td>10, 50, 75,.. etc.</td>
		</tr>
		<tr>
			<td>UnitPrice</td>
			<td>Only Numeric/Decimal values</td>
			<td>50, 50.00, 50.50,.. etc.</td>
		</tr>
		<tr>
			<td>SpecialMargin</td>
			<td>Only Numeric/Decimal values</td>
			<td>50, 50.00, 50.50,.. etc.</td>
		</tr>
		</tbody>
	</table>
</div>
