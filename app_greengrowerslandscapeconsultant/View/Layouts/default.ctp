<?php
// conditions to hide advert: if shopping cart is not empty, if in request price quote page, if user is logged in
	$shoppingCartIsEmpty = true;
	$userIsNotLoggedIn = true;
	$inRequestPriceQuotePage = false;
	$inLoginPage = false;
	
	App::uses('ShoppingCart', 'Model');
	$shoppingCartModel = new ShoppingCart;
	$shoppingCart = $shoppingCartModel->getShoppingCartProducts();

	if(isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
		$shoppingCartIsEmpty = false;
	}
	
	if($this->Session->check('User.id')) {
		$userIsNotLoggedIn = false;
	}
	
	if($this->request->params['controller'] == 'RequestPriceQuote') {
		$inRequestPriceQuotePage = true;
	}
	
	
	if(($this->request->params['controller'] == 'users') and ($this->request->params['action'] == 'login')) {
		$inLoginPage = true;
	}	
?>
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
	echo $this->Html->css('styles/layout.min');		
	echo $this->Html->css('styles/custom');
	echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom.min');
	
	// Javascript
	echo '<!--[if lt IE 9]>'.$this->Html->script('scripts/html5shiv').'<![endif]-->';
	echo $this->Html->script('jquery-1.7.2.min');
	echo $this->Html->script('jquery-ui-1.8.18.custom.min', array('async'=>'async')); // jQuery UI	
	
	echo $this->fetch('meta');	
	echo (isset($customMeta)) ? $customMeta : null;
	echo (isset($facebookMetaTags)) ? $facebookMetaTags : null;
	?>
	<meta property="fb:admins" content="530846121" />
	<meta name=viewport content="width=device-width, initial-scale=1">
	<!-- <meta property="fb:admins" content="103762946445270" /> -->
	<?php
	echo $this->fetch('css');
	echo $this->fetch('script');	
	?>
	
</head>
<body>
	<style>	
	#desktopCategoriesMenuDiv {
		display:block;
	}
	#mobileCategoriesMenuDiv {
		display:none;
	}
	#searchDiv {
		background-color: #efefef;
		border:1px dotted #aaaaaa; 
		padding:0;
		margin: 0 0 10px 0;
	}
	#searchDivText {
		background-color: #efefef;
		padding: 5px 5px 5px 10px;
		font-weight: bold;
	}
	#siteHorizontalAdDiv {
		border: 1px solid #efefef; 
		margin-bottom: 15px; 
		max-width: 960px;
		padding: 5px; 
		overflow:hidden;
	}
	.cse .gsc-control-cse, .gsc-control-cse-en {	
		padding: 5px 10px 0 10px ;
		width: auto;
		background-color: #ffffcc;
		margin:0;
	}	
	</style>
		
	
	<div class="wrapper row1">
		<?php
		echo $this->element('header');
		?>
	</div>
	
	<div class="wrapper row2">
		<?php echo $this->element('top_nav_menu');?>		
	</div>
	<!-- content -->
	<div class="wrapper row3" style='background:#ffffff url("/pale_green.jpg") repeat scroll 0 0;'>
		<div id="container" class="clear">	  
			<?php echo $this->Session->flash(); ?>	
			
			<?php 
			$searchEngineCode = $this->Session->read('Site.search_engine_code');	
			$userLoggedIn = $this->Session->check('User.id');			

			if(!empty($searchEngineCode) and (!$userLoggedIn) and (!$inLoginPage)) {
			?>
			<div id="searchDiv">	
				<div id="searchDivText">
					SEARCH
				</div>
				<?php
					echo $searchEngineCode;
				?>	
			</div>
			<?php
			}
			?>								
			
			<?php
			if(isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home')) {
				if($this->Session->read('Site.show_landing_page')) { 		
					echo $this->element('show_landing_page_info');		
				}				
			}
			?>
			
			<?php			
			if($this->Session->read('Site.description') and (!isset($this->request->params['admin'])) and (isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home'))) { 				
			?>
				<section class="siteDescriptionSection">		
					<?php echo $this->Session->read('Site.description');?>				
				</section>
			<?php 
			}
			
			$showLeftMenu = false;
			$hideLeftMenu = (isset($hideLeftMenu)) ? $hideLeftMenu : null; 
			
			if(!isset($this->request->params['admin'])) {
				$showLeftMenu = true;
				
				if($hideLeftMenu) {
					$showLeftMenu = false;
				}
			}			
			?>
			
			<?php
			// hide in request price quote page
			if(!$inRequestPriceQuotePage) {
			?>
			<!-- show this cart when in mobile view port -->
			<div class="mobileShoppingCartDiv">
				<?php 	echo ($this->Session->read('Site.request_price_quote')) ? $this->element('myshoppinglist_left_menu') : null; ?>
			</div>
			<?php
			}
			?>
			
			<?php
			if($showLeftMenu) {
			?>			
				<!-- left menu -->
				<?php echo $this->element('left_menu');?>
				<!-- / left menu -->
				
				<!-- content body -->
				<div id="content">					
					<?php echo $this->fetch('content'); ?>
				</div>
				<!-- / content body -->
			<?php
			}
			else {
			?>
				<!-- content body -->
				<?php echo $this->fetch('content'); ?>
				<!-- / content body -->
			<?php
			}
			?>	
			
			<?php
			if(!$this->Session->read('isMobile')) {
				if(isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home')) {
					if(!$this->Session->check('User.id')) { 
					?>	 
					<div style="clear:both;"></div>
					<div>
						<div style="float:right; margin:0 0 0 5px;">
							<!-- Your like button code -->						
							<div class="fb-like" data-href="<?php echo $this->Html->url('/', true);?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
						</div>
						<div style="float:right; margin:0 0 0 5px;">
							<g:plusone annotation="bubble" size="standard"></g:plusone>					
						</div>
						<div style="clear:both;"></div>
					</div>
					<?php 
					}
				} 
			}
			?>
	  </div>
	</div>
	<!-- / content body -->
	<!-- Footer -->
	<?php		
		if(!isset($this->request->params['admin'])) { 
			if(!$this->Session->read('Site.under_maintenance')) 
			{
				?>
				<div class="wrapper row4">
				  <div id="footer" class="clear">				  
					<?php 
						echo $this->element('footer');
					?>
					<div class="clear"></div>
					
				  </div>
				</div>			
				<?php 
			}
		} 
	?>
	<!-- / Footer -->
	<?php /* Request Price Quote Form */ ?>
	<div style="display:none; font-size:12px;" id="RPQF-Div">
		<h2 id="RPQF-ProductName" style="font-size:13px;"></h2>
		<br>
		<?php echo $this->Form->create(null, array('id'=>'RPQF')); ?>				
		<div class="floatLeft" style="width:100px; margin:5px 10px 0 0;">
			<?php 
			$qtyOptions = Configure::read('Product.quantity');
			echo $this->Form->input('ShoppingCartProduct.quantity', array('options'=>$qtyOptions, 'empty'=>false));
			?>
		</div>	
		<div class="floatLeft" style="width:100px; margin:5px 10px 0 0;">
			<?php 
			$sizeOptions = Configure::read('Product.size');
			echo $this->Form->input('ShoppingCartProduct.size', array('options'=>$sizeOptions, 'empty'=>'-'));
			?>
		</div>			
		<div class="floatLeft" style="width:150px; margin:5px 10px 0 0;">
			<?php 
			$ageOptions = Configure::read('Product.age');
			echo $this->Form->input('ShoppingCartProduct.age', array('options'=>$ageOptions, 'empty'=>'-'));
			?>
		</div>
		<div class="floatLeft" style="margin:5px 10px 0 0;">
			<br>
			<?php echo $this->Form->submit('Submit &raquo;', array('escape'=>false));?>
		</div>
		<div class='clear'></div>			
		<?php echo $this->Form->end();?>
	</div>	
	<?php /** --> End of Request Price Quote Form */ ?>
	
	
	<!-- Copyright -->
	<div class="wrapper row5">	
		
	  <footer id="copyright" class="clear">
		<div>
			<div style="text-align:center;">
				<a href="<?php echo $this->Html->url('/privacypolicy.htm');?>" target='_blank' style="color:#f90;">Privacy Policy</a> 
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="<?php echo $this->Html->url('/tos.htm');?>" target='_blank' style="color:#f90;">Terms of Service</a>
			</div>
			<br>
			<div style="text-align:center;">
				Copyright &copy; <?php echo date('Y');?> - All Rights Reserved - <?php echo $this->Html->link($this->request->host(), $this->Html->url('/', true), array('style'=>'color:#f90;'));?>
			</div>
		</div>
		
	  </footer>
	</div>	
	<!-- / Copyright -->	
	<?php
	echo $this->element('customjs');
	?>
	
	
	<?php 
	if(!$this->Session->check('User.id')) { 
		echo $this->Session->read('Site.analytics_code');	
		
		if($this->Session->read('isMobile')) {
		?>		
			<!-- Load Facebook SDK for JavaScript -->
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=156270707845549";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<!-- load google plus one -->
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js" async></script>
		<?php
		}
	}
	?>
	
	<style type="text/css">
	/* ------------ google search box style ---------- */
	input.gsc-input {
		border-color: #666;
		border-radius: 2px;
		font-size: 16px;
		padding: 4px 6px;
	}	
	.cse input.gsc-search-button, input.gsc-search-button {
		background-color: #eeeeee;
		border: 1px solid #666;
		border-radius: 2px;
		color: #000;
		font-family: inherit;
		font-size: 13px;
		font-weight: bold;
		height: 30px;
		min-width: 54px;
		padding: 0 8px;
	}
	/* ------------ end of google search box style ---------- */
	</style>
</body>
</html>

