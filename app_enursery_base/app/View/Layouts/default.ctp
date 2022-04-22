<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<title>LetsGreenify<?php echo ($title_for_layout) ? ': ' . $title_for_layout : ''; ?></title>
	<meta charset="utf-8">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<?php
	echo $this->Html->css('styles/navi');
	echo $this->Html->css('styles/forms');
	echo $this->Html->css('styles/tables');
	echo $this->Html->css('styles/homepage');
	echo $this->Html->css('styles/layout');
	echo $this->Html->css('styles/custom');

	// Javascript
	echo '<!--[if lt IE 9]>' . $this->Html->script('scripts/html5shiv') . '<![endif]-->';

	// echo $this->Html->script('scripts/jquery-easySlider1.7');
	?>
	<meta property="fb:admins" content="530846121"/>
	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	echo '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">';
	?>
    <script data-ad-client="ca-pub-1985514378863670" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f575f846b5d9900194e2a66&product=sop' async='async'></script>
</head>
<body>
	<div class="wrapper row1">
		<?php
		echo $this->element('header');
		?>
	</div>
	<!-- content -->
	<div class="wrapper row2">
		<div id="container" class="clear">
			<?php echo $this->Session->flash(); ?>

			<?php
			print($this->Session->read('SearchEngineCode'));
			?>

			<?php echo $this->fetch('content'); ?>

            <div style="margin-top:15px; margin-bottom:15px;">
                <div class="sharethis-inline-share-buttons"></div>
            </div>
		</div>
		<div class="clear"></div>

	</div>
	</div>
	<!-- / content body -->
	<!-- Footer -->
	<div class="wrapper row3">
		<div id="footer" class="clear">
			<?php echo $this->element('footer'); ?>
		</div>
	</div>
	<!-- / Footer -->

	<!-- Copyright -->
	<div class="wrapper row4">
		<footer id="copyright" class="clear" style="text-align:center; margin:auto;">
			<p class="privacypolicyParagraph">
				<a href="<?php echo $this->Html->url('/privacypolicy.htm'); ?>" target='_blank' style="color:#ff9900;">Privacy
					Policy</a>
				&nbsp;&nbsp; &nbsp;&nbsp;
				<a href="<?php echo $this->Html->url('/tos.htm'); ?>" target='_blank' style="color:#ff9900;">Terms of
					Service</a>
				<br/><br/>
				Copyright &copy; <?php echo date('Y'); ?> - All Rights Reserved
				- <?php echo $this->Html->link('letsgreenify.com', '/'); ?>
			</p>
		</footer>
	</div>
	<!-- / Copyright -->

	<?php
	echo $this->Html->script('scripts/jquery-PseudoCSS.1.0');
	echo $this->element('customjs');
	?>
</body>
</html>
