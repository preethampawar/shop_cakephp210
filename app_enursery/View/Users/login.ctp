<?php 
echo $this->Html->meta('keywords', 'Log in', array('inline'=>false));
echo $this->Html->meta('description', 'Log in to access your site account', array('inline'=>false));
echo $this->element('message');
?>

<div>
	<?php echo $this->Form->create();?>	
	<h1>Log in</h1>
	
	<div>
		<div>
			<div class="floatLeft" style="width: 140px;">Email Address*</div>
			<div class="floatLeft" style="width: 250px;">
				<?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Email Address..', 'title'=>'Enter Email Address..', 'style'=>'width:100%'));?>
			</div>
			<div class="clearBoth"></div>
		</div>
		<div style="margin-bottom:30px;">&nbsp;</div>
		<div>
			<div class="floatLeft" style="width: 140px;">Password*</div>
			<div class="floatLeft" style="width: 250px;">
				<?php echo $this->Form->input('User.password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Enter Password..', 'title'=>'Enter Password..', 'style'=>'width:100%'));?>
			</div>
			<div class="clearBoth"></div>
		</div>		
		<div style="margin-bottom:30px;">&nbsp;</div>
		<div>
			<div class="floatLeft" style="width: 140px;">&nbsp;</div>
			<div class="floatLeft" style="width: 250px;">
				<?php echo $this->Form->submit('Log In &nbsp;&raquo;', array('escape'=>false, 'div'=>false, 'class'=>'small green button'));?>	
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $this->Html->link('Forgot Password?', '/users/forgotpassword', array('escape'=>false));	?>
				<div class="clearBoth"></div>
				<br><br>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end();?>
</div>