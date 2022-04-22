<h1>Step2 - Store Access</h1>
<br>
<div class="row">
	<div class="col">
		<?php echo $this->Form->create(); ?>

		<?php
		$selectedUserType = '';
		if (isset($this->data['Store']['user_type'])) {
			$selectedUserType = $this->data['Store']['user_type'];
		}
		?>


		<label>Select User Type</label>
		<select name="data[Store][user_type]" class="form-control input-sm">
			<option value="admin" <?php echo $selectedUserType == 'admin' ? 'selected' : null; ?>>Admin</option>
			<option value="user"<?php echo $selectedUserType == 'user' ? 'selected' : null; ?>>Store User</option>
		</select>
		<br>


		<label>Store Password</label>
		<input type="password" name="data[Store][access_password]" placeholder="Enter store password"
			   class="form-control input-sm" minlength="4" required>
		<br>

		<button type="submit" class="form-control btn btn-purple btn-md">Submit</button>


		<?php echo $this->Form->end(); ?>
	</div>
</div>
