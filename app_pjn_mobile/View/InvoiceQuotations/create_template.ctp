<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>
<?php $this->set('enableTextEditor', true); ?>

<h1>Create Template for Invoice / Quotation</h1><br>
<?php echo $this->Form->create(); ?>

<div class="">
	<?php echo $this->Form->input('type', [
			'type' => 'hidden',
			'value' => 'template',
		]
	); ?>

	<?php echo $this->Form->input('name', ['label' => 'Template Name', 'placeholder' => 'Enter Template Name', 'class' => 'form-control input-sm mb-3', 'required' => true]); ?>
	<br>
	<h5>Configure Template Content</h5>
	<hr>
	<div class="mb-3">
		<?php echo $this->Form->input('header', [
			'label' => 'Template Header',
			'type' => 'textarea',
			'class' => 'texteditor',
			'default' => '<h1 style="text-align: center;">Invoice / Quotation</h1>',
		]); ?>
	</div>


	<div class="mb-3">
		<?php echo $this->Form->input('from', [
			'label' => 'From Company Details',
			'type' => 'textarea',
			'class' => 'texteditor',
			'default' => '<b>From Company:</b><br><br><b>Address:</b>',
		]); ?>
	</div>

	<div class="mb-3">
		<?php echo $this->Form->input('for', [
			'label' => 'To Company Details',
			'type' => 'textarea',
			'class' => 'texteditor',
			'default' => '<b>To Company:</b><br><br><b>Address:</b>',
		]); ?>
	</div>

	<div class="mb-3">
		<?php echo $this->Form->input('instructions', [
			'label' => 'Instructions',
			'type' => 'textarea',
			'class' => 'texteditor',
			'default' => '<b>Terms & Conditions / Instructions:</b><br>',
		]); ?>
	</div>

	<div class="mb-3">
		<?php echo $this->Form->input('footer', [
			'label' => 'Template Footer',
			'type' => 'textarea',
			'class' => 'texteditor',
			'default' => '<h5 style="text-align: center; ">Template Footer</h5><div style="text-align: center; ">Thank you!</div>',
		]); ?>
	</div>

	<br>
	<h5>Configure Labels</h5>
	<hr>
	<?php echo $this->Form->input('from_date_label', ['label' => 'Invoice/Quotation Date Label', 'class' => 'form-control input-sm mb-3', 'default' => 'Date:', 'required' => true]); ?>

	<?php echo $this->Form->input('for_date_label', ['label' => 'Validity Date Label', 'class' => 'form-control input-sm mb-3', 'default' => 'Validity:', 'required' => true]); ?>

	<?php echo $this->Form->input('from_label', ['label' => 'From Company Details Label', 'class' => 'form-control input-sm mb-3', 'default' => 'From Company / Individual:', 'required' => true]); ?>

	<?php echo $this->Form->input('for_label', ['label' => 'To Company Details Label', 'class' => 'form-control input-sm mb-3', 'default' => 'Quotation / Invoice For:', 'required' => true]); ?>

	<button type="submit" class="btn btn-purple btn-sm">Save Template</button>

</div>

<?php echo $this->Form->end(); ?><br><br>
