<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>
	<?php 
	if(!empty($title_for_layout)) {
		echo $title_for_layout.' - '.$this->Session->read('Site.title');
	}
	else {
		$siteCaption = $this->Session->read('Site.caption');
		$title_for_layout = $this->Session->read('Site.title');
		$title_for_layout.=(!empty($siteCaption)) ? ' - '.$siteCaption : '';
		echo $title_for_layout;
	}
	?>
</title>
<meta charset="utf-8">
<?php 
	// CSS
	echo $this->Html->css('styles/layout');
	
	// Javascript
	// echo '<!--[if lt IE 9]>'.$this->Html->script('scripts/html5shiv').'<![endif]-->';
	// echo $this->Html->script('jquery-1.7.2.min');
	echo $this->fetch('meta');	
	// echo (isset($customMeta)) ? $customMeta : null;
	// echo (isset($facebookMetaTags)) ? $facebookMetaTags : null;
	
	echo $this->fetch('css');
	echo $this->fetch('script');	
?>	
</head>
<body>
	<div class="wrapper row1">
		<?php
		echo $this->element('header_suspended');
		?>
	</div>
	
	<!-- content -->
	<div class="wrapper row3" style="height:200px;">
	  <div id="container" class="clear">
			<?php echo $this->Session->flash(); ?>		
		
			<!-- content body -->
			<?php echo $this->fetch('content'); ?>
			<!-- / content body -->
	  </div>
	</div>
	<!-- / content body -->
	
	<!-- Copyright -->
	<div class="wrapper row5">
	  <footer id="copyright" class="clear">
		<p class="fl_left">
			<a href="http://www.w3.org/html/logo/">
			<img src="http://www.w3.org/html/logo/badge/html5-badge-h-performance-semantics.png" width="120" height="40" alt="HTML5 Powered with Performance &amp; Integration, and Semantics" title="HTML5 Powered with Performance &amp; Integration, and Semantics">
			</a>
		</p>
		<p class="fl_left" style="margin-left:160px"><br>Copyright &copy; 2012 - All Rights Reserved - <?php echo $this->Html->link($this->request->host(), $this->Html->url('/', true));?></p>
		<p class="fl_right"><br>Powered by <?php echo $this->Html->link(Configure::read('BaseDomain'), Configure::read('BaseDomainUrl'), array('title'=>Configure::read('BaseDomain'), 'style'=>'padding:2px; border:1px solid #888;', 'target'=>'_blank'));?></p>
	  </footer>
	</div>	
	<!-- / Copyright -->	
	
</body>
</html>

