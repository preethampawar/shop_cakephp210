<?php
$productsLimitForThisSite = (int) $this->Session->read('Site.products_limit');

App::uses('Product', 'Model');
$productModel = new Product();
$productsCount = $productModel->find('count', ['conditions' => ['Product.site_id' => $this->Session->read('Site.id')]]);
$percentage = floor(($productsCount / $productsLimitForThisSite)*100);

$bg = ' bg-success ';
if($percentage > 70) {
	$bg = ' bg-warning ';
}
if($percentage > 85) {
	$bg = ' bg-danger ';
}
?>
<div class="border p-2 pt-3 rounded">
	<h6>Products Quota (<?= $productsCount ?> / <?= $productsLimitForThisSite ?>)</h6>
	<div class="progress">
		<div class="progress-bar <?= $bg ?>" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $percentage ?>%</div>
	</div>
	<?php if($percentage >= 70): ?>
		<div class="alert alert-warning p-1 mt-1"><i class="fa fa-exclamation-circle"></i> You can add only <?= ($productsLimitForThisSite - $productsCount) ?> products to your store.</div>
	<?php endif; ?>

	<?php if($percentage >= 100): ?>
	<div class="alert alert-danger p-1 mt-1"><i class="fa fa-exclamation-circle"></i> You cannot add anymore products to your store.</div>
	<?php endif; ?>
</div>
