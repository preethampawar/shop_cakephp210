<div>
	<?php echo $this->Form->create('User', ['onsubmit' => "disableButton('loginSubmitButton')"]); ?>
	<h1 class="mb-4">Employee Login</h1>

	<div class="mb-3">
		<label class="form-label">Employee Type</label>
		<select class="form-select" name="data[User][type]" id="UserType">
			<option value="delivery">Delivery Staff</option>
		</select>
	</div>

	<div class="mb-1">
		<label for="exampleFormControlInput1" class="form-label">Mobile Number</label>
		<input
			type="number"
			name="data[User][mobile]"
			class="form-control"
			id="UserMobile"
			placeholder="Enter your 10 digit mobile number ex: 9494555588."
			min="6000000000"
			max="9999999999"
			required
			autofocus>
	</div>
	<div class="mb-4 small text-danger">
		<?php
		$text = "*OTP will be sent to your Email Address.";
		if((bool)$this->Session->read('Site.sms_notifications') === true) {
			$text = "*OTP will be sent to the specified Mobile no. and Email Address.";
		}
		echo $text;
		?>
	</div>
	<div class="mb-3">
		<button type="submit" class="btn btn-md btn-primary" id="loginSubmitButton">Next - Generate OTP</button>
	</div>

	<?php echo $this->Form->end(); ?>
</div>
