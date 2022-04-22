<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $this->Session->read('Site.title') ?></title>

	<link rel="stylesheet" href="/vendor/bootstrap-5.0.0-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/vendor/fontawesome-free-5.15.3-web/css/all.min.css">
	<link rel="stylesheet" href="/css/site.css?v=1.0.0">
	<?= $this->element('customcss') ?>
</head>

<body>

<nav class="navbar navbar-dark bg-dark bg-gradient">
	<div class="container">
		<a class="navbar-brand text-truncate" href="#"><?= $this->Session->read('Site.title') ?></a>
	</div>
</nav>

<div class="container">
	<?php echo $this->Session->flash(); ?>
</div>

<div class="container mt-3">
	<?php echo $this->fetch('content'); ?>
</div>

<div class="container">
	<!-- footer -->
</div>

</body>
</html>
