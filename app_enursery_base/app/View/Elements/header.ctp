<?php
	$homeLinkActive = (isset($homeLinkActive)) ? 'class="active"' : null;
	$loginLinkActive = (isset($loginLinkActive)) ? 'class="active"' : null;
	$registerLinkActive = (isset($registerLinkActive)) ? 'class="active"' : null;
	$contactLinkActive = (isset($contactLinkActive)) ? 'class="active"' : null;
	$clientsLinkActive = (isset($clientsLinkActive)) ? 'class="active"' : null;
	$aboutusLinkActive = (isset($aboutusLinkActive)) ? 'class="active"' : null;
?>
<header id="header" class="clear">
	<hgroup>
		<div class="desktopHgroup">
			<h1><?php 
				//echo $this->Html->link($this->Html->image('logo/enursery_logo_grey_m.jpg', array('alt'=>'eNursery', 'title'=>'eNurery - take a green step')), '/', array('title'=>'eNursery - take a green step', 'escape'=>false));?>
				LetsGreenify
			</h1>
			<h2>&nbsp;&nbsp;- take a green step</h2>
		</div>
		<div class="mobileHgroup">			
			<div class="floatLeft" style="margin-right:0px;"  onclick="$('#topNavMenuDiv').animate({height: 'toggle'});">				
				<a class="menu btn"><?php echo $this->Html->image('hamburger.png', array('alt'=>' ', 'title'=>'Click here for more options'));?></a>				
			</div>
			<div class="floatLeft" style="padding:0;">
				<h1 style="font-size:35px; color:#12b242; height:44px; line-height: 32px; margin-top:13px;">LetsGreenify</span></h1>
			</div>
		</div>
		<div class="clear"></div>
	</hgroup>	
	<div class="clear"></div>
	<div id="topNavMenuDiv">
		<nav class="clear">
			<ul class="topnavUL">
				<li <?php echo $homeLinkActive;?>><?php echo $this->Html->link('Homepage', '/', array('title'=>'letsgreenify.com Home Page', 'class'=>'topNavLink'));?></li>
				<?php
				if(!$this->Session->check('User.id')) {
				?>
					<li <?php echo $aboutusLinkActive;?>><?php echo $this->Html->link('About us', '/pages/about-us', array('style'=>'text-decoration:none;', 'escape'=>false, 'title'=>'About us', 'class'=>'topNavLink'));?></li>
					<li <?php echo $clientsLinkActive;?>><?php echo $this->Html->link('Our Clients', '/pages/our-clients', array('style'=>'text-decoration:none;', 'escape'=>false, 'title'=>'Our Clients'));?></li>
					<li <?php echo $contactLinkActive;?>><?php echo $this->Html->link('Contact us', array('controller'=>'users', 'action'=>'contactus'), array('style'=>'text-decoration:none;', 'escape'=>false, 'title'=>'Contact us'));?></li>
					<li <?php echo $registerLinkActive;?>><?php echo $this->Html->link('Register', array('controller'=>'users', 'action'=>'register'), array('style'=>'text-decoration:none;', 'escape'=>false, 'title'=>'Register your plant nursery, garden, landscaping and gardening service, etc.'));?></li>
					<li <?php echo $loginLinkActive;?>><?php echo $this->Html->link('Admin Login', array('controller'=>'users', 'action'=>'login'), array('style'=>'text-decoration:none;', 'escape'=>false, 'title'=>'Login'));?></li>
				
					<!--
					<li><a href="style-demo.html">Style Demo</a></li>
					<li><a href="full-width.html">Full Width</a></li>
					<li><a href="gallery.html">Gallery</a></li>
					<li><a href="portfolio.html">Portfolio</a></li>
					
					<li><a href="#">DropDown</a>
					  <ul>
						<li><a href="#">Link 1</a></li>
						<li><a href="#">Link 2</a></li>
						<li><a href="#">Link 3</a></li>
					  </ul>
					</li>
					<li class="last"><a href="#">A Long Link Text Here</a></li>
					-->
				<?php
				}
				else {
					if($this->Session->read('User.superadmin')) {
					?>
						<li style="float:right"><?php echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
						<li style="float:right"><?php echo $this->Html->link('Manage Users', '/admin/users/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
						<li style="float:right"><?php echo $this->Html->link('Manage Sites', '/admin/sites/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
					<?php
					}
					else {
						?>
						<li style="float:right"><?php echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
						<li style="float:right"><?php echo $this->Html->link('Site Info', '/admin/sites/siteInfo', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
						<li style="float:right"><?php echo $this->Html->link('Account Info', '/admin/users/userInfo', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
						<?php
					}
				}
				?>
				
			</ul>
		</nav>
	</div>
</header>
