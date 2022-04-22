<section>
	<div class="text-end">
		<a href="/admin/banners/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<header><h2>Add New Banner</h2></header>

		<?= $this->Form->create() ?>

		<div class="form-check form-switch mt-3">
			<input type="hidden" name="data[Banner][active]" value="0">
			<input
					type="checkbox"
					id="BannerActive"
					name="data[Banner][active]"
					value="1"
					class="form-check-input"
					<?php echo isset($this->data['Banner']['active']) && $this->data['Banner']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="BannerActive">Active</label>
		</div>

		<div class="mt-3">
			<label for="BannerTitle">Title <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Banner.title', [
				'type' => 'text',
				'placeholder' => 'Enter Title',
				'label' => false,
				'class' => 'form-control form-control-sm',
				'minlength' => "2",
				'maxlength' => "55",
				'required' => true,
			]) ?>
		</div>
		<div class="mt-3">
			<label for="BannerDescription">Description</label>
			<?= $this->Form->input('Banner.description', [
				'type' => 'textarea',
				'placeholder' => 'Enter Title',
				'label' => false,
				'class' => 'form-control form-control-sm',
				'rows' => "2",
			]) ?>
		</div>
		<div class="mt-3">
			<label for="BannerUrl">Redirection URL</label>
			<?= $this->Form->input('Banner.url', [
					'type' => 'url',
					'placeholder' => 'Enter Redirection URL',
					'label' => false,
					'class' => 'form-control form-control-sm',
			]) ?>
		</div>
		<div class="mt-4">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

		<?= $this->Form->end() ?>
	</article>
</section>
<br><br>
