<section>
	<div class="text-end">
		<a href="/admin/suppliers/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<header><h2>Add New Supplier</h2></header>

		<?= $this->Form->create() ?>

		<div class="form-check form-switch mt-3">
			<input type="hidden" name="data[Supplier][active]" value="0">
			<input
					type="checkbox"
					id="SupplierActive"
					name="data[Supplier][active]"
					value="1"
					class="form-check-input"
					<?php echo isset($this->data['Supplier']['active']) && $this->data['Supplier']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="SupplierActive">Active</label>
		</div>

		<div class="mt-3">
			<label for="SupplierTitle">Name <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Supplier.name', [
					'type' => 'text',
					'placeholder' => 'Enter Supplier Name',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'minlength' => "2",
					'maxlength' => "255",
					'required' => true,
			]) ?>
		</div>

		<div class="mt-3">
			<label for="SupplierPhone">Phone No. <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('Supplier.phone', [
					'type' => 'number',
					'placeholder' => 'Enter only 10 digit Phone No.',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'min' => "6000000000",
					'max' => "9999999999",
					'required' => true,
			]) ?>
			<span class="text-danger small">*Login OTP and Notification SMS will be sent to this number</span>
		</div>

		<div class="mt-3">
			<label for="SupplierEmail">Email</label>
			<?= $this->Form->input('Supplier.email', [
					'type' => 'email',
					'placeholder' => 'Enter Email',
					'label' => false,
					'class' => 'form-control form-control-sm',
			]) ?>
		</div>

		<div class="mt-3">
			<label for="SupplierEmail">Address</label>
			<?= $this->Form->input('Supplier.address', [
					'type' => 'textarea',
					'placeholder' => 'Enter Address',
					'label' => false,
					'rows' => 2,
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
