<h1>Contact Us</h1>

<div class="row mt-3">
	<div class="col-md-12 col-lg-6 mb-3">
		<?= $this->Session->read('Site.contact_info') ?>
	</div>
	<div class="col-md-12 col-lg-6 mb-3">
		<div class="alert alert-primary small">
			<?php echo $this->Form->create(null, ['encoding' => false]); ?>

			<h3>Send us a message</h3>

			<?php echo $this->element('message'); ?>

			<div class="mt-3">
				<label for="UserName">Full Name <span class="small text-danger">*</span></label>
				<?=
				$this->Form->input('User.name', [
						'label' => false,
						'type' => 'text',
						'div' => false,
						'required' => true,
						'placeholder' => 'Enter your full name',
						'minlength' => 2,
						'maxlength' => 55,
						'class' => 'form-control form-control-sm',
				]);
				?>
			</div>

			<div class="mt-3">
				<label for="UserEmail">Email Address <span class="small text-danger">*</span></label>
				<?=
				$this->Form->input('User.email', [
						'label' => false,
						'type' => 'email',
						'div' => false,
						'required' => true,
						'placeholder' => 'Enter your email address',
						'class' => 'form-control form-control-sm',
				]);
				?>
			</div>

			<div class="mt-3">
				<label for="UserName">Phone Number</label>
				<?=
				$this->Form->input('User.phone', [
						'label' => false,
						'type' => 'text',
						'div' => false,
						'required' => false,
						'placeholder' => 'Enter your phone no.',
						'minlength' => 2,
						'maxlength' => 55,
						'class' => 'form-control form-control-sm',
				]);
				?>
			</div>

			<div class="mt-3">
				<label for="UserMessage">Message <span class="small text-danger">*</span></label>
				<?=
				$this->Form->input('User.message', [
						'label' => false,
						'div' => false,
						'type' => 'textarea',
						'rows' => '3',
						'required' => true,
						'placeholder' => 'Enter your message',
						'class' => 'form-control form-control-sm',
				]);
				?>
			</div>

			<div class="mt-4 text-center">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>

			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>


<?php
$pageUrl = $this->Html->url($this->request->here, true);
$customMeta = '';
$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $pageUrl, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'website', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => 'Contact Us', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => strip_tags($this->Session->read('Site.contact_info')), 'inline' => false]);
// $customMeta .= ($productImageUrl) ? $this->Html->meta(['property' => 'og:image', 'content' => $productImageUrl, 'inline' => false]) : '';
$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);

echo $this->Html->meta('keywords', 'Contact us', ['inline' => false]);
echo $this->Html->meta('description', strip_tags($this->Session->read('Site.contact_info')), ['inline' => false]);

$this->set('customMeta', $customMeta);
$this->set('title_for_layout', 'Contact Us');


?>
