function Register(name, email, mobile, password, confirmPassword) {
	this.name = name.trim();
	this.email = email.trim();
	this.mobile = mobile.trim();
	this.password = password.trim();
	this.confirmPassword = confirmPassword.trim();
	
	// API details
	this.apiUrl = "http://filmify.southindia.cloudapp.azure.com/filmify/api/account/register";
	this.params = {
		"Email" : this.email, 
		"PhoneNumber" : this.mobile, 
		"Password" : this.password, 
		"ConfirmPassword" : this.confirmPassword 
	}
}
Register.prototype.tcCheck = function(tcCheckbox) {
	if(tcCheckbox == false) {
		alert('You need to accept our "Terms & Conditions" and "Privacy Policy" to proceed further.');
		$('#tcCheckbox').focus();
		return false;
	}
	return true;
}
Register.prototype.validate = function() {
	if(this.name == "") {
		alert('Please enter your Name');
		return false;
	}
	if(this.email == "") {
		alert('Please enter your Email');
		return false;
	}
	if(this.mobile == "") {
		alert('Please enter your Mobile');
		return false;
	}
	if(this.password == "") {
		alert('Please enter your Password');
		return false;
	}
	if(this.confirmPassword == "") {
		alert('Please re-enter your Password (Confirmation Password)');
		return false;
	}
	if(this.password != this.confirmPassword) {
		alert('Password field and Confirm Password field should contain the same value');
		return false;
	}
	
	return true;
}
Register.prototype.submitRequest = function() {	
	$.ajax({
		method: "POST",
		url: this.apiUrl,
		data: this.params,
		success: function(responseData, textStatus) {
				alert(responseData.Message);
				console.log(responseData);
				console.log(textStatus);
			},
		dataType: "json"
	});
}
