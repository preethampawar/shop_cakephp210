<?php echo $this->element('message');?>	
<div>		
	<h1>Create New Account</h1>
	<?php echo $this->Form->create();?>	

	<table style='width:500px;'>		
		<tr>
			<td style='width:150px;'>Site *</td>
			<td><?php echo $this->Form->input('User.site_id', array('label'=>false, 'options'=>$sites, 'div'=>false, 'required'=>true));?></td>
		</tr>
		<tr>
			<td style='width:150px;'>Name *</td>
			<td><?php echo $this->Form->input('User.name', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Name..'));?></td>
		</tr>
		<tr>
			<td style='width:150px;'>Email Address*</td>
			<td><?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true));?></td>
		</tr>
		
		<tr>
			<td style='width:150px;'>Password *</td>
			<td><?php echo $this->Form->input('User.password', array('label'=>false, 'type'=>'password', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Password..'));?></td>
		</tr>
		<tr>
			<td style='width:150px;'>Confirm Password *</td>
			<td><?php echo $this->Form->input('User.confirm_password', array('label'=>false, 'type'=>'password', 'div'=>false, 'required'=>true, 'placeholder'=>'Confirm Your Password..'));?></td>
		</tr>
		
		<tr>
			<td>Phone No *</td>
			<td><?php echo $this->Form->input('User.phone', array('label'=>false, 'type'=>'number', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Phone No..'));?></td>
		</tr>		
		<tr>
			<td>Street Address</td>
			<td><?php echo $this->Form->input('User.address', array('label'=>false, 'type'=>'text', 'div'=>false, 'placeholder'=>'Enter Street Address..'));?></td>
		</tr>
		<tr>
			<td>City</td>
			<td><?php echo $this->Form->input('User.city', array('label'=>false, 'type'=>'text', 'div'=>false, 'placeholder'=>'Enter City..'));?></td>
		</tr>
		<tr>
			<td>State</td>
			<td>
				<?php 
				echo $this->element('indian_states_select_box', array('selectedState'=>null));
				?>
			</td>
		</tr>
		<tr>
			<td>Country</td>
			<td>
				<?php 
				$options = array('India'=>'India');
				echo $this->Form->input('User.country', array('options'=>$options, 'label'=>false));
				?>
			</td>
		</tr>
		<tr>
			<td>Pin Code</td>
			<td><?php echo $this->Form->input('User.postcode', array('label'=>false, 'type'=>'text', 'div'=>false, 'placeholder'=>'Enter pin code..'));?></td>
		</tr>				
		<tr>
			<td>&nbsp;</td>
			<td>
				<br>
				<?php echo $this->Form->submit('Save Changes', array('escape'=>false, 'div'=>false, 'class'=>'button small green', 'style'=>'width:150px;'));?>	
				&nbsp;&nbsp;
				<?php echo $this->Html->link('Cancel &raquo;', array('controller'=>'users', 'action'=>'index'), array('escape'=>false));?>
			</td>
		</tr>
	</table>	
	<?php echo $this->Form->end();
	?>
</div>

<br><br>