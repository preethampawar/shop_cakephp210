<section>
	<div class="text-end">
		<a href="/admin/groups/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<header><h2>Add New Group</h2></header>

		<?= $this->Form->create() ?>

		<div class="form-check form-switch mt-3">
			<input type="hidden" name="data[Group][active]" value="0">
			<input
					type="checkbox"
					id="GroupActive"
					name="data[Group][active]"
					value="1"
					class="form-check-input"
					<?php echo isset($this->data['Group']['active']) && $this->data['Group']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="GroupActive">Active</label>
		</div>

		<div class="mt-3">
			<label for="GroupName">Name <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Group.name', [
					'type' => 'text',
					'placeholder' => 'Enter Group Name',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'minlength' => "2",
					'maxlength' => "255",
					'required' => true,
			]) ?>
		</div>

		<div class="mt-3">
			<label for="GroupRate">Base Rate</label>
			<?= $this->Form->input('Group.rate', [
					'type' => 'number',
					'placeholder' => 'Enter Rate',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'required' => false,
			]) ?>
			<span class="text-danger small">*All products related to this group will be updated accordingly.</span>
		</div>

		<div class="mt-3">
			<label for="GroupRate">Default Paper Rate</label>
			<?= $this->Form->input('Group.default_paper_rate', [
					'type' => 'number',
					'placeholder' => 'Enter Rate',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'required' => false,
			]) ?>
			<span class="text-danger small">*Default rate used to calculate seller product rates</span>
		</div>

		<div class="mt-4">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

		<?= $this->Form->end() ?>
	</article>
</section>
<br><br>
