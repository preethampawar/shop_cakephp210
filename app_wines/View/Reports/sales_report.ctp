<?php $this->start('reports_menu');?>
<?php echo $this->element('reports_menu');?>
<?php $this->end();?>

<?php 
$title_for_layout = 'Sales Report';
$this->set('title_for_layout',$title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>

<?php echo $this->Form->create('Report', array('target'=>'_blank', 'id'=>'SaleReportForm')); ?>
<div id="paramsDiv">	
	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('category_id', array('empty'=>'All', 'label'=>'Category', 'type'=>'select', 'options'=>$productCategoriesList, 'escape'=>false, 'onchange'=>'$("#SaleReportForm").removeAttr("target"); $("#SaleReportForm").removeAttr("action"); $("#SaleReportForm").submit();', 'style'=>'width:200px;'));?>
	</div>
	<div style="float:left; clear:none; ">
		<?php echo $this->Form->input('product_id', array('empty'=>'All', 'label'=>'Product', 'type'=>'select', 'options'=>$productsList, 'escape'=>false));?>
	</div>
	<div style="float:left; clear:none; ">
		<?php 
		$options = array('print'=>'Print View');
		echo $this->Form->input('view_type', array('empty'=>'Normal View', 'label'=>'Select View', 'type'=>'select', 'options'=>$options, 'escape'=>false));
		?>
	</div>
	<div style="float:left; clear:both;">
		<?php echo $this->Form->input('from_date', array('label'=>'From Date', 'required'=>true, 'type'=>'date'));?>
	</div>
	<div style="float:left; clear:none; ">
		<?php echo $this->Form->input('to_date', array('label'=>'To Date', 'required'=>true, 'type'=>'date'));?>
	</div>
	<div style="float:left; clear:both;">
		<br>
		<?php echo $this->Form->input('show_all_records', array('label'=>'Show all records', 'type'=>'checkbox', 'default'=>'1'));?>
	</div>
	<div style="float:left; clear:none; ">	
		<?php 
		$url=$this->Html->url('/reports/generateSalesReport/', true);
		echo $this->Form->submit('Generate Report', array('id'=>'SubmitForm', 'onclick'=>'$("#SaleReportForm").attr("action","'.$url.'");'));?>
	</div>
	<div style="clear:both;"></div>
</div>
<?php	echo $this->Form->end(); ?>

