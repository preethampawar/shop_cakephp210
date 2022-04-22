<?php 
echo $this->Html->script('https://www.google.com/jsapi', array('inline'=>false));	// Google Javascript API
?>
<style type="text/css">
form div {
    margin-bottom: 10px;
    padding: 0px;
}
</style>

<h1>Generate Day to Day Report</h1>
<div class="clear"></div>
<div id="search" class="corner setBackground" style=" padding:10px 10px 0px 10px;">
<?php echo $this->Form->create();?>
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">
		<?php
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>	
		
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Report.startdate', array('label'=>'From Date', 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select From Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#enddatepicker').focus()"));
		echo $this->Form->input('Report.enddate', array('label'=>'To Date', 'id'=>'enddatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate2" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select To Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px; margin-top:8px;">
		<?php echo $this->Form->submit('Generate Report &nbsp;&raquo;', array('escape'=>false));?>
	</div>
	
	<div class="clear"></div>
<?php echo $this->Form->end();?>
</div>


<script type="text/javascript">
$(function() {
	// start date picker
	$( "#startdatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#startdatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#startdatepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
	$( "#startdatepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Report']['startdate'])) {
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo $this->data['Report']['startdate'];?>" );
	<?php
	}
	else{
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
	
	// end date picker
	$( "#enddatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#enddatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#enddatepicker" ).datepicker( "option", "altField", "#alternate2");
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
	$( "#enddatepicker" ).datepicker( "option", "defaultDate", '');
	<?php
	if(isset($this->data['Report']['enddate'])) {
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo $this->data['Report']['enddate'];?>" );
	<?php
	}
	else{
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
});

</script>

<?php
echo $this->element('Reports/income_expense_daterange_visual_report');
?>
