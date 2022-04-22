<?php echo $this->element('message');?>
<div style="width:400px; border:1px solid #efefef; padding:20px; margin:auto; background:#fafafa; margin-top:5%;" class="corner shadow">
	<?php echo $this->Form->create();?>
	<div class="floatLeft">
		<h1>Reset Password</h1>
	</div>
	<div class="floatRight" style=" margin-top:5px;"><?php echo $this->Html->link('&raquo; Request new code?', '/users/forgotpassword', array('style'=>'text-decoration:none;', 'escape'=>false)); ?></div>
	<div class="clear"></div>	
	
	<div class="input text required"><label for="email">Verification Code</label>
		<?php echo $this->Form->input('User.verification_code', array('label'=>false, 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Verification Code'));?>		
	</div>
	&nbsp;Note<span style='color:#ff0000;'>*</span>: Account Password will be sent to your Email Address.	
	<br><br>
	<div>
	<?php echo $this->Form->submit('Submit &nbsp;&raquo;', array('escape'=>false, 'div'=>false));?>	
	</div>
	<br>
	<br>
	&nbsp;<?php echo $this->Html->link('&raquo; Log in', '/users/login', array('style'=>'text-decoration:none;', 'escape'=>false)); ?>	
	<br>
	<?php echo $this->Form->end();?>
	
</div>