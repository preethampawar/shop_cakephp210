<?php $this->start('reports_menu');?>
<?php echo $this->element('reports_menu');?>
<?php $this->end();?>

<h1>Snapshot Report</h1><br>

<?php echo $this->Form->create('Report', ['url' => '/reports/generateSnapshotReport/', 'target'=> '_blank', 'id' => 'SnapshotReport']); ?>
	<div class="mt-3">
		<?php //echo $this->Form->input('category_id', ['empty' => 'All', 'label' => 'Select Category', 'type' => 'select', 'options' => $categoriesList, 'escape' => false, 'class' => 'form-control form-control-sm']); ?>
		<?php
		$options = array('print'=>'Print View');
		echo $this->Form->input('view_type', array('empty'=>'Normal View', 'label'=>'Select View', 'type'=>'select', 'options'=>$options, 'escape'=>false, 'class' => 'form-control form-control-sm'));
		?>
	</div>

	<div class="mt-3">
		<label class="form-label">From Date *</label>
		<input name="data[Report][from_date]" type="date" class="form-control form-control-sm" value="<?= date('Y-m') ?>-01" required>
	</div>
	<div class="mt-3">
		<label class="form-label">To Date *</label>
		<input name="data[Report][to_date]" type="date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
	</div>


	<div class="mt-4">
		<?php echo $this->Form->submit('Generate Report', ['id' => 'SubmitForm', 'title' => '', 'type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'return submitButtonMsg()']); ?>
	</div>

<?php
echo $this->Form->end();
?>
