<style type="text/css">
	.mb-3 {
		margin-bottom: 1.2rem;
	}
</style>
<div>
	<?php echo $this->Form->create(); ?>
	<h1 class="mb-3">Verify OTP</h1>

	<div class="mb-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">Enter your OTP
			<span class="badge bg-info" data-toggle="tooltip" data-placement="top"
				  title="Enter the OTP which you have received in the email or on mobile">?</span>
		</label>
		<input
			type="text"
			name="data[User][otp]"
			class="form-control"
			id="UserOtp"
			placeholder="Enter OTP"
			minlength="4"
			maxlength="4"
			required
			autofocus
			autocomplete="off"
		>
	</div>

	<div class="mb-3">
		<button type="submit" class="btn btn-md btn-primary">Next - Verify OTP</button>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
