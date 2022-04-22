<?php echo $this->element('message');?>
<div style="width:400px; border:1px solid #efefef; padding:20px; margin:auto; background-color:#f6f6f6; margin-top:5%;" class="corner shadow">
	<?php echo $this->Form->create();?>
	<div class="floatLeft">
		<h1>Log in</h1>
	</div>
	<div class="floatRight" style=" margin-top:5px;"><?php echo $this->Html->link('&raquo; Forgot your password?', '/users/forgotpassword', array('style'=>'text-decoration:none;', 'escape'=>false)); ?></div>
	<div class="clear"></div>
	
	<div class="input text required"><label for="email">Email Address</label>
		<?php echo $this->Form->input('User.email', array('label'=>false, 'div'=>false, 'type'=>'email', 'required'=>true, 'placeholder'=>'Enter Email Address'));?>
	</div>

	<div class="input text required"><label for="email">Password</label>
		<?php echo $this->Form->input('User.password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Enter Password'));?>
	</div>
	<br>
	<div>
	<?php echo $this->Form->submit('Log in &nbsp;&raquo;', array('escape'=>false, 'div'=>false));?>	
	</div>
	<?php //echo $this->Html->link('&raquo; Forgot your password?', '/users/forgotpassword', array('style'=>'text-decoration:none;', 'escape'=>false)); ?>
	<div>
	<br><?php echo $this->Html->link('Need an account? Click here to Register', '/users/register', array('style'=>'text-decoration:none;', 'escape'=>false)); ?>.
	</div>
	<?php echo $this->Form->end();?>
	
</div>
<br><br><br>