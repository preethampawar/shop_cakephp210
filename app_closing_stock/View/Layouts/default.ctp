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
	<link href="/vendor/bootstrap-5.0.0-alpha1-dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/site.css" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

    <!-- Bootstrap core JavaScript -->
	<script src="/vendor/jquery/jquery.slim.min.js"></script>
</head>

<body>

<?php if ($this->Session->check('Store.name')) { ?>
	<nav class="navbar navbar-dark bg-purple py-0">
		<div class="container">
			<a class="navbar-brand text-truncate" href="#"><?php echo $this->Session->read('Store.name'); ?></a>
		</div>
	</nav>
<?php } ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-1">
	<div class="container">
        <div>
            <div class="d-inline navbar-toggler py-0 px-0 border-0 text-white" type="button" data-toggle="collapse" data-target="#navbarNav"
                 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </div>
            <div class="d-inline pl-2" data-toggle="collapse" data-target="#navbarNav"
                 aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <a class="navbar-brand" href="#">
                    SimpleAccounting
                </a>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
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
							<a class="nav-link" href="/reports/dayWiseStockReport"><i class="fas fa-clipboard-list"></i> Show
								Custom Stock Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/reports/completeStockReport"><i class="fas fa-clipboard-list"></i> Show
								Complete Stock Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/reports/completeStockReportChart/store_performance">
                                <i class="fas fa-chart-bar"></i> My Store Performance Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/reports/completeStockReportChart/top_performing_products">
                                <i class="fas fa-chart-pie"></i> Top Performing Products Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/reports/completeStockReportChart/sales_purchases_profit">
                                <i class="fas fa-chart-line"></i> Sales, Purchases & Profit on sales Report</a>
						</li>
						<?php
					}
					?>
					<li class="nav-item  border-top">
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
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>

<!-- Navigation -->

<!-- Page Content -->
<div class="container">
	<div class="row">
		<div class="col-lg-12 text-left mt-2">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
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


<script src="/vendor/popper.js"></script>
<script src="/vendor/bootstrap-5.0.0-alpha1-dist/js/bootstrap.bundle.min.js"></script>

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
