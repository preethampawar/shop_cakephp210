<?php echo $this->element('message');?>
<div>
	<h1>Edit User - <?php echo $this->data['User']['name'];?></h1><br>
	<?php 
	echo $this->Form->create();
	?>
	<div class="floatLeft" style="width:300px">
	<?php
		echo $this->Form->input('User.name', array('label'=>'Name', 'required'=>true, 'div'=>array('class'=>'required')));
		echo $this->Form->input('User.email', array('label'=>'Email Address', 'required'=>true));		
		echo $this->Form->input('User.phone', array('label'=>'Phone No.', 'required'=>false));
		
		
		$options=array('male'=>'Male','female'=>'Female');
		$attributes=array('legend'=>false,'label'=>false, 'div'=>false, 'separator'=>'&nbsp;&nbsp;&nbsp;', 'escape'=>false, 'style'=>'float:none;');
		echo '<div class="input text"><label>Gender</label>';							
		echo $this->Form->radio('User.gender',$options,$attributes);
		echo '</div>';
		echo $this->Form->input('User.active', array('label'=>'Active', 'required'=>false, 'default'=>'1'));
		echo $this->Form->input('User.registered', array('label'=>'Registered', 'required'=>false, 'default'=>'1'));
	?>
	</div>
	<div class="floatLeft" style="width:30px">&nbsp;</div>
	<div class="floatLeft" style="width:300px">
	<?php
		echo $this->Form->input('User.city', array('label'=>'City', 'required'=>false));
		echo $this->Form->input('User.state', array('label'=>'State', 'required'=>false));
		echo $this->Form->input('User.country', array('label'=>'Country', 'required'=>false));
		echo $this->Form->input('User.zip', array('label'=>'Zip Code', 'required'=>false));
	?>
	</div>
	<div class="clear"></div>
	<?php
	echo $this->Form->submit('Update Account');
	echo $this->Form->end();								
	?>
</div>
<br><br>
<h1>Business/Personal Accounts</h1>

<div style="width:790px; padding:0px 0px 5px 0px;">
	<div class="floatRight button grey medium" onclick="javascript: $('#newCompanyDiv').dialog({ modal: true, minWidth: 450, minHeight:300, title: 'Create New Business/Personal Account' })"> + Add New Account</div>
	<div class="clear"></div>
</div>

<div id="newCompanyDiv" style="max-width:400px; display:none;">
	
	<?php 
		echo $this->Form->create(null, array('url'=>'/admin/users/add_user_company/'.$userID));
		echo $this->Form->input('Company.title', array('label'=>'Company Name', 'required'=>true, 'placeholder'=>'Enter Company Name'));	
		echo $this->Form->input('Company.active', array('label'=>'Active', 'required'=>false));		
		
		$d_p_id='datePickerNew';
		$a_d_p_id = 'altDatePickerNew';
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#".$d_p_id."').focus()"));
		echo $this->Form->input('Company.subscription_end_date', array('label'=>false, 'id'=>$d_p_id, 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="'.$a_d_p_id.'" style="border:0px solid #fff; color:blue; background-color:#fff; float:left;" disabled="disabled">', 'readonly'=>true, 'placeholder'=>'Select Start Date', 'style'=>'width:90%'));
		?>
		<script type="text/javascript">
			$(function() {
				// start date picker
				$( "#<?php echo $d_p_id;?>" ).datepicker({ altFormat: "yy-mm-dd" });
				$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "dateFormat", "yy-mm-dd");
				$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "altField", "#<?php echo $a_d_p_id;?>");
				$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "altFormat", "d M, yy");	
				$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "defaultDate", '' );							
				$( "#<?php echo $d_p_id;?>" ).attr( "value", "<?php echo date('Y-m-d' , strtotime('+1 years'));?>" );						
			});
		</script>		
		<br>
		<?php 
		echo $this->Form->submit('Create New Account', array('div'=>true));
		echo $this->Form->end();
	?>		
</div>

<?php
	if(!empty($userInfo['UserCompany'])) {
	?>
	<table style="width:800px;">
		<tr>
			<th width='200'>Company Name</th>
			<th width='120'>Status</th>
			<th width='180'>Expiry</th>
			<th width='110'>Action</th>
		</tr>

	<?php
		$hasAccounts = false;
		foreach($userInfo['UserCompany'] as $row) {
			if($row['User']['id'] == $row['Company']['user_id']) {
				$hasAccounts = true;
		?>
		<tr>
			<td><?php echo $row['Company']['title'];?></td>
			<td><?php echo ($row['Company']['active']) ? 'Active' : 'InActive';?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Company']['subscription_end_date']));?></td>
			<td>	
				<div class="button grey small floatLeft" onclick="javascript: $('#editCompanyDiv<?php echo $row['Company']['id'];?>').dialog({ modal: true, minWidth: 450, minHeight:300, title: 'Edit Company - <?php echo $row['Company']['title'];?>' })" title="<?php echo $row['Company']['title'];?>"> &nbsp; Edit &nbsp; </div>
				<div class="floatLeft">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo $this->Html->link('Delete', '/admin/users/delete_user_company/'.$row['User']['id'].'/'.$row['Company']['id'], array('class'=>'button grey small'), 'This action will delete all information regarding Company, Data and Users associated with this account. This action is irreversable, data once deleted cannot be recovered. Are you sure you want to perform this action?'); ?>
				</div>
				<div class="clear"></div>
				<div id="editCompanyDiv<?php echo $row['Company']['id'];?>" style="display:none;">
				<?php echo $this->Form->create(null, array('url'=>'/admin/users/edit_company_info/'.$row['User']['id'].'/'.$row['Company']['id']));?>
					
					<?php echo $this->Form->input('Company.title', array('label'=>'Company', 'default'=>$row['Company']['title'], 'required'=>true));?>
					<?php echo $this->Form->input('Company.active', array('label'=>'Active', 'id'=>'active'.$row['Company']['id'], 'required'=>false, 'default'=>$row['Company']['active']));?>
			
					<?php
					$d_p_id='datePicker'.$row['Company']['id'];
					$a_d_p_id = 'altDatePicker'.$row['Company']['id'];
					$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#".$d_p_id."').focus()"));
					echo $this->Form->input('Company.subscription_end_date', array('label'=>'Expiry Date', 'id'=>$d_p_id, 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="'.$a_d_p_id.'" style="border:0px solid #fff; color:blue; background-color:#fff; float:left;" disabled="disabled">', 'readonly'=>true, 'placeholder'=>'Select Start Date', 'style'=>'width:90%;'));
					?>
					<script type="text/javascript">
						$(function() {
							// start date picker
							$( "#<?php echo $d_p_id;?>" ).datepicker({ altFormat: "yy-mm-dd" });
							$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "dateFormat", "yy-mm-dd");
							$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "altField", "#<?php echo $a_d_p_id;?>");
							$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "altFormat", "d M, yy");	
							$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "defaultDate", '' );							
							$( "#<?php echo $d_p_id;?>" ).attr( "value", "<?php echo $row['Company']['subscription_end_date'];?>" );						
						});
					</script>
					<br><br>
					<?php echo $this->Form->submit('Save Changes', array('div'=>false));?>
					
				<?php echo $this->Form->end();?>
				</div>
			</td>
		</tr>
		<?php	
			}
		}
		if(!$hasAccounts) {
		?>
		<tr>
			<td colspan='4'>&nbsp;<?php echo 'No Business/Personal Accounts';?></td>
		</tr>
		<?php
		}
		?>
	</table>	
	<?php
	}
?>