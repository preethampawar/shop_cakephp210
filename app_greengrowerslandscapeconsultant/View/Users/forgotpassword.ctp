<?php echo $this->element('message');?>
<section>
	<article>
		<header><h2>Forgot your password?</h2></header>
		<div style="width:400px;">
		<?php 
		echo $this->Form->create(null, array('encoding'=>false));
		echo $this->Form->input('User.email', array('label'=>false, 'div'=>false, 'type'=>'email', 'required'=>true, 'placeholder'=>'Enter Email Address'));
		echo '<br><br><br>';
		echo $this->Form->submit('Continue &nbsp;&raquo;', array('escape'=>false, 'div'=>false));
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		echo $this->Html->link('Cancel &raquo;', '/', array('escape'=>false));
		echo $this->Form->end();
		echo '<br><br><br>';
		 echo $this->Html->link('&raquo; Log in', '/users/login', array('style'=>'text-decoration:none;', 'escape'=>false));
		
		?>		
		</div>
	</article>
</section>