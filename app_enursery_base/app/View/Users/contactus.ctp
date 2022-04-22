<?php $this->set('contactLinkActive', true); ?>
<?php $this->set('title_for_layout', 'Contact us'); ?>
<?php $this->Html->meta('keywords', 'contact us,contact LetsGreenify support team', array('inline'=>false)); ?>
<?php $this->Html->meta('description', 'Get in touch with the LetsGreenify team. Send us a message or contact us over phone or through email. We are ready to assist you.', array('inline'=>false)); ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	function onSubmit(token) {
		document.getElementById("UserContactusForm").submit();
	}
</script>

<div itemscope itemtype ="http://schema.org/ContactPage">
	<meta itemprop="url" content="http://www.LetsGreenify.com/users/contactus" />
	<?php echo $this->Form->create();?>	
	<h1><span itemprop="name">Contact us</span></h1>
	<p itemprop="description">Get in touch with the LetsGreenify team. We are ready to assist you in any possible way. Send us a message, contact us over phone or send us an email. </p>
	<p>Find the below details to contact us.</p>
	
	<p>
	Email: <?php echo $this->Html->link(Configure::read('SupportEmail'), 'mailto:'.Configure::read('SupportEmail'));?><br/>
	<!-- Phone: +91 8500203040, +91 9493935599, +91 9494203060 <br/>	-->
	</p>
	<br/>
	<!-- <div class="heading">(or) Write to us</div>
	<p>	
		#3950, VidyutNagar, <br>
		BHEL, RC Puram, <br>
		Hyderabad, Andhra Pradesh, India<br>
		Pin - 502032
	</p>
	<br> -->
	
	<div class="heading">(or) Send us a message</div>
	<table style='width:500px;'>
		<?php if($errorMsg or $successMsg) { ?>
		<tr>
			<td colspan='2'>
				<?php echo $this->element('message');?>
			</td>
		</tr>
		<?php }?>
		<?php if(!$this->Session->check('User')) { ?>		
		<tr>
			<td width='120'>Name</td>
			<td><?php echo $this->Form->input('User.name', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Full Name', 'style'=>'width:100%', 'title'=>'Enter Full Name'));?></td>
		</tr>
		<tr>
			<td>Email Address</td>
			<td><?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Email Address', 'style'=>'width:100%', 'title'=>'Enter Email Address'));?></td>
		</tr>
		<?php } ?>
		<tr>
			<td valign='top'>Message</td>
			<td><?php echo $this->Form->input('User.message', array('label'=>false, 'div'=>false, 'type'=>'textarea', 'rows'=>'3', 'required'=>true, 'placeholder'=>'Your message  goes here..', 'title'=>'Your message  goes here..', 'style'=>'width:100%'));?></td>
		</tr>		
		<tr>
			<td>&nbsp;</td>
			<td>
				<?php 
					//echo $this->Form->submit('Submit', array('escape'=>false, 'div'=>false, 'class'=>'button small green', 'title'=>'Submit'));
					?>
					<button
						class="g-recaptcha"
						data-sitekey="6Ldj7vEUAAAAAD7MlV5FVOOMWhJpuu98mwEZDcLs"
						data-callback="onSubmit">
						Submit
					</button>
					<br/>

					<?php
					if($this->Session->check('User')) { 
						echo '<br>';
						echo $this->Html->link('Cancel', '/', array('escape'=>false, 'class'=>''));				
					}
				?>				
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<br><br>
				<?php 
					if(!$this->Session->check('User')) {
						echo $this->Html->link('Need an account? Click here to Register', '/users/register', array('style'=>'text-decoration:none;', 'escape'=>false)); 
					} 
					else { 
					?>
						Click <?php echo $this->Html->link('here', '/');?> to visit <?php echo $this->Html->link('home page', '/');?>.
					<?php 
					} 
					?>.
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end();?>
</div>
<br><br><br>