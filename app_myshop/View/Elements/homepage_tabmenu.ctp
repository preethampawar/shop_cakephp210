<?php
$homepageActive = (bool)($homepage ?? false);
$featuredPageActive = (bool)($featuredPage ?? false);

if ($homepageActive === false && $featuredPageActive === false) {
	$homepageActive = true;
}
?>

<header>
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link <?= $homepageActive ? ' fw-bold active' : '' ?>" aria-current="page" href="/products/showFeatured"><span class="text-orange"><i class="fa fa-bahai"></i></span> Best Deals</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= $featuredPageActive ? ' fw-bold active' : '' ?>" href="/products/showAll">Show All Products</a>
		</li>
	</ul>
</header>
