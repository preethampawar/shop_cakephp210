<?php
header('Access-Control-Request-Headers: X-Requested-With, accept, content-type');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Origin: *');
?>

<div ui-content-for="title">
  <span>{{categories.result.Category.name}}</span>
</div>

<div class="scrollable">
	<div class="scrollable-content list-group">
		<div class="list-group-item">
			<div class="container-fluid">				
				<div class="row">
					<div class="col-sm-6" ng-repeat="(key, product) in categories.result.Products">
						<div class="panel panel-default">
							<div class="panel-heading">
								{{ product.name }}
								
								{{ $even==true ? 'even' : 'odd' }}
							</div>							
							<div class="panel-body">
								<div class="row">		
									<div class="col-sm-12">
										<img ng-src="{{ product.Image[0].small_url }}" title="{{ product.Image[0].caption }}" alt="" class="img-thumbnail" width="{{ categories.result.ImageSettings.small.width }}" height="{{ categories.result.ImageSettings.small.height }}">
									</div>
								</div>
							</div>
						</div>						
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
	
