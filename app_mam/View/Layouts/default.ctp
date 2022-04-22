<?php
$cakeDescription = __d('cake_dev', 'MyAccountManager');
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
	<link rel="stylesheet" href="<?php echo $this->Html->url('/css/print.css', true);?>" type="text/css" media="print" />
	<?php
		//echo $this->Html->meta('icon');
	
		echo $this->Html->css('cake.generic', null, array('media'=>'screen'));		
		echo $this->Html->css('dcmegamenu/dcmegamenu');	// DC Mega Menu CSS
		echo $this->Html->css('dcmegamenu/skins/black.css');	// DC Mega Menu CSS
		echo $this->Html->css('dcmegamenu/skins/white.css');	// DC Mega Menu CSS
		echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom'); // jQuery UI
		
		echo $this->Html->script('jquery');			
		echo $this->Html->script('jquery-ui-1.8.18.custom.min'); // jQuery UI
		echo $this->Html->script('jquery.dcmegamenu.1.3.3.min');	// DC Mega Menu JS
		echo $this->Html->script('jquery.hoverIntent.minified');	// DC Mega Menu JS
		echo $this->Html->script('jquery.corner');	// jQuery corner plugin
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		echo $this->element('customjs');
		echo $this->Js->writeBuffer();
	?>
	
		
</head>
<body>
	<div id="container">
		<div id="header" <?php echo (!$this->Session->check('UserCompany.company_id')) ? 'style="border-bottom:1px solid #cacaca;"' : '';?>>
			<div class="floatLeft">
				<div id="logo"><?php echo $this->Html->link($cakeDescription, '/'); ?></div>
				<div id="caption"> - a simple yet powerful online bookkeeping system</div>
			</div>	
			<div class='clear'></div>	
			
			<?php if($this->Session->check('User')) { ?>
			
			<?php echo ($this->Session->check('Company.title')) ? '<div class="floatLeft" style="margin-left:20px;"><b> - '.$this->Session->read('Company.title').'</b></div>' : '';?>
			
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
			
			<?php } ?>
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
</body>
</html>
