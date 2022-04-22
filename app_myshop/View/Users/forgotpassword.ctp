<?php echo $this->element('message'); ?>
<section>
	<article>
		<header><h2>Forgot your password?</h2></header>
		<div style="width:400px;">
			<?php
			echo $this->Form->create(null, ['encoding' => false]);
			echo $this->Form->input('User.email', ['label' => false, 'div' => false, 'type' => 'email', 'required' => true, 'placeholder' => 'Enter Email Address']);
			echo '<br><br><br>';
			echo $this->Form->submit('Continue &nbsp;&raquo;', ['escape' => false, 'div' => false]);
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo $this->Html->link('Cancel &raquo;', '/', ['escape' => false]);
			echo $this->Form->end();
			echo '<br><br><br>';
			echo $this->Html->link('&raquo; Log in', '/users/login', ['style' => 'text-decoration:none;', 'escape' => false]);

			?>
		</div>
	</article>
</section>
