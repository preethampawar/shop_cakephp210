<?php
$msg = $this->Session->flash('auth');
if (!empty($msg)) {
	?>
	<div class="alert alert-danger">
		<?php echo $msg; ?>
	</div>
	<?php
}
?>

<h1>Login</h1><br>
<?php
echo $this->Form->create('User');
?>
<div class="row">
	<div class="col-lg-12">
		<div class="form-group input-group-lg">
			<label for="UserEmail">Email Address:</label>
			<?php echo $this->Form->input('email', [
				'type' => 'email',
				'label' => false,
				'required' => true,
				'maxlength' => '55',
				'placeholder' => 'Email Address',
				'autofocus' => true,
				'class' => 'form-control']); ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="form-group input-group-lg">
			<label for="UserPassword">Password:</label>
			<?php echo $this->Form->input('password', [
				'type' => 'password',
				'label' => false,
				'required' => true,
				'maxlength' => '55',
				'placeholder' => 'Password',
				'autofocus' => true,
				'class' => 'form-control']); ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xl-12 text-center">
		<br>
		<button type="submit" id='SubmitForm' title='' class="form-control btn btn-purple btn-lg">Login</button>
	</div>
</div>
<?php
echo $this->Form->end();
?>
