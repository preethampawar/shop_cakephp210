<div>
	<?php echo $this->Form->create(); ?>
	<h1 class="mb-3">Register</h1>

	<div class="mb-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Email Address
		</label>
		<input
			type="email"
			name="data[User][email]"
			class="form-control"
			id="UserEmail"
			placeholder="Enter your email address"
			maxlength="55"
			required
			autofocus>
	</div>

	<div class="mb-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Mobile Number
		</label>
		<input
			type="tel"
			name="data[User][mobile]"
			class="form-control"
			id="UserMobile"
			placeholder="Enter your 10 digit mobile number"
			minlength="10"
			maxlength="10"
			required
			autofocus>
	</div>
	<div class="mb-3">
		<button type="submit" class="btn btn-md btn-primary">Next - Generate OTP</button>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
