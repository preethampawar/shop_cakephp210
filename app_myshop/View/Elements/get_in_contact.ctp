<?php
$name = $this->Session->read('Site.Account.name');
$email = $this->Session->read('Site.contact_email');
$phone = $this->Session->read('Site.contact_phone');
$address = ($this->Session->read('Site.address')) ? $this->Session->read('Site.address') : '';

?>
<section>
	<h2>Contact Information</h2>
	<address>
		<?php echo $name; ?><br>
		<pre>
<?php echo $address; ?><br>
</pre>
		<p>Phone: <?php echo $phone; ?></p>
		<p>Email: <?php echo $this->Html->link($email, 'mailto:' . $email, ['title' => 'contact: ' . $email]); ?></p>

	</address>
</section>
