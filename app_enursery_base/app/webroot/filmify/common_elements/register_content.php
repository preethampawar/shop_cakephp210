<div class="register_body">
	<div class="container">
    <div class="row">
      <div class="col-md-12 p-3">
        <nav>
          <ul class="nav nav-pills float-right">
            <li class="nav-item"> <a class="nav-link" href="#">Home <span class="sr-only">Aboutus</span></a> </li>
            <li class="nav-item"> <a class="nav-link" href="#">Gallery</a> </li>
            <li class="nav-item"> <a class="nav-link" href="#">Contact</a> </li>
          </ul>
        </nav>
        <h2 class="logo-color">filmify</h2>
      </div>
    </div>
  </div>
  <div class="container p-3">
    <div class="row">
      <div class="col-md-7 mt-5 pt-5 featurette">
        <h2 class="featurette-heading mb-4">Welcome to filmify.com</h2>
        <p class="lead pr-5">Top Movies Like is a free tool that helps you to find similar movies to any movie!
          Enter any movie name above (e.g. Toy Story, Godfather) to use the similar movie search.</p>
        <button class="btn btn-outline-light btn-lg mt-3">Register Now</button>
      </div>
      <div class="col-md-5">
        <form class="form_register">
          <h5 class="h-75 mb-0 p-3 border">Register with <span>filmify</span></h5>
          <div class="p-3">
            <label>Name</label>
            <input class="fname" name="name" placeholder="Fullname" id="RegisterName">
            <label>Email</label>
            <input class="femail" name="email" type="email" placeholder="abc@abc.com" id="RegisterEmail">
            <label>Mobile</label>
            <input class="fcode country_code" name="mobile" type="text" placeholder="+91">
            <input class="mobile_no" name="mobile" type="text" placeholder="999999999" id="RegisterMobile">
            <label>Password</label>
            <input class="fpwd" name="password" type="password" placeholder="*********" id="RegisterPassword">
            <label>Confirm Password</label>
            <input class="fpwd" name="confirm_password" type="password" placeholder="*********" id="RegisterConfirmPassword">
            <input type="hidden" name="tc_checkbox">
            <input type="checkbox" class="cb" name="tc_checkbox" id="tcCheckbox">
            <label for="tcCheckbox" class="cb_text">i agree to terms &amp; conditions and privacy policy</label>
            <button type="button" class="btn_register" onclick="registerUser()">Complete Registration!</button>
          </div>
          
          <center><span class="or">or</span></center>
          
         <div class="mt-4 pb-4 text-center">
         <button type="button" class="btn btn-primary btn-sm">Signin with Facebook</button>
         <button type="button" class="btn btn-danger btn-sm">Signin with Google+</button>
         </div>
        </form>
      </div>
    </div>
    
  </div>
</div>
