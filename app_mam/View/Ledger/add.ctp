<?php 
echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom', false);
echo $this->Html->script('jquery-ui-1.8.18.custom.min', array('inline'=>false));
?>
<h1>Sales Book</h1><br>
<div style="width:600px; margin:auto">
<h2>Add New Sales Record</h2><br>
<?php 
	echo $this->Form->create('Sale', array('controller'=>'sales', 'action'=>'add'));	
	echo $this->Form->input('category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'required'=>true));
	echo $this->Form->input('particular', array('label'=>'Particular', 'required'=>true));
	echo $this->Form->input('reference', array('label'=>'Reference / Invoice / Receipt No.', 'required'=>false));
	echo $this->Form->input('unitrate', array('label'=>'Unit Rate', 'required'=>false));
	echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false));
	echo $this->Form->input('amount', array('label'=>'Total Amount', 'required'=>true)); 
	echo $this->Form->input('payment_method', array('label'=>'Payment Method', 'options'=>Configure::read('PaymentMethods'), 'escape'=>false, 'empty'=>false, 'required'=>true));
	echo $this->Form->input('date', array('label'=>'Date', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'<input type="text" id="alternate" style="border:0px solid #fff;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar'));
?>
	
<?php	
	
	
	echo $this->Form->submit('Add New Record &nbsp;&raquo;', array('escape'=>false));
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
	if(isset($this->data['Sale']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Sale']['date'];?>" );
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