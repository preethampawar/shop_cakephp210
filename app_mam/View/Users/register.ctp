<?php echo $this->element('message');?>
<br><br>
<div style="width:400px; border:1px solid #efefef; padding:10px 10px 20px 20px; margin:auto; background-color:#f6f6f6;" class="corner shadow">
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/users/login', array('class'=>'button small red floatRight', 'escape'=>false));	?>
	<div class="clear" style="margin:0px;"></div>
	<?php echo $this->Form->create();?>
	<h1>Register your account</h1>
	<hr>
	<br>	
	<?php echo $this->Form->input('User.name', array('label'=>'Name', 'required'=>true, 'div'=>array('class'=>'required'), 'placeholder'=>'Enter Full Name'));?>
	<div class="input text required"><label for="email">Email Address</label>
		<?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Email Address'));?>
	</div>

	<div class="input text required"><label for="email">Password</label>
		<?php echo $this->Form->input('User.password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Enter Password'));?>
	</div>
	
	<div class="input text required"><label for="email">Confirm Password</label>
		<?php echo $this->Form->input('User.confirm_password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Confirm Your Password'));?>
	</div>
	<br>
	<?php echo $this->Form->submit('Register &nbsp;&raquo;', array('escape'=>false));?>	
	<br>
	<div>
	<?php echo $this->Html->link('Forgot your password?', '/users/forgotpassword', array('style'=>'text-decoration:none;')); ?>
	<?php //echo $this->Html->link('Need an account?', '/users/signup'); ?>
	</div>
	<?php echo $this->Form->end();
	?>
</div>
<br><br>