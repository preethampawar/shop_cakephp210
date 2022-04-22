<?php
echo $this->element('get_category_price');

$inventory = false;
$isWineStore = false;
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
			$isWineStore = true;
			break;				
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>

<?php echo $this->element('message');?>

<?php
if(empty($categories)) {
	echo 'You need to create a category before you add Sale records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
	<h2 class="floatLeft">Modify Sales Record: # <?php echo $dataID;?></h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/sales/', array('class'=>'button small red floatRight', 'escape'=>false));	?>
	<?php echo $this->Form->create();?>		
		
	<div>	
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Category/Product</b></div>
				<?php 				
				if(!isset($this->data['Sale']['category_id'])) {
					foreach($categories as $index=>$name) {
						$default = $index;
						break;
					}
				}
				else {
					$default = $this->data['Sale']['category_id'];	
				}
				echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'23', 'style'=>'border:0px solid #efefef; padding:0px; background:transparent; font-size:95%;', 'id'=>'category', 'default'=>$default)); 
				?>
				<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
			</div>			
		</div>		
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;">
			<div class="corner contentDiv">
				<?php
				$label = 'Particular/Description *';
				$placeholder = 'Enter Particular/Description';
				if($this->Session->read('Company.business_type') == 'wineshop') {
					$label = 'Invoice No.*';
					$placeholder = 'Enter Invoice No.';
				}	
				if($isWineStore) {
					echo $this->Form->input('particular', array('label'=>$label, 'required'=>false, 'type'=>'hidden', 'placeholder'=>$placeholder));
				}
				else {
					echo $this->Form->input('particular', array('label'=>$label, 'required'=>true, 'placeholder'=>$placeholder));					
				}
				if($inventory) {
					?>
					<div id="" style="padding:0px; margin:0px;" class="">						
						<div style="width:110px; margin-right:10px;" class="floatLeft">
							<?php echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Qty.', 'onchange'=>'calculateTotal()', 'id'=>'Quantity')); ?>
						</div>						
						<div style="width:110px; margin-right:0px;" class="floatRight">							
							<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price ('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'calculateTotal()')); ?>
						</div>
						<div class="clear" style="padding:0px; margin:0px;"></div>
					</div>		
					<?php					
				}
				else {
					echo $this->Form->input('quantity', array('type'=>'hidden', 'value'=>'1', 'required'=>false, 'id'=>'Quantity'));
				}
				echo $this->Form->input('total_amount', array('label'=>'Total Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'placeholder'=>'Enter Total Amount', 'onchange'=>'$("#PaymentAmount").val(this.value)', 'id'=>'TotalAmount')); 
				echo $this->Form->input('payment_amount', array('label'=>'Received Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'PaymentAmount', 'placeholder'=>'Enter Payment Amount')); 
				
				if($isWineStore) {
					echo $this->Form->hidden('payment_method', array('value'=>'cash'));
				}
				else {
					echo $this->Form->input('payment_method', array('label'=>'Payment Method', 'options'=>Configure::read('PaymentMethods'), 'escape'=>false, 'empty'=>false, 'required'=>true));
				}
				$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
				echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));		
				echo $this->Form->submit('Save Changes &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;', 'div'=>array('style'=>'text-align:center;')));
				?>
			</div>
		</div>
		<div class="floatLeft" style="width:200px; float:left; text-align:center; margin-right:0px;">
			<div class="corner groupSelectionDiv">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Group</b></div>
				<div style="padding:0px; height:365px; overflow:auto; background:transparent; padding:8px; text-align:left;">
				<?php echo $this->element('select_groups', array('dataID'=>$dataID));?>
				</div>
				<?php echo $this->Html->link('+ Add New Group', '/groups/add', array('style'=>'text-decoration:none;'));?>
			</div>				
		</div>	
		<div class='clear'></div>
	</div>	
	<?php echo $this->Form->end();?>
</div>
<br>
<?php
echo $this->element('datalogs');
?>
<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Sale']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Sale']['date'];?>" );
	<?php
	}
	else{
		if($this->Session->check('PrevDate')) {
			$date = $this->Session->read('PrevDate');
		}
		else {
			$date = date('Y-m-d');
		}
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $date;?>" );	
	<?php
	}	
	?>
});

</script>
<?php
}
?>
