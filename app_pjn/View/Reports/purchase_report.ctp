<?php $this->start('reports_menu'); ?>
<?php echo $this->element('reports_menu'); ?>
<?php $this->end(); ?>

<?php
$title_for_layout = 'Purchase Report';
$this->set('title_for_layout', $title_for_layout);
?>
<h1><?php echo $title_for_layout; ?></h1>

<?php echo $this->Form->create('Report', ['target' => '_blank', 'id' => 'PurchaseReportForm']); ?>
<div id="paramsDiv">
	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('category_id', ['empty' => 'All', 'label' => 'Category', 'type' => 'select', 'options' => $productCategoriesList, 'escape' => false, 'onchange' => '$("#PurchaseReportForm").removeAttr("target"); $("#PurchaseReportForm").removeAttr("action"); $("#PurchaseReportForm").submit();', 'style' => 'width:200px;']); ?>
	</div>
	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('product_id', ['empty' => 'All', 'label' => 'Product', 'type' => 'select', 'options' => $productsList, 'escape' => false]); ?>
	</div>
	<div style="float:left; clear:none;">
		<?php
		$options = ['print' => 'Print View'];
		echo $this->Form->input('view_type', ['empty' => 'Normal View', 'label' => 'Select View', 'type' => 'select', 'options' => $options, 'escape' => false]);
		?>
	</div>
	<div style="float:left; clear:both;">
		<?php echo $this->Form->input('from_date', ['label' => 'From Date', 'required' => true, 'type' => 'date']); ?>
	</div>
	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('to_date', ['label' => 'To Date', 'required' => true, 'type' => 'date']); ?>
	</div>
	<div style="float:left; clear:both;">
		<br>
		<?php echo $this->Form->input('show_all_records', ['label' => 'Show all records', 'type' => 'checkbox', 'default' => '1']); ?>
	</div>
	<div style="float:left; clear:none;">
		<?php
		$url = $this->Html->url('/reports/generatePurchaseReport/', true);
		echo $this->Form->submit('Generate Report', ['id' => 'SubmitForm', 'onclick' => '$("#PurchaseReportForm").attr("action","' . $url . '");']); ?>
	</div>
	<div style="clear:both;"></div>
</div>
<?php echo $this->Form->end(); ?>

