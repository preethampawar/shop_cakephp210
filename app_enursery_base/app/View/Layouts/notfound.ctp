<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<title>Page not found</title>
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
		
		//$options = array('homeLinkActive'=>$homeLinkActive, 'loginLinkActive'=>$loginLinkActive, 'registerLinkActive'=>$registerLinkActive);
		echo $this->element('header_notfound');
		?>
	</div>
	<!-- content -->
	<div class="wrapper row2">
	  <div id="container" class="clear">
		<?php echo $this->Session->flash(); ?>		 
		<?php echo $this->fetch('content'); ?>
	  </div>
	</div>
	<!-- / content body -->
	
	<!-- Copyright -->
	<div class="wrapper row4">
	  <footer id="copyright" class="clear">		
		<p style="margin:auto; text-align:center;"><br />Copyright &copy; <?php echo date('Y');?> - All Rights Reserved - <?php echo $this->Html->link('LetsGreenify', '/');?></p>				
	  </footer>
	</div>
	
</body>
</html>




<?php
/*
?>
$cakeDescription = __d('cake_dev', 'eNursery');
//$cakeDescription.=($this->Session->check('Company.title')) ? ' :: '.$this->Session->read('Company.title') : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true);?>">
	
	<?php
		//echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('dcmegamenu/dcmegamenu');	// DC Mega Menu CSS
		echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom'); // jQuery UI
		echo $this->Html->css('colortip-1.0-jquery'); // jQuery Color Tip 1.0
		// echo $this->Html->css('dcmegamenu/skins/black.css');	// DC Mega Menu CSS
		// echo $this->Html->css('dcmegamenu/skins/white.css');	// DC Mega Menu CSS
		
		echo $this->Html->script('jquery-1.7.2.min');			
		echo $this->Html->script('jquery.corner');	// jQuery corner plugin
		echo $this->Html->script('jquery-ui-1.8.18.custom.min'); // jQuery UI
		echo $this->Html->script('colortip-1.0-jquery');	// jQuery colortip plugin
		// echo $this->Html->script('jquery.dcmegamenu.1.3.3.min');	// DC Mega Menu JS
		// echo $this->Html->script('jquery.hoverIntent.minified');	// DC Mega Menu JS
		// echo $this->Html->script('gen_validatorv4');	// JS Validator
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');		
		
		echo $this->Js->writeBuffer();
	?>
	
		
</head>


<body>
	<div id="container">
		<div id="header">
			<div class="floatLeft logo">
				<div class="floatLeft"><div id="logo"><?php echo $this->Html->link($cakeDescription, '/'); ?></div></div>	
				<div class='clear'></div>	
				<div class="floatLeft" style="margin-left:20px;">- take a green step</div>
				<div class='clear'></div>	
			</div>			
			<div class="floatRight headerRightMenu">
				<?php if($this->Session->check('User')) { ?>
				
				<?php echo ($this->Session->check('Company.title')) ? '<div class="floatLeft" style="margin-left:20px;"><strong> - '.$this->Session->read('Company.title').'</strong></div>' : '';?>
				
				<div class="floatRight"><?php echo $this->Html->link('&nbsp;&raquo; Logout', array('controller'=>'users', 'action'=>'logout'), array('style'=>'text-decoration:none;', 'escape'=>false));?></div>	
				<div class="floatRight" style="width:20px;">&nbsp;</div>
				
				<div class="floatRight"><?php echo $this->Html->link('&nbsp;&raquo; Change Password', array('controller'=>'users', 'action'=>'changepassword'), array('style'=>'text-decoration:none;', 'escape'=>false));?></div>	
				<div class="floatRight" style="width:20px;">&nbsp;</div>
				<?php
				if($this->Session->check('User.admin') and ($this->Session->read('User.admin') == '1')) {
				?>
					<div class="floatRight"><?php echo $this->Html->link('&nbsp;&raquo; Admin', '/admin/users/', array('style'=>'text-decoration:none;', 'escape'=>false));?></div>	
					<div class="floatRight" style="width:20px;">&nbsp;</div>
				<?php			
				}
				?>
				<div class="floatRight"><?php echo $this->Html->link('&nbsp;&raquo; Business/Personal Accounts', array('controller'=>'companies', 'action'=>'selectCompany'), array('style'=>'text-decoration:none;', 'escape'=>false));?></div>	
				<div class="floatRight" style="width:20px;">&nbsp;</div>
				
				<div class="floatRight" style="font-weight:bold;">Welcome, <?php echo $this->Session->read('User.name');?></div>	
				<div class='clear'></div>						
				
				<?php } else {	?>
					<div class="floatRight"><?php echo $this->Html->link('&nbsp;&raquo; Login', array('controller'=>'users', 'action'=>'login'), array('style'=>'text-decoration:none;', 'escape'=>false));?></div>	
					<div class="floatRight" style="width:20px;">&nbsp;</div>
					
					<div class="floatRight"><?php echo $this->Html->link('&nbsp;&raquo; Register', array('controller'=>'users', 'action'=>'register'), array('style'=>'text-decoration:none;', 'escape'=>false));?></div>	
					<div class='clear'></div>						
				<?php
				} ?>
			</div>
			<div class='clear'></div>	
		</div>
		<div id="nav">
			<?php 
			$showTopNavMenu = true;
			
			if(isset($hideTopNavMenu) and ($hideTopNavMenu == true)) {
				$showTopNavMenu  = false;
			}
			if($showTopNavMenu) {
				echo $this->element('top_nav_menu');
			}
			?>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" style="vertical-align:center; border-top:1px solid #cacaca;">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false, 'title'=>'This site is powered by CakePHP')
				);
			?>	
			<!-- 
			<div class="floatRight"><a href="http://affiliates.mozilla.org/link/banner/12249" target="_blank" title="This site is best viewed in firefox browser. Click here to download the latest version of firefox"><img src="http://affiliates.mozilla.org/media/uploads/banners/ac502446d8392cea778bcdaf8b3e07f8958a0216.png" alt="Download: Fast, Fun, Awesome" /></a></div>
				-->
		</div>
	</div>	
	<?php echo $this->Js->writeBuffer(array('inline' => 'true'));?>
	<?php echo $this->element('sql_dump'); ?>
	<?php
	echo $this->element('customjs');
	?>
</body>
</html>
<?php
*/
?>
