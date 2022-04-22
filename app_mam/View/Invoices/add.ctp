<?php echo $this->element('message');?>

<?php
if(empty($categories)) {
	echo 'You need to create a categor/product before you add records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<?php echo $this->Form->create(null, array('id'=>'InvoiceAddForm'));?>		

<h2>Create New Invoice</h2>		
<div style="margin:0px;">		
	<div class="floatLeft" style="width:200px; float:left; margin-right:25px;">
		<?php			
		echo $this->Form->input('name', array('label'=>'Invoice No.', 'required'=>true, 'placeholder'=>'Enter Invoice No.'));			
		?>
	</div>		
		
	<div class="floatLeft" style="width:250px; float:left; margin-right:25px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
		echo $this->Form->input('date', array('label'=>'Date (Y-m-d)*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent; padding:0px; margin:0px; font-size:85%;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));
		?>
	</div>	
	<div class="floatLeft" style="width:250px; float:left; margin-right:25px;">
		<?php
		echo $this->Form->submit('Create &raquo;', array('style'=>'margin-top:7px;', 'escape'=>false));
		?>
	</div>		
	<div class='clear'></div>
</div>	

<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Invoice']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Invoice']['date'];?>" );
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

<?php echo $this->Form->end();?>
<?php
}
?>
