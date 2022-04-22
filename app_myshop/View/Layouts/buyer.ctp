<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $this->Session->read('Site.title') ?></title>

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">

	<!-- light box css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
		  integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
		  crossorigin="anonymous"/>

	<!-- custom css -->
	<?= $this->element('customcss') ?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<?php
	if ((isset($loadVueJs) && $loadVueJs == true) || $this->Session->read('Site.shopping_cart') == true) {
		?>
		<!-- development version, includes helpful console warnings -->
		<!--		<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>-->

		<!-- production version, optimized for size and speed -->
		<script src="https://cdn.jsdelivr.net/npm/vue"></script>
		<?php
	}
	?>

	<script src="https://kit.fontawesome.com/231b614f56.js" crossorigin="anonymous" async></script>
</head>

<body>

<nav class="navbar navbar-dark bg-primary active bg-gradient">
	<div class="container">
		<a class="navbar-brand text-truncate" href="#"><?= $this->Session->read('Site.title') ?></a>
	</div>
</nav>

<nav class="navbar p-0" role="navigation">
	<!-- navbar-side will go here -->
	<ul class="navbar-side navbar-nav bg-white text-dark px-2 text-left list-group" id="navbarSide">
		<?php echo $this->element('categories_menu'); ?>
	</ul>
	<div class="overlay"></div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark navbar-static bg-dark ">
	<div class="container">
		<div class="navbar-toggler border-0 p-1 py-0 text-white" type="button" data-toggle="collapse"
			 data-target="#navbarNav"
			 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="fa fa-bars"></span> Menu
		</div>
		<a class="navbar-brand" href="/">
			Home
		</a>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<?php
				if ($this->App->isSellerForThisSite()) {
					?>
					<li class="nav-item">
						<a class="nav-link" href="/users/setView/seller">Manage
							Store</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/users/myaccount">My Account</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/sites/contact">Contact</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/sites/paymentInfo">Payment Details</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/users/logout">Logout</a>
					</li>
					<?php
				} else { ?>
					<li class="nav-item">
						<a class="nav-link" href="/sites/contact">Contact</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/sites/paymentInfo">Payment Details</a>
					</li>
					<li class="nav-item">
						<?php if ($this->Session->check('User.id')): ?>
							<a class="nav-link" href="/users/logout">Logout</a>
						<?php else: ?>
							<a class="nav-link" href="/users/login">Login</a>
						<?php endif; ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>

<div class="bg-light border-bottom">
	<ul class="nav container justify-content-start">
		<li class="nav-item productSideBar">
			<a class="nav-link font-weight-normal" href="#"><i class="fa fa-chevron-circle-right"></i> Shop By Category</a>
		</li>
		<?php if ($this->Session->read('Site.shopping_cart')): ?>
			<li class="nav-item">
				<a class="nav-link font-weight-normal" href="#"><i class="fa fa-shopping-basket"></i> My Orders</a>
			</li>
		<?php endif; ?>
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
	<?php if ($this->Session->read('Site.shopping_cart')): ?>
		<div id="topNavShoppingCart"></div>
	<?php endif; ?>

	<?php echo $this->fetch('content'); ?>

	<?php
	$showPaymentContactInfo = false;

	if ($this->request->params['controller'] != 'users'
		&& $this->request->params['controller'] != 'sites') {
		$showPaymentContactInfo = true;
	}

	if ($showPaymentContactInfo && !empty($this->Session->read('Site.contact_info'))):
		?>
		<div class="text-center small alert alert-info">
			<h4 class="mb-3 text-decoration-underline">Contact</h4>
			<?= $this->Session->read('Site.contact_info') ?>
		</div>
	<?php
	endif;
	?>

	<?php
	if ($showPaymentContactInfo && !empty($this->Session->read('Site.payment_info'))):
		?>

		<div class="text-center small alert alert-info">
			<h4 class="mb-3 text-decoration-underline">Payment Details</h4>
			<?= $this->Session->read('Site.payment_info') ?>
		</div>
	<?php
	endif;
	?>

	<?php
	if ($showPaymentContactInfo && !empty($this->Session->read('Site.tos'))):
		?>
		<div class="text-center small alert alert-warning">
			Please read our <a href="/sites/tos">Terms of Service</a> before you place an order with us.
		</div>
	<?php
	endif;
	?>

	<br>

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
</div>

<div class="container">
	<?php echo $this->element('sql_dump'); ?>
</div>

<!-- Popper.js first, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
		integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
		crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.min.js"
		integrity="sha384-t6I8D5dJmMXjCsRLhSzCltuhNZg6P10kE0m0nAncLUjH6GeYLhRU1zfLoW3QNQDF"
		crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>

<!-- images zoom in - lightbox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
		integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
		crossorigin="anonymous" async></script>

<?= $this->element('customjs') ?>

</body>
</html>
