<section>
	<div class="text-end">
		<a href="/admin/testimonials/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<header><h2>Add New Testimonial</h2></header>

		<?= $this->Form->create() ?>

		<div class="form-check form-switch mt-3">
			<input type="hidden" name="data[Testimonial][active]" value="0">
			<input
					type="checkbox"
					id="TestimonialActive"
					name="data[Testimonial][active]"
					value="1"
					class="form-check-input"
					<?php echo isset($this->data['Testimonial']['active']) && $this->data['Testimonial']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="TestimonialActive">Active</label>
		</div>

		<div class="mt-3">
			<label for="TestimonialTitle">Testimonial <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Testimonial.title', [
					'type' => 'textarea',
					'placeholder' => 'Enter Testimonial',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'rows' => "2",
					'required' => true,
			]) ?>
		</div>

		<div class="mt-3">
			<label for="TestimonialCustomerName">Customer Name <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Testimonial.customer_name', [
				'type' => 'text',
				'placeholder' => 'Enter Customer Name',
				'label' => false,
				'class' => 'form-control form-control-sm',
				'minlength' => "2",
				'maxlength' => "255",
				'required' => true,
			]) ?>
		</div>

		<div class="mt-3">
			<label for="TestimonialUrl">Redirection URL</label>
			<?= $this->Form->input('Testimonial.url', [
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
