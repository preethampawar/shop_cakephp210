<?php 
$title_for_layout = 'DD Report';
$this->set('title_for_layout',$title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>
<script type="text/javascript">
function toggleDateDDReport(){
	var dd_id = $('#ReportDdId').val();
	if(parseInt(dd_id) > 0) {
		$('.ddReportDate').css('display', 'none');
	}
	else {
		$('.ddReportDate').css('display', 'block');	
	}
}
</script>
<?php echo $this->Form->create('Report', array('target'=>'_blank', 'url'=>'/reports/generateDdReport/')); ?>
<div id="paramsDiv">			
	
	<div style="float:left; clear:none;" class="ddReportDate">
		<?php echo $this->Form->input('from_date', array('label'=>'From Date', 'required'=>true, 'type'=>'date'));?>
	</div>
	<div style="float:left; clear:none; border-left:2px solid #eee;" class="ddReportDate">
		<?php echo $this->Form->input('to_date', array('label'=>'To Date', 'required'=>true, 'type'=>'date'));?>
	</div>
	<div style="float:left; clear:both;">
		<?php 
		$options = array('print'=>'Print View');
		echo $this->Form->input('view_type', array('empty'=>'Normal View', 'label'=>'Select View', 'type'=>'select', 'options'=>$options, 'escape'=>false));
		?>
	</div>
	<div style="float:left; clear:none; border-left:2px solid #eee;">	
		<?php echo $this->Form->submit('Generate Report');?>
	</div>
	<div style="clear:both;"></div>
</div>
<?php	echo $this->Form->end(); ?>
<script type="text/javascript">toggleDateDDReport();</script>

