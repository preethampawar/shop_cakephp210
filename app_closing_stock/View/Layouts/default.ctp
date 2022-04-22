<?php
$storeTitle = $this->Session->check('Store.name') ? $this->Session->read('Store.name') : 'Simple Accounting';
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true); ?>">

	<title>SimpleAccounting</title>

	<!-- Bootstrap core CSS -->
	<link href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet">

	<link href="/css/site.css" rel="stylesheet">

	<link href="/vendor/fontawesome-free-6.0.0-beta2-web/css/all.min.css" rel="stylesheet">
<!--	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">-->

    <!-- Bootstrap core JavaScript -->
	<script src="/vendor/jquery/jquery.slim.min.js"></script>
</head>

<body>
	<nav class="navbar navbar-dark bg-purple">
		<div class="container">
			<div class="p-0">
				<span class="navbar-toggler p-0 border-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
					<i class="fa fa-bars"></i>
				</span>
				<a class="navbar-brand ms-2 text-truncate" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
					<span class="small"><?= $storeTitle ?></span>
				</a>
			</div>
			<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
				<div class="offcanvas-header text-secondary p-2">
					<h5 class="offcanvas-title" id="offcanvasNavbarLabel">Simple Accounting</h5>
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>
				<div class="offcanvas-body bg-dark">
					<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
						<?php
						if ($this->Session->check('Auth.User')) {
							?>
							<?php
							if ($this->Session->check('Store.name')) {
								?>
								<li class="nav-item active">
									<a class="nav-link" href="/stores/home"><i class="fa fa-home"></i> Home</span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/products/"><i class="fa fa-object-group"></i> Products</span></a>
								</li>
								<li class="nav-item border-top">
									<a class="nav-link" href="/sales/addClosingStockMobile"><i class="fa fa-plus-circle"></i>
										Add Closing Stock</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/sales/viewClosingStock"><i class="fas fa-clipboard-list"></i> Show
										Closing Stock Report</a>
								</li>
								<li class="nav-item border-top">
									<a class="nav-link" href="/invoices/add"><i class="fa fa-plus-circle"></i> Add New
										Invoice</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/invoices/"><i class="fa fa-list-alt"></i> Show Invoice List</a>
								</li>
								<li class="nav-item border-top">
									<a class="nav-link" href="/cashbook/"><i class="fas fa-clipboard-list"></i> Manage Expenses
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/reports/cashbookReport"><i class="fa fa-list-alt"></i> Expenses Report</a>
								</li>
								<li class="nav-item border-top">
									<a class="nav-link" href="/transactions/"><i class="fas fa-clipboard-list"></i> Manage Transactions
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/TransactionCategories/"><i class="fa fa-clipboard-list"></i> Transaction Categories</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/reports/transactionsReport"><i class="fa fa-list-alt"></i> Transactions Report</a>
								</li>
								<li class="nav-item border-top">
									<a class="nav-link" href="/reports/dayWiseStockReport"><i class="fas fa-clipboard-list"></i> Show
										Daywise Stock Report</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/reports/completeStockReport"><i class="fas fa-clipboard-list"></i> Show
										Complete Stock Report</a>
								</li>
								<li class="nav-item d-none">
									<a class="nav-link" href="/reports/completeStockReportChart/store_performance">
										<i class="fas fa-chart-bar"></i> My Store Performance Report</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/reports/completeStockReportChart/top_performing_products">
										<i class="fas fa-chart-pie"></i> Top Performing Products Report</a>
								</li>
								<li class="nav-item d-none">
									<a class="nav-link" href="/reports/completeStockReportChart/sales_purchases_profit">
										<i class="fas fa-chart-line"></i> Sales, Purchases & Profit on sales Report</a>
								</li>
								<?php
							}
							?>
							<li class="nav-item  <?= $this->Session->check('Store.name') ? 'border-top' : '' ?>">
								<a class="nav-link" href="/stores/"><i class="fa fa-store"></i> My Stores</span>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#" onclick="location.reload(true);"><i class="fa fa-sync-alt"></i>
									Refresh App</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="/users/logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
							</li>

							<?php
						} else { ?>
							<li class="nav-item">
								<a class="nav-link" href="#" onclick="location.reload(true);"><i class="fa fa-sync-alt"></i>
									Refresh App</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="/users/login"><i class="fa fa-sign-in-alt"></i> Login</a>
							</li>
						<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</nav>

	<!-- Navigation -->

	<!-- Page Content -->
	<div class="container mt-2">
		<?php
		if ($this->Session->check('showExpiryNotice') && $this->Session->check('showExpiryNotice') === true) {
			$storeExpiryDate = date('d-m-Y', strtotime($this->Session->read('Store.expiry_date')));
			$message = "This Store will expire on '$storeExpiryDate'. Contact software owner to renew this store before expiry date.";
			?>
			<div id="FlashMessage" class="notice alert alert-warning alert-dismissible" role="alert">
				<strong>Notice!</strong> <?php echo $message; ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
			<br>
			<?php
		}
		?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>

	</div>


	<style type="text/css">
		.select2-results ul {
			color: #333;
		}

		.select2-container--default .select2-selection--single {
			height: auto;
		}

		input:invalid {
			border: 1px solid #ff000085;
		}

		input:focus:invalid {
			border: 1px solid red;
		}

		input:focus:valid {
			border: 1px solid green;
		}
	</style>


<!--	<script src="/vendor/popper.js"></script>-->
	<!--<script src="/vendor/bootstrap-5.0.0-alpha1-dist/js/bootstrap.bundle.min.js"></script>-->
	<script src="/vendor/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

	<!-- select2 CSS -->
	<link rel="stylesheet" href="<?php echo $this->Html->url('/select2/select2.min.css'); ?>">
	<!-- select2 JS -->
	<script type="text/javascript" src="<?php echo $this->Html->url('/select2/select2.min.js'); ?>"></script>
	<!-- html table search JS -->

	<script>
		// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function () {
			if ($('.autoSuggest').length) {
				$('.autoSuggest').select2();
			}
		});
	</script>
	<!-- <script src="/vendor/fa.js" crossorigin="anonymous"></script> -->

	<?php echo $this->element('sql_dump'); ?>

</body>

</html>
