<?php echo $this->element('message');?>
<div>
	<h1>Edit User</h1><br>
	<?php 
	echo $this->Form->create();
	?>
	<div class="floatLeft" style="width:300px">
	<?php
		echo $this->Form->input('User.name', array('label'=>'Name', 'required'=>true, 'div'=>array('class'=>'required')));
		echo $this->Form->input('User.email', array('label'=>'Email Address', 'required'=>true));		
		echo $this->Form->input('User.phone', array('label'=>'Phone No.', 'required'=>true, 'div'=>array('class'=>'required')));
		echo $this->Form->input('User.company_name', array('label'=>'Company', 'required'=>true, 'empty'=>false, 'div'=>array('class'=>'required')));
		
		$options=array('male'=>'Male','female'=>'Female');
		$attributes=array('legend'=>false,'label'=>false, 'div'=>false, 'separator'=>'&nbsp;&nbsp;&nbsp;', 'escape'=>false, 'style'=>'float:none;');
		echo '<div class="input text required"><label>Gender</label>';							
		echo $this->Form->radio('User.gender',$options,$attributes);
		echo '</div>';
		echo $this->Form->input('User.active', array('label'=>'Active', 'required'=>false, 'default'=>'1'));
	?>
	</div>
	<div class="floatLeft" style="width:30px">&nbsp;</div>
	<div class="floatLeft" style="width:300px">
	<?php
		echo $this->Form->input('User.city', array('label'=>'City', 'required'=>false));
		echo $this->Form->input('User.state', array('label'=>'State', 'required'=>false));
		echo $this->Form->input('User.country', array('label'=>'Country', 'required'=>false));
		echo $this->Form->input('User.zip', array('label'=>'Zip Code', 'required'=>false));
		echo $this->Form->input('User.user_level', array('label'=>'Access Level', 'required'=>true, 'empty'=>false, 'options'=>Configure::read('UserLevel')));
	?>
	</div>
	<div class="clear"></div>
	<?php
	echo $this->Form->submit('Update Account');
	echo $this->Form->end();								
	?>
</div>