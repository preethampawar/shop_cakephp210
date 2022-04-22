<?php echo $this->element('message'); ?>
<section>
	<article>
		<header><h2>Change Password</h2></header>
		<div style="width:400px;">
			<?php
			echo $this->Form->create();
			echo $this->Form->input('User.password', ['label' => 'Old Password *', 'required' => true, 'placeholder' => 'Enter Old Password']);
			echo '<br>';
			echo $this->Form->input('User.new_password', ['label' => 'New Password *', 'type' => 'password', 'required' => true, 'placeholder' => 'Enter New Password']);
			echo '<br>';
			echo $this->Form->input('User.confirm_password', ['label' => 'Confirm New Password *', 'type' => 'password', 'required' => true, 'placeholder' => 'Confirm New Password']);
			echo '<br><br>';
			echo $this->Form->submit('Update &nbsp;&raquo;', ['escape' => false, 'div' => false]);
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			echo $this->Html->link('Cancel &raquo;', '/', ['escape' => false]);
			echo $this->Form->end();
			?>
		</div>
	</article>
</section>
