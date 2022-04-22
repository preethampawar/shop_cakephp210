<?php echo $this->element('message'); ?>
<div>
	<h1>Modify Account Information:</h1>
	<?php echo $this->Form->create(); ?>

	<table style='width:500px;'>
		<tr>
			<td style='width:150px;'>Email Address</td>
			<td><?php echo $this->Form->input('User.email', ['label' => false, 'type' => 'text', 'div' => false, 'readonly' => true]); ?></td>
		</tr>
		<tr>
			<td style='width:150px;'>Name *</td>
			<td><?php echo $this->Form->input('User.name', ['label' => false, 'type' => 'text', 'div' => false, 'required' => true, 'placeholder' => 'Enter Name', 'value' => html_entity_decode($this->data['User']['name'])]); ?></td>
		</tr>
		<tr>
			<td>Phone No *</td>
			<td><?php echo $this->Form->input('User.phone', ['label' => false, 'type' => 'number', 'div' => false, 'required' => true, 'placeholder' => 'Enter Phone No.', 'value' => html_entity_decode($this->data['User']['phone'])]); ?></td>
		</tr>
		<tr>
			<td>Street Address</td>
			<td><?php echo $this->Form->input('User.address', ['label' => false, 'type' => 'textarea', 'rows' => '2', 'div' => false, 'placeholder' => 'Enter Street Address', 'value' => html_entity_decode($this->data['User']['address'])]); ?></td>
		</tr>
		<tr>
			<td>City</td>
			<td><?php echo $this->Form->input('User.city', ['label' => false, 'type' => 'text', 'div' => false, 'placeholder' => 'Enter City', 'value' => html_entity_decode($this->data['User']['city'])]); ?></td>
		</tr>
		<tr>
			<td>State</td>
			<td>
				<?php
				echo $this->element('indian_states_select_box', ['selectedState' => $this->data['User']['state']]);
				?>
			</td>
		</tr>
		<tr>
			<td>Country</td>
			<td>
				<?php
				$options = ['India' => 'India'];
				echo $this->Form->input('User.country', ['options' => $options, 'label' => false]);
				?>
			</td>
		</tr>
		<tr>
			<td>Pin Code</td>
			<td><?php echo $this->Form->input('User.postcode', ['label' => false, 'type' => 'text', 'div' => false, 'placeholder' => 'Enter pin code', 'value' => html_entity_decode($this->data['User']['postcode'])]); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<br>
				<?php echo $this->Form->submit('Save Changes', ['escape' => false, 'div' => false, 'class' => 'button small green', 'style' => 'width:150px;']); ?>
				&nbsp;&nbsp;
				<?php echo $this->Html->link('Cancel &nbsp;&raquo;', ['controller' => 'users', 'action' => 'index'], ['escape' => false, 'class' => 'button']); ?>
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end();
	?>
</div>

<br><br>
