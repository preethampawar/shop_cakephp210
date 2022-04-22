<?php
$assetDomainUrl = Configure::read('AssetDomainUrl');
$enableImageCropper = $enableImageCropper ?? false;
$enableTextEditor = $enableTextEditor ?? false;
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
		  integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
		  crossorigin="anonymous"/>

	<title>Seller</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<?php
	if (isset($loadVueJs) && $loadVueJs == true) {
		?>
		<!-- development version, includes helpful console warnings -->
		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

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
	</div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark navbar-static bg-dark ">
	<div class="container">
		<div class="navbar-toggler border-0 p-1 py-0 text-white" type="button" data-toggle="collapse"
			 data-target="#navbarNav"
			 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="fa fa-bars"></span> Admin
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
						<a class="nav-link" href="/users/setView/buyer"><i class="fa fa-sign-out-alt"></i>Customer View</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="/users/myaccount"><i class="fa fa-sign-out-alt"></i>My Account</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/users/logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
					</li>
					<?php
				} else { ?>
					<li class="nav-item">
						<a class="nav-link" href="/users/login"><i class="fa fa-sign-in-alt"></i> Login</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>

<div class="bg-light border-bottom">
	<ul class="nav container justify-content-start">
		<li class="nav-item productSideBar">
			<a class="nav-link font-weight-bold" href="/admin/categories/">Manage Products</a>
		</li>
		<li class="nav-item">
			<a class="nav-link font-weight-bold" href="#">Manage Orders</a>
		</li>
		<li class="nav-item">
			<a class="nav-link font-weight-bold" href="/admin/sites/settings">Store Settings</a>
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
<div class="modal fade" id="confirmPopup" data-backdrop="static" data-keyboard="false" tabindex="-1"
	 aria-labelledby="deleteModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteModal"></h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body">
				<div class="content">Are you sure?</div>
			</div>
			<div class="modal-footer mt-2 p-1">
				<a href="#" class="actionLink btn btn-danger btn-sm mr-2"><span class="ok">Ok</span></a>
				<button type="button" class="actionLinkButton btn btn-danger btn-sm mr-2" data-dismiss="modal"><span
						class="ok">Ok</span></button>
				<button type="button" class="btn btn-outline-secondary btn-sm cancelButton" data-dismiss="modal">
					Cancel
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteImagePopup" data-backdrop="static" data-keyboard="false" tabindex="-1"
	 aria-labelledby="deleteModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteModal">Delete</h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body">
				<div class="content">Are you sure you want to delete it?</div>
			</div>
			<div class="modal-footer mt-2 p-1">
				<a href="#" class="deleteLink btn btn-danger btn-sm mr-2"><span class="ok">Ok</span></a>
				<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div id="ToastMessage" class="fixed-top d-none"
	 style="width:16rem; left: auto; margin-top: 8rem; margin-right: 0.5rem;">
	<div id="toastDiv" class="toast text-white border-white" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="d-flex align-items-center">
			<div class="toast-body"></div>
			<button type="button" class="btn-close btn-close-white ml-auto mr-2" data-dismiss="toast"
					aria-label="Close"></button>
		</div>
	</div>
</div>

<!-- Optional JavaScript -->
<!-- Popper.js first, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
		integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
		crossorigin="anonymous"></script>

<?php
if ($enableTextEditor) {
	// use bootstrap 4.x.x to fix texteditor issue
	?>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
		integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
		crossorigin="anonymous"></script>
<?php
} else {
?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.min.js"
			integrity="sha384-t6I8D5dJmMXjCsRLhSzCltuhNZg6P10kE0m0nAncLUjH6GeYLhRU1zfLoW3QNQDF"
			crossorigin="anonymous"></script>

	<?php
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
		integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
		crossorigin="anonymous"></script>

<?php if ($enableTextEditor): ?>
	<?= $this->element('text_editor') ?>
<?php endif; ?>

<?= $this->element('customjs') ?>

<?php if ($enableImageCropper): ?>
	<?= $this->element('imagecropper') ?>
<?php endif; ?>

<script src="https://kit.fontawesome.com/231b614f56.js" crossorigin="anonymous" async></script>

<?= $this->element('sql_dump'); ?>

</body>
</html>
