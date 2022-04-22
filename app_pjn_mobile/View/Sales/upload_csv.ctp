<p><?php echo $this->Html->link('Back', ['controller' => 'sales', 'action' => 'viewClosingStock'], ['title' => 'Show Closing Stock']); ?></p>

<?php if (isset($updateResponse)) { ?>
	<div class="">
		<h2>File Upload Summary</h2>
		<p>
			Total "<?php echo $updateResponse['info']['totalRecords']; ?>" records found for updation.<br>
			Successfull = "<?php echo $updateResponse['info']['savedRecords']; ?>" record(s)<br>
			Failed = "<?php echo $updateResponse['info']['failedRecords']; ?>" record(s)
		</p>
		<br>
	</div>
<?php } ?>


<h1>Upload CSV File - Closing Stock</h1>

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
	<p>Category Name, Product Name, Closing Stock, Closing Date</p>
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
			<td>Category Name</td>
			<td>Only Alpha Numeric Characters</td>
			<td>Soft Drinks, Water Bottles 2Ltrs,.. etc.</td>
		</tr>
		<tr>
			<td>Product Name</td>
			<td>Only Alpha Numeric Characters</td>
			<td>CocaCola, Pepsi, Bisleri, Kinlay 500ml,.. etc.</td>
		</tr>
		<tr>
			<td>Closing Quantity</td>
			<td>Only Numeric values</td>
			<td>0, 10, 50, 75,.. etc.</td>
		</tr>
		<tr>
			<td>Closing Date</td>
			<td>Date should be in 'dd-mm-yyyy' format</td>
			<td>05-12-2013, 30-01-2013, ...etc.</td>
		</tr>
		</tbody>
	</table>
</div>
