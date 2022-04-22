<?php
$assetDomainUrl = Configure::read('AssetDomainUrl');
$enableImageCropper = $enableImageCropper ?? false;
$enableBannerImageCropper = $enableBannerImageCropper ?? false;
$enableCategoryImageCropper = $enableCategoryImageCropper ?? false;
$enableTextEditor = $enableTextEditor ?? false;
?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Seller</title>

	<link rel="stylesheet" href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/lightbox2-2.11.3/dist/css/lightbox.min.css">
	<link rel="stylesheet" href="/vendor/bootstrap-icons-1.8.0/bootstrap-icons.css" media="print" onload="this.media='all'">
	<link rel="stylesheet" href="/css/site.css?v=1.2.8">
	<?= $this->element('customcss') ?>

	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>

	<?php
	if (isset($loadVueJs) && $loadVueJs == true) {
	?>
		<script src="/vendor/vue/vue.min.js"></script>

		<!-- development version, includes helpful console warnings -->
		<!--		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>-->

		<!-- production version, optimized for size and speed -->
		<!--		<script src="https://cdn.jsdelivr.net/npm/vue"></script>	-->
	<?php
	}
	?>
</head>

<body>

	<nav class="navbar navbar-dark bg-info active bg-gradient">
		<div class="container">
			<a class="navbar-brand text-truncate" href="#"><?php echo $this->Session->read('Site.title'); ?></a>
			<div>
				<a href="/admin/orders/" class=""><div id="deliveryHearbeat"></div></a>
			</div>
		</div>



	</nav>

	<nav class="navbar navbar-expand-lg navbar-dark navbar-static bg-dark ">
		<div class="container">
			<div class="navbar-toggler border-0 p-1 py-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="bi bi-list"></span> Admin
			</div>
			<a class="navbar-brand" href="/admin/sites/home">
				Home
			</a>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<?php
					if ($this->App->isSellerForThisSite()) {
					?>

						<li class="nav-item">
							<a class="nav-link" href="/users/setView/buyer"><i class="bi bi-box-arrow-right"></i>Customer View</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="/users/myaccount"><i class="bi bi-box-arrow-right"></i>My Account</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/users/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
						</li>
					<?php
					} else { ?>
						<li class="nav-item">
							<a class="nav-link" href="/users/login"><i class="bi bi-box-arrow-in-right"></i> Login</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>

	<div class="bg-light border-bottom">
		<ul class="nav container justify-content-start">
			<li class="nav-item">
				<a class="nav-link" href="/admin/banners/">Banners</a>
			</li>
			<li class="nav-item productSideBar">
				<a class="nav-link" href="/admin/categories/">Products</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/orders/">Orders</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/users/manage">Users</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/testimonials/">Testimonials</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/promo_codes/">PromoCodes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/groups/">Groups</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/suppliers/">Suppliers</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/transactions/">Transactions</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/GroupPaperRates/">PaperRates</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/sites/settings">Store Settings</a>
			</li>
		</ul>
	</div>
	<!-- Navigation -->
	<?php
	//debug($this->Session->read());
	?>
	<div class="container">
		<?php echo $this->Session->flash(); ?>
	</div>

	<div class="container mt-3">
		<?php echo $this->fetch('content'); ?>
	</div>

	<div class="container">
		<!-- footer -->
	</div>

	<!-- Modal -->
	<div class="modal" id="confirmPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModal"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="content">Are you sure?</div>
				</div>
				<div class="modal-footer mt-2 p-1">
					<a href="#" class="actionLink btn btn-danger btn-sm me-2 px-3" onclick="this.classList.add('disabled')"><span class="ok">Ok</span></a>
					<button type="button" class="actionLinkButton btn btn-danger btn-sm me-2 px-3" data-bs-dismiss="modal"><span class="ok">Ok</span></button>
					<button type="button" class="btn btn-outline-secondary btn-sm cancelButton" data-bs-dismiss="modal">
						Cancel
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="deleteImagePopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModal">Delete</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="content">Are you sure you want to delete it?</div>
				</div>
				<div class="modal-footer mt-2 p-1">
					<a href="#" class="deleteLink btn btn-danger btn-sm me-2"><span class="ok">Ok</span></a>
					<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<div id="ToastMessage" class="toast-container fixed-top end-0 p-2 mt-5 d-none" style="left: auto">
		<div id="toastDiv" class="toast text-white border-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2000">
			<div class="d-flex align-items-center">
				<div class="toast-body"></div>
				<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>

	<!-- Optional JavaScript -->
	<!-- Popper.js first, then Bootstrap JS -->


	<?php
	if ($enableTextEditor) {
		// use bootstrap 4.x.x to fix texteditor issue
	?>
		<!--
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
		integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
		crossorigin="anonymous"></script>
		-->
	<?php
	} else {
	?>
		<!--
	<script src="/vendor/bootstrap-5.0.0-dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.min.js"
			integrity="sha384-t6I8D5dJmMXjCsRLhSzCltuhNZg6P10kE0m0nAncLUjH6GeYLhRU1zfLoW3QNQDF"
			crossorigin="anonymous"></script>
	-->
	<?php
	}
	?>


	<script src="/vendor/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
	<script src="/vendor/jquery-lazy-load/jquery.lazyload.min.js"></script>
	<script src="/vendor/lightbox2-2.11.3/dist/js/lightbox.min.js"></script>

	<?php if ($enableTextEditor) : ?>
		<?= $this->element('text_editor') ?>
	<?php endif; ?>

	<?= $this->element('custom_seller_js') ?>

	<?php if ($enableImageCropper) : ?>
		<?= $this->element('imagecropper') ?>
	<?php endif; ?>

	<?php if ($enableBannerImageCropper) : ?>
		<?= $this->element('bannerimagecropper') ?>
	<?php endif; ?>

	<?php if ($enableCategoryImageCropper) : ?>
		<?= $this->element('categoryimagecropper') ?>
	<?php endif; ?>

	<?= $this->element('sql_dump'); ?>

	<script src="/vendor/howler/howler.core.min.js"></script>

	<!-- React scripts -->
	<!-- <script src="/vendor/react/react.development.js"></script>
	<script src="/vendor/react/react-dom.development.js"></script> -->
	<script src="/vendor/react/react.production.min.js"></script>
	<script src="/vendor/react/react-dom.production.min.js"></script>

	<script src="/react-myshop/dist/delivery-heartbeat.js"></script>
	<!-- <script src="/react-myshop/dist/categories-menu.js"></script> -->
	<!-- <script src="/react-myshop/dist/homepage-category-products.js"></script> -->


</body>

</html>