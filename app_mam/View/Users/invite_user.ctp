<?php echo $this->element('message');?>
<br><br>
<div style="width:400px; border:1px solid #efefef; padding:10px 10px 20px 20px; margin:auto; background-color:#f6f6f6;" class="corner shadow">
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/users/', array('class'=>'button small red floatRight', 'escape'=>false));	?>
	<div class="clear" style="margin:0px;"></div>
	<?php echo $this->Form->create();?>
	<h1>Invite User</h1>
	<hr>
	<br>	
	<?php echo $this->Form->input('User.name', array('label'=>'Name', 'required'=>true, 'div'=>array('class'=>'required'), 'placeholder'=>'Enter Full Name'));?>
	<div class="input text required"><label for="email">Email Address</label>
		<?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Email Address'));?>
	</div>
	<?php echo $this->Form->input('UserCompany.user_level', array('label'=>'Access Level', 'required'=>true, 'empty'=>false, 'options'=>Configure::read('UserLevel')));	?>
	
	<?php echo '<br>'.$this->Form->submit('Send Invite &nbsp;&raquo;', array('escape'=>false));?>	
	
	<?php echo $this->Form->end();?>
</div>