<?php
$categoryID = $categoryID ?? null;
$productID = $productID ?? null;
$uniqueId = $categoryID . '-' . $productID;

if ($categoryID &&  $productID) {
?>
	<div class="input-group input-group-sm mt-1 flex-nowrap">
		<select class="form-select pe-4" id="productCardInputGroupSelect<?= $uniqueId ?>" aria-label="Product Quantity">
			<option value="1" selected>1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>
		<button class="btn btn-primary w-50 text-nowrap" type="button" onclick="productAddToCart('<?= $categoryID ?>', '<?= $productID ?>', (document.getElementById('productCardInputGroupSelect<?= $uniqueId ?>').value), this)">
		Add <i class="bi bi-cart-plus-fill"></i>
		</button>
	</div>
<?php
} else {
	echo 'Error! Both $categoryID & $productID are required';
}
?>