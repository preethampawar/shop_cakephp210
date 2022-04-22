<?php echo $this->Html->link('+ Add Closing Stock', array('controller'=>'available_stock', 'action'=>'add'), array('class'=>'button medium grey floatRight'));?>
<h1>Closing Stock Report</h1>
<div class="clear"></div>
<br>
<?php
if(!empty($availableStock)) {
?>
	<?php echo $this->element('available_stock_list', array('availableStock'=>$availableStock));?>	
	<br>
	<?php echo $this->Paginator->prev(' << ' . __('previous'), array(), null, array('class' => 'prev disabled'));?>
	&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->numbers();?>&nbsp;&nbsp;&nbsp;
	<?php echo $this->Paginator->next(__('next').' >>' , array(), null, array('class' => 'next disabled'));?>
<?php	
}
else {
	echo '&nbsp;Data Not Available.<br> <br>';	
}
?>

