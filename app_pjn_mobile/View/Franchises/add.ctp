<?php $this->start('franchise_menu'); ?>
<?php echo $this->element('franchise_menu'); ?>
<?php $this->end(); ?>

	<a href="/franchises/" class=""> &laquo; Back to franchise list</a><br>
	<h1>Create Franchise</h1>
<?php echo $this->Form->create(); ?>
	<table class=" table-condensed" style="width:500px;">
		<tbody>
		<tr>
			<td>Name</td>
			<td><?php echo $this->Form->input('name', ['placeholder' => 'Enter Franchise Name', 'label' => false, 'required' => true, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><?php echo $this->Form->input('is_active', ['type' => 'checkbox',]); ?></td>
		</tr>
		<tr>
			<td>Code</td>
			<td><?php echo $this->Form->input('code', ['placeholder' => 'Enter Franchise Code', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>Login Pin</td>
			<td><?php echo $this->Form->input('login_pin', ['placeholder' => 'Login Pin', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>Mobile No.</td>
			<td><?php echo $this->Form->input('mobile', ['placeholder' => 'Enter Mobile No.', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>Email Address</td>
			<td><?php echo $this->Form->input('email', ['placeholder' => 'Enter Email Address', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>City</td>
			<td><?php echo $this->Form->input('city', ['placeholder' => 'Enter City', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>District</td>
			<td><?php echo $this->Form->input('district', ['placeholder' => 'Enter District', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>State</td>
			<td><?php echo $this->Form->input('state', ['placeholder' => 'Enter State', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>Country</td>
			<td><?php echo $this->Form->input('country', ['placeholder' => 'Enter Country', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td>Address</td>
			<td><?php echo $this->Form->input('address1', ['placeholder' => 'Enter Address', 'label' => false, 'class' => 'form-control input-sm']); ?></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center">
				<br>
				<button class="btn btn-purple" type="submit">Create Franchise</button>
				<br><br>
				<a href="/franchises/" class="btn btn-sm btn-warning">Cancel</a>
			</td>
		</tr>
		</tbody>
	</table>
<?php echo $this->Form->end(); ?>
