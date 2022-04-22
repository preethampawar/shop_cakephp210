<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Preetham Pawar, Shankar Naik">
	<meta name="theme-color" content="#7952b3">

	<title>Wines: <?php echo $title_for_layout; ?></title>

	<link rel="icon" type="image/gif" href="/img/stats.gif" crossorigin="anonymous">
	<link rel="stylesheet" href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/select2-4.1/select2.min.css">
	<link href="/css/sidebars.css" rel="stylesheet">
	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
	<script src="/vendor/select2-4.1/select2.min.js"></script>
	<script src="/html-table-search/html-table-search.js"></script>
	<style>
		.select2-results {
			font-size: 0.75rem;
		}
	</style>
</head>
<body>


<main>
	<div class="flex-shrink-0 p-3 bg-white" style="width: 280px; overflow-y: auto">
		<a href="/stores/home" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
			<span class="fs-5 fw-semibold"><?php echo ($this->Session->check('Store')) ? strtoupper($this->Session->read('Store.name')) : 'SimpleAccounting'; ?></span>
		</a>
		<ul class="list-unstyled ps-0">
			<li class="mb-1">
				<button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
					Bills
				</button>
				<div class="collapse show" id="home-collapse">
					<ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
						<li><a href="/bills/add" class="link-dark rounded">+ Add New Bill</a></li>
						<!--
						<li><a href="#" class="link-dark rounded">Updates</a></li>
						<li><a href="#" class="link-dark rounded">Reports</a></li>
						-->
					</ul>
				</div>
			</li>


			<?php
			/*
			?>
			<li class="mb-1">
				<button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
					Home
				</button>
				<div class="collapse show" id="home-collapse">
					<ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
						<li><a href="#" class="link-dark rounded">Overview</a></li>
						<li><a href="#" class="link-dark rounded">Updates</a></li>
						<li><a href="#" class="link-dark rounded">Reports</a></li>
					</ul>
				</div>
			</li>
			<li class="mb-1">
				<button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
					Dashboard
				</button>
				<div class="collapse" id="dashboard-collapse">
					<ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
						<li><a href="#" class="link-dark rounded">Overview</a></li>
						<li><a href="#" class="link-dark rounded">Weekly</a></li>
						<li><a href="#" class="link-dark rounded">Monthly</a></li>
						<li><a href="#" class="link-dark rounded">Annually</a></li>
					</ul>
				</div>
			</li>
			<li class="mb-1">
				<button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
					Orders
				</button>
				<div class="collapse" id="orders-collapse">
					<ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
						<li><a href="#" class="link-dark rounded">New</a></li>
						<li><a href="#" class="link-dark rounded">Processed</a></li>
						<li><a href="#" class="link-dark rounded">Shipped</a></li>
						<li><a href="#" class="link-dark rounded">Returned</a></li>
					</ul>
				</div>
			</li>
			<li class="border-top my-3"></li>
			<li class="mb-1">
				<button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
					Account
				</button>
				<div class="collapse" id="account-collapse">
					<ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
						<li><a href="#" class="link-dark rounded">New...</a></li>
						<li><a href="#" class="link-dark rounded">Profile</a></li>
						<li><a href="#" class="link-dark rounded">Settings</a></li>
						<li><a href="#" class="link-dark rounded">Sign out</a></li>
					</ul>
				</div>
			</li>
			*/
			?>
		</ul>
	</div>

	<div class="b-example-divider"></div>

	<div class="container-fluid p-3" style="overflow: auto">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
</main>


<script src="/vendor/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

<script src="/js/sidebars.js"></script>
</body>
</html>
