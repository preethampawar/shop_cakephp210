<?php 
echo $this->Html->script('https://www.google.com/jsapi', array('inline'=>false));	// Google Javascript API
?>
<style type="text/css">
form div {
    margin-bottom: 10px;
    padding: 0px;
}
</style>

<h1>Yearly Report</h1>
<div id="search" class="corner setBackground" style=" padding:10px 10px 0px 10px;">
<?php echo $this->Form->create();?>	
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">
		<?php
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>	
		
	<div class="floatLeft" style="width:100px; padding:0px; margin-right:10px;">
		<?php 
		$thisYear = date('Y');
		$years = array();
		for($i=0; $i<=100; $i++) {
			$years[$thisYear-$i] = $thisYear-$i;
		}		
		echo $this->Form->input('Report.year', array('options'=>$years, 'label'=>'Select Year', 'empty'=>false));
		?>
	</div>	
	
	<div class="floatLeft" style="width:200px; padding:0px; margin:8px 10px; 0px 0px;">
		<?php echo $this->Form->submit('Generate Report &nbsp;&raquo;', array('escape'=>false, 'div'=>true));?>
	</div>
	
	<div class="clear" style="margin:0px; padding:0px;"></div>
<?php echo $this->Form->end();?>
</div>


<script type="text/javascript">
$(function() {
	// start date picker
	$( "#startdatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#startdatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#startdatepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
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
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
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
<br>
<?php
if(isset($this->data['Report'])) {
	echo $this->element('Reports/income_expense_yearly_visual_report');
}
?>
