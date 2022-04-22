<header id="header" class="clear">
	<hgroup>
		<h1>
			<?php
			echo $this->Html->link($this->Session->read('Site.title'), '/', ['title' => $this->Session->read('Site.title')]);
			?>
		</h1>
		<h2>&nbsp;<?php echo $this->Session->read('Site.caption'); ?></h2>
	</hgroup>
	<form action="#" method="post">

		<nav style="text-transform:uppercase;">
			<?php
			if (!$this->Session->read('User.id')) {
				echo $this->Html->link('Admin Login', '/users/login', ['class' => 'floatRight', 'style' => 'padding:0 0 0 10px;', 'title' => 'Admin Login']);
			} else {
				echo $this->Html->link('Logout', '/users/logout', ['class' => 'floatRight', 'style' => 'padding:0 0 0 10px;', 'title' => 'Logout']);
			}
			?>
			<?php // echo $this->Html->link('HomePage', '/', array('class'=>'floatRight', 'style'=>'padding:0 10px;', 'title'=>'Home Page'));?>
		</nav>

	</form>
</header>
