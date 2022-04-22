<header id="header" class="clear">
	<hgroup>
		<div class="desktopHgroup">
			<h1>
			<?php 
				if($this->Session->check('visitedLandingPage')) {					
					$link = '/showcase';
				}
				else {
					$link = '/';
				}
				echo $this->Html->link($this->Session->read('Site.title'), $link, array('title'=>$this->Session->read('Site.title')));
			?>
			</h1>
			<h2>&nbsp;<?php echo $this->Session->read('Site.caption');?></h2>
		</div>	
		<div class="mobileHgroup">	
			<div class="floatLeft" style="margin-right:0px;"  onclick="$('#topNavMenuDiv').animate({height: 'toggle'});">				
				<a class="menu btn"><?php echo $this->Html->image('hamburger.png', array('alt'=>' ', 'title'=>'Click here for more options'));?></a>				
			</div>
			<div class="floatLeft" style="padding:0;">
				<h1 style="font-size:20px; color:#e6e6e6; height:44px; line-height: 32px; margin-top:13px;"><?php echo $this->request->host();?></h1>
			</div>
		</div>
		<div class="clear"></div>		
	</hgroup>
	
	<div action="#" method="post" id="logoutDiv">
		<!--
		<fieldset>
			<legend>Search:</legend>
			<input type="text" value="Search Our Website&hellip;" onFocus="this.value=(this.value=='Search Our Website&hellip;')? '' : this.value ;" title='Search Our Website'>
			<input type="submit" id="sf_submit" value="submit">
		</fieldset>  
		<br>
		-->	
			
		<br>
		<nav style="text-transform:uppercase;">		
			<?php 
				if(!$this->Session->read('User.id')) {
					//echo $this->Html->link('Admin Login', '/users/login', array('class'=>'floatRight', 'style'=>'padding:0 0 0 10px;', 'title'=>'Admin Login'));
				}
				else {
					echo $this->Html->link('Logout', '/users/logout', array('class'=>'floatRight', 'style'=>'padding:0 0 0 10px;', 'title'=>'Logout'));				
				}
			?>
			<?php // echo $this->Html->link('HomePage', '/', array('class'=>'floatRight', 'style'=>'padding:0 10px;', 'title'=>'Home Page'));?>
		</nav>		
	</div>
</header>
