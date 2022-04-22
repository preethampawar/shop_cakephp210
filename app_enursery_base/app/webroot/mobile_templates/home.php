<?php
header('Access-Control-Request-Headers: X-Requested-With, accept, content-type');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Origin: *');
?>
<div class="scrollable">  
  <div class="scrollable-content">

    <div class="list-group text-center">
		<div class="list-group-item list-group-item-home">
			<div class="panel panel-default" ng-hide="{{ authorized }}">
				<div class="panel-heading">
					<h1>Login</h1>
				</div>
				<div class="panel-body">
					<form role="form" ng-submit="authenticate()">
						<div class="form-group">
							<label for="email">Email address:</label>
							<input type="email" class="form-control" id="email" ng-model="$parent.authentication_email" name="authentication_email" required>
						</div>
						<div class="form-group">
							<label for="pwd">Password:</label>
							<input type="password" class="form-control" id="pwd" ng-model="$parent.authentication_password" name="authentication_password" required>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block">Submit</button>
						</div>
					</form>
				</div>
			</div>			
		</div>      
    </div>

  </div>
</div>
