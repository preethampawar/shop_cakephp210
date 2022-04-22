<style type="text/css">
	.mb-3 {
		margin-bottom: 1.2rem;
	}
</style>
<div>
	<?php echo $this->Form->create('User', ['onsubmit' => "showRequestProcessingMsg('#loginVerifyOtp')", "id" =>"loginVerifyOtpForm"]); ?>
	<h1 class="mb-3">Verify OTP</h1>

	<div class="mb-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Enter your OTP			
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
		<button type="submit" class="btn btn-md btn-primary" id="loginVerifyOtp">Next - Verify OTP</button>
	</div>
	<?php echo $this->Form->end(); ?>

	<div class="alert alert-warning mt-4 p-2 small">
		Note: You will receive OTP in your Email. Also, check "SPAM" folder if you don't find it in Inbox.
	</div>
</div>
