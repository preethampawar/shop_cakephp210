<?php echo $this->Session->flash('auth'); ?>
<br>


<div class="container-fluid">
<div class="row">
	<div class="col-md-4">
		<br>
		<div class="jumbotron" style="padding: 10px 10px 40px 30px;">
			<h1>Login</h1><br>
			<?php
			echo $this->Form->create('User');
			echo $this->Form->input('email', array('type'=>'email', 'required'=>true, 'maxlength'=>'40', 'label'=>'Email Address', 'autofocus'=>true, 'class' => 'form-control'));
			echo $this->Form->input('password', array('type'=>'password', 'required'=>true, 'maxlength'=>'40', 'class' => 'form-control'));
			?>
			<br>
			<button type="submit" class="btn btn-primary">Login</button>
			<?php
			echo $this->Form->end();
			?>
		</div>

		<div class="text-center">

		</div>
	</div>
	<div class="col-md-8 text-center">
		<img src="/img/img1.png" alt="" class="">

		<img src="/img/img2.png" alt="" class="">
	</div>
</div>
</div>
