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
	$logoUrl = $this->Html->url('/'.$this->Session->read('Site.logo'), true);
}

$testimonialsSlideShowEnabled = (int)$this->Session->read('Site.show_testimonials') === 1;

$showLocationOptions = false;
$showLocationPopup = false;
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

if ( !empty($siteLocations)
		&& $this->request->controller === 'pages'
		&& $this->request->action === 'display'
) {
	$showLocationPopup = true;
}
$isMobileApp = $this->Session->check('isMobileApp') ? $this->Session->read('isMobileApp') : false;
$locationQueryParam = $isMobileApp ? '?s=mobile' : '';
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
	echo (isset($customMeta)) ? $customMeta : null;
	echo (isset($facebookMetaTags)) ? $facebookMetaTags : null;
	?>
	<title><?= $title_for_layout ?></title>
	<script>
		if (!window.fetch) {
			window.location = '/pages/unsupportedbrowser'
		}
	</script>

	<meta name="theme-color" content="#317EFB"/>
	<!--
	<link rel="manifest" href="/manifest.json" />
	<script type="module">
		// import '/pwaupdate.js';
		// const el = document.createElement('pwa-update');
		// document.body.appendChild(el);
	</script>
	-->

	<?php
	if (!empty($canonical)) {
		$canonicalUrl = $this->Html->url($canonical, true);
		echo '<link rel="canonical" href="'. $canonicalUrl .'">';
	}
	?>

	<link rel="stylesheet" href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/lightbox2-2.11.3/dist/css/lightbox.min.css" media="print" onload="this.media='all'">
	<link rel="stylesheet" href="/vendor/fontawesome-free-6.0.0-beta2-web/css/all.min.css" media="print" onload="this.media='all'">
	<link rel="stylesheet" href="/css/site.css?v=1.2.3">
	<?= $this->element('customcss') ?>

	<?= $analyticsCode ?>

	<?php
	if (isset($loadVueJs) && $loadVueJs == true) {
		?>
		<script src="/vendor/vue/vue.min.js"></script>
		<?php
	}
	?>

	<script>
		function selectLocation() {
			let defaultLocation = localStorage.getItem('location');
			let defaultLocationId = localStorage.getItem('locationId');
			let defaultLocationUrl = localStorage.getItem('locationUrl');
			if (!defaultLocation) {
				showLocationPopup();
			} else {
				if (location.host !== defaultLocationUrl) {
					goToLocation(defaultLocationId, defaultLocation, defaultLocationUrl);
				}
			}
		}

		function goToLocation(locationId, title, url) {
			setLocation(locationId, title, url);
			window.location = '//'+url+'/sites/setLocation/'+locationId+'<?= $locationQueryParam ?>';
		}

		function setLocation(locationId, title, url) {
			localStorage.setItem('locationId', locationId);
			localStorage.setItem('location', title);
			localStorage.setItem('locationUrl', url);
		}
	</script>

	<?php
	if ($showLocationPopup === false && isset($siteLocations[$locationId]) && !empty($siteLocations[$locationId])) {
		$linkedLocation = $siteLocations[$locationId];
		$locationTitle = $linkedLocation['title'];
		$locationUrl = $linkedLocation['url'];
		?>
		<script>
			if (!localStorage.getItem('locationId')) {
				setLocation('<?= $locationId ?>', '<?= $locationTitle ?>', '<?= $locationUrl ?>');
			}
		</script>
		<?php
	}
	?>
</head>

<body class="bg-dark" onbeforeunload="showLoadingBar()">
	<div class="bg-white ">

		<?php
		if(!empty($andriodAppBadgeUrl) || $showLocationOptions) {
			?>
			<nav class="navbar navbar-expand-lg navbar-static navbar-light">
				<div class="container-fluid justify-content-between">
					<div>
						<?php
						if ($andriodAppBadgeUrl && !$this->Session->read('isMobileApp')) {
							?>
							<?= $andriodAppBadgeUrl ?>
							<?php
							}
						?>
					</div>

					<?php
					if ($showLocationOptions) {
					?>
					<div onclick="showLocationPopup()">
						<div role="button" class="">
							<i class="fa fa-map-marker-alt text-danger"></i> <h6 id="locationTitleSpan" class="d-inline"></h6>
							<span class="d-inline nav-link p-1 text-danger"><i class="fa fa-caret-down"></i></span>
						</div>
					</div>
					<?php
					}
					?>
				</div>
			</nav>
			<?php
		}
		?>

		<nav class="navbar navbar-expand-lg navbar-static <?= $navbarTheme ?>">
			<div class="container">
				<a class="navbar-brand" href="/">
					<?php
					if ($logoUrl) {
						?>
							<img
								src="<?= $logoUrl ?>"
								alt="<?= $this->Session->read('Site.title') ?>"
								title="<?= $this->Session->read('Site.title') ?>"
								class="img-fluid"
								width="<?= (int)$this->Session->read('Site.logo_width') > 0 ? (int)$this->Session->read('Site.logo_width') : 200 ?>"
								height="<?= (int)$this->Session->read('Site.logo_height') > 0 ? (int)$this->Session->read('Site.logo_height') : 50 ?>"
							>
						<?php
					} else {
						?>
						<i class="fa fa-home"></i> <?= $this->Session->read('Site.title') ?>
						<?php
					}
					?>
				</a>

				<div class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fa fa-bars navbar-brand"></i>
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
									<a class="nav-link px-1 <?= $hightlightLink ?> highlight-link" href="/users/setView/seller"><i class="fa fa-tools"></i> Manage Store</a>
								</li>
								<?php
							}
							?>
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
									<a class="nav-link px-1" href="<?= $andriodAppUrl ?>"><i class="fa fa-download"></i> Download App</a>
								</li>
								<?php
							}
							*/
							?>
							<?php if (!$this->Session->check('User.id')): ?>
								<li class="nav-item px-1">
									<a class="nav-link px-1" href="/users/customerRegistration">Register</a>
								</li>
							<?php endif; ?>


							<?php if ($this->Session->check('User.id')): ?>

								<?php if ($this->Session->read('Site.shopping_cart')): ?>
								<li class="nav-item px-1">
									<a class="nav-link px-1" href="/orders/">My Orders</a>
								</li>
								<?php endif; ?>

								<li class="nav-item dropdown px-1">
									<a class="nav-link dropdown-toggle" href="#" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-user-circle"></i>
										<?= $this->Session->read('User.firstname')!= '' ? $this->Session->read('User.firstname') : $this->Session->read('User.mobile') ?>
									</a>
									<ul class="dropdown-menu" aria-labelledby="offcanvasNavbarDropdown">
										<li class="nav-item px-1"><a class="nav-link px-1" href="/users/logout">Logout</a></li>
									</ul>
								</li>
							<?php else: ?>
								<li class="nav-item px-1">
									<a class="nav-link px-1" href="/users/login">Login</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</nav>

		<div class="sticky-top shadow <?= $secondaryMenuBg ?>" style="z-index: 999">
			<ul class="nav container justify-content-between pt-2 pb-2">
				<li class="nav-item">
					<a href="#" class="nav-link <?= $linkColor ?>" data-bs-toggle="offcanvas" data-bs-target="#categoriesMenu">
						<i class="fa fa-bars-staggered"></i> Products
					</a>
				</li>
				<?php if ($this->Session->read('Site.shopping_cart')): ?>
					<li class="nav-item" id="topNavShoppingCart">
						<a href="#" class="nav-link <?= $linkColor ?>" data-bs-toggle="offcanvas" data-bs-target="#myShoppingCart">
							<i class="fa fa-cart-shopping"></i> My Cart <span class="badge rounded-pill <?= $cartBadgeBg ?>">0</span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
			<div class="progress rounded-0 d-none" id="topNavProgressBar">
				<div
					class="progress-bar progress-bar-striped progress-bar-animated bg-orange small"
					role="progressbar"
					aria-valuenow="100"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width: 100%">Loading...</div>
			</div>
		</div>

		<?= $this->element('banner_slideshow') ?>


		<div class="container mt-4" style="min-height: 500px;">

			<?= $this->fetch('content') ?>

			<?= $this->element('testimonials_slideshow') ?>


			<?php
			$showPaymentContactInfo = false;

			if ($this->request->params['controller'] != 'users'
					&& $this->request->params['controller'] != 'sites'
					&& $this->request->params['controller'] != 'orders') {
				$showPaymentContactInfo = true;
			}

			if ($showPaymentContactInfo && !empty($this->Session->read('Site.contact_info'))):
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
			if ($showPaymentContactInfo && !empty($this->Session->read('Site.payment_info'))):
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
				<div class="small">
					<a class="nav-link small" href="/" title="Show all products">
						<i class="fa fa-home"></i> Home
					</a>
				</div>
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
					<h5 id="offcanvasTopLabel"><i class="fa fa-cart-shopping"></i> My Cart</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="myShoppingCartBody"></div>
			</div>

			<!-- Order Summary -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderSummary" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="fa fa-info-circle"></i> Order Summary</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderSummaryBody"></div>
			</div>

			<!-- Order delivery details -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderDeliveryDetails" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="fa fa-shipping-fast"></i> Delivery Details</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderDeliveryDetailsBody"></div>
			</div>

			<!-- Order payment details -->
			<div class="offcanvas offcanvas-end" tabindex="-1" id="orderPaymentDetails" aria-labelledby="offcanvasTopLabel">
				<div class="offcanvas-header border-bottom border-4 border-warning">
					<h5 id="offcanvasTopLabel"><i class="fa fa-wallet"></i> Payment Details</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body" id="orderPaymentDetailsBody"></div>
			</div>

			<!-- Product Details -->
			<div class="modal fade" id="productDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="productDetailsLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="productDetailsLabel">Product Details</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body" id="productDetailsBody">
							<div class="text-center">
								<div class="spinner-border text-primary" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Product Quantity -->
			<div class="modal" id="addProductQty" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductQtyLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body" id="addProductQtyBody">
							<div class="d-flex justify-content-between">
								<h6 class="modal-title" id="addProductQtyLabel">Select Quantity</h6>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<table class="table table-borderless table-sm small mt-4 mb-2">
								<tbody>
								<tr>
									<td>
										<select id="addProductQtyModal-quantity"
												name="addProductQtyModal-quantity"
												class="form-select form-select-sm"
										>
											<?php foreach(range(1,10) as $qty): ?>
												<option value="<?= $qty ?>"><?= $qty ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td style="width: 50px;">
										<button
												id="addProductQtyModal-saveButton"
												class="btn btn-primary btn-sm ms-2"
												onclick="saveProductQtyToCart()">
											Add
										</button>
									</td>
									<td style="width:45px">
										<div id="addProductQtyModal-spinner" class="d-none">
											<div class="spinner-border spinner-border-sm mt-2 text-primary" role="status">
												<span class="visually-hidden">Loading...</span>
											</div>
										</div>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
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

							<div class="mt-4 text-center d-none" id="confirmPopupBuyerSpinner">
								<div class="spinner-border spinner-border-sm text-primary" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
								<br>
								Please wait. Your request is in process.
							</div>
						</div>
						<div class="modal-footer mt-3 p-1">
							<a href="#" class="actionLink btn btn-danger btn-sm me-2 w-25" onclick="$('#confirmPopupBuyerSpinner').removeClass('d-none')"><span class="ok">Ok</span></a>
							<button type="button" class="actionLinkButton btn btn-danger btn-sm me-2" data-bs-dismiss="modal"><span
										class="ok">Ok</span></button>
							<button type="button" class="btn btn-outline-secondary btn-sm cancelButton w-25" data-bs-dismiss="modal">
								Cancel
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- delete product from cart popup -->
			<div class="modal fade" id="deleteProductFromCartPopup" data-bs-backdrop="static" data-bs-keyboard="false"
				 aria-labelledby="deleteProductFromCartModal" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body">
							<div class="d-flex justify-content-between">
								<div>
									Are you sure you want to delete this product?
									<div id="deleteProductFromCartPopupProductName" class="fw-bold mt-3"></div>
								</div>
								<div class="ms-2">
									<button
											type="button"
											class="btn-close p-2"
											data-bs-dismiss="modal"
											aria-label="Close"
											onclick="deleteProductFromCartPopup.hide(); myShoppingCart.show();">
										<span aria-hidden="true"></span>
									</button>
								</div>
							</div>
						</div>
						<div class="modal-footer mt-2 p-1">
							<a href="#" class="deleteLink btn btn-danger btn-sm me-3 w-25" onclick="deleteProductFromCart()"><span class="ok">Ok</span></a>
							<button
									type="button"
									class="btn btn-outline-secondary btn-sm w-25"
									data-bs-dismiss="modal"
									onclick="deleteProductFromCartPopup.hide(); myShoppingCart.show();">Cancel</button>
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

			<!-- Show login popup in place order section -->
			<div class="modal fade" id="placeOrderLoginPopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="placeOrderLoginPopupLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<?php if((bool)$this->Session->read('Site.sms_notifications') === true) { ?>
							<div>
								<?php echo $this->Form->create('User', ['url' => '/users/otpVerification', 'onsubmit' => "verifyOtp()"]); ?>
								<button type="submit" disabled style="display: none" aria-hidden="true"></button>
								<h1>Verify OTP</h1>

								<div class="mt-5">
									<input
										type="number"
										name="data[User][otp]"
										class="form-control"
										id="UserVerifyOtp"
										placeholder="Enter OTP"
										min="1000"
										max="9999"
										required
										autofocus
										autocomplete="off"
									>
								</div>

								<div class="text-danger mt-2 small">
									<?php
									$text = "*OTP is sent to your Email Address.";
									if((bool)$this->Session->read('Site.sms_notifications') === true) {
										$text = "*OTP is sent to your Mobile no. and Email Address provided in Order details";
									}
									echo $text;
									?>
								</div>

								<div class="mt-4">
									<button type="button" class="btn btn-md btn-primary" id="orderVerifyOtpButton" onclick="verifyOtp()">Next - Verify OTP</button>
								</div>

								<?php echo $this->Form->end(); ?>
							</div>
							<?php } else { ?>
								<div>If you are already registered, please <a href="/users/login" class="text-orange">login</a> to place an Order.</div>

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
							<?php if((bool)$this->Session->read('Site.sms_notifications') === false) { ?>
							<a href="/users/login" role="button" class="btn btn-orange">Go to Login Page</a>
							<?php } ?>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>

			<?php
			if ($showLocationPopup && !empty($siteLocations)) {
			?>
			<!-- Location Modal -->
			<div class="modal fade" id="locationBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="locationBackdropLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="locationBackdropLabel">Select Location</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body pb-5">
							<div>We currently serve in the following areas. Choose your location of interest.</div>

								<div class="list-group  list-group-flush mt-3 fw-bold">
								<?php
								foreach($siteLocations as $id => $row) {
								?>
									<a href="#" onclick="goToLocation('<?= $id ?>', '<?= $row['title'] ?>', '<?= $row['url'] ?>');" class="list-group-item list-group-item-action py-3">
										<i class="fa fa-map-marker-alt text-danger"></i> <?= $row['title'] ?>
									</a>
								<?php
								}
								?>
								</div>

						</div>
					</div>
				</div>
			</div>
			<?php
			}
			?>
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
		</footer>
	</div>

	<div class="container">
		<?php echo $this->element('sql_dump'); ?>
	</div>

	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
	<script src="/vendor/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>
	<script src="/vendor/jquery.lazy-master/jquery.lazy.min.js" defer></script>
	<script src="/vendor/lightbox2-2.11.3/dist/js/lightbox.min.js" defer></script>
	<script src="/js/site.js?v=1.2.7" defer></script>
	<?php
	echo $this->element('customjs', ['showLocationPopup' => $showLocationPopup]);
	echo $this->element('footerscripts');
	?>
</body>
</html>
