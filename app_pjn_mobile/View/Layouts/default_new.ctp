<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>
		SimpleAccounting ::
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true); ?>">

	<!-- Bootstrap core CSS -->
	<link href="/vendor/bootstrap-5.0.0-alpha1-dist/css/bootstrap.min.css" rel="stylesheet">
	<!--        <link href="/css/site.css" rel="stylesheet">-->

	<!-- Bootstrap core JavaScript -->
	<script src="/vendor/jquery/jquery.slim.min.js"></script>


	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>

</head>

<body class="small">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
	your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
	improve your experience.</p>
<![endif]-->


<?php
$showHeader = true;
if (isset($hideHeader) and ($hideHeader)) {
	$showHeader = false;
}
?>

<div id="content" class="container-fluid">
	<?php if ($showHeader) { ?>
		<header id="header" class="bg-light p-2 border-bottom border-secondary">
			<h1 style="float:left;">
				<?php echo ($this->Session->check('Store')) ? strtoupper($this->Session->read('Store.name')) : 'SimpleAccounting.in'; ?>
			</h1>
			<?php if ($this->Session->check('Auth.User')) { ?>
				<div style="float:right;">
					<?php
					echo $this->Html->link('My Stores', ['controller' => 'stores', 'action' => 'index']);
					echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
					echo $this->Html->link('Logout', ['controller' => 'users', 'action' => 'logout']);
					?>
				</div>
			<?php } ?>
			<div style="clear:both;"></div>
			<?php if ($this->Session->check('Auth.User')) { ?>
				<nav>

					<?php
					if ($this->Session->check('Store')) {
						?>
						<?php echo $this->Html->link('Home', ['controller' => 'stores', 'action' => 'home']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Products', ['controller' => 'product_categories', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Brands', ['controller' => 'brands', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Invoices', ['controller' => 'invoices', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<!--
						<?php echo $this->Html->link('Closing Stock', ['controller' => 'sales', 'action' => 'viewClosingStock']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Breakage Stock', ['controller' => 'breakages', 'action' => 'viewBreakageStock']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						-->
						<?php echo $this->Html->link('Purchases', ['controller' => 'purchases', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Sales', ['controller' => 'sales', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Cashbook', ['controller' => 'cashbook', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<!--
						<?php echo $this->Html->link('Counter Balance Sheets', ['controller' => 'CounterBalanceSheets', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Employees', ['controller' => 'employees', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Dealers', ['controller' => 'dealers', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Bank Book', ['controller' => 'banks', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						-->
						<?php
						if ($this->Session->check('StoreSetting.hasFranchise') && $this->Session->read('StoreSetting.hasFranchise') == 1) {
							echo $this->Html->link('Franchise', ['controller' => 'franchises', 'action' => 'index']);
							echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
						}
						?>

						<?php echo $this->Html->link('Invoices/Quotations', ['controller' => 'invoice_quotations', 'action' => 'index']); ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Reports', ['controller' => 'reports', 'action' => 'home']); ?>
						<?php
					}
					?>
				</nav>
			<?php } ?>
		</header>
	<?php } ?>

	<?php
	$showSideBar = true;
	$class = "contentBar";
	if (isset($hideSideBar) and ($hideSideBar == true)) {
		$showSideBar = false;
		$class = "properMargin";
	}
	?>
	<div class="row p-2 mt-2">
		<?php
		if ($showSideBar) {
			?>
			<div class="col-xs-3 col-sm-3 col-lg-2">
				<div id="leftSideBar">
					<nav>
						<?php
						// reports menu
						if ($this->fetch('reports_menu')):
							echo $this->fetch('reports_menu');
						endif;

						// stock report menu
						if ($this->fetch('stock_reports_menu')):
							echo $this->fetch('stock_reports_menu');
						endif;

						// sales report menu
						if ($this->fetch('sales_report_menu')):
							echo $this->fetch('sales_report_menu');
						endif;

						// purchases report menu
						if ($this->fetch('purchases_report_menu')):
							echo $this->fetch('purchases_report_menu');
						endif;

						// invoices report menu
						if ($this->fetch('invoices_report_menu')):
							echo $this->fetch('invoices_report_menu');
						endif;

						// employees report menu
						if ($this->fetch('employees_report_menu')):
							echo $this->fetch('employees_report_menu');
						endif;

						// dealers report menu
						if ($this->fetch('dealers_report_menu')):
							echo $this->fetch('dealers_report_menu');
						endif;

						// bank report menu
						if ($this->fetch('bank_menu')):
							echo $this->fetch('bank_menu');
						endif;

						// quotations menu
						if ($this->fetch('quotation_menu')):
							echo $this->fetch('quotation_menu');
						endif;

						// franchise menu
						if ($this->fetch('franchise_menu')):
							echo $this->fetch('franchise_menu');
						endif;

						// cashbook menu
						if ($this->fetch('cashbook_menu')):
							echo $this->fetch('cashbook_menu');
						endif;

						?>
					</nav>
					<br>
				</div>
			</div>
			<?php
		}
		?>
		<div <?php if ($showSideBar) { ?> class="col-xs-9 col-sm-9 col-lg-10" <?php } ?>>
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<?php
	/*
	?>
	<div class="<?php echo $class;?>">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
	<?php
	*/
	?>
	<div class="clear"></div>
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


<!-- select2 CSS -->
<link rel="stylesheet" href="<?php echo $this->Html->url('/select2/select2.min.css'); ?>">
<!-- select2 JS -->
<script type="text/javascript" src="<?php echo $this->Html->url('/select2/select2.min.js'); ?>"></script>
<!-- html table search JS -->

<script type="text/javascript"
		src="<?php echo $this->Html->url('/html-table-search/html-table-search.js'); ?>"></script>

<script>
	// In your Javascript (external .js resource or <script> tag)
	$(document).ready(function () {
		if ($('.autoSuggest').length) {
			$('.autoSuggest').select2();
		}
	});
</script>
<!-- <script src="/vendor/fa.js" crossorigin="anonymous"></script> -->

<?php
// enable text editor
if (isset($enableTextEditor) && $enableTextEditor) {
	echo $this->element('text_editor');
} else {
	?>
	<script src="/vendor/bootstrap-5.0.0-alpha1-dist/js/bootstrap.bundle.min.js"></script>
	<?php
}
?>
<?php echo $this->element('sql_dump'); ?>

</body>
</html>
