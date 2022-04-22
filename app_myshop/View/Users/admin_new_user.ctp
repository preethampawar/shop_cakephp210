<?php
App::uses('AppModel', 'Model');
?>
<div>
	<?php echo $this->Form->create('User', ['onsubmit' => "disableButton('customerRegisterButton')"]); ?>
	<h1 class="">Add New User</h1>

	<div class="mt-4">
		<label for="UserType" class="form-label font-weight-bold">
			User Type <span class="text-danger small">(required)</span>
		</label>
		<?php
		echo $this->Form->select('type', User::USER_TYPE_OPTIONS, ['class'=>'form-select', 'label' => false, 'default'=>User::USER_TYPE_BUYER, 'empty'=>false]);
		?>
	</div>

	<div class="mt-4">
		<label for="UserName" class="form-label font-weight-bold">
			Name <span class="text-danger small">(required)</span>
		</label>
		<input
				type="text"
				name="data[User][name]"
				class="form-control"
				id="UserName"
				placeholder="Enter User Name"
				minlength="2"
				maxlength="55"
				value="<?= $name ?>"
				required>
	</div>

	<div class="mt-4">
		<label for="UserMobile" class="form-label font-weight-bold">
			Mobile Number <span class="text-danger small">(required)</span>
		</label>
		<input
				type="number"
				name="data[User][mobile]"
				class="form-control"
				id="UserMobile"
				placeholder="Enter your 10 digit mobile number ex: 9494555588."
				min="6000000000"
				max="9999999999"
				value="<?= $mobile ?>"
				required>
	</div>

	<div class="mt-3">
		<label for="UserEmail" class="form-label font-weight-bold">
			Email Address
			<?php
			if((bool)$this->Session->read('Site.sms_notifications') === false) {
			?>
				<span class="text-danger small">(required)</span>
			<?php
			}
			?>
		</label>
		<input
			type="email"
			name="data[User][email]"
			class="form-control"
			id="UserEmail"
			placeholder="Enter your email address"
			maxlength="55"
			value="<?= $email ?>"
			<?php
			if((bool)$this->Session->read('Site.sms_notifications') === false) {
				?>
				required
				<?php
			}
			?>
			>
	</div>

	<div class="mt-4">
		<button type="submit" class="btn btn-md btn-primary" id="customerRegisterButton">Submit</button>

	</div>
	<?php echo $this->Form->end(); ?>

</div>
