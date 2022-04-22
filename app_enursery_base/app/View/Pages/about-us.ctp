<?php $this->set('aboutusLinkActive', true); ?>
<?php $this->set('title_for_layout', 'About us'); ?>
<?php $this->Html->meta('keywords', 'about letsgreenify, about us, about e nursery', array('inline'=>false)); ?>
<?php $this->Html->meta('description', 'We are a small group of Engineers working towards development of Agricutural Sector by utilizing the power of Web and Open Source Technologies', array('inline'=>false)); ?>
<div itemscope itemtype ="http://schema.org/AboutPage">
	<meta itemprop="name" content="About us" />
	<meta itemprop="description" content="We are a small group of Engineers working towards development of Agricutural Sector by utilizing the power of Web and Open Source Technologies" />
	<meta itemprop="url" content="http://www.letsgreenify.com/pages/about-us" />

	<h2 class="title">About US</h2>
	<p>We are a small group of Engineers working towards development of Agricutural Sector by utilizing the power of Web and Open Source Technologies.</p>
	<p>
		LetsGreenify, formerly known as letsgreenify, founded in November 2011, is a web platform which provides Websites or Web Stores for Landscapers, Plant Nurseries and Gardens. It provides an opportunity for Nursery owners to showcase and sell plants over web. For Landscapers it acts as a medium to showcase their work and the services they provide.
	</p>
	<p>	
		Customers, garden enthusiasts and hobbyists can benefit from this platform(online nursery store) as they don't have to look around for their favorite plant(s) as LetsGreenify brings all the nurseries and gardens under one roof. 
	</p>
	<p>
		For more information on using the platform, please contact us. You can do this by visiting <a href="<?php echo $this->Html->url('/users/contactus');?>" title="Contact us">CONTACT US</a> page or send an email to the following email address. "<a href="mailto:<?php echo Configure::read('SupportEmail');?>" title="<?php echo Configure::read('SupportEmail');?>"><?php echo Configure::read('SupportEmail')?></a>".
	</p>
</div>
<br><br>