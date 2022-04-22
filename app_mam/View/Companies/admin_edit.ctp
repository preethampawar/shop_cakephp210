<div style="width:500px;">
<?php echo $this->element('message');?>
<h1>Edit Company :: <?php echo $companyInfo['Company']['title'];?></h1><br>
<?php 
echo $this->Form->create('Company');
echo $this->Form->input('title', array('label'=>'Name', 'required'=>true));
?>
<?php
	$d_p_id='datePickerNew';
	$a_d_p_id = 'altDatePickerNew';
	$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#".$d_p_id."').focus()"));
	echo $this->Form->input('Company.subscription_end_date', array('label'=>false, 'id'=>$d_p_id, 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="'.$a_d_p_id.'" style="border:0px solid #fff; color:blue; background-color:#fff; float:left;" disabled="disabled">', 'readonly'=>true, 'placeholder'=>'Select Start Date', 'style'=>'width:100px;'));
	?>
	<script type="text/javascript">
		$(function() {
			// start date picker
			$( "#<?php echo $d_p_id;?>" ).datepicker({ altFormat: "yy-mm-dd" });
			$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "dateFormat", "yy-mm-dd");
			$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "altField", "#<?php echo $a_d_p_id;?>");
			$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "altFormat", "d M, yy");	
			$( "#<?php echo $d_p_id;?>" ).datepicker( "option", "defaultDate", '' );

			<?php
			if(isset($this->data['Company']['subscription_end_date'])) {
			?>
			$( "#<?php echo $d_p_id;?>" ).attr( "value", "<?php echo $this->data['Company']['subscription_end_date'];?>" );
			<?php
			}
			else{
			?>
			$( "#<?php echo $d_p_id;?>" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
			<?php
			}	
			?>									
		});
	</script>
<?php
$options = Configure::read('BusinessAccounts');
$attributes=array('legend'=>false,'label'=>false, 'div'=>false, 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;', 'escape'=>false, 'style'=>'float:none;', 'required'=>true);
echo '<div class="input text required"><label>Account Type</label>';							
echo $this->Form->radio('business_type',$options, $attributes);
echo '</div>';
echo $this->Form->input('active', array('label'=>'Active', 'required'=>false));
echo $this->Form->submit('Save Changes');
echo $this->Form->end();								
?>
</div>
<br><br>
<?php
if(!empty($companyInfo['UserCompany'])) {
?>
	<table style="width:700px;">
		<tr>
			<th>Sl.No.</th>
			<th>Name</th>
			<th>Email</th>			
			<th>Access Type</th>
		</tr>
		<?php
		$i=0;
		foreach($companyInfo['UserCompany'] as $row) {
			$i++;
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $row['User']['name'];?></td>
			<td><?php echo $this->Html->link($row['User']['email'], '/admin/users/edit/'.$row['User']['id']);?></td>
			<td>
				<?php 
				$userLevel = Configure::read('UserLevel.'.$row['user_level']);
				echo ($row['Company']['user_id'] == $row['User']['id']) ? '<b>'.$userLevel.' - Owner</b>' : $userLevel;						
				?>
			</td>
		</tr>
		<?php
		}
		?>
	</table>
<?php	
}
?>