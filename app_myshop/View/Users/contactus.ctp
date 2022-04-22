<?php
echo $this->Html->meta('keywords', 'Contact us', ['inline' => false]);
echo $this->Html->meta('description', 'Contact support team - ' . $this->Session->read('Site.title'), ['inline' => false]);
?>
<div>
	<?php echo $this->Form->create(null, ['encoding' => false]); ?>
	<h1>Contact Us</h1>
	<p>
		Email: <?php echo $this->Html->link('support@enursery.in', 'mailto:support@enursery.in'); ?><br/>
		Phone: +91 8500203040, +91 9493935599, +91 9494203060 <br/>
	</p>
	<br/>
	<h1>Send us a message</h1>
	<table style='width:500px;'>
		<?php if ($errorMsg or $successMsg) { ?>
			<tr>
				<td colspan='2'>
					<?php echo $this->element('message'); ?>
				</td>
			</tr>
		<?php } ?>
		<?php if (!$this->Session->check('User')) { ?>
			<tr>
				<td width='120'>Name</td>
				<td><?php echo $this->Form->input('User.name', ['label' => false, 'type' => 'text', 'div' => false, 'required' => true, 'placeholder' => 'Enter Full Name', 'style' => 'width:100%', 'title' => 'Enter Full Name']); ?></td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td><?php echo $this->Form->input('User.email', ['label' => false, 'type' => 'email', 'div' => false, 'required' => true, 'placeholder' => 'Enter Email Address', 'style' => 'width:100%', 'title' => 'Enter Email Address']); ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td valign='top'>Message</td>
			<td><?php echo $this->Form->input('User.message', ['label' => false, 'div' => false, 'type' => 'textarea', 'rows' => '3', 'required' => true, 'placeholder' => 'Your message  goes here..', 'title' => 'Your message  goes here..', 'style' => 'width:100%']); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<?php
				echo $this->Form->submit('Submit', ['escape' => false, 'div' => false, 'class' => 'button small green', 'title' => 'Submit']);

				if ($this->Session->check('User')) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo $this->Html->link('Cancel', '/', ['escape' => false, 'class' => '']);
				}
				?>
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
