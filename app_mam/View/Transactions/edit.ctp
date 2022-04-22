<?php echo $this->element('message');?>

<?php
if(empty($categories)) {
	echo 'You need to create a category before you add records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
	<h2>Create New Transaction</h2>
	<?php echo $this->Form->create();?>		
		
	<div>	
		<div class="floatLeft" style="width:250px; float:left; margin-right:30px;text-align:left;">
			<b>Select Category</b>
			
			<?php 
			if(!isset($this->data['Transaction']['category_id'])) {
				foreach($categories as $index=>$name) {
					$default = $index;
					break;
				}
			}
			else {
				$default = $this->data['Transaction']['category_id'];	
			}
			echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'18', 'style'=>'border:0px solid #efefef; padding:10px; background:transparent;', 'id'=>'category', 'default'=>$default)); 
			?>
			<?php echo $this->Html->link('+ Add New Category', '/categories/add');?>
		</div>		
		<div class="floatLeft" style="width:300px; float:left; margin-right:35px;">
			<?php		
			$transaction_types = array('debit'=>'Expense', 'credit'=>'Income');			
			$attributes=array('legend'=>false,'label'=>false, 'div'=>false, 'separator'=>'&nbsp;&nbsp;&nbsp;', 'escape'=>false, 'style'=>'float:none;', 'required'=>true);
			echo '<div class="input text required"><label>Transaction Type</label>';							
			echo $this->Form->radio('transaction_type',$transaction_types, $attributes);
			echo '</div>';
			
			echo $this->Form->input('particular', array('label'=>'Transaction Details*', 'required'=>true, 'placeholder'=>'Enter Particular/Purpose'));
			
			echo $this->Form->input('total_amount', array('label'=>'Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'id'=>'PaymentAmount', 'placeholder'=>'Enter Payment Amount')); 
			
			$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
			echo $this->Form->input('date', array('label'=>'Date (Y-m-d)*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));		
			echo $this->Form->submit('Save Transaction &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;'));
			echo '<br>&nbsp;&laquo;&nbsp;'.$this->Html->link('Cancel', '/transactions/');	
			?>
		</div>		
		<div class='clear'></div>
	</div>	
	<?php		
	echo $this->Form->end();
	?>
</div>

<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Transaction']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Transaction']['date'];?>" );
	<?php
	}
	else{
	?>
	$( "#datepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
});

</script>
<?php
}
?>

