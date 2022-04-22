<?php
$inventory = false;
if($this->Session->check('UserCompany')) {	
	switch($this->Session->read('Company.business_type')) {
		case 'personal':			
			break;			
		case 'general':
			break;			
		case 'inventory':
			$inventory = true;
			break;			
		case 'wineshop':
			$inventory = true;
			break;			
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>

<?php
if(empty($categories)) {
	echo 'You need to create a category before you add Purchase records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
<h2 class="floatLeft">Modify Purchase Record</h2>

<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/purchases/', array('class'=>'button small red floatRight', 'escape'=>false));?>
<?php echo $this->Form->create('Purchase');?>
	<div style="width:400px;">
	
		<?php
		echo $this->Form->input('category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'required'=>true));
		?>
		<?php		
		echo $this->Form->input('particular', array('label'=>'Particular*', 'required'=>true, 'placeholder'=>'Enter Particular/Purpose', 'style'=>'width:250px;', 'div'=>array('class'=>'floatLeft')));
		?>			
		<div class="floatRight button small grey" style="margin-top:27px;" onclick="$('#groupDiv').css('display', 'block')">Select Group</div>
		<div class='clear'></div>
		<div id="groupDiv" style="display:none;">
			<div class="corner" style="height:200px; overflow:auto; padding:10px; background-color:#fafafa;">
				<?php echo $this->element('select_groups', array('dataID'=>$dataID));?>
			</div>
		</div>
		<?php
		echo ($inventory) ? $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Quantity', 'id'=>'Quantity', 'onchange'=>'setPurchaseTotalAmount()')) : null; 
		echo ($inventory) ? $this->Form->input('unitrate', array('label'=>'Unit Price', 'required'=>false, 'placeholder'=>'Enter Unit Price', 'id'=>'UnitRate', 'onchange'=>'setPurchaseTotalAmount()')) : null; 
		echo $this->Form->input('total_amount', array('label'=>'Total Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'default'=>0, 'placeholder'=>'Enter Total Amount', 'onchange'=>'$("#PaymentAmount").val(this.value)', 'id'=>'TotalAmount')); 
		echo $this->Form->input('payment_amount', array('label'=>'Paid Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'default'=>'0', 'id'=>'PaymentAmount')); 
		
		// echo $this->Form->input('amount', array('label'=>'Total Amount*', 'required'=>true)); 
		echo $this->Form->input('payment_method', array('label'=>'Payment Method', 'options'=>Configure::read('PaymentMethods'), 'escape'=>false, 'empty'=>false, 'required'=>true));
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
		echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:blue;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:90%'));
		
		// echo $this->Form->input('reference', array('label'=>'Reference / Invoice / Receipt No.', 'required'=>false));
		// echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false));
		// echo $this->Form->input('unitrate', array('label'=>'Unit Rate', 'required'=>false));
		
		// echo $this->Form->input('pending_payment', array('label'=>'Pending Payment', 'required'=>false, 'div'=>false, 'type'=>'checkbox', 'before'=>''));
		// echo $this->Form->input('pending_amount', array('label'=>false, 'div'=>true, ));
		echo $this->Form->input('message', array('label'=>'Log Message', 'required'=>false));			
		?>
		<?php echo $this->Form->submit('Save Changes &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;', 'class'=>'floatLeft'));?>
		<?php echo '&nbsp;'.$this->Html->link('Delete &nbsp;&nbsp;&nbsp; x', '/purchases/delete/'.$dataID, array('class'=>'button small red floatRight', 'escape'=>false), 'Are you sure you want to delete this record?');?>	
		<div class='clear'></div>	
	</div>
		<?php 
			echo $this->Form->end();
			// echo '<br>&nbsp;&laquo;&nbsp;'.$this->Html->link('Cancel', '/purchases/');	
		?>
</div>
	<br>
<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d MM, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Purchase']['date'];?>" );
});
</script>


<?php
echo $this->element('datalogs');
?>
<?php
}
?>