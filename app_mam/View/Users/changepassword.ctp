<?php echo $this->element('message');?>
<div style="width:400px; border:1px solid #efefef; padding:20px; margin:auto; background:#fafafa; margin-top:5%;" class="corner shadow">
	<?php echo $this->Form->create();?>
	<h1>Change Password</h1>
	<br>	
	<div class="input text required"><label for="UserPassword">Old Password</label>
		<?php echo $this->Form->input('User.password', array('label'=>false, 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Old Password'));?>
	</div>

	<div class="input text required"><label for="UserNewPassword">New Password</label>
		<?php echo $this->Form->input('User.new_password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Enter New Password'));?>
	</div>
	
	<div class="input text required"><label for="UserConfirmPassword">Confirm Password</label>
		<?php echo $this->Form->input('User.confirm_password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Confirm New Password'));?>
	</div>
	<br>
	<div>
	<?php echo $this->Form->submit('Save Changes &nbsp;&raquo;', array('escape'=>false, 'div'=>false));?>	
	</div>
	
	
	<?php echo $this->Form->end();?>
</div>
<br><br>