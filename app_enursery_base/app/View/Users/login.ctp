<?php echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex'));?>
<?php $this->Html->meta('keywords', 'log in to your enursery account', array('inline'=>false)); ?>
<?php $this->Html->meta('description', 'Log in to your enursery account to manage your site.', array('inline'=>false)); ?>

<?php echo $this->element('message');?>
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
				<?php echo '&nbsp;'.$this->Html->link('Cancel', '/', array('escape'=>false));	?>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<br>
				<?php //echo $this->Html->link('Forgot your password?', '/users/forgotpassword', array('style'=>'text-decoration:none;')); ?>		
				<br><?php echo $this->Html->link('Need an account? Click here to Register', '/users/register', array('style'=>'text-decoration:none;', 'escape'=>false)); ?>				
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end();?>
</div>