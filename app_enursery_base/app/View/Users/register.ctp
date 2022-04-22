<?php $this->Html->meta('keywords', 'register account with letsgreenify, get website for your garden or plant nursery for free', array('inline'=>false)); ?>
<?php $this->Html->meta('description', 'Register your plant nursery or garden with letsgreenify and get your online nursery portal up within no time. It only takes a minute to register.', array('inline'=>false)); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
	function onSubmit(token) {
		//document.getElementById("UserRegisterForm").submit();
		document.getElementById("RegisterButton").click();
		
	}

</script>

<?php echo $this->element('message');?>
<div itemscope itemtype ="http://schema.org/WebPage">
	<meta itemprop="url" content="http://www.letsgreenify.com/users/register" />	
	<h1 itemprop="name">Register Your Account</h1>
	<p itemprop="description">It only takes a minute to register.</p>
	
	<?php echo $this->Form->create();?>	
	<table style='width:420px;' class='defaultTable'>
		<tr>
			<td colspan='3'><h2>Account Information</h2></td>		
		</tr>
		<tr>
			<td style='width:150px;'>Name*</td>
			<td><?php echo $this->Form->input('User.name', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Name..', 'title'=>'Enter Name..'));?></td>
			<td style='width:25px;'>&nbsp;</td>
		</tr>
		<tr>
			<td>Email Address*</td>
			<td><?php echo $this->Form->input('User.email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Email Address..', 'title'=>'Enter Email Address..'));?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Phone No.*</td>
			<td><?php echo $this->Form->input('User.phone', array('label'=>false, 'type'=>'text', 'div'=>false, 'minlength'=>'10', 'maxlength'=>'55', 'required'=>true, 'placeholder'=>'Enter Phone No..', 'title'=>'Enter Phone No..'));?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Password*</td>
			<td><?php echo $this->Form->input('User.password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Enter Password..', 'title'=>'Enter Password..'));?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Confirm Password*</td>
			<td><?php echo $this->Form->input('User.confirm_password', array('label'=>false, 'div'=>false, 'type'=>'password', 'required'=>true, 'placeholder'=>'Confirm Your Password..', 'title'=>'Confirm Your Password..'));?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='2'><br/><h2>Site Information</h2></td>			
		</tr>
		<tr>
			<td>Site Title*</td>
			<td>
				<?php echo $this->Form->input('Site.title', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Site Title..', 'title'=>'Enter Site Title..', 'onfocus'=>"$('#sitetitletext').css('display', 'block');", 'onkeyup'=>'setSubdomainName()'));?>
				<div id='sitetitletext' class="textnote">
					*Eg: <strong>Gardens & Landscapers</strong>
				</div>
			</td>
			<td>
			</td>
		</tr>
		<tr>
			<td>Site Caption</td>
			<td>
				<?php echo $this->Form->input('Site.caption', array('label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Site Caption..', 'title'=>'Enter Site Caption..', 'onfocus'=>"$('#sitecaptiontext').css('display', 'block');", 'onblur'=>"$('#sitecaptiontext').css('display', 'none');"));?>
				<div id='sitecaptiontext' class="textnote">
					*Eg: - World class service providers
				</div>		
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>Subdomain Name*</td>
			<td>
				<?php echo $this->Form->input('Site.name', array('id'=>'SiteDomainName', 'label'=>false, 'type'=>'text', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Subdomain Name..', 'title'=>'Enter Subdomain Name..', 'onkeyup'=>'showSubdomainUrl()', 'onfocus'=>"showSubdomainUrl()"));?>
				<div id='sitenametext' class="textnote"></div>	
			</td>
			<td>
				
			</td>
		</tr>		
		<tr>
			<td>&nbsp;</td>
			<td style="height:50px;">
				<br/>
				<?php echo $this->Form->submit('Register &nbsp;&raquo;', array('escape'=>false, 'div'=>false, 'class'=>'button small grey', 'style'=>'display: none', 'id'=>'RegisterButton'));?>	
				<!--
				<button
					class="g-recaptcha button small grey"
					data-sitekey="6LeWmVAUAAAAAKtq7RN8FAun3RS-Qw4iFOHN0Rwb"
					data-callback="onSubmit"
					data-theme="custom"
					type="button"
					onclick="return false;"
					>
					Submit
				</button>
				-->
				<input 
					class="g-recaptcha button small grey"
					data-sitekey="6Ldj7vEUAAAAAD7MlV5FVOOMWhJpuu98mwEZDcLs"
					data-callback="onSubmit"
					data-theme="custom"
					type="button"
					onclick="return false;"
					value="Submit &nbsp;&raquo;"
					/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo '&nbsp;'.$this->Html->link('Cancel', '/', array('escape'=>false));	?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='3'>
				<br>
				<?php //echo $this->Html->link('Forgot your password?', '/users/forgotpassword', array('style'=>'text-decoration:none;')); ?>				
			</td>
		</tr>
	</table>
	<?php echo $this->Form->end();
	?>
</div>
<?php
echo $this->Html->script('gen_validatorv4');	// JS Validator
?>
<script type="text/javascript">	
	var userRegistration  = new Validator("UserRegisterForm");
	//Name
	userRegistration.addValidation("UserName","req","Please enter your Name");
	userRegistration.addValidation("UserName","maxlen=50", "Name should not exceed 50 chars in length");
	// userRegistration.addValidation("UserName","alphanumeric_space", "Name should contain only alpha numeric characters");
	//Email Address
	userRegistration.addValidation("UserEmail","req","Please enter your Email Address");
	userRegistration.addValidation("UserEmail","maxlen=55", "Email Address should not exceed 55 chars in length");
	//userRegistration.addValidation("UserEmail","email", "Invalid Email Address");
	//Password & Confirm Password
	userRegistration.addValidation("UserPassword","req","Password field cannot be empty");
	userRegistration.addValidation("UserPassword","minlen=5", "Password should be minimum 5 chars in length");
	userRegistration.addValidation("UserConfirmPassword","req","Confirm Password field cannot be empty");
	userRegistration.addValidation("UserConfirmPassword","eqelmnt=UserPassword", "The confirmed password is not same as password");
	
	//Site title
	userRegistration.addValidation("SiteTitle","req","Enter Site Title");
	userRegistration.addValidation("SiteTitle","minlen=3", "Site title should be minimum 3 chars in length");
	userRegistration.addValidation("SiteTitle","maxlen=50", "Site title should not exceed 50 chars in length");
	userRegistration.addValidation("SiteTitle","alphanumeric_space", "Site title should contain only alpha numeric characters");
	//Site Caption
	if($('#SiteCaption').val()) {
		userRegistration.addValidation("SiteCaption","minlen=3", "Site caption should be minimum 3 chars in length");
		userRegistration.addValidation("SiteCaption","maxlen=250", "Site Caption should not exceed 250 chars in length");
		// userRegistration.addValidation("SiteCaption","alphanumeric_space", "Site caption should contain only alpha numeric characters");
	}
	//Site Subdomain Name
	userRegistration.addValidation("SiteDomainName","req","Subdomain name cannot be empty");
	userRegistration.addValidation("SiteDomainName","minlen=3", "Subdomain name should be minimum 3 chars in length");
	userRegistration.addValidation("SiteDomainName","maxlen=25", "Subdomain name should not exceed 25 chars in length");
	userRegistration.addValidation("SiteDomainName","alphanumeric", "Subdomain name should not contain any space or special characters");
</script>
<br><br>