<?php
App::uses('PromoCode', 'Model');
?>
<section>
	<div class="text-end">
		<a href="/admin/promo_codes/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<header><h2>Create New PromoCode</h2></header>

		<?= $this->Form->create() ?>

		<div class="form-check form-switch mt-3">
			<input type="hidden" name="data[PromoCode][active]" value="0">
			<input
					type="checkbox"
					id="PromoCodeActive"
					name="data[PromoCode][active]"
					value="1"
					class="form-check-input"
					<?php echo isset($this->data['PromoCode']['active']) && $this->data['PromoCode']['active'] ? 'checked' : null; ?>
			>
			<label class="form-check-label" for="PromoCodeActive">Active</label>
		</div>

		<div class="mt-3">
			<label for="PromoCodeName">Promo Code <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('PromoCode.name', [
					'type' => 'text',
					'placeholder' => 'Enter Promo Code',
					'label' => false,
					'class' => 'form-control form-control-sm',
					'required' => true,
					'minlength' =>4,
					'maxlength'=>32
			]) ?>
		</div>

		<div class="mt-3">
			<label for="PromoCodeType">Type (Linked to) <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('PromoCode.type', [
				'type' => 'select',
				'label' => false,
				'class' => 'form-select form-select-sm',
				'options' => PromoCode::PROMO_CODE_TYPES,
				'required' => true,
			]) ?>
		</div>

		<div class="mt-3">
			<label for="PromoCodeMinPurchaseValue">Min Purchase Value <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('PromoCode.min_purchase_value', [
				'type' => 'number',
				'label' => false,
				'class' => 'form-control form-control-sm',
				'min' => 1,
				'max' => 999999,
				'required' => true,
				'default' => 100
			]) ?>
		</div>

		<div class="mt-3">
			<label for="PromoCodeDiscountValue">Discount Amount <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('PromoCode.discount_value', [
				'type' => 'number',
				'label' => false,
				'class' => 'form-control form-control-sm',
				'min' => 1,
				'max' => 999999,
				'required' => true,
				'default' => 100
			]) ?>
		</div>

		<div class="mt-3">
			<label for="PromoCodeStartDate">Start Date <span class="text-danger small">(required)</span></label>
			<input
				type="date"
				id ="PromoCodeStartDate"
				name = "data[PromoCode][start_date]"
				value="<?= $this->data['PromoCode']['start_date'] ?? date('Y-m-d') ?>"
				class="form-control form-control-sm"
			>
		</div>

		<div class="mt-3">
			<label for="PromoCodeEndDate">End Date <span class="text-danger small">(required)</span></label>
			<input
				type="date"
				id ="PromoCodeEndDate"
				name = "data[PromoCode][end_date]"
				value="<?= $this->data['PromoCode']['end_date'] ?? date('Y-m-d') ?>"
				class="form-control form-control-sm"
			>
		</div>


		<div class="mt-3">
			<label for="PromoCodeRedeemType">Redeem Type <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('PromoCode.redeem_type', [
					'type' => 'select',
					'label' => false,
					'class' => 'form-select form-select-sm',
					'options' => PromoCode::PROMO_CODE_REDEEM_TYPES,
					'required' => true,
			]) ?>
		</div>

		<div class="mt-3">
			<label for="PromoCodeTerms">Terms & Conditions <span class="text-danger small">(required)</span></label>
			<?= $this->Form->input('PromoCode.terms', [
				'type' => 'textarea',
				'placeholder' => 'Enter Terms and Conditions',
				'label' => false,
				'class' => 'form-control form-control-sm',
				'rows' => "2",
				'required' => true,
			]) ?>
		</div>

		<div class="mt-4">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

		<?= $this->Form->end() ?>
	</article>
</section>
<br><br>
