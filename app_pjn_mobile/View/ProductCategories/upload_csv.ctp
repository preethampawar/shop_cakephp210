<p><?php echo $this->Html->link('Back', ['controller' => 'product_categories', 'action' => 'index'], ['title' => 'Show all Categories']); ?></p>

<h1>Import products from CSV file</h1>

<?php
if (!$beforeUpload) {
	?>
	<p>
	<div class="<?php echo $response['error'] ? 'text-danger' : 'text-success'; ?>">
		Updated Records: <?php echo $response['updatedRecords'] ?? 0; ?>
		<?= isset($response['errorMsg']) && !empty($response['errorMsg']) ? $response['errorMsg'] : null ?>
	</div>
	</p>
	<?php
}
?>

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
	<h3>Import Product CSV File Format</h3>
	<p>Before you upload CSV file, you need to have the following information.<br>Category Name, Brand Name, Product
		Name, Box Buying Price, Quantity in Box, Unit Selling Price</p>
	<table class='table'>
		<thead>
		<tr>
			<th>Order</th>
			<th>Column</th>
			<th>Data Type</th>
			<th>Example</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>Column 1</td>
			<td>CategoryName</td>
			<td>Only Alpha Numeric Characters</td>
			<td>Mobiles, Computers, Televisions,.. etc.</td>
		</tr>
		<tr>
			<td>Column 2</td>
			<td>BrandName</td>
			<td>Only Alpha Numeric Characters</td>
			<td>Apple, Samsung, Nokia,.. etc.</td>
		</tr>
		<tr>
			<td>Column 3</td>
			<td>ProductName</td>
			<td>Only Alpha Numeric Characters</td>
			<td>iPhone SE, Samsung Galaxy Note 4, Macbook Pro Laptop,.. etc.</td>
		</tr>
		<tr>
			<td>Column 4</td>
			<td>BoxPrice</td>
			<td>Only Numeric/Decimal values</td>
			<td>550, 550.00, 550.50,.. etc.</td>
		</tr>
		<tr>
			<td>Column 5</td>
			<td>BoxQuantity</td>
			<td>Only Numeric values</td>
			<td>10, 50, 75,.. etc.</td>
		</tr>
		<tr>
			<td>Column 6</td>
			<td>UnitPrice</td>
			<td>Only Numeric/Decimal values</td>
			<td>50, 50.00, 50.50,.. etc.</td>
		</tr>
		</tbody>
	</table>
</div>
