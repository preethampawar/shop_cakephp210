<?php
App::uses('Order', 'Model');
$testimonialsEnabled = (int)$this->Session->read('Site.show_testimonials') === 1;
$theme = $this->Session->read('Theme');
$navbarTheme = $theme['navbarTheme'];
$secondaryMenuBg = $theme['secondaryMenuBg'];
$linkColor = $theme['linkColor'];
$cartBadgeBg = $theme['cartBadgeBg'];
$hightlightLink = $theme['hightlightLink'];
$canonical = $canonical ?? null;
$enableLightbox = $enableLightbox ?? false;

//debug($enableLightbox);

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

$logoUrl = null;
if ($this->Session->read('Site.logo')) {
	$logoUrl = $this->Html->url('/' . $this->Session->read('Site.logo'), true);
}

$testimonialsSlideShowEnabled = (int)$this->Session->read('Site.show_testimonials') === 1;

$showLocationOptions = false;
$siteLocations = null;

$siteConfiguration = $this->Session->check('siteConfiguration') ? $this->Session->read('siteConfiguration') : null;

$andriodAppBadgeUrl = $siteConfiguration['andriodAppBadgeUrl'] ?? null;
$andriodAppUrl = $siteConfiguration['andriodAppUrl'] ?? null;
$siteLocations = $siteConfiguration['locations'] ?? null;

$subdomain = $this->request->subdomains()[0];
$locationId = $siteConfiguration['defaultLocationId'] ?? '';
$locationTitle = null;
$locationUrl = null;
$showLocationOptions = !empty($siteLocations);

$isMobileApp = $this->Session->check('isMobileApp') ? $this->Session->read('isMobileApp') : false;
$locationQueryParam = $isMobileApp ? '?s=mobile' : '';

?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php
	echo $this->fetch('meta');
	echo (isset($customMeta)) ? $customMeta : null;
	echo (isset($facebookMetaTags)) ? $facebookMetaTags : null;
	?>
	<title><?= $title_for_layout ?></title>
	<script>
		if (!window.fetch) {
			window.location = '/pages/unsupportedbrowser'
		}

		var isLightBoxEnabled = "<?= $enableLightbox ? 1 : '' ?>";
	</script>

	<meta name="theme-color" content="#317EFB" />
	<?php
	if ($this->Session->check('siteConfiguration.manifestJsonUrl') && !empty($this->Session->read('siteConfiguration.manifestJsonUrl'))) {
	?>
		<link rel="manifest" href="<?= $this->Session->read('siteConfiguration.manifestJsonUrl') ?>" />
	<?php
	}
	?>

	<?php
	if (!empty($canonical)) {
		$canonicalUrl = $this->Html->url($canonical, true);
		echo '<link rel="canonical" href="' . $canonicalUrl . '">';
	}
	?>

	<link rel="stylesheet" href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/site.css?v=1.2.9" media="print" onload="this.media='all'">
	<?php if ($enableLightbox) { ?>
		<link rel="stylesheet" href="/vendor/lightbox2-2.11.3/dist/css/lightbox.min.css" media="print" onload="this.media='all'">
	<?php } ?>
	<!-- <link rel="stylesheet" href="/vendor/fontawesome-free-6.0.0-beta3-web/css/all.min.css" media="print" onload="this.media='all'"> -->
	<link rel="stylesheet" href="/vendor/bootstrap-icons-1.8.0/bootstrap-icons.css" media="print" onload="this.media='all'">
	<?= $this->element('customcss') ?>

	<?= $analyticsCode ?>

	<?php
	if (isset($loadVueJs) && $loadVueJs == true) {
	?>
		<script src="/vendor/vue/vue.min.js"></script>
	<?php
	}
	?>
</head>

<body class="bg-white" onbeforeunload="showLoadingBar()">
	<div class="bg-white" id="root">
		<?php
		if (!$isMobileApp) {
		?>
			<nav class="navbar navbar-expand-lg navbar-static <?= $navbarTheme ?>">
				<div class="container">
					<a class="navbar-brand" href="/">
						<?php
						if ($logoUrl) {
						?>
							<img src="<?= $logoUrl ?>" alt="<?= $this->Session->read('Site.title') ?>" title="<?= $this->Session->read('Site.title') ?>" class="img-fluid" width="<?= (int)$this->Session->read('Site.logo_width') > 0 ? (int)$this->Session->read('Site.logo_width') : 200 ?>" height="<?= (int)$this->Session->read('Site.logo_height') > 0 ? (int)$this->Session->read('Site.logo_height') : 50 ?>" loading="eager">
						<?php
						} else {
						?>
							<i class="bi bi-house-door"></i> <?= $this->Session->read('Site.title') ?>
						<?php
						} ?>
					</a>

					<div class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<i class="bi bi-list navbar-brand"></i>
					</div>
					<div class="offcanvas offcanvas-end" id="navbarNav">
						<div class="offcanvas-header border-bottom border-4 border-warning">
							<h5 class="offcanvas-title" id="offcanvasNavbarLabel"><?= $this->Session->read('Site.title') ?></h5>
							<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
						</div>
						<div class="offcanvas-body  <?= $navbarTheme ?>">
							<ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
								<li class="nav-item px-1">
									<a class="nav-link px-1" href="/">Home</a>
								</li>
								<?php
								if ($this->App->isSellerForThisSite()) {
								?>
									<li class="nav-item px-1">
										<a class="nav-link px-1 <?= $hightlightLink ?> highlight-link" href="/users/setView/seller"><i class="bi bi-tools"></i> Manage Store</a>
									</li>
								<?php
								} ?>
								<li class="nav-item px-1">
									<a class="nav-link px-1" href="/sites/about">About Us</a>
								</li>
								<li class="nav-item px-1">
									<a class="nav-link px-1" href="/sites/contact">Contact Us</a>
								</li>
							</ul>
							<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
								<?php
								/*
                            if ($andriodAppUrl && !$this->Session->read('isMobileApp')) {
                                ?>
                                <li class="nav-item px-1">
                                    <a class="nav-link px-1" href="<?= $andriodAppUrl ?>"><i class="bi bi-download"></i> Download App</a>
                                </li>
                                <?php
                            }
                            */
								?>
								<?php if (!$this->Session->check('User.id')) : ?>
									<li class="nav-item px-1">
										<a class="nav-link px-1" href="/users/customerRegistration">Register</a>
									</li>
								<?php endif; ?>


								<?php if ($this->Session->check('User.id')) : ?>

									<?php if ($this->Session->read('Site.shopping_cart')) : ?>
										<li class="nav-item px-1">
											<a class="nav-link px-1" href="/orders/">My Orders</a>
										</li>
									<?php endif; ?>

									<li class="nav-item dropdown px-1">
										<a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="bi bi-person-circle"></i>
											<?= $this->Session->read('User.firstname') != '' ? $this->Session->read('User.firstname') : $this->Session->read('User.mobile') ?>
										</a>
										<ul class="dropdown-menu" aria-labelledby="offcanvasNavbarDropdown">
											<li class="nav-item px-1"><a class="nav-link px-1" href="/users/logout">Logout</a></li>
										</ul>
									</li>
								<?php else : ?>
									<li class="nav-item px-1">
										<a class="nav-link px-1" href="/users/login">Login</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</nav>
		<?php
		}
		?>

		<div class="sticky-top shadow <?= $secondaryMenuBg ?> opacity-98" style="z-index: 999;">
			<ul class="nav container justify-content-between">
				<li class="nav-item middle">
					<a href="#" class="nav-link <?= $linkColor ?>" data-bs-toggle="offcanvas" data-bs-target="#categoriesMenu">
						<?php
						if (!$isMobileApp) {
						?>
							<div class="d-inline-block" style="width:21px;">
								<i class="bi bi-list-nested fs-5"></i>
							</div> Products
						<?php
						} else {
						?>
							<div class="d-inline-block" style="width:21px;">
								<i class="bi bi-list fs-3"></i>
							</div>
						<?php
						}
						?>
					</a>

				</li>

				<?php
				if ($isMobileApp) {
				?>
					<li class="nav-item"><a href="/" class="nav-link <?= $linkColor ?>"><i class="bi bi-house fs-5"></i> Home</a></li>
				<?php
				}
				?>

				<?php
				if ($this->App->isSellerForThisSite()) {
				?>
					<li class="nav-item pt-1">
						<div id="deliveryHearbeat" class="mt-2"></div>
					</li>
				<?php
				}
				?>

				<?php if ($this->Session->read('Site.shopping_cart')) : ?>
					<li class="nav-item" id="topNavShoppingCart">
						<a href="#" class="nav-link <?= $linkColor ?>" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
							<div class="d-inline-block"><i class="bi bi-cart fs-5"></i></div> My Cart <span class="badge bg-orange rounded-pill">0</span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
			<div style="height: 12px;" class="bg-light">
				<div class="progress rounded-0 d-none small" id="topNavProgressBar" style="height: 12px;">
					<div class="progress-bar progress-bar-striped progress-bar-animated bg-orange small" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading...</div>
				</div>
			</div>
		</div>

		<?= $this->element('banner_slideshow') ?>

		<div class="container mt-4" style="min-height: 500px;">
			<?= $this->fetch('content') ?>
			<?= $this->element('testimonials_slideshow') ?>

			<?php
			$showPaymentContactInfo = false;

			if (
				$this->request->params['controller'] != 'users'
				&& $this->request->params['controller'] != 'sites'
				&& $this->request->params['controller'] != 'orders'
			) {
				$showPaymentContactInfo = true;
			}

			if ($showPaymentContactInfo && !empty($this->Session->read('Site.contact_info'))) :
			?>

				<div class="text-center alert alert-info mt-4">
					<h4 class="text-decoration-underline">Contact Us</h4>
					<div class="small mt-4">
						<?= $this->Session->read('Site.contact_info') ?>
					</div>
				</div>
			<?php
			endif;
			?>

			<?php
			if ($showPaymentContactInfo && !empty($this->Session->read('Site.payment_info'))) :
			?>
				<div class="text-center alert alert-info mt-4">
					<h4 class="text-decoration-underline">Payment Details</h4>
					<div class="small mt-4">
						<?= $this->Session->read('Site.payment_info') ?>
					</div>
				</div>
			<?php
			endif;
			?>

			<?= $this->element('showmap') ?>

			<!-- --------------------------End of visible content----------------------------- -->

			<!-- Categories Menu -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="categoriesMenu" aria-labelledby="offcanvasTopLabel">

				<?php
				if ($isMobileApp) {
				?>
					<div class="small d-flex justify-content-between bg-light">
						<?php
						if ($this->App->isSellerForThisSite()) {
						?>
							<a class="nav-link px-2 <?= $hightlightLink ?> highlight-link" href="/users/setView/seller"><i class="bi bi-tools"></i> Manage Store</a>
						<?php
						} ?>

						<?php if (!$this->Session->check('User.id')) : ?>
							<a class="nav-link px-2" href="/users/customerRegistration">Register</a>
						<?php endif; ?>


						<?php if ($this->Session->check('User.id')) : ?>
							<?php if ($this->Session->read('Site.shopping_cart')) : ?>
								<a class="nav-link px-2" href="/orders/">My Orders</a>
							<?php endif; ?>
							<a class="nav-link px-2 disabled" href="#">
								<i class="bi bi-person-circle"></i>
								<?= $this->Session->read('User.firstname') != '' ? $this->Session->read('User.firstname') : $this->Session->read('User.mobile') ?>
							</a>
							<a class="nav-link px-2" href="/users/logout">Logout</a>
						<?php else : ?>
							<a class="nav-link px-2" href="/users/login">Login</a>
						<?php endif; ?>
					</div>
				<?php
				}
				?>

				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel">Shop By Category</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="categoriesMenuBody">
					<?php
					echo $this->element('categories_menu');
					?>
					<div class="mt-4 text-center bottom">
						<a role="button" class="nav-link btn btn-sm btn-light" data-bs-dismiss="offcanvas" aria-label="Close">Close</a>
					</div>
				</div>
			</div>

			<!-- Shopping Cart -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="myShoppingCart" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="bi bi-cart"></i> My Cart</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="myShoppingCartBody"></div>
			</div>

			<!-- Order Summary -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderSummary" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="bi bi-info-circle"></i> Order Summary</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderSummaryBody"></div>
			</div>

			<!-- Order delivery details -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderDeliveryDetails" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="bi bi-truck"></i> Delivery Details</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderDeliveryDetailsBody"></div>
			</div>

			<!-- Order payment details -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderPaymentDetails" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="bi bi-wallet-fill"></i> Payment Details</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderPaymentDetailsBody"></div>
			</div>

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

			<!-- delete product from cart popup -->
			<div class="modal fade" id="deleteProductFromCartPopup" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="deleteProductFromCartModal" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body">
							<div class="d-flex justify-content-between">
								<div>
									Are you sure you want to delete this product?
									<div id="deleteProductFromCartPopupProductName" class="fw-bold mt-3"></div>
								</div>
								<div class="ms-2">
									<button type="button" class="btn-close p-2" data-bs-dismiss="modal" aria-label="Close" onclick="deleteProductFromCartPopup.hide(); myShoppingCart.show();">
										<span aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="modal-footer mt-2 p-1">
							<a href="#" class="deleteLink btn btn-danger btn-sm me-3 w-25" onclick="deleteProductFromCart()"><span class="ok">Ok</span></a>
							<button type="button" class="btn btn-outline-secondary btn-sm w-25" data-bs-dismiss="modal" onclick="deleteProductFromCartPopup.hide(); myShoppingCart.show();">Cancel</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Toast messages -->
			<div aria-live="polite" aria-atomic="true" class="position-relative">
				<div class="toast-container fixed-top end-0 p-2 mt-5" style="left: auto">
					<div id="ToastMessage" class="d-none">
						<div id="toastDiv" class="toast toast-js text-white border-white border-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="1500">
							<div class="d-flex align-items-center justify-content-between">
								<div class="toast-body"></div>
								<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Show login popup in place order section -->
			<div class="modal fade" id="placeOrderLoginPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="placeOrderLoginPopupLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<?php if ((bool)$this->Session->read('Site.sms_notifications') === true) { ?>

								<?php echo $this->Form->create('User', ['url' => '/users/otpVerification', 'onsubmit' => "verifyOtp()"]); ?>
								<button type="submit" disabled style="display: none" aria-hidden="true"></button>
								<h1>Verify OTP</h1>

								<div class="mt-5">
									<input type="number" name="data[User][otp]" class="form-control" id="UserVerifyOtp" placeholder="Enter OTP" min="1000" max="9999" required autofocus autocomplete="off">
								</div>

								<div class="text-danger mt-2 small">
									<?php
									$text = "*OTP is sent to your Email Address.";
									if ((bool)$this->Session->read('Site.sms_notifications') === true) {
										$text = "*OTP is sent to your Mobile no. and Email Address provided in Order details";
									}
									echo $text;
									?>
								</div>

								<div class="mt-4">
									<button type="button" class="btn btn-md btn-primary" id="orderVerifyOtpButton" onclick="verifyOtp()">Next - Verify OTP</button>
								</div>

								<?php echo $this->Form->end(); ?>

							<?php } else { ?>
								If you are already registered, please <a href="/users/login" class="text-orange">login</a> to place an Order.

								<div class="mt-3 text-center">(OR)</div>

								<div class="mt-3">
									Click to <a href="#" id="placeOrderLinkGuest" class="text-orange" onclick="placeOrder(1)">Auto Register & Place Order</a>.
									A new account will be created for you and order will be placed based on the contact information provided in the order details.
								</div>
							<?php
							}
							?>
							<div id="confirmOrderSpinnerGuest" class="mt-4"></div>
						</div>
						<div class="modal-footer">
							<?php if ((bool)$this->Session->read('Site.sms_notifications') === false) { ?>
								<a href="/users/login" role="button" class="btn btn-orange">Go to Login Page</a>
							<?php } ?>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container mt-4">
			<?php echo $this->Session->flash(); ?>
		</div>

		<!-- footer -->
		<footer>
			<nav class="navbar navbar-expand-lg navbar-static border-top <?= $navbarTheme ?>">
				<div class="container-fluid justify-content-center text-center mb-2 small">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/about">About Us</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/contact">Contact Us</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/tos">Terms of Service</a>
						</li>
						<li class="nav-item px-1">
							<a class="nav-link px-1" href="/sites/privacy">Privacy Policy</a>
						</li>
						<?php
						if ($testimonialsEnabled) {
						?>
							<li class="nav-item px-1">
								<a class="nav-link px-1" href="/testimonials/">Testimonials</a>
							</li>
						<?php
						}
						?>

					</ul>
				</div>
			</nav>

			<?php
			if (!empty($andriodAppBadgeUrl) || $showLocationOptions) {
			?>
				<div class="d-flex justify-content-between container text-muted small bg-light">
					<div>
						<?php
						if ($andriodAppBadgeUrl && !$this->Session->read('isMobileApp')) {
						?>
							<div class="px-3 py-2" role="button" data-bs-toggle="offcanvas" data-bs-target="#offCanvasApp" aria-controls="offCanvasApp">
								Download App <i class="text-danger bi bi-download"></i>
							</div>
							<div class="offcanvas offcanvas-start" tabindex="-1" id="offCanvasApp" aria-labelledby="offCanvasAppLabel">
								<div class="offcanvas-header">
									<h5 class="offcanvas-title" id="offCanvasAppLabel">Download Mobile App</h5>
									<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
								</div>
								<div class="offcanvas-body">
									<?= $andriodAppBadgeUrl ?>
								</div>
							</div>
						<?php
						}
						?>
					</div>
					<div>
						<?php
						if ($showLocationOptions) {
						?>
							<div class="px-3 py-2" role="button" data-bs-toggle="offcanvas" data-bs-target="#offCanvasLocation" aria-controls="offCanvasLocation">
								<i class="bi bi-geo-alt-fill text-danger"></i> <?= $siteLocations[$subdomain]['title'] ?>
							</div>
							<div class="offcanvas offcanvas-end" tabindex="-1" id="offCanvasLocation" aria-labelledby="offCanvasLocationLabel">
								<div class="offcanvas-header">
									<h5 class="offcanvas-title" id="offCanvasLocationLabel">Select Location</h5>
									<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
								</div>
								<div class="offcanvas-body">
									<div>We currently serve in the following areas. Choose your location of interest.</div>

									<div class="list-group  list-group-flush mt-3">
										<?php
										foreach ($siteLocations as $id => $row) {
										?>
											<a href="//<?= $row['url'] ?>" class="list-group-item list-group-item-action py-3">
												<i class="bi bi-geo-alt-fill text-danger"></i> <?= $row['title'] ?>
											</a>
										<?php
										} ?>
									</div>
								</div>
							</div>
						<?php
						}
						?>

					</div>
				</div>
			<?php
			}
			?>
		</footer>
	</div>

	<div class="container bg-white text-dark">
		<?php echo $this->element('sql_dump'); ?>
	</div>

	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
	<script src="/vendor/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
	<script src="/js/site.js?v=1.3.1"></script>
	<?= $this->element('customjs') ?>

	<?php if ($enableLightbox) { ?>
		<script src="/vendor/lightbox2-2.11.3/dist/js/lightbox.min.js" defer></script>
	<?php } ?>
	<script src="/js/final.js?v=1.1.3" defer></script>

	<!-- third party scripts from backend db -->
	<?= $this->element('footerscripts')	?>



	<?php
	if ($this->App->isSellerForThisSite()) {
	?>
		<script src="/vendor/howler/howler.core.min.js"></script>

		<!-- React scripts -->
		<!-- <script src="/vendor/react/react.development.js"></script>
		<script src="/vendor/react/react-dom.development.js"></script> -->
		<script src="/vendor/react/react.production.min.js"></script>
		<script src="/vendor/react/react-dom.production.min.js"></script>

		<script src="/react-myshop/dist/delivery-heartbeat.js"></script>
		<!-- <script src="/react-myshop/dist/categories-menu.js"></script> -->
		<!-- <script src="/react-myshop/dist/homepage-category-products.js"></script> -->

	<?php
	}
	?>

</body>

</html>