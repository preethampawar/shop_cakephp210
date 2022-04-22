<?php
$inventory = false;
$wineStore = false;
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
			$wineStore = true;
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
	echo 'You need to create a category before you add Cash records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>

	
	<h2 class="floatLeft">Modify Cash Record</h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/cash/', array('class'=>'button small red floatRight', 'escape'=>false));?>	
	<?php echo $this->Form->create();	?>
	<div style="width:400px;">
		
			<?php
			echo $this->Form->input('Cash.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'required'=>true));
			?>
			<?php		
			if($wineStore) {
				echo $this->Form->input('invoice_id', array('label'=>'Invoice no.', 'required'=>false, 'empty'=>'-'));
			}
			
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
			if(false) {
				?>
				<div id="" style="padding:0px; margin:0px;" class="">						
					<div style="width:110px; margin-right:10px;" class="floatLeft">
						<?php echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Qty.', 'onchange'=>'setPurchaseTotalAmount()', 'id'=>'Quantity')); ?>
					</div>						
					<div style="width:110px; margin-right:0px;" class="floatRight">							
						<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price ('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'setPurchaseTotalAmount()', 'placeholder'=>'')); ?>
					</div>
					<div class="clear" style="padding:0px; margin:0px;"></div>
				</div>		
				<?php					
			}
			// echo $this->Form->input('Cash.particular', array('label'=>'Particular*', 'required'=>true));
			
			echo $this->Form->input('total_amount', array('label'=>'Total Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'default'=>0, 'placeholder'=>'Enter Total Amount', 'onchange'=>'$("#PaymentAmount").val(this.value)', 'id'=>'TotalAmount')); 
			echo $this->Form->input('payment_amount', array('label'=>'Payment Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'default'=>'0', 'id'=>'PaymentAmount')); 
			
			// echo $this->Form->input('Cash.amount', array('label'=>'Total Amount*', 'required'=>true)); 
			echo $this->Form->input('Cash.transaction_type', array('label'=>'Credit/Debit', 'options'=>Configure::read('TransactionTypes'), 'escape'=>false, 'empty'=>false, 'required'=>true));
			$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
			echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:blue;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:90%'));
			?>
			
			<?php
			// echo $this->Form->input('Cash.reference', array('label'=>'Reference / Invoice / Receipt No.', 'required'=>false));
			// echo $this->Form->input('Cash.quantity', array('label'=>'Quantity', 'required'=>false));
			// echo $this->Form->input('Cash.unitrate', array('label'=>'Unit Rate', 'required'=>false));
			
			echo $this->Form->input('Cash.message', array('label'=>'Message', 'required'=>false));			
			?>
		
		<?php echo $this->Form->submit('Save Changes &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin:0px;', 'class'=>'floatLeft'));?>
		<?php echo '&nbsp;'.$this->Html->link('Delete &nbsp;&nbsp;&nbsp; x', '/cash/delete/'.$dataID, array('class'=>'button small red floatRight', 'escape'=>false), 'Are you sure you want to delete this record?');?>	
		<div class='clear'></div>	
	</div>
	<?php
		echo $this->Form->end();
		//echo '<br>&nbsp;&laquo;&nbsp;'.$this->Html->link('Cancel', '/cash/');	
	?>

<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Cash']['date'];?>" );
});
</script>

<br><br>
<?php
echo $this->element('datalogs');
?>
<?php
}
?>
