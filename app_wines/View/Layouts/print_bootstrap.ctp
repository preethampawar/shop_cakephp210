<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Preetham Pawar, Shankar Naik">
	<meta name="theme-color" content="#7952b3">

	<title><?php echo $this->Session->read('Store.name') . ' - ' .$title_for_layout; ?></title>
	<link rel="icon" type="image/gif" href="/img/stats.gif" crossorigin="anonymous">
	<link rel="stylesheet" href="/vendor/bootstrap-5.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $this->Html->url('/css/main.css', true);?>">
	<?php
	//echo $this->Html->css('print');
	?>

	<script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
	<style>
		h1 {
			font-size: 1.6rem;
		}
		h2 {
			font-size: 1.5rem;
		}
		h3 {
			font-size: 1.4rem;
		}
		h4 {
			font-size: 1.3rem;
		}
		h5 {
			font-size: 1.2rem;
		}
		h6 {
			font-size: 1.1rem;
		}
	</style>
</head>
<body class="small">

<main>
	<div class="text-end d-print-none">
		<button type="button" class="btn btn-light btn-sm" onclick="window.print()">&#x1F5B6; Print</button>
	</div>
	<div class="container-fluid">
		<?= $this->Session->read('Store.print_header') ?>
		<?php echo $this->fetch('content'); ?>
		<?= $this->Session->read('Store.print_footer') ?>
	</div>
</main>


</body>
</html>
