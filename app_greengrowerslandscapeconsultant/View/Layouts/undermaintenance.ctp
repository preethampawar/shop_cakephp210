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
	echo $this->fetch('meta');	
	echo $this->fetch('css');
	echo $this->fetch('script');	
?>	
</head>
<body>
	
	<div class="wrapper row1">
		<?php
		echo $this->element('header_under_maintenance');
		?>
	</div>
	<div class="wrapper row2">
		<?php echo $this->element('top_nav_menu');?>
		
	</div>
	
	<!-- content -->
	<div class="wrapper row3">
	  <div id="container" class="clear">
			<?php echo $this->Session->flash(); ?>	
			<!-- content body -->
			<?php echo $this->fetch('content'); ?>
			<!-- / content body -->			
	  </div>
	</div>
	<!-- / content body -->
	<!-- Footer -->
	<?php if(!isset($this->request->params['admin'])) { ?>
	
	<?php } ?>
	<!-- / Footer -->
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
	<?php
	echo $this->element('customjs');
	?>
	
	<?php /* Request Price Quote Form */ ?>
	<div style="display:none;" id="RPQF-Div">
		<h2 id="RPQF-ProductName"></h2>
		<br>
		<?php echo $this->Form->create(null, array('id'=>'RPQF')); ?>				
		<div class="floatLeft" style="width:100px; margin-right:10px;">
			<?php 
			$qtyOptions = Configure::read('Product.quantity');
			echo $this->Form->input('ShoppingCartProduct.quantity', array('options'=>$qtyOptions, 'empty'=>false));
			?>
		</div>	
		<div class="floatLeft" style="width:100px; margin-right:10px;">
			<?php 
			$sizeOptions = Configure::read('Product.size');
			echo $this->Form->input('ShoppingCartProduct.size', array('options'=>$sizeOptions, 'empty'=>'-'));
			?>
		</div>			
		<div class="floatLeft" style="width:150px; margin-right:10px;">
			<?php 
			$ageOptions = Configure::read('Product.age');
			echo $this->Form->input('ShoppingCartProduct.age', array('options'=>$ageOptions, 'empty'=>'-'));
			?>
		</div>
		<div class="floatLeft" style="margin-right:10px;">
			<br>
			<?php echo $this->Form->submit('Submit &raquo;', array('escape'=>false));?>
		</div>
		<div class='clear'></div>			
		<?php echo $this->Form->end();?>
	</div>	
	<?php /** --> End of Request Price Quote Form */ ?>
	<?php 
	if(!$this->Session->check('User.id')) { 
		echo $this->Session->read('Site.analytics_code');
	} 
	?>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
</body>
</html>

