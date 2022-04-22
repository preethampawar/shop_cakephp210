<?php echo $this->element('message');?>	
<div>		
	<h1>Modify Account Information:</h1>
	<?php echo $this->Form->create();?>	

	<table style='width:500px;'>		
		<tr>
			<td style='width:150px;'>Email Address</td>
			<td><?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'text', 'div'=>false, 'readonly'=>true));?></td>
		</tr>
		<tr>
			<td style='width:150px;'>Name *</td>
			<td><?php echo $this->Form->input('User.name', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Name', 'value'=>html_entity_decode($this->data['User']['name'])));?></td>
		</tr>
		<tr>
			<td>Phone No *</td>
			<td><?php echo $this->Form->input('User.phone', array('label'=>false, 'type'=>'number', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Phone No.', 'value'=>html_entity_decode($this->data['User']['phone'])));?></td>
		</tr>		
		<tr>
			<td>Street Address</td>
			<td><?php echo $this->Form->input('User.address', array('label'=>false, 'type'=>'textarea', 'rows'=>'2', 'div'=>false, 'placeholder'=>'Enter Street Address', 'value'=>html_entity_decode($this->data['User']['address'])));?></td>
		</tr>
		<tr>
			<td>City</td>
			<td><?php echo $this->Form->input('User.city', array('label'=>false, 'type'=>'text', 'div'=>false, 'placeholder'=>'Enter City', 'value'=>html_entity_decode($this->data['User']['city'])));?></td>
		</tr>
		<tr>
			<td>State</td>
			<td>
				<?php 
				echo $this->element('indian_states_select_box', array('selectedState'=>$this->data['User']['state']));
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
			<td><?php echo $this->Form->input('User.postcode', array('label'=>false, 'type'=>'text', 'div'=>false, 'placeholder'=>'Enter pin code', 'value'=>html_entity_decode($this->data['User']['postcode'])));?></td>
		</tr>				
		<tr>
			<td>&nbsp;</td>
			<td>
				<br>
				<?php echo $this->Form->submit('Save Changes', array('escape'=>false, 'div'=>false, 'class'=>'button small green', 'style'=>'width:150px;'));?>	
				&nbsp;&nbsp;
				<?php echo $this->Html->link('Cancel &nbsp;&raquo;', array('controller'=>'users', 'action'=>'index'), array('escape'=>false, 'class'=>'button'));?>
			</td>
		</tr>
	</table>	
	<?php echo $this->Form->end();
	?>
</div>

<br><br>