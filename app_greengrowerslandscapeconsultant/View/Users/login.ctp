<?php 
echo $this->Html->meta('keywords', 'Log in', array('inline'=>false));
echo $this->Html->meta('description', 'Log in to access your site account', array('inline'=>false));
echo $this->element('message');
?>

<div>
	<?php echo $this->Form->create();?>	
	<h1>Log in</h1>
	<table style='width:400px;'>
		<tr>
			<td style='width:130px;'>Email Address*</td>
			<td><?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Email Address..', 'title'=>'Enter Email Address..', 'style'=>'width:100%'));?></td>
		</tr>
		<tr>
			<td>Password*</td>
			<td><?php echo $this->Form->input('User.password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Enter Password..', 'title'=>'Enter Password..', 'style'=>'width:100%'));?></td>
		</tr>		
		<tr>
			<td>&nbsp;</td>
			<td>
				<br/>
				<?php echo $this->Form->submit('Log In &nbsp;&raquo;', array('escape'=>false, 'div'=>false, 'class'=>'small green button'));?>	
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo '&nbsp;'.$this->Html->link('Forgot Password?', '/users/forgotpassword', array('escape'=>false));	?>
			</td>
		</tr>
		
	</table>
	<?php echo $this->Form->end();?>
</div>