<?php $this->start('reports_menu'); ?>
<?php echo $this->element('reports_menu'); ?>
<?php $this->end(); ?>

<?php
$title_for_layout = 'Dealer Brand Purchase Report';
$this->set('title_for_layout', $title_for_layout);
?>
<h1><?php echo $title_for_layout; ?></h1>

<?php echo $this->Form->create('Report', ['target' => '_blank', 'id' => 'PurchaseReportForm']); ?>
<div id="paramsDiv">
	<div style="float:left; clear:none;">
		<?php
		$options = ['show_brand_purchase_report' => 'Dealer Brand Purchase Report', 'show_product_purchase_report' => 'Dealer Product Purchase Report'];
		echo $this->Form->input('report_type', ['label' => 'Select Report Type', 'type' => 'select', 'options' => $options, 'escape' => false, 'default' => 'show_brand_purchase_report']);
		?>
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
		<?php echo $this->Form->input('dealer_id', ['label' => 'Dealer', 'type' => 'select', 'options' => $dealersList, 'escape' => false, 'onchange' => '$("#PurchaseReportForm").removeAttr("target"); $("#PurchaseReportForm").removeAttr("action"); $("#PurchaseReportForm").submit();', 'style' => 'width:200px;', 'multiple' => 'multiple', 'class' => 'autoSuggest']); ?>
	</div>
	<div style="float:left; clear:none;">
		<?php echo $this->Form->input('brand_id', ['label' => 'Brand', 'type' => 'select', 'options' => $brandsList, 'escape' => false, 'style' => 'width:200px;', 'multiple' => 'multiple', 'class' => 'autoSuggest']); ?>
	</div>

	<div style="float:left; clear:both;">
		<?php
		$url = $this->Html->url('/reports/generateDealerBrandPurchases/', true);
		echo $this->Form->submit('Generate Report', ['id' => 'SubmitForm', 'onclick' => '$("#PurchaseReportForm").attr("action","' . $url . '");']); ?>
	</div>
	<div style="clear:both;"></div>
</div>
<?php echo $this->Form->end(); ?>

