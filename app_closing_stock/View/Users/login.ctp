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

	<h1>Login</h1>
<?php
echo $this->Form->create('User');
?>
<div class="form-group input-group-lg mt-3">
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
<div class="form-group input-group-lg mt-3">
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
<button type="submit" id='SubmitForm' title='' class="form-control btn btn-purple btn-md mt-3">Login</button>
<?php
echo $this->Form->end();
?>
