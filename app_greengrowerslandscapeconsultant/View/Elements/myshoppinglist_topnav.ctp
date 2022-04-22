<?php
App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts();

if(isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	$totalItems = count($shoppingCart['ShoppingCartProduct']);
?>
<li>
	<?php echo $this->Html->link('My Shopping List - '.$totalItems.' item(s)', '#');?>	
	<ul style="width:auto;">	
		<li style="margin-right:0px;">
			<div id="myShoppingListTopNavDiv">				
				<table style="width:auto;" class='table'>
					<thead>
						<tr>
							<th style="width:20px;">No.</th>							
							<th>Product Name</th>
							<th style="width:50px;">Size</th>
							<th style="width:70px;">Age</th>
							<th style="width:60px;">Qty</th>
							<th style="width:70px;">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i=0;
						foreach($shoppingCart['ShoppingCartProduct'] as $row) {
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
							$qty = ($row['quantity']) ? $row['quantity'] :'-';
						
						?>
						<tr>
							<td style="text-align:center;"><?php echo $i;?></td>
							<td><?php echo $this->Html->link($productName, '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('style'=>' padding:0px;', 'title'=>$categoryNameSlug.' &raquo; '.$productNameSlug, 'escape'=>false));?></td>
							<td style="text-align:center;"><?php echo $size;?></td>
							<td style="text-align:center;"><?php echo $age;?></td>
							<td style="text-align:center;"><?php echo $qty;?></td>
							<td style="text-align:center;"><?php echo $this->Html->link('Delete', '/ShoppingCarts/deleteShoppingCartProduct/'.$shoppingCartProductID, array('style'=>'width:30px; color:red; padding:0px;', 'title'=>'Delete: '.$categoryNameSlug.' &raquo; '.$productNameSlug, 'escape'=>false), 'Are you sure you want to delete this product. '.$categoryName.' &raquo; '.$productName.', size: '.$size.', age: '.$age.', quantity: '.$qty);?></td>
						</tr>
						<?php
						}
						?>						
					</tbody>
				</table>	
				<div style="text-align:center;">
					<?php
					echo $this->Form->create(null, array('url'=>'/RequestPriceQuote', 'method'=>'get', 'encoding'=>false));
					echo $this->Form->submit('Request Price Quote &raquo;', array('escape'=>false, 'div'=>false));
					echo $this->Form->end();					
					?>
					<br>
				</div>
			</div>			
		</li>
	</ul>
</li>	
<?php
}
else {
?>
<li>
	<a href="#" title="No items in cart">My Shopping List (0)</a>
	<ul>
		<li>
			<a href="#" title="No items in cart"> - No items in cart</a>
		</li>
	</ul>
</li>
<?php
}
?>	