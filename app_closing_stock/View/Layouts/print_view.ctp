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
	<link rel="stylesheet" href="<?php echo $this->Html->url('/css/normalize.css', true); ?>">
	<link rel="stylesheet" href="<?php echo $this->Html->url('/css/main.css', true); ?>">
	<?php
	echo $this->Html->css('print');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
	<!-- jQuery JS -->
	<script type="text/javascript" src="<?php echo $this->Html->url('/js/jquery-3.2.1.min.js'); ?>"></script>
</head>
<body>
<div id="content">
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
</div>
</body>
</html>
