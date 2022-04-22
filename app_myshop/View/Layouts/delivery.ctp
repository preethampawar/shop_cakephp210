<?php
App::uses('Order', 'Model');

if (!empty($title_for_layout)) {
	$title_for_layout = $title_for_layout . ' - ' . $this->Session->read('Site.title');
} else {
	$siteCaption = $this->Session->read('Site.caption');
	$title_for_layout = $this->Session->read('Site.title');
	$title_for_layout .= (!empty($siteCaption)) ? ' - ' . $siteCaption : '';
}

$analyticsCode = null;
if (!empty(trim($this->Session->read('Site.analytics_code')))) {
	$analyticsCode = $this->Session->read('Site.analytics_code');
}
?>

<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php
	echo $this->fetch('meta');
	?>
	<title><?= $title_for_layout ?></title>
	<script>
		if (!window.fetch) {
			window.location = '/pages/unsupportedbrowser'
		}

		function showLoadingBar() {
			document.getElementById("topNavProgressBar").classList.remove('d-none')
		}
	</script>

	<link rel="stylesheet" href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/fontawesome-free-6.0.0-beta2-web/css/all.min.css" media="print" onload="this.media='all'">
	<link rel="stylesheet" href="/css/site.css?v=1.2.2">

	<?= $analyticsCode ?>
</head>

<body class="bg-dark" onbeforeunload="showLoadingBar()">
	<div class="bg-white pb-5">

		<nav class="navbar navbar-expand-lg navbar-static navbar-dark bg-dark">
			<div class="container-fluid">
				<a class="navbar-brand" href="/deliveries/home">
					<?= $this->Session->read('Site.title') ?>
				</a>

				<div class="navbar-toggler border-0 " type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fa fa-bars"></i>
				</div>
				<div class="offcanvas offcanvas-end" id="navbarNav">
					<div class="offcanvas-header border-bottom border-4 border-warning">
						<h5 class="offcanvas-title" id="offcanvasNavbarLabel"><?= $this->Session->read('Site.title') ?></h5>
						<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					</div>
					<div class="offcanvas-body">
						<ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
							<li class="nav-item px-1">
								<a class="nav-link px-1" href="/deliveries/home">Home</a>
							</li>
							<li class="nav-item px-1 d-none">
								<a class="nav-link px-1" href="/deliveries/dashboard">Dashboard</a>
							</li>
						</ul>
						<?php if ($this->Session->check('User.id')): ?>
						<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
							<li class="nav-item px-1">
								<a class="nav-link px-1" href="#">
									<i class="fa fa-user-circle"></i>
									<?= $this->Session->read('User.firstname')!= '' ? $this->Session->read('User.firstname') : $this->Session->read('User.mobile') ?>
								</a>
							</li>
							<li class="nav-item px-1">
								<a class="nav-link px-1" href="/users/logout">Logout</a>
							</li>
						</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</nav>
		<div class="progress rounded-0 d-none" style="height:5px;" id="topNavProgressBar">
			<div class="progress-bar progress-bar-striped progress-bar-animated bg-orange" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
		</div>

		<div class="container">
			<?php echo $this->Session->flash(); ?>
		</div>

		<div class="container mt-4 pb-5" style="min-height: 500px;">
			<div class="my-3 text-end">
				<a href="/deliveries/home" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Refresh</a>
			</div>
			<?php echo $this->fetch('content'); ?>

			<!-- --------------------------End of visible content----------------------------- -->
			<!-- Alert -->
			<div class="modal" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body" id="alertModalBody">
							<div class="d-flex justify-content-between">
								<h5 class="modal-title" id="alertModalLabel">Alert!</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<hr>
							<div class="mt-3 mb-3" id="alertModalContent"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Ajax loader -->
			<div id="fullLoader">
				<div class="modal" id="fullLoaderBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-sm">
						<div class="modal-content">
							<div class="modal-body text-center text-purple">
								<div class="d-flex justify-content-center">
									<div class="spinner-border text-purple" role="status" aria-hidden="true"></div>
									<span class="ms-3 fs-5">Loading...</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Confirm Popup -->
			<div class="modal" id="confirmPopup" data-bs-backdrop="static" data-bs-keyboard="false"
				 aria-labelledby="deleteModal" aria-hidden="true">
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

							<div class="mt-4 text-center d-none" id="confirmPopupSpinner">
								<div class="spinner-border spinner-border-sm text-primary" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
								<br>
								Please wait. Your request is in process.
							</div>
						</div>
						<div class="modal-footer mt-3 p-1">
							<a href="#" class="actionLink btn btn-danger btn-sm me-2 w-25" onclick="$('#confirmPopupSpinner').removeClass('d-none')"><span class="ok">Ok</span></a>
							<button type="button" class="actionLinkButton btn btn-danger btn-sm me-2" data-bs-dismiss="modal"><span
										class="ok">Ok</span></button>
							<button type="button" class="btn btn-outline-secondary btn-sm cancelButton w-25" data-bs-dismiss="modal">
								Cancel
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Toast messages -->
			<div aria-live="polite" aria-atomic="true" class="position-relative">
				<!-- Position it: -->
				<!-- - `.toast-container` for spacing between toasts -->
				<!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
				<!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
				<div class="toast-container fixed-top end-0 p-2 mt-5" style="left: auto">
					<div id="ToastMessage" class="d-none">
						<div
							id="toastDiv"
							class="toast toast-js text-white border-white border-2"
							role="alert"
							aria-live="assertive"
							aria-atomic="true"
							data-bs-autohide="true"
							data-bs-delay="1500">
							<div class="d-flex align-items-center justify-content-between">
								<div class="toast-body"></div>
								<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast"
										aria-label="Close"></button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="ToastMessage" class="toast-container fixed-top end-0 p-2 mt-5 d-none" style="left: auto">
				<div id="toastDiv" class="toast text-white border-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2000">
					<div class="d-flex align-items-center">
						<div class="toast-body"></div>
						<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast"
								aria-label="Close"></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<?php echo $this->element('sql_dump'); ?>
	</div>

	<script src="/vendor/jquery/jquery-3.6.0.slim.min.js"></script>
	<script src="/vendor/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
	<?= $this->element('custom_delivery_js') ?>
	<script>
		var time = new Date().getTime();
		$(document.body).bind("mousemove keypress touchmove scroll", function(e) {
			time = new Date().getTime();
		});

		function refresh() {
			if(new Date().getTime() - time >= (30*1000)) //30 seconds
				window.location.reload(true);
			else
				setTimeout(refresh, 15000);
		}

		setTimeout(refresh, 15000);
	</script>



</body>
</html>
