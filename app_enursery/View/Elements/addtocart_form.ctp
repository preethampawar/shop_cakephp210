<h2><?php echo $productName;?></h2>
<br>
<?php echo $this->Form->create('ShoppingCart', array('url'=>'/shopping_carts/add'.'/'.$categoryID.'/'.$productID, 'id'=>'ShoppingCartForm'.$categoryID.'-'.$productID, 'encoding'=>false)); ?>				
<div class="floatLeft" style="width:100px; margin-right:10px;">
	<?php 
	$qtyOptions = Configure::read('Product.quantity');
	echo $this->Form->input('ShoppingCartProduct.quantity', array('options'=>$qtyOptions, 'empty'=>false, 'id'=>'ShoppingCartQuantity'.$categoryID.'-'.$productID));
	?>
</div>	
<div class="floatLeft" style="width:100px; margin-right:10px;">
	<?php 
	$sizeOptions = Configure::read('Product.size');
	echo $this->Form->input('ShoppingCartProduct.size', array('options'=>$sizeOptions, 'empty'=>'-', 'id'=>'ShoppingCartSize'.$categoryID.'-'.$productID));
	?>
</div>			
<div class="floatLeft" style="width:150px; margin-right:10px;">
	<?php 
	$ageOptions = Configure::read('Product.age');
	echo $this->Form->input('ShoppingCartProduct.age', array('options'=>$ageOptions, 'empty'=>'-', 'id'=>'ShoppingCartAge'.$categoryID.'-'.$productID));
	?>
</div>
<div class="floatLeft" style="margin-right:10px;">
	<br>
	<?php echo $this->Form->submit('Submit &raquo;', array('escape'=>false));?>
</div>
<div class='clear'></div>
	
<?php echo $this->Form->end();?>
