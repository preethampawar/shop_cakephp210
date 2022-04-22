<?php echo $this->element('group_paper_rates_menu');?>

<h1 class="">Add New Rate</h1>
<br>

<?= $this->Form->create('GroupPaperRate', ['url' => '/GroupPaperRates/add/']); ?>

<div class="mt-3">
	<label class="form-label">Group * (<a href="/Groups/add" class="small">+Add New</a>)</label>
	<?php
	if (!empty($groupList)) {
		echo $this->Form->select('group_id', $groupList, ['empty' => false, 'class' => 'form-control form-control-sm']);
		?>

		<?php
	} else {
		echo '<div class="text-muted small mt-3">Please create a new group to log paper rates.</div>';
		return;
	}
	?>
</div>

<div class="mt-3">
	<label class="form-label">Date *</label>
	<input name="data[GroupPaperRate][date]" type="date" class="form-control form-control-sm" value="<?= $this->Session->check('date') ? $this->Session->read('date') : date('Y-m-d') ?>" required>
</div>

<div class="mt-3">
	<label class="form-label">Rate *</label>
	<?= $this->Form->input('rate', ['type' => 'number', 'label' => false, 'required' => true, 'class' => 'form-control form-control-sm', 'default' => 0, 'min' => 1]); ?>
</div>

<div class="mt-4 text-center">
	<button type="submit" class="btn btn-primary btn-sm">Submit</button>
	&nbsp;&nbsp; <a href="/GroupPaperRates/" class="btn btn-outline-warning btn-sm">Cancel</a>
</div>

<?= $this->Form->end() ?>

<br><br>
