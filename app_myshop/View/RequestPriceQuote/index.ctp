<?php
echo $this->Html->meta('keywords', 'Request price quote, Get price quotation, plants quotation', ['inline' => false]);
echo $this->Html->meta('description', 'Get price quotation for plants.', ['inline' => false]);
?>

<?php echo $this->element('message'); ?>
<?php
if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	$totalItems = count($shoppingCart['ShoppingCartProduct']);
	?>
	<h1>Request Price Quote</h1>
	<div>
		<?php echo $this->Form->create(null, ['encoding' => false]); ?>
		<table style='max-width:500px; font-size:13px;'>
			<tr>
				<td width='110'>Name *</td>
				<td><?php echo $this->Form->input('ShoppingCart.name', ['label' => false, 'type' => 'text', 'div' => false, 'required' => true, 'placeholder' => 'Enter Full Name..', 'style' => 'width:100%', 'title' => 'Enter Full Name']); ?></td>
			</tr>
			<tr>
				<td>Phone *</td>
				<td><?php echo $this->Form->input('ShoppingCart.phone', ['label' => false, 'type' => 'text', 'div' => false, 'required' => true, 'placeholder' => 'Enter Phone Number..', 'style' => 'width:100%', 'title' => 'Enter Phone Number']); ?></td>
			</tr>
			<tr>
				<td>Email Address *</td>
				<td><?php echo $this->Form->input('ShoppingCart.email', ['label' => false, 'type' => 'email', 'div' => false, 'required' => true, 'placeholder' => 'Enter Email Address..', 'style' => 'width:100%', 'title' => 'Enter Email Address']); ?></td>
			</tr>

			<tr>
				<td valign='top'>Address *</td>
				<td><?php echo $this->Form->input('ShoppingCart.address', ['label' => false, 'div' => false, 'type' => 'textarea', 'rows' => '1', 'required' => true, 'placeholder' => 'Your message  goes here..', 'title' => 'Enter your delivery address', 'style' => 'width:100%']); ?></td>
			</tr>
			<tr>
				<td valign='top'>Message</td>
				<td><?php echo $this->Form->input('ShoppingCart.message', ['label' => false, 'div' => false, 'type' => 'textarea', 'rows' => '1', 'required' => false, 'placeholder' => 'Your message  goes here..', 'title' => 'Your message  goes here..', 'style' => 'width:100%']); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<br>
					<?php
					echo $this->Form->submit('Submit &raquo;', ['escape' => false, 'div' => false, 'class' => 'button small green', 'title' => 'Submit']);
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo $this->Html->link('Cancel', '/', ['escape' => false, 'class' => '']);
					?>
				</td>
			</tr>
		</table>
		<?php echo $this->Form->end(); ?>
	</div>

	<br><br>

	<h2><?php echo 'My Shopping List - ' . $totalItems . ' item(s)'; ?></h2>
	<div>
		<table class='table' style="width:auto; font-size:13px;">
			<thead>
			<tr>
				<th style="width:10px;"></th>
				<th>Product Name</th>
				<th style="max-width:200px;">Category Name</th>
				<th style="max-width:100px;">Size</th>
				<th style="max-width:100px;">Age</th>
				<th style="max-width:100px;">Qty</th>

			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
				$i++;
				$shoppingCartProductID = $row['id'];

				$categoryID = $row['category_id'];
				$categoryName = ucwords($row['category_name']);
				$categoryNameSlug = Inflector::slug($categoryName, '-');

				$productID = $row['product_id'];
				$productName = ucwords($row['product_name']);
				$productNameSlug = Inflector::slug($productName, '-');

				$age = ($row['age']) ? $row['age'] : '-';
				$size = ($row['size']) ? $row['size'] : '-';
				$qty = ($row['quantity']) ? $row['quantity'] : '-';

				?>
				<tr>
					<td style="text-align:center;"><?php echo $i; ?>.</td>
					<td><?php echo $this->Html->link($productName, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, ['style' => ' padding:0px;', 'title' => $categoryNameSlug . ' &raquo; ' . $productNameSlug, 'escape' => false]); ?></td>
					<td><?php echo $this->Html->link($categoryName, '/products/show/' . $categoryID . '/' . $categoryNameSlug, ['style' => ' padding:0px;', 'title' => $categoryNameSlug, 'escape' => false]); ?></td>
					<td style="text-align:center;"><?php echo $size; ?></td>
					<td style="text-align:center;"><?php echo $age; ?></td>
					<td style="text-align:center;"><?php echo $qty; ?></td>
					<!--
					<td style="text-align:center;"><?php //echo $this->Html->link('Delete', '/ShoppingCarts/deleteShoppingCartProduct/'.$shoppingCartProductID, array('style'=>'width:30px; color:red; padding:0px;', 'title'=>'Delete: '.$categoryNameSlug.' &raquo; '.$productNameSlug, 'escape'=>false), 'Are you sure you want to delete this product. '.$categoryName.' &raquo; '.$productName.', size: '.$size.', age: '.$age.', quantity: '.$qty);?></td>
					-->
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>

	</div>

	<?php
} else {
	echo '<h2>My Shopping List</h2><br>';
	echo ' - No items in your shopping list. You need to add a product before you request for a price quote.';
}
?>
