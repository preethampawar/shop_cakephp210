<?php
$homepageActive = (bool)($homepage ?? false);
$featuredPageActive = (bool)($featuredPage ?? false);

if ($homepageActive === false && $featuredPageActive === false) {
	$homepageActive = true;
}
$activeAlertClass = "alert-danger";
$inactiveAlertClass = "alert-info"
?>

<header>
	<ul class="nav nav-tabs d-none">
		<li class="nav-item">
			<a class="nav-link <?= $homepageActive ? ' fw-bold active' : '' ?>" aria-current="page" href="/products/showFeatured"><span class="text-orange"><i class="bi bi-stars"></i></span> Hot Deals</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?= $featuredPageActive ? ' fw-bold active' : '' ?>" href="/products/showAll">Show All Products</a>
		</li>
	</ul>
</header>


<div class="table-responsive mt-3">
	<div class="hstack gap-3">
		<div class="alert alert-danger p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/showFeatured">
				<div class="d-inline-block" style="width: 20px;"><i class="bi bi-stars text-orange"></i></div> Hot Deals
			</a>
		</div>
		<div class="alert alert-warning p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/0/99/asc">
				Below <?= $this->App->price(99); ?>
			</a>
		</div>
		<div class="alert alert-info p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/99/199/asc">
				<?= $this->App->price(99); ?> -
				<?= $this->App->price(199); ?>
			</a>
		</div>
		<div class="alert alert-success p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/199/299/asc">
				<?= $this->App->price(199); ?> -
				<?= $this->App->price(299); ?>
			</a>
		</div>
		<div class="alert alert-primary p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/299/399/asc">
				<?= $this->App->price(299); ?> -
				<?= $this->App->price(399); ?>
			</a>
		</div>
		<div class="alert alert-info p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/399/499/asc">
				<?= $this->App->price(399); ?> -
				<?= $this->App->price(499); ?>
			</a>
		</div>
		<div class="alert alert-secondary bg-light p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/499/0/asc">
				<?= $this->App->price(499); ?> & Above
			</a>
		</div>
		<div class="alert alert-secondary p-0 mb-4 shadow-sm" role="button">
			<a class="nav-link text-nowrap" aria-current="page" href="/products/filter/price/0/0/asc">
				Show All
			</a>
		</div>
	</div>
</div>