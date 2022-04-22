<header id="header" class="clear">
	<hgroup>
		<div class="desktopHgroup">
			<h1>
				<?php
				if ($this->Session->check('visitedLandingPage')) {
					$link = '/showcase';
				} else {
					$link = '/';
				}
				echo $this->Html->link($this->Session->read('Site.title'), $link, ['title' => $this->Session->read('Site.title')]);
				?>
			</h1>
			<h2>&nbsp;<?php echo $this->Session->read('Site.caption'); ?></h2>
		</div>
		<div class="mobileHgroup">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td>
						<div class="floatLeft" style="margin-right:0px;"
							 onclick="$('#topNavMenuDiv').animate({height: 'toggle'});">
							<a class="menu btn"><?php echo $this->Html->image('hamburger.png', ['alt' => ' ', 'title' => 'Click here for more options']); ?></a>
						</div>
					</td>
					<td>
						<h3 style="font-size:17px;"><?php echo $this->Session->read('Site.title'); ?></h3>
					</td>
				</tr>
			</table>

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

		<?php
		if (!$this->Session->read('User.id')) {
			// echo $this->Html->link('Admin Login', '/users/login', array('class'=>'floatRight', 'style'=>'padding:0 0 0 10px; background-color: transparent; ', 'title'=>'Admin Login'));
		} else {
			// echo $this->Html->link('Logout', '/admin/users/logout', array('class'=>'floatRight', 'style'=>'padding:0 0 0 10px; background-color: transparent;', 'title'=>'Logout'));
		}
		?>
	</div>
</header>
