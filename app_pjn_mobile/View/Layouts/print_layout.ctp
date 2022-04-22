<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true); ?>">

	<link rel="stylesheet" href="<?php echo $this->Html->url('/css/tailwindcss.css'); ?>">
	<link rel="stylesheet" href="<?php echo $this->Html->url('/bootstrap-3.3.7/dist/css/bootstrap.min.css'); ?>">

	<style type="text/css">
		h1 {
			font-size: 200%;
		}

		h2 {
			font-size: 175%;
		}

		h3 {
			font-size: 150%;
		}

		h4 {
			font-size: 125%;
		}

		@media print {
			.noprint {
				display: none;
			}

			#printableTable {
				display: block;
			}
		}

		@page {
			margin: 0 -6cm;
			page-break-before: always;
		}

		html {
			margin: 0 6cm
		}
	</style>

	<script>
		function printDiv() {
			window.print();
		}
	</script>

	<!-- jQuery JS -->
	<!--		<script type="text/javascript" src="-->
	<?php //echo $this->Html->url('/js/jquery-3.2.1.min.js');?><!--"></script>-->

</head>
<body>
<div class="container">
	<?php echo $this->fetch('content'); ?>
</div>
</body>
</html>
