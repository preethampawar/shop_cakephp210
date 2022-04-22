<?php echo $this->Session->flash('auth'); ?>

<div class="mx-auto w-25 container" style="width: 400px;">
	<div class="row">
		<div class="col-sm-12">
			<h1 class="text-center">Log In</h1><br>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('email', ['type' => 'email', 'required' => true, 'maxlength' => '40', 'label' => 'Email Address', 'autofocus' => true, 'class' => 'form-control input-sm mb-4']);
			echo $this->Form->input('password', ['type' => 'password', 'required' => true, 'maxlength' => '40', 'class' => 'form-control input-sm mb-4']);
			?>
			<br>
			<button type="submit" class="btn btn-primary btn-md form-control">Login</button>
			<?php
			echo $this->Form->end();
			?>
		</div>
	</div>
</div>
