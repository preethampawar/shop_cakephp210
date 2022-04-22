<div>
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Register your account'); ?></legend>
		<?php
		echo $this->Form->input('name', ['label' => 'Full Name', 'placeholder' => 'Enter your full name']);
		echo $this->Form->input('email', ['label' => 'Email Address', 'placeholder' => 'Enter your email address', 'type' => 'text']);
		echo $this->Form->input('password', ['label' => 'Password', 'placeholder' => 'Enter your password']);
		?>
		<?php echo $this->Form->end(__('Submit')); ?>
	</fieldset>
</div>
