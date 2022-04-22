<h1>User Access</h1>
<br>
<div class="container">
	<div>
		<h2>Add New Access Password</h2>
		<?php
		echo $this->Form->create('StorePassword');
		echo $this->Form->input('store_id', ['type' => 'select', 'id' => 'stores', 'class' => 'form-control input-sm', 'empty' => false, 'required' => true, 'options' => $storeKeyValuePair]);
		echo $this->Form->input('pin', ['type' => 'password', 'id' => 'storePasswordValue', 'class' => 'form-control input-sm', 'minlength' => 4, 'required' => true]);
		echo '&nbsp;<button type="submit" class="btn btn-purple btn-sm">Add Access Password</button>';
		echo $this->Form->end();
		?>

	</div>
	<br><br>
	<h2>Store Access Passwords</h2>
	<br>
	<?php
	if ($result) {
		?>

		<label for="showStoreAccessPasswords">
			<input type="checkbox" id="showStoreAccessPasswords" name="showStoreAccessPasswords" value="1"
				   onclick="showStoreAccessPasswords(this)"> Show/Hide Access Passwords
		</label>
		<br>
		<table class="table table-condensed table-striped">
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>User</th>
				<th>Email</th>
				<th>Store Name</th>
				<th>Access Password</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($result as $index => $row) {
				$userId = $row['u']['id'];
				$userName = $row['u']['name'];
				$userEmail = $row['u']['email'];
				$userMasterPassword = $row['u']['store_password'];
				$storeName = $row['s']['name'];
				$storeId = $row['s']['store_id'];
				$userStorePasswordId = $row['sp']['id'];
				$userStorePassword = $row['sp']['password'];
				?>
				<tr>
					<td><?php echo $index + 1; ?></td>
					<td><?php echo $userName; ?></td>
					<td><?php echo $userEmail; ?></td>
					<td><?php echo $storeName; ?></td>
					<td>
						<?php
						echo $this->Form->create('StorePassword', ['style' => 'display: inline;']);
						echo $this->Form->hidden('id', ['value' => $userStorePasswordId, 'id' => 'storePasswordId' . $index]);
						echo $this->Form->hidden('store_id', ['value' => $storeId, 'id' => 'storeId' . $index]);
						echo $this->Form->input('pin', ['type' => 'password', 'id' => 'storePasswordValue' . $index, 'value' => $userStorePassword, 'class' => 'input-sm storeAccessPasswords', 'label' => false, 'div' => false, 'minlength' => 4, 'required' => true]);
						echo '&nbsp;<button type="submit" class="btn btn-purple btn-sm">Update</button>';
						echo $this->Form->end();
						?>

						<?php
						if ($userStorePasswordId) {
							echo $this->Form->create('StorePassword', ['url' => '/stores/deleteAcccess', 'style' => 'display: inline;', 'onsubmit' => "return confirm('Are you sure you want to delete store access for \"$storeName\"')"]);
							echo $this->Form->hidden('id', ['value' => $userStorePasswordId, 'id' => 'storePasswordId' . $index]);
							echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
							echo $this->Form->end();
						}
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	} else {
		echo 'No data found';
	}
	?>
</div>
<script>
	function showStoreAccessPasswords(ele) {
		console.log(ele.checked);
		if (ele.checked == true) {
			$('.storeAccessPasswords').attr('type', 'text');
		} else {
			$('.storeAccessPasswords').attr('type', 'password');
		}
	}
</script>
