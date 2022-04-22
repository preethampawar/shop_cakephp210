<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts();

if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	$totalItems = count($shoppingCart['ShoppingCartProduct']);
	?>
	<h2 style="margin-bottom:5px;">My Shopping
		List <?php echo ($totalItems) ? ' - ' . $totalItems . ' item(s)' : ''; ?></h2>

	<div style="margin:0; padding:0;">
		<table style="width:100%; padding:0px; font-size:11px; margin-bottom:5px;" class='table' cellpadding='0'
			   cellspacing='0'>
			<thead>
			<tr>
				<th style="width:20px; padding:2px;">No.</th>
				<th style="padding:2px;">Product Name</th>
				<th style="width:20px; padding:2px;">Size</th>
				<th style="width:20px; padding:2px;">Age</th>
				<th style="width:20px; padding:2px;">Qty</th>
				<th style="width:20px; padding:2px;">&nbsp;</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($shoppingCart['ShoppingCartProduct'] as $row) {
				$i++;
				$shoppingCartProductID = $row['id'];

				$categoryID = $row['category_id'];
				$categoryName = $row['category_name'];
				$categoryNameSlug = Inflector::slug($categoryName, '-');

				$productID = $row['product_id'];
				$productName = $row['product_name'];
				$productNameSlug = Inflector::slug($productName, '-');

				$age = ($row['age']) ? $row['age'] : '-';
				$size = ($row['size']) ? $row['size'] : '-';
				$qty = ($row['quantity']) ? $row['quantity'] : '-';

				?>
				<tr>
					<td style="text-align:center; padding:2px;"><?php echo $i; ?>.</td>
					<td style="padding:2px;"><?php echo $this->Html->link($productName, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, ['style' => ' padding:0px;', 'title' => $categoryNameSlug . ' &raquo; ' . $productNameSlug, 'escape' => false]); ?></td>
					<td style="text-align:center; padding:2px;"><?php echo $size; ?></td>
					<td style="text-align:center; padding:2px;"><?php echo $age; ?></td>
					<td style="text-align:center; padding:2px;"><?php echo $qty; ?></td>
					<td style="text-align:center; padding:2px;">
						<?php
						$image = $this->Html->image('delete_icon.gif', ['alt' => 'Delete', 'title' => 'Delete "' . $productName . '" from shopping list']);
						echo $this->Html->link($image, '/ShoppingCarts/deleteShoppingCartProduct/' . $shoppingCartProductID, ['style' => 'width:30px; color:red; padding:0px;', 'title' => 'Delete: ' . $categoryNameSlug . ' &raquo; ' . $productNameSlug, 'escape' => false], 'Are you sure you want to delete this product. ' . $categoryName . ' &raquo; ' . $productName . ', size: ' . $size . ', age: ' . $age . ', quantity: ' . $qty); ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<div style="text-align:center;">
			<?php
			echo $this->Form->create(null, ['url' => '/RequestPriceQuote', 'method' => 'get', 'encoding' => false]);
			echo $this->Form->submit('Request Price Quote &raquo;', ['escape' => false, 'div' => false, 'style' => 'width:100%']);
			echo $this->Form->end();
			?>
		</div>
	</div>
	<br>

	<?php
}
?>
