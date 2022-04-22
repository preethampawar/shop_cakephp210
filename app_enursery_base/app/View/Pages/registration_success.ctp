<h1>Registration Success!</h1> 

Thank you for registering with <?php echo Configure::read('Domain');?>. 

<br><br>
Before you start using your site, you need to confirm your account. A confirmation link has been sent to your email address. Follow the instructions specified in the email.<br>

<?php
if($this->Session->check('DomainInfo')) {
	$domainInfo = $this->Session->read('DomainInfo');
	$siteTitle = $this->Session->read('DomainInfo.Site.title');
	$siteLink = $this->Session->read('DomainInfo.Domain.name');
?>
	<p>If you have confirmed your account, you can proceed to <?php echo $this->Html->link('login', 'http://'.$siteLink.'/users/login/', array('title'=>'Login to '.$siteTitle, 'target'=>'_blank'));?>.</p>
	<br>
	<h2>Your Site Information</strong></h2>
	<p><strong><?php echo $siteTitle;?></strong><p>
	<p>Link: <?php echo $this->Html->link('http://'.$siteLink, 'http://'.$siteLink, array('title'=>$siteTitle, 'target'=>'_blank'));?><p>
	
	<p><?php echo $this->Html->link('Go to Login page', 'http://'.$siteLink.'/users/login', array('title'=>$siteTitle, 'target'=>'_blank'));?>.<p>
	<br><br>
<?php 
}
?>