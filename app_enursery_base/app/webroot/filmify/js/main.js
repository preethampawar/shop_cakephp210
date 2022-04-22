// Register User by calling WebAPI
var register = "";
function registerUser() {
	var name = $('#RegisterName').val();
	var email = $('#RegisterEmail').val();
	var mobile = $('#RegisterMobile').val();
	var pass = $('#RegisterPassword').val();
	var confirmPass = $('#RegisterConfirmPassword').val();
	var tcCheckbox = $('#tcCheckbox').prop('checked');
	
	// validate registration form
	register = new Register(name, email, mobile, pass, confirmPass);
	console.log(register);
	
	// Allow submission only if a user accepts t&c's.	
	if(!register.tcCheck(tcCheckbox)) {
		return false;
	}
	
	// validate input data
	if(!register.validate()) {
		return false;
	}
	
	// if there are no errors then register user
	register.submitRequest();
	
}