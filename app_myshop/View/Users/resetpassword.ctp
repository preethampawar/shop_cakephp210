<?php echo $this->element('message'); ?>
<section>
	<article>
		<header><h2>Reset Password</h2></header>
		<div style="width:400px;">
			<?php


			echo $this->Form->create();
			echo $this->Form->input('User.verification_code', ['label' => 'Verification Code *', 'div' => false, 'required' => true, 'placeholder' => 'Enter Verification Code']);
			echo '<br><br>';
			echo "&nbsp;Note<span style='color:#ff0000;'>*</span>: Password will be sent to your Email Address.	";
			echo '<br><br><br>';
			echo $this->Form->submit('Submit &nbsp;&raquo;', ['escape' => false, 'div' => false]);
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo $this->Html->link('Cancel &raquo;', '/', ['escape' => false]);
			echo $this->Form->end();
			echo '<br><br><br>';
			echo $this->Html->link('Request new code &raquo;', '/users/forgotpassword', ['escape' => false]);


			?>
		</div>
	</article>
</section>
